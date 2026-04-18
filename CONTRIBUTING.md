# 📋 Contributing Guidelines - Web Bookstore THLD

This document serves as the **Project Constitution**. It outlines mandatory development principles (code, architecture, logic) for every Developer and Agentic AI working within this repository.

---

## 🔥 **A. Debug & Logging Policy**

### **1. Debug Code Cleanup**
- **MANDATORY**: Remove all `dd()`, `dump()`, `print_r()`, and `var_dump()` calls immediately after verification.
- **FORBIDDEN**: Committing debug code to the repository.
- **WORKFLOW**: Debug → Verify → Purge → Commit.

### **2. Environment Cleanliness**
- **LOGGING**: Temporary scripts (e.g., `test_*.php`, `script.js`) must reside ONLY in `/storage/logs/` or `/storage/framework/testing/`.
- **ROOT PROTECTION**: Do not create any test or temporary files in the root directory.
- **POST-TASK**: Delete all temporary files before submitting a pull request.

### **3. Logging Standards**
```php
// ✅ DO: Structured logging with context
Log::info('User performed action', ['user_id' => $user->id]);
Log::error('Connection failed', ['exception' => $e->getMessage()]);

// ❌ DON'T: Log raw data or messy strings
Log::debug('dumping data');
Log::info(print_r($payload, true)); 
```

---

## 🏗️ **B. Architecture & Structure Policy**

### **1. Layered Architecture (The Golden Rule)**
Standard Laravel layers must be strictly followed to ensure maintainability:
```
Controller → Service → Repository → Model
```
- **Controller**: Handles Request/Response only. No business logic.
- **Service**: Contains all Business Logic and Rule Validations.
- **Repository**: Handles all Data Retrieval and Eloquent Queries.
- **Model**: Defines Relationships, Scopes, and Casts.

### **2. Communication Constraints**
- ❌ **Forbidden**: Controller calling a Model or Repository directly (bypassing the Service).
- ❌ **Forbidden**: Service calling a Controller.
- ❌ **Forbidden**: Business logic inside `routes/web.php`.

### **3. Language Protocol (Critical)**
- **Development Language**: All code (variables, methods, comments, documentation) must be in **English**.
- **User Interface (UI)**: All strings displayed to the end-user on the website must be in **Vietnamese**.

---

## 📝 **C. Code Quality & Naming Conventions**

### **1. Professional Naming**
Naming should be descriptive and follow the layer pattern:
```php
// ✅ Controllers
BookController, OrderController

// ✅ Services
BookService, OrderService

// ✅ Repositories
BookRepository, OrderRepository

// ❌ Forbidden: Vague or non-standard names
processData(), handleRequest(), testService()
```

### **2. Method Responsibilities**
```php
// Controller - Request & Response handling
public function store(StoreBookRequest $request)
{
    $book = $this->bookService->registerNewBook($request->validated());
    return response()->json($book, 201);
}

// Service - Business/Logic processing
public function registerNewBook(array $data): Book
{
    $this->applyDiscountRules($data);
    return $this->bookRepository->persist($data);
}

// Repository - Database abstraction
public function persist(array $data): Book
{
    return Book::create($data);
}
```

---

## 🎨 **D. Frontend & Blade Standards**

### **1. Component-Driven UI**
- Use **Blade Components** (`<x-header />`) for reusable elements.
- Pass data via `@props` or use `$attributes` for customization.

### **2. Styling Principles**
- **Tailwind CSS First**: Use utility classes for all layouts and spacing.
- **Inline Styles**: Strictly restricted to dynamic values from the database (e.g., specific background images or user-defined gradients).
- **Custom CSS**: For complex visual effects that Tailwind cannot cleanly express (e.g., card-fanning animations, book-spine 3D effects), place styles in `resources/css/custom.css`. Do not abuse this — Tailwind remains the primary styling tool.

### **3. Localization**
- All UI text must be in **Vietnamese**.
- Use Laravel's localization files (`lang/vi/*.php`) for static strings to keep the templates clean.

---

## �️ **E. Data Integrity & Display Rules**

### **1. Zero Static Data Rule**
- **MANDATORY**: All information rendered on the UI (book listings, rankings, prices, user info, etc.) must be sourced from the Database via the standard Controller → Service → Repository flow.
- **FORBIDDEN**: Hard-coding text or numbers directly into Blade files to "fill" the interface.
- **EXCEPTION**: Fixed UI labels such as "Tên sách", "Giá bán", "Thêm vào giỏ hàng" are allowed as static strings, but they **must** live in the localization files under `lang/vi/`.

### **2. Mock Data & Seeding**
- If a feature is under development and has no real data yet, the developer/AI **must** create a Database Seeder or Factory to generate sample data.
- Seed data must mirror real-world data in structure and quality — no `"abc"`, `"123"`, `"test data"`, or `"Lorem Ipsum"` placeholders.
- **Workflow**: Create Migration → Create Factory/Seeder → Register in `DatabaseSeeder.php` → Render in Blade.

### **3. Empty State Handling**
- Always handle the case where the database returns no records.
- Use `@forelse` instead of `@foreach` for all collection loops.
- Every list or grid **must** include a meaningful empty-state UI (e.g., an icon + message) to prevent layout breakage.

```blade
{{-- ✅ DO --}}
@forelse ($books as $book)
    <x-book-card :book="$book" />
@empty
    <div class="empty-state">
        <p>Chưa có sách nào trong danh mục này.</p>
    </div>
@endforelse

{{-- ❌ DON'T --}}
@foreach ($books as $book)
    <x-book-card :book="$book" />
@endforeach
```

---

## ⚙️ **F. Logic Separation Policy**

### **1. Pure Logic Separation**
- **MANDATORY**: All calculations, complex string processing, data classification, and business rule checks must be encapsulated in dedicated methods within the **Service Layer**.
- **FORBIDDEN**: Writing lengthy calculation logic directly inside Controller request-handling methods.

### **2. Database Logic Separation**
- Complex Eloquent queries (e.g., `join`, `whereHas`, `groupBy`, subqueries) must reside in the **Repository Layer**.
- Services only call named Repository methods (e.g., `$this->bookRepo->getTopSellingBooks()`). Services must not write raw SQL or Eloquent query chains internally.

### **3. UI/Display Logic Separation**
- Display-formatting logic (e.g., converting `100000` → `100.000đ`, or deriving a status color) must be extracted from Blade files and placed in:
  - **Eloquent Accessors** on the Model, or
  - **View Components / Presenters**.
- **FORBIDDEN**: Performing complex logic calculations inside `.blade.php` files. Blade is for rendering only.

```php
// ✅ DO: Accessor on Model
public function getFormattedPriceAttribute(): string
{
    return number_format($this->price, 0, ',', '.') . 'đ';
}

// In Blade: {{ $book->formatted_price }}

// ❌ DON'T: Logic in Blade
{{ number_format($book->price, 0, ',', '.') . 'đ' }}
```

---

## �🛰️ **G. Git Workflow & Version Control**

### **1. Branching Strategy**
- **Feature**: `feat/[description]` (e.g., `feat/stacked-combo-covers`)
- **Fix**: `fix/[description]` (e.g., `fix/banner-overflow`)
- **Refactor**: `refactor/[module]` (e.g., `refactor/auth-service`)

### **2. Commit Message (Conventional Commits)**
Follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:
```bash
feat(home): implement dynamic fanning effect for combo cards
fix(ui): correct spacing on mobile for feature grid
docs(guide): update repository contribution rules
```

---

## 🧪 **H. Testing & Reliability**

- **Unit Tests**: Mandatory for Services and Repositories.
- **Feature Tests**: Required for critical API endpoints and User flows.
- **Cleanup**: Mock external dependencies to ensure fast and isolated test execution.

---

## ⚡ **I. AI Development Protocol**

To maintain repository integrity, Agentic AI must adhere to the following:
- ❌ **Forbidden**: Creating new files or folders outside standard Laravel directories unless explicitly requested.
- ❌ **Forbidden**: Self-generating documentation files (`instructions.txt`, `.tmp`, etc.) in the root.
- ❌ **Forbidden**: Rewriting existing files without a clear objective.
- ✅ **Required**: Refactoring logic to match the MVC/Repository pattern.
- ✅ **Required**: Adding appropriate unit tests for any new logic introduced.

---

## 🛠️ **J. CLI Quick Start & Maintenance**

### **1. Fresh Installation**
```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
npm run dev
```

> **Note**: After copying `.env.example`, review all environment variables carefully before running the app.
> This project currently uses standard Laravel services (DB, Mail, Cache, Queue, AWS S3).
> If third-party API keys are added in the future (e.g., payment gateways, SMS providers), they will appear in `.env.example` with placeholder values — make sure to fill them in, otherwise those features will silently fail.

> **Seed Data Quality**: The `--seed` flag populates the database with sample data. All seeders in this project must use realistic, professional-looking data (proper book titles, author names, prices, etc.). Placeholder values like `"test1"`, `"abcxyz"`, or `"Lorem Ipsum"` are **not allowed** in seeders — they break the UI and misrepresent the product.

### **2. System Optimization**
Run these before any review or deployment:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🤖 **L. AI Artifacts & Sandbox Policy**

> This section extends the general rules in **A2** (Environment Cleanliness) and **I** (AI Development Protocol) with a dedicated workspace structure for AI-generated non-production files.

### **1. AI-Specific Directory**
- **MANDATORY**: All files generated by AI that do not directly participate in the application's runtime (e.g., `instructions.txt`, `prompt_history.json`, `logic_explanation.md`, draft test scripts) must be placed under:
  ```
  /_ai_workspace/
  ```
- **FORBIDDEN**: Leaving these files scattered in the root directory or in Laravel functional directories such as `app/`, `database/`, `resources/`.

### **2. Temporary Nature (Sandbox)**
- The `/_ai_workspace/` directory is treated as a **Sandbox** — a temporary scratchpad only.
- After a feature is fully coded, verified, and merged into the main branch, this directory **must be deleted entirely** before closing the task.
- Add `/_ai_workspace/` to your local `.gitignore` if you do not want draft files pushed to the team's GitHub repository.

### **3. Permanent Assets vs. AI Artifacts**
| Type | Definition | Location |
|---|---|---|
| **Permanent Asset** | Code logic, migrations, views, configs | Standard Laravel directory structure |
| **AI Artifact** | AI notes, explanations, draft files, prompt logs | `/_ai_workspace/` only |

---

## 📊 **K. Pre-Commit Checklist**

- [ ] Is the code clean of all debug calls (`dd`, `dump`, `log::debug`)?
- [ ] Are all methods, variables, and comments in **English**?
- [ ] Is the UI strictly in **Vietnamese**?
- [ ] Does the logic flow correctly (Controller → Service → Repository)?
- [ ] Have all temporary AI files been deleted and `/_ai_workspace/` cleared?
- [ ] Are all tests passing?
- [ ] Does every collection loop use `@forelse` with an empty state?
- [ ] Is all displayed data sourced from the database (no hard-coded UI values)?
- [ ] Is display-formatting logic in Accessors or Presenters, not in Blade?

---

*💡 **Objective**: Keep the codebase professional, maintainable, and robust. Every contribution matters.*
