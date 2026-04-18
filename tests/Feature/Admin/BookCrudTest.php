<?php

namespace Tests\Feature\Admin;

use Tests\Support\BaseAdminTestCase;
use Tests\Support\Traits\AssertsCrud;
use Tests\Support\Traits\AssertsSystemLog;
use Tests\Support\Traits\AssertsStorage;
use App\Models\Book;
use App\Models\SystemLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * CRUD tests for Book entity.
 *
 * Uses session-based admin auth (AdminMiddleware checks session('user_id') and session('user_role')).
 * Book route key is 'slug', so routes use slug not id.
 * Schema uses string instead of enum for SQLite :memory: compatibility.
 */
class BookCrudTest extends BaseAdminTestCase
{
    use AssertsCrud;
    use AssertsSystemLog;
    use AssertsStorage;

    // -------------------------------------------------------------------------
    // Schema
    // -------------------------------------------------------------------------

    protected function createSchema(): void
    {
        Schema::create('roles', function ($t) {
            $t->tinyIncrements('id');
            $t->string('code', 20)->unique();
            $t->string('name', 50);
            $t->string('description', 255)->nullable();
        });

        Schema::create('users', function ($t) {
            $t->increments('id');
            $t->string('name', 100);
            $t->string('email', 100)->unique();
            $t->string('password', 255);
            $t->string('phone', 15)->nullable();
            $t->string('avatar', 255)->nullable();
            $t->date('date_of_birth')->nullable();
            $t->string('gender', 10)->default('other');
            $t->unsignedTinyInteger('role_id')->default(2);
            $t->string('status', 20)->default('active');
            $t->unsignedInteger('loyalty_points')->default(0);
            $t->decimal('total_spent', 15, 0)->default(0);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('categories', function ($t) {
            $t->increments('id');
            $t->unsignedInteger('parent_id')->nullable();
            $t->string('name', 100);
            $t->string('slug', 150)->unique();
            $t->text('description')->nullable();
            $t->string('image', 255)->nullable();
            $t->smallInteger('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->softDeletes();
        });

        Schema::create('publishers', function ($t) {
            $t->increments('id');
            $t->string('name', 200);
            $t->string('slug', 200)->unique();
            $t->string('logo', 255)->nullable();
            $t->text('description')->nullable();
            $t->string('website', 255)->nullable();
            $t->boolean('is_partner')->default(false);
            $t->timestamps();
        });

        Schema::create('books', function ($t) {
            $t->increments('id');
            $t->string('sku', 30)->unique();
            $t->string('title', 255);
            $t->string('slug', 255)->unique();
            $t->unsignedInteger('category_id')->nullable();
            $t->unsignedInteger('publisher_id')->nullable();
            $t->decimal('cost_price', 12, 0)->default(0);
            $t->decimal('original_price', 12, 0)->default(0);
            $t->decimal('sale_price', 12, 0)->default(0);
            $t->integer('stock')->default(0);
            $t->integer('sold_count')->default(0);
            $t->text('description')->nullable();
            $t->string('short_description', 500)->nullable();
            $t->string('cover_image', 255)->nullable();
            $t->json('extra_images')->nullable();
            $t->string('isbn', 20)->nullable();
            $t->unsignedSmallInteger('pages')->nullable();
            $t->unsignedSmallInteger('weight')->nullable();
            $t->string('cover_type', 20)->default('paperback'); // string instead of enum for SQLite
            $t->string('language', 50)->default('English');
            $t->year('published_year')->nullable();
            $t->decimal('rating_avg', 3, 2)->default(0.00);
            $t->unsignedInteger('rating_count')->default(0);
            $t->string('status', 30)->default('in_stock'); // string instead of enum for SQLite
            $t->boolean('is_featured')->default(false);
            $t->timestamps();
            $t->softDeletes();
        });

        // Pivot table for Book <-> Author many-to-many
        Schema::create('book_author', function ($t) {
            $t->unsignedInteger('book_id');
            $t->unsignedInteger('author_id');
            $t->primary(['book_id', 'author_id']);
        });

        Schema::create('system_logs', function ($t) {
            $t->id();
            $t->string('type', 30)->index();
            $t->string('action', 50)->index();
            $t->string('level', 20)->default('info')->index();
            $t->text('description');
            $t->string('object_type', 100)->nullable();
            $t->unsignedBigInteger('object_id')->nullable();
            $t->json('old_data')->nullable();
            $t->json('new_data')->nullable();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->string('user_name', 100)->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->string('url', 500)->nullable();
            $t->timestamps();
        });
    }

    protected function dropSchema(): void
    {
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('books');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function adminSession(): array
    {
        return [
            'user_id'    => 1,
            'user_name'  => 'Admin Test',
            'user_role'  => 1,
            'user_email' => 'admin@test.vn',
        ];
    }

    private function validPayload(array $overrides = []): array
    {
        static $counter = 0;
        $counter++;
        return array_merge([
            'sku'    => 'SKU-TEST-' . $counter,
            'title'  => 'Test Book ' . $counter,
            'slug'   => 'test-book-' . $counter,
            'stock'  => 10,
            'status' => 'in_stock',
        ], $overrides);
    }

    /**
     * Create a fake GIF UploadedFile that bypasses Intervention Image processing.
     * UploadsFile trait skips Intervention for GIF files and uses $file->store() directly.
     */
    private function makeMinimalJpeg(string $name = 'test.gif'): UploadedFile
    {
        // Rename to .gif and use image/gif mime — UploadsFile trait skips Intervention for GIF
        // This avoids GD extension requirement while still testing file upload path
        $gifName = str_replace(['.jpg', '.jpeg'], '.gif', $name);
        return UploadedFile::fake()->create($gifName, 1, 'image/gif');
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    public function test_create_book_persists_record_and_logs(): void
    {
        $payload = $this->validPayload(['title' => 'My New Book', 'slug' => 'my-new-book', 'sku' => 'SKU-NEW-001']);

        $response = $this->withSession($this->adminSession())
            ->post(route('admin.books.store'), $payload);

        $response->assertRedirect(route('admin.books.index'));

        $this->assertDatabaseHas('books', [
            'sku'   => 'SKU-NEW-001',
            'title' => 'My New Book',
            'slug'  => 'my-new-book',
        ]);

        $book = Book::where('slug', 'my-new-book')->first();
        $this->assertNotNull($book);

        $this->assertSystemLog(
            action: 'create',
            objectType: 'Book',
            objectId: $book->id,
            level: 'info',
            type: 'data'
        );
    }

    public function test_create_book_with_cover_image_stores_file(): void
    {
        // Create a minimal valid JPEG file that Intervention Image can process
        $file = $this->makeMinimalJpeg('cover.jpg');

        $payload = $this->validPayload(['slug' => 'book-with-cover', 'sku' => 'SKU-COVER-001']);
        $payload['cover_image'] = $file;

        $this->withSession($this->adminSession())
            ->post(route('admin.books.store'), $payload);

        $book = Book::where('slug', 'book-with-cover')->first();
        $this->assertNotNull($book);
        $this->assertNotNull($book->cover_image, 'cover_image should not be null after upload');

        Storage::disk('public')->assertExists($book->cover_image);
    }

    public function test_update_book_with_new_cover_image_replaces_old_file(): void
    {
        // Create book with initial cover image
        $oldFile = $this->makeMinimalJpeg('old-cover.jpg');
        $createPayload = $this->validPayload(['slug' => 'book-replace-cover', 'sku' => 'SKU-REPLACE-001']);
        $createPayload['cover_image'] = $oldFile;

        $this->withSession($this->adminSession())
            ->post(route('admin.books.store'), $createPayload);

        $book = Book::where('slug', 'book-replace-cover')->first();
        $this->assertNotNull($book);
        $oldPath = $book->cover_image;
        $this->assertNotNull($oldPath);
        Storage::disk('public')->assertExists($oldPath);

        // Update with new cover image
        $newFile = $this->makeMinimalJpeg('new-cover.jpg');
        $updatePayload = $this->validPayload(['slug' => 'book-replace-cover', 'sku' => 'SKU-REPLACE-001']);
        $updatePayload['cover_image'] = $newFile;
        $updatePayload['_method'] = 'PUT';

        $this->withSession($this->adminSession())
            ->put(route('admin.books.update', $book), $updatePayload);

        $book->refresh();
        $newPath = $book->cover_image;

        $this->assertNotNull($newPath);
        $this->assertNotEquals($oldPath, $newPath, 'New cover path should differ from old');
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_delete_book_soft_deletes(): void
    {
        $book = Book::create([
            'sku'   => 'SKU-DELETE-001',
            'title' => 'Book To Delete',
            'slug'  => 'book-to-delete',
            'stock' => 5,
        ]);

        $id = $book->id;

        $this->withSession($this->adminSession())
            ->delete(route('admin.books.destroy', $book));

        $this->assertSoftDeleted('books', ['id' => $id]);
        $this->assertNull(Book::find($id), 'Soft-deleted book should not appear in default query');
    }

    public function test_system_log_created_for_book_crud(): void
    {
        $session = $this->adminSession();

        // Create
        $createPayload = $this->validPayload(['slug' => 'book-crud-cycle', 'sku' => 'SKU-CYCLE-001', 'title' => 'Cycle Book']);
        $this->withSession($session)->post(route('admin.books.store'), $createPayload);

        $book = Book::where('slug', 'book-crud-cycle')->first();
        $this->assertNotNull($book);

        // Update
        $updatePayload = $this->validPayload(['slug' => 'book-crud-cycle', 'sku' => 'SKU-CYCLE-001', 'title' => 'Cycle Book Updated']);
        $this->withSession($session)->put(route('admin.books.update', $book), $updatePayload);

        // Delete
        $this->withSession($session)->delete(route('admin.books.destroy', $book));

        // Assert 3 SystemLog records for this Book
        $logCount = SystemLog::where('object_type', 'Book')
            ->where('object_id', $book->id)
            ->whereIn('action', ['create', 'update', 'delete'])
            ->count();

        $this->assertEquals(3, $logCount, 'Expected 3 SystemLog entries (create, update, delete) for Book');

        // Verify no null object_type
        $nullCount = SystemLog::where('object_id', $book->id)
            ->whereNull('object_type')
            ->count();
        $this->assertEquals(0, $nullCount, 'No SystemLog for Book should have null object_type');
    }
}
