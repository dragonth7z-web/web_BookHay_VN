<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('sku', 30)->unique();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();

            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('publisher_id')->nullable();

            $table->decimal('cost_price', 12, 0)->default(0);
            $table->decimal('original_price', 12, 0)->default(0);
            $table->decimal('sale_price', 12, 0)->default(0);

            $table->integer('stock')->default(0);
            $table->integer('sold_count')->default(0);

            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->string('cover_image', 255)->nullable();
            $table->json('extra_images')->nullable();

            $table->string('isbn', 20)->nullable();
            $table->unsignedSmallInteger('pages')->nullable();
            $table->unsignedSmallInteger('weight')->nullable();
            $table->enum('cover_type', ['hardcover', 'paperback'])->default('paperback');
            $table->string('dimensions', 100)->nullable()->comment('Kích thước sách');
            $table->string('material', 255)->nullable()->comment('Chất liệu giấy');
            $table->string('language', 50)->default('English');
            $table->year('published_year')->nullable();

            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->unsignedInteger('rating_count')->default(0);

            $table->enum('status', ['in_stock', 'out_of_stock', 'discontinued'])->default('in_stock');
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');

            $table->index('category_id', 'idx_danh_muc');
            $table->index('status', 'idx_books_status');
            $table->index('created_at', 'idx_books_created_at');
            $table->index(['sale_price', 'status'], 'idx_gia_trang_thai');
            $table->index(['is_featured', 'created_at'], 'idx_noi_bat_ngay');
            $table->index('deleted_at', 'idx_deleted');
        });

        // FULLTEXT index is MySQL-only
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE books ADD FULLTEXT idx_search(title, short_description)');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE books DROP INDEX idx_search');
            DB::statement('ALTER TABLE books DROP INDEX idx_books_status');
            DB::statement('ALTER TABLE books DROP INDEX idx_books_created_at');
        }
        Schema::dropIfExists('books');
    }
};
