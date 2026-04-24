# Design Document

## Register Form Advanced Validation

---

## Overview

Tính năng nâng cấp toàn bộ hệ thống validation cho form đăng ký tài khoản THLD Bookstore. Thiết kế tập trung vào UX-friendly validation: phản hồi lỗi realtime theo blur/debounce (không validate ngay khi gõ), submit button luôn enabled, và chỉ hiển thị tối đa 3 lỗi đầu tiên trong ErrorBox.

Stack hiện tại:
- **Frontend**: Vanilla JavaScript (không dùng framework), Tailwind CSS
- **Backend**: Laravel (PHP), session-based auth
- **Entry point JS**: `resources/js/auth/auth.js`
- **View**: `resources/views/auth/register.blade.php`
- **Controller**: `app/Http/Controllers/AuthController.php`

### Nguyên tắc thiết kế chính

1. **Blur-first validation** — validate khi rời field hoặc debounce 500-800ms, không validate ngay khi gõ
2. **Submit button luôn enabled** — không disable theo form state, chỉ disable sau khi bấm submit (chống spam)
3. **ErrorBox giới hạn 3 lỗi** — hiển thị tối đa 3 lỗi đầu tiên + scroll đến field lỗi đầu tiên
4. **Password: chỉ length là bắt buộc** — các tiêu chí hoa/thường/số/ký tự đặc biệt chỉ là gợi ý (warning)
5. **Họ tên: không auto title-case** — tránh phá tên như "McDonald", "NguyễnVăn"
6. **Email tạm: check ở backend** — không block client-side

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Browser (Client)                          │
│                                                             │
│  register.blade.php                                         │
│  ┌─────────────────────────────────────────────────────┐   │
│  │  RegisterForm (#registerForm)                        │   │
│  │  ├── FieldValidator (per field)                      │   │
│  │  │   ├── validateOnBlur()                            │   │
│  │  │   ├── validateOnDebounce(500ms)                   │   │
│  │  │   └── showFieldState(valid|invalid|idle)          │   │
│  │  ├── ErrorBox (#errorBox)                            │   │
│  │  │   └── max 3 errors + scroll to first              │   │
│  │  ├── EmailChecker (AJAX debounce 500ms)              │   │
│  │  └── SubmitHandler                                   │   │
│  │      ├── always enabled                              │   │
│  │      ├── disable after click (anti-spam)             │   │
│  │      └── loading state "Đang xử lý..."               │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│  resources/js/auth/auth.js                                  │
│  └── RegisterValidator class                                │
└─────────────────────────────────────────────────────────────┘
                          │ POST /register
                          │ GET  /auth/check-email (AJAX)
                          ▼
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Server                            │
│                                                             │
│  AuthController                                             │
│  ├── register() — validate + trim + create user             │
│  └── checkEmail() — AJAX email existence check              │
│                                                             │
│  RegisterRequest (Form Request)                             │
│  └── rules() — server-side validation rules                 │
└─────────────────────────────────────────────────────────────┘
```

---

## Components and Interfaces

### 1. RegisterValidator (JavaScript class)

Module chính quản lý toàn bộ validation phía client. Thay thế các hàm rời rạc hiện tại trong `auth.js`.

```javascript
class RegisterValidator {
  constructor(formId)

  // Khởi tạo tất cả event listeners
  init()

  // Validation từng field
  validateName(value)        // → { valid: bool, error: string|null }
  validateEmail(value)       // → { valid: bool, error: string|null }
  validatePhone(value)       // → { valid: bool, error: string|null, skip: bool }
  validatePassword(value)    // → { valid: bool, error: string|null, warnings: string[] }
  validateConfirm(value, passwordValue) // → { valid: bool, error: string|null }

  // UI state
  setFieldState(fieldId, state, message)  // state: 'valid'|'invalid'|'idle'|'loading'
  showErrorBox(errors)   // max 3 errors, scroll to first invalid field
  hideErrorBox()

  // AJAX
  checkEmailExists(email)  // debounce 500ms, returns Promise

  // Submit
  handleSubmit(event)
}
```

**Timing:**
- `blur` event → validate ngay
- `input` event → debounce 500-800ms trước khi validate (không validate ngay khi gõ)
- Phone `input` → strip non-digits ngay lập tức (không debounce)

### 2. Field State UI

Mỗi field có 4 trạng thái visual:

| State | Border | Icon | Mô tả |
|-------|--------|------|-------|
| `idle` | `border-slate-100` | — | Chưa tương tác |
| `valid` | `border-green-500` | ✔ (green) | Hợp lệ |
| `invalid` | `border-red-500` | ❌ (red) | Không hợp lệ |
| `loading` | `border-brand-primary/30` | spinner | AJAX đang chạy |

Icon được inject vào wrapper `.relative.group` của mỗi field (absolute positioned, right side).

### 3. ErrorBox

```html
<div id="errorBox" class="hidden p-4 rounded-xl bg-red-50 border border-red-200 mb-6">
  <div class="flex items-center gap-2 mb-2">
    <span class="material-symbols-outlined text-red-500">error</span>
    <p class="text-sm font-bold text-red-600">Vui lòng kiểm tra lại thông tin:</p>
  </div>
  <ul class="list-disc list-inside space-y-1">
    <!-- max 3 items -->
  </ul>
</div>
```

- Hiển thị tối đa 3 lỗi đầu tiên (theo thứ tự field trong form)
- Sau khi hiển thị, scroll đến field lỗi đầu tiên (`scrollIntoView({ behavior: 'smooth' })`)

### 4. Password Strength Indicator

Giữ nguyên UI strength bars hiện tại (`#pwStrength`, `#bar1-4`, `#pwLabel`). Bổ sung checklist gợi ý bên dưới:

```html
<div id="pwHints" class="hidden space-y-1 px-1 mt-1">
  <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Gợi ý để mật khẩu mạnh hơn:</p>
  <div id="hint-upper" class="text-xs text-slate-400">○ Có chữ hoa (A-Z)</div>
  <div id="hint-lower" class="text-xs text-slate-400">○ Có chữ thường (a-z)</div>
  <div id="hint-digit" class="text-xs text-slate-400">○ Có chữ số (0-9)</div>
  <div id="hint-special" class="text-xs text-slate-400">○ Có ký tự đặc biệt (!@#$...)</div>
</div>
```

Hints chuyển sang màu xanh khi tiêu chí được đáp ứng, nhưng **không block submit**.

### 5. AuthController — checkEmail endpoint

```php
// routes/web.php
Route::get('auth/check-email', [AuthController::class, 'checkEmail'])
    ->middleware('guest')
    ->name('auth.check-email');
```

```php
// AuthController.php
public function checkEmail(Request $request): JsonResponse
{
    $email = strtolower(trim($request->get('email', '')));
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['exists' => false]);
    }
    
    $exists = User::where('email', $email)->exists();
    return response()->json(['exists' => $exists]);
}
```

### 6. RegisterRequest (Form Request)

Tách validation rules ra khỏi controller thành một Form Request riêng:

```php
// app/Http/Requests/RegisterRequest.php
class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ho_ten'       => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
            'email'        => ['required', 'email:rfc,dns', 'unique:users,email', 'max:255'],
            'password'     => ['required', 'min:8', 'confirmed'],
            'so_dien_thoai'=> ['nullable', 'regex:/^(03|05|07|08|09)\d{8}$/'],
        ];
    }

    public function messages(): array { /* Vietnamese messages */ }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ho_ten'        => trim($this->ho_ten ?? ''),
            'email'         => strtolower(trim($this->email ?? '')),
            'so_dien_thoai' => trim($this->so_dien_thoai ?? ''),
        ]);
    }
}
```

---

## Data Models

Không có thay đổi schema database. Model `User` hiện tại đã đủ các field cần thiết.

### Validation Rules Summary

| Field | Client (block) | Client (warn) | Server |
|-------|---------------|---------------|--------|
| Họ tên | required, ≥3 chars, no digits, no special chars | — | required, min:3, regex unicode letters |
| Email | required, valid format, no spaces | — | required, email:rfc, unique |
| Số điện thoại | optional; nếu có: digits only, VN prefix, 10 digits | — | nullable, regex VN phone |
| Mật khẩu | required, ≥8 chars | uppercase, lowercase, digit, special char | required, min:8, confirmed |
| Xác nhận MK | required, matches password | — | confirmed (via Laravel) |
| Checkbox | required (highlight đỏ khi submit) | — | — |

### Temp Email Domains

Danh sách domain email tạm được validate **chỉ ở backend** (không block client):

```php
// config/auth.php hoặc constant trong RegisterRequest
const TEMP_EMAIL_DOMAINS = [
    '10minutemail.com', 'guerrillamail.com', 'mailinator.com',
    'tempmail.com', 'throwaway.email', 'yopmail.com',
    'sharklasers.com', 'guerrillamailblock.com', 'grr.la',
    'spam4.me', 'trashmail.com', 'dispostable.com',
];
```

---

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Họ tên ngắn bị từ chối

*For any* chuỗi ký tự có độ dài từ 1 đến 2 (không rỗng), hàm `validateName` SHALL trả về `valid: false` với thông báo lỗi về độ dài tối thiểu.

**Validates: Requirements 1.2**

---

### Property 2: Họ tên chứa số hoặc ký tự đặc biệt bị từ chối

*For any* chuỗi họ tên hợp lệ, nếu thêm vào bất kỳ chữ số (0-9) hoặc ký tự đặc biệt nào, hàm `validateName` SHALL trả về `valid: false`.

**Validates: Requirements 1.3, 1.4**

---

### Property 3: Field state phản ánh đúng kết quả validation

*For any* giá trị input của bất kỳ field nào trong form, trạng thái visual của field (border color, icon) SHALL nhất quán với kết quả validation: valid → green border + ✔, invalid → red border + ❌.

**Validates: Requirements 1.6, 1.7, 2.8, 2.9, 3.4, 3.5, 4.7, 4.8, 5.4, 5.5, 7.2, 7.3, 7.4, 7.5**

---

### Property 4: Email có khoảng trắng bị từ chối

*For any* chuỗi email, nếu chứa ít nhất một ký tự khoảng trắng ở bất kỳ vị trí nào, hàm `validateEmail` SHALL trả về `valid: false` với thông báo lỗi về khoảng trắng.

**Validates: Requirements 2.1**

---

### Property 5: Email được normalize về lowercase

*For any* chuỗi email hợp lệ về định dạng có chứa ký tự hoa, sau khi blur khỏi field email, giá trị hiển thị trong input SHALL bằng `email.toLowerCase()`.

**Validates: Requirements 2.4**

---

### Property 6: AJAX check email chỉ gọi khi email hợp lệ

*For any* giá trị nhập vào field email, AJAX request đến `/auth/check-email` SHALL chỉ được gửi khi và chỉ khi email đó vượt qua validation định dạng (không có khoảng trắng, đúng format).

**Validates: Requirements 2.5**

---

### Property 7: Phone input chỉ chứa chữ số

*For any* chuỗi nhập vào field số điện thoại có chứa ký tự không phải số, sau khi xử lý, giá trị trong input SHALL chỉ còn lại các chữ số (non-digit characters bị strip).

**Validates: Requirements 3.1**

---

### Property 8: Số điện thoại VN hợp lệ phải đúng prefix và đúng 10 số

*For any* chuỗi 10 chữ số không bắt đầu bằng 03/05/07/08/09, hoặc bất kỳ chuỗi chữ số nào có độ dài khác 10, hàm `validatePhone` SHALL trả về `valid: false`.

**Validates: Requirements 3.2, 3.3**

---

### Property 9: Mật khẩu dưới 8 ký tự bị block

*For any* chuỗi mật khẩu có độ dài từ 0 đến 7 ký tự, hàm `validatePassword` SHALL trả về `valid: false` với lỗi về độ dài tối thiểu (đây là lỗi blocking, không phải warning).

**Validates: Requirements 4.2**

---

### Property 10: Xác nhận mật khẩu không khớp bị từ chối

*For any* cặp (password, confirmPassword) mà `password !== confirmPassword`, hàm `validateConfirm` SHALL trả về `valid: false`. Ngược lại, nếu hai giá trị bằng nhau và không rỗng, SHALL trả về `valid: true`.

**Validates: Requirements 5.2, 5.3**

---

### Property 11: ErrorBox hiển thị tối đa 3 lỗi

*For any* trạng thái form có N field không hợp lệ (N ≥ 1), ErrorBox SHALL hiển thị đúng `min(N, 3)` thông báo lỗi — không bao giờ hiển thị nhiều hơn 3 lỗi cùng lúc.

**Validates: Requirements 8.1**

---

### Property 12: Submit button chỉ disable sau khi bấm

*For any* trạng thái form (dù có lỗi hay không), submit button SHALL ở trạng thái enabled trước khi người dùng bấm. Sau khi bấm submit, button SHALL bị disable cho đến khi nhận được phản hồi từ server.

**Validates: Requirements 9.1, 9.5, 10.2**

---

### Property 13: Trim whitespace trước khi submit

*For any* giá trị text field có khoảng trắng đầu/cuối, giá trị được gửi lên server SHALL bằng `value.trim()` — không có leading/trailing whitespace.

**Validates: Requirements 10.1**

---

### Property 14: Server trim dữ liệu trước khi lưu

*For any* request đến `POST /register` với các field text có khoảng trắng đầu/cuối, dữ liệu được lưu vào database SHALL là giá trị đã được trim.

**Validates: Requirements 10.3**

---

### Property 15: Server validate số điện thoại VN

*For any* request đến `POST /register` với `so_dien_thoai` được cung cấp, server SHALL từ chối nếu số điện thoại không khớp regex `^(03|05|07|08|09)\d{8}$`.

**Validates: Requirements 10.6**

---

## Error Handling

### Client-side

| Tình huống | Xử lý |
|-----------|-------|
| AJAX check email thất bại (network error) | Silent fail — không hiển thị lỗi, cho phép submit bình thường |
| AJAX check email timeout (>5s) | Abort request, silent fail |
| Submit thất bại (HTTP 5xx) | Re-enable button, hiển thị "Có lỗi xảy ra, vui lòng thử lại." trong ErrorBox |
| Submit thất bại (network error) | Re-enable button, hiển thị "Không thể kết nối đến server." |
| Server trả về validation errors (422) | Re-enable button, hiển thị server errors trong ErrorBox |

### Server-side

| Tình huống | Xử lý |
|-----------|-------|
| Email đã tồn tại | `unique:users,email` rule → 422 với message "Email này đã được sử dụng." |
| Email tạm thời | Custom validation rule → 422 với message "Không chấp nhận email tạm thời." |
| Rate limiting | `throttle:5,1` middleware trên route register → 429 Too Many Requests |
| Dữ liệu không hợp lệ | `RegisterRequest` validation → redirect back với errors |

### Rate Limiting

```php
// routes/web.php
Route::post('register', 'register')
    ->name('register.post')
    ->middleware('throttle:5,1'); // 5 requests per minute per IP
```

---

## Testing Strategy

### Unit Tests (JavaScript)

Sử dụng **Vitest** (đã có trong project hoặc thêm vào) để test các hàm validation thuần túy.

Các test cần viết:
- `validateName(value)` — test các trường hợp: rỗng, < 3 ký tự, chứa số, chứa ký tự đặc biệt, hợp lệ
- `validateEmail(value)` — test: có khoảng trắng, sai format, hợp lệ
- `validatePhone(value)` — test: rỗng (skip), non-digits, sai prefix, sai độ dài, hợp lệ
- `validatePassword(value)` — test: < 8 ký tự (block), ≥ 8 ký tự (pass), warnings cho từng tiêu chí
- `validateConfirm(value, password)` — test: rỗng, không khớp, khớp
- `showErrorBox(errors)` — test: 1 lỗi, 3 lỗi, 5 lỗi (chỉ hiển thị 3)

### Property-Based Tests (JavaScript)

Sử dụng **fast-check** để test các properties được định nghĩa ở trên.

```javascript
// Ví dụ: Property 2
import fc from 'fast-check';
import { validateName } from '../auth/validators';

test('Feature: register-form-advanced-validation, Property 2: name with digits is invalid', () => {
  fc.assert(
    fc.property(
      fc.stringMatching(/^[\p{L}\s]{3,}$/u),  // valid name base
      fc.integer({ min: 0, max: 9 }),           // digit to inject
      (name, digit) => {
        const nameWithDigit = name + digit.toString();
        const result = validateName(nameWithDigit);
        return result.valid === false;
      }
    ),
    { numRuns: 100 }
  );
});
```

Mỗi property test chạy tối thiểu **100 iterations**.

Tag format: `Feature: register-form-advanced-validation, Property {N}: {property_text}`

### Integration Tests (PHP/Laravel)

Sử dụng **PHPUnit** (có sẵn trong Laravel) để test server-side:

- `POST /register` với dữ liệu hợp lệ → redirect đến dashboard, user được tạo
- `POST /register` với email đã tồn tại → redirect back với error
- `POST /register` với email tạm → redirect back với error
- `POST /register` với phone không hợp lệ → redirect back với error
- `POST /register` với whitespace-padded fields → user được lưu với trimmed values
- `GET /auth/check-email` với email tồn tại → `{"exists": true}`
- `GET /auth/check-email` với email không tồn tại → `{"exists": false}`
- Rate limiting: 6 requests trong 1 phút → request thứ 6 trả về 429

### Smoke Tests

- Form render đúng với tất cả fields
- Submit button hiển thị và enabled khi trang load
- ErrorBox ẩn khi trang load
