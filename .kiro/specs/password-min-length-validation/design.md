# Password Min Length Validation Bugfix Design

## Overview

Form đăng ký tài khoản hiện tại chấp nhận mật khẩu ngắn hơn 8 ký tự do hai lỗi độc lập:

1. **Server-side** (`AuthController@register`): rule `min:6` cho phép mật khẩu 6–7 ký tự vượt qua validation; thông báo lỗi cũng sai ("6 ký tự" thay vì "8 ký tự").
2. **Client-side** (`auth.js` + `register.blade.php`): không có validation độ dài mật khẩu trước khi submit — nút submit luôn hoạt động dù mật khẩu chưa đủ 8 ký tự.

Chiến lược fix: thay đổi tối thiểu, đúng chỗ — chỉ sửa rule server-side và thêm validation client-side vào `auth.js`. Không thay đổi bất kỳ logic nào khác (login, logout, các form khác).

## Glossary

- **Bug_Condition (C)**: Điều kiện kích hoạt lỗi — khi `length(password) < 8` trong form đăng ký
- **Property (P)**: Hành vi đúng khi bug condition xảy ra — form phải từ chối và hiển thị thông báo "Mật khẩu tối thiểu 8 ký tự"
- **Preservation**: Các hành vi hiện tại phải giữ nguyên — mật khẩu ≥ 8 ký tự vẫn được chấp nhận, login không bị ảnh hưởng, các validation khác không thay đổi
- **`AuthController@register`**: Hàm trong `app/Http/Controllers/AuthController.php` xử lý server-side validation và tạo tài khoản
- **`initPasswordStrength`**: Hàm trong `resources/js/auth/auth.js` hiển thị thanh độ mạnh mật khẩu — hiện dùng ngưỡng `>= 6` để tính điểm, cần cập nhật thành `>= 8`
- **`handleAuthFormSubmit`**: Hàm trong `resources/js/auth/auth.js` xử lý loading state khi submit form
- **`registerForm`**: Form HTML trong `resources/views/auth/register.blade.php` với id `registerForm`
- **`password` input**: Input field mật khẩu trong form đăng ký, id `password`

## Bug Details

### Bug Condition

Lỗi xảy ra khi người dùng nhập mật khẩu có độ dài nhỏ hơn 8 ký tự vào form đăng ký. Hệ thống không chặn ở client-side, và server-side chỉ chặn khi dưới 6 ký tự (thay vì 8).

**Formal Specification:**
```
FUNCTION isBugCondition(X)
  INPUT: X of type RegisterFormInput
  OUTPUT: boolean

  RETURN length(X.password) < 8
END FUNCTION
```

### Examples

- Nhập mật khẩu `"abc123"` (6 ký tự) → client không chặn, server chấp nhận tạo tài khoản ✗ (phải bị từ chối)
- Nhập mật khẩu `"abc1234"` (7 ký tự) → client không chặn, server chấp nhận tạo tài khoản ✗ (phải bị từ chối)
- Nhập mật khẩu `"abc12"` (5 ký tự) → client không chặn, server trả về "Mật khẩu tối thiểu 6 ký tự" ✗ (thông báo sai)
- Nhập mật khẩu `"abc12345"` (8 ký tự) → phải được chấp nhận ✓ (unchanged behavior)

## Expected Behavior

### Preservation Requirements

**Unchanged Behaviors:**
- Mật khẩu có độ dài đúng bằng 8 ký tự phải tiếp tục được chấp nhận
- Mật khẩu có độ dài lớn hơn 8 ký tự phải tiếp tục được chấp nhận
- Khi mật khẩu hợp lệ nhưng không khớp với xác nhận, vẫn hiển thị lỗi "Xác nhận mật khẩu không khớp"
- Đăng ký thành công với mật khẩu hợp lệ vẫn tạo tài khoản và redirect đến dashboard
- Form đăng nhập (`login`) không bị ảnh hưởng — vẫn dùng rule `min:6`
- Các chức năng khác của `auth.js` (toggle password, loading state, password strength display) không thay đổi

**Scope:**
Tất cả input KHÔNG thuộc bug condition (`length(password) >= 8`) phải hoàn toàn không bị ảnh hưởng bởi fix này.

## Hypothesized Root Cause

1. **Server-side rule sai**: `AuthController@register` dùng `'password' => 'required|min:6|confirmed'` và message `'password.min' => 'Mật khẩu tối thiểu 6 ký tự.'` — cần đổi thành `min:8` và cập nhật message.

2. **Thiếu client-side validation**: `auth.js` không có hàm nào kiểm tra độ dài mật khẩu trước khi submit. `handleAuthFormSubmit` chỉ xử lý loading state, không validate. Form `register.blade.php` không có attribute `minlength` trên input password.

3. **Ngưỡng trong `initPasswordStrength` không nhất quán**: Hàm tính điểm mạnh dùng `this.value.length >= 6` làm ngưỡng đầu tiên — không nhất quán với yêu cầu 8 ký tự (không phải bug chính nhưng nên cập nhật để nhất quán).

## Correctness Properties

Property 1: Bug Condition - Mật khẩu dưới 8 ký tự bị từ chối

_For any_ input đăng ký mà `isBugCondition` trả về `true` (tức `length(password) < 8`), hệ thống sau khi fix SHALL từ chối form — phía client ngăn submit và/hoặc phía server trả về lỗi với message chứa "8 ký tự".

**Validates: Requirements 2.1, 2.2, 2.3**

Property 2: Preservation - Mật khẩu hợp lệ vẫn hoạt động bình thường

_For any_ input đăng ký mà `isBugCondition` trả về `false` (tức `length(password) >= 8`), hệ thống sau khi fix SHALL cho kết quả giống hệt hệ thống trước khi fix — chấp nhận mật khẩu, tạo tài khoản thành công, và không thay đổi bất kỳ hành vi nào khác.

**Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5**

## Fix Implementation

### Changes Required

**File 1**: `app/Http/Controllers/AuthController.php`

**Function**: `register()`

**Specific Changes**:
1. **Đổi rule validation**: `'password' => 'required|min:6|confirmed'` → `'password' => 'required|min:8|confirmed'`
2. **Đổi message lỗi**: `'password.min' => 'Mật khẩu tối thiểu 6 ký tự.'` → `'password.min' => 'Mật khẩu tối thiểu 8 ký tự.'`

---

**File 2**: `resources/js/auth/auth.js`

**Function**: `handleAuthFormSubmit` (mở rộng) hoặc thêm hàm mới `initRegisterValidation`

**Specific Changes**:
1. **Thêm hàm `initRegisterValidation`**: Lắng nghe sự kiện `input` trên field `password`, kiểm tra `value.length < 8`, hiển thị thông báo lỗi inline và disable nút submit khi chưa đủ 8 ký tự.
2. **Cập nhật ngưỡng trong `initPasswordStrength`**: Đổi `this.value.length >= 6` thành `this.value.length >= 8` để nhất quán với yêu cầu mới.

---

**File 3**: `resources/views/auth/register.blade.php`

**Specific Changes**:
1. **Thêm `minlength="8"`** vào input `password` và `password_confirmation` làm lớp bảo vệ HTML5 native.
2. **Gọi `initRegisterValidation`** trong script block cuối trang.

## Testing Strategy

### Validation Approach

Chiến lược kiểm thử theo hai giai đoạn: trước tiên chạy test trên code CHƯA FIX để xác nhận bug tồn tại (exploratory), sau đó verify fix hoạt động đúng và không gây regression.

### Exploratory Bug Condition Checking

**Goal**: Xác nhận bug tồn tại trên code chưa fix, confirm root cause analysis.

**Test Plan**: Gửi request POST đến route `register` với mật khẩu 6 và 7 ký tự, kiểm tra response. Chạy trên code CHƯA FIX để quan sát lỗi.

**Test Cases**:
1. **Server accepts 6-char password**: POST `/register` với `password = "abc123"` (6 ký tự) → kỳ vọng bị từ chối, thực tế được chấp nhận (sẽ fail trên code chưa fix)
2. **Server accepts 7-char password**: POST `/register` với `password = "abc1234"` (7 ký tự) → kỳ vọng bị từ chối, thực tế được chấp nhận (sẽ fail trên code chưa fix)
3. **Wrong error message for 5-char**: POST `/register` với `password = "abc12"` (5 ký tự) → kỳ vọng message "8 ký tự", thực tế "6 ký tự" (sẽ fail trên code chưa fix)
4. **No client-side block**: Kiểm tra DOM — input `password` không có `minlength`, không có JS validation (sẽ confirm trên code chưa fix)

**Expected Counterexamples**:
- Server trả về HTTP 302 (redirect thành công) thay vì HTTP 422 khi password = 6 hoặc 7 ký tự
- Message lỗi chứa "6 ký tự" thay vì "8 ký tự"

### Fix Checking

**Goal**: Verify rằng với mọi input có bug condition, hệ thống sau fix từ chối đúng cách.

**Pseudocode:**
```
FOR ALL X WHERE isBugCondition(X) DO
  result := register_fixed(X)
  ASSERT result.isRejected = true
    AND result.errorMessage CONTAINS "8 ký tự"
END FOR
```

### Preservation Checking

**Goal**: Verify rằng với mọi input KHÔNG có bug condition, hệ thống sau fix cho kết quả giống hệt trước fix.

**Pseudocode:**
```
FOR ALL X WHERE NOT isBugCondition(X) DO
  ASSERT register_original(X) = register_fixed(X)
END FOR
```

**Testing Approach**: Property-based testing phù hợp cho preservation checking vì:
- Tự động sinh nhiều test case với password có độ dài từ 8 đến N ký tự
- Bắt được edge case (đúng 8 ký tự, password rất dài, ký tự đặc biệt)
- Đảm bảo mạnh mẽ rằng không có regression

**Test Cases**:
1. **Preservation - 8 ký tự chính xác**: POST với `password = "abcd1234"` (8 ký tự) → vẫn tạo tài khoản thành công
2. **Preservation - password dài**: POST với `password = "abcdefgh12345"` (13 ký tự) → vẫn tạo tài khoản thành công
3. **Preservation - confirmed mismatch**: POST với `password = "abcd1234"`, `password_confirmation = "abcd5678"` → vẫn trả về lỗi "Xác nhận mật khẩu không khớp"
4. **Preservation - login không đổi**: POST `/login` với password 6 ký tự → vẫn hoạt động bình thường (login dùng `min:6`)

### Unit Tests

- Test `AuthController@register` với password 5, 6, 7 ký tự → expect validation error với message "8 ký tự"
- Test `AuthController@register` với password 8, 9, 20 ký tự → expect tạo tài khoản thành công
- Test `AuthController@login` với password 6 ký tự → expect không bị ảnh hưởng
- Test client-side: `initRegisterValidation` disable submit khi `password.length < 8`, enable khi `>= 8`

### Property-Based Tests

- Sinh ngẫu nhiên password có `length` từ 1–7: tất cả phải bị từ chối với message chứa "8 ký tự"
- Sinh ngẫu nhiên password có `length` từ 8–100: tất cả phải được chấp nhận (nếu các field khác hợp lệ)
- Sinh ngẫu nhiên password hợp lệ (≥ 8 ký tự) với `password_confirmation` khác nhau: luôn trả về lỗi confirmed

### Integration Tests

- Điền form đăng ký đầy đủ với password 7 ký tự → nút submit bị disable hoặc hiển thị lỗi client-side
- Điền form đăng ký đầy đủ với password 8 ký tự → submit thành công, redirect dashboard
- Kiểm tra form đăng nhập với password 6 ký tự → vẫn hoạt động bình thường (không regression)
