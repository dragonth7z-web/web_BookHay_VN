# Implementation Plan: Register Form Advanced Validation

## Overview

Nâng cấp toàn bộ hệ thống validation cho form đăng ký THLD Bookstore. Triển khai theo thứ tự: backend trước (RegisterRequest, checkEmail endpoint, rate limiting), sau đó frontend (RegisterValidator class, field state UI, ErrorBox, pwHints), cuối cùng cập nhật Blade view để wire tất cả lại.

## Tasks

- [x] 1. Tạo RegisterRequest với server-side validation
  - Tạo file `app/Http/Requests/RegisterRequest.php`
  - Implement `rules()`: `ho_ten` (required, min:3, regex unicode letters+spaces), `email` (required, email:rfc, unique:users,email), `password` (required, min:8, confirmed), `so_dien_thoai` (nullable, regex VN phone)
  - Implement `messages()` với thông báo tiếng Việt cho từng rule
  - Implement `prepareForValidation()`: trim `ho_ten`, lowercase+trim `email`, trim `so_dien_thoai`
  - Thêm custom validation rule cho temp email domains (danh sách trong design.md) vào `withValidator()`
  - _Requirements: 10.3, 10.4, 10.5, 10.6_

  - [x] 1.1 Viết PHPUnit integration test cho RegisterRequest
    - Test `POST /register` với dữ liệu hợp lệ → redirect dashboard, user được tạo trong DB
    - Test `POST /register` với email đã tồn tại → redirect back với error "Email này đã được sử dụng."
    - Test `POST /register` với email tạm (mailinator.com) → redirect back với error "Không chấp nhận email tạm thời."
    - Test `POST /register` với `so_dien_thoai` không hợp lệ (sai prefix, sai độ dài) → redirect back với error
    - Test `POST /register` với fields có whitespace → user được lưu với trimmed values
    - _Requirements: 10.3, 10.4, 10.5, 10.6_

- [x] 2. Cập nhật AuthController: dùng RegisterRequest và thêm checkEmail
  - Cập nhật `AuthController@register`: thay `$request->validate(...)` bằng type-hint `RegisterRequest $request`
  - Thêm method `checkEmail(Request $request): JsonResponse`: validate email format, query `User::where('email', $email)->exists()`, trả về `{"exists": bool}`
  - _Requirements: 2.5, 2.6, 10.3, 10.4_

  - [x] 2.1 Viết PHPUnit integration test cho checkEmail endpoint
    - Test `GET /auth/check-email?email=existing@test.com` → `{"exists": true}`
    - Test `GET /auth/check-email?email=new@test.com` → `{"exists": false}`
    - Test `GET /auth/check-email?email=invalid-format` → `{"exists": false}`
    - _Requirements: 2.5, 2.6_

- [x] 3. Thêm route check-email và rate limiting vào routes/web.php
  - Thêm `Route::get('auth/check-email', [AuthController::class, 'checkEmail'])->middleware('guest')->name('auth.check-email')` vào nhóm auth routes
  - Thêm middleware `throttle:5,1` vào route `POST register`
  - _Requirements: 2.5, 10.2_

  - [x] 3.1 Viết PHPUnit test cho rate limiting
    - Test 6 requests `POST /register` trong 1 phút từ cùng IP → request thứ 6 trả về HTTP 429
    - _Requirements: 10.2_

- [x] 4. Checkpoint — Backend hoàn chỉnh
  - Đảm bảo tất cả PHPUnit tests pass, ask the user if questions arise.

- [x] 5. Tạo RegisterValidator class trong auth.js
  - Tạo class `RegisterValidator` với constructor nhận `formId`
  - Implement `init()`: gắn event listeners blur/input cho tất cả fields, gắn submit handler
  - Implement `setFieldState(fieldId, state, message)`: cập nhật border class và inject/remove icon vào wrapper `.relative.group` của field (state: `'idle'|'valid'|'invalid'|'loading'`)
  - Implement `showErrorBox(errors)`: render tối đa 3 lỗi đầu tiên vào `#errorBox`, scroll đến field lỗi đầu tiên
  - Implement `hideErrorBox()`
  - Giữ nguyên các hàm hiện có (`togglePassword`, `handleAuthFormSubmit`, `initPasswordStrength`) để không break login form
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5, 8.1, 8.4_

  - [x] 5.1 Viết property test cho Property 11: ErrorBox hiển thị tối đa 3 lỗi
    - **Property 11: ErrorBox hiển thị tối đa 3 lỗi**
    - **Validates: Requirements 8.1**
    - Dùng fast-check: với N lỗi bất kỳ (N ≥ 1), `showErrorBox` chỉ render `min(N, 3)` items

  - [x] 5.2 Viết property test cho Property 3: Field state nhất quán với validation result
    - **Property 3: Field state phản ánh đúng kết quả validation**
    - **Validates: Requirements 1.6, 1.7, 2.8, 2.9, 3.4, 3.5, 4.7, 4.8, 5.4, 5.5, 7.2, 7.3, 7.4, 7.5**
    - Dùng fast-check: với bất kỳ input value nào, `setFieldState('valid', ...)` → border-green-500, `setFieldState('invalid', ...)` → border-red-500

- [x] 6. Implement validateName
  - Implement `validateName(value)`: trả về `{ valid: bool, error: string|null }`
  - Logic: required (lỗi "Vui lòng nhập họ và tên."), min 3 chars (lỗi "Họ và tên phải có ít nhất 3 ký tự."), no digits (lỗi "Họ và tên không được chứa chữ số."), no special chars — chỉ cho phép chữ cái unicode + khoảng trắng (lỗi "Họ và tên không được chứa ký tự đặc biệt.")
  - Gắn blur + debounce 500ms input event cho field `#ho_ten`
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.6, 1.7_

  - [x] 6.1 Viết property test cho Property 1: Họ tên ngắn bị từ chối
    - **Property 1: Họ tên ngắn bị từ chối**
    - **Validates: Requirements 1.2**
    - Dùng fast-check: với bất kỳ string độ dài 1-2, `validateName` trả về `valid: false`

  - [x] 6.2 Viết property test cho Property 2: Họ tên chứa số/ký tự đặc biệt bị từ chối
    - **Property 2: Họ tên chứa số hoặc ký tự đặc biệt bị từ chối**
    - **Validates: Requirements 1.3, 1.4**
    - Dùng fast-check: với valid name base + inject digit hoặc special char, `validateName` trả về `valid: false`

- [x] 7. Implement validateEmail và AJAX checkEmailExists
  - Implement `validateEmail(value)`: required, no spaces (lỗi "Email không được chứa khoảng trắng."), valid format (lỗi "Địa chỉ email không hợp lệ.")
  - Implement `checkEmailExists(email)`: debounce 500ms, gọi `GET /auth/check-email?email=...`, set field state `loading` khi đang gọi, set `invalid` nếu exists, set `valid` nếu không exists; silent fail nếu network error
  - Gắn blur event: lowercase normalize → validateEmail → nếu valid thì checkEmailExists
  - Gắn input event: debounce 500ms → validateEmail (không gọi AJAX khi đang gõ)
  - _Requirements: 2.1, 2.2, 2.4, 2.5, 2.6, 2.7, 2.8, 2.9_

  - [x] 7.1 Viết property test cho Property 4: Email có khoảng trắng bị từ chối
    - **Property 4: Email có khoảng trắng bị từ chối**
    - **Validates: Requirements 2.1**
    - Dùng fast-check: với bất kỳ string có ít nhất 1 khoảng trắng, `validateEmail` trả về `valid: false`

  - [x] 7.2 Viết property test cho Property 5: Email normalize về lowercase
    - **Property 5: Email được normalize về lowercase**
    - **Validates: Requirements 2.4**
    - Dùng fast-check: với email hợp lệ có chứa uppercase, sau khi blur giá trị input === `email.toLowerCase()`

  - [x] 7.3 Viết property test cho Property 6: AJAX chỉ gọi khi email hợp lệ
    - **Property 6: AJAX check email chỉ gọi khi email hợp lệ**
    - **Validates: Requirements 2.5**
    - Dùng fast-check: với email không hợp lệ (có spaces hoặc sai format), `checkEmailExists` không được gọi

- [x] 8. Implement validatePhone
  - Implement `validatePhone(value)`: nếu rỗng trả về `{ valid: true, skip: true }` (optional field)
  - Logic khi có giá trị: chỉ giữ digits (strip non-digits ngay trên `input` event), check VN prefix 03/05/07/08/09 (lỗi "Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09."), check đúng 10 digits (lỗi "Số điện thoại phải có đúng 10 chữ số.")
  - Gắn `input` event: strip non-digits ngay lập tức (không debounce), sau đó debounce 500ms để validate
  - Gắn `blur` event: validate ngay
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6_

  - [x] 8.1 Viết property test cho Property 7: Phone input chỉ chứa chữ số
    - **Property 7: Phone input chỉ chứa chữ số**
    - **Validates: Requirements 3.1**
    - Dùng fast-check: với bất kỳ string có non-digit chars, sau khi strip chỉ còn digits

  - [x] 8.2 Viết property test cho Property 8: Số điện thoại VN hợp lệ
    - **Property 8: Số điện thoại VN hợp lệ phải đúng prefix và đúng 10 số**
    - **Validates: Requirements 3.2, 3.3**
    - Dùng fast-check: với 10 digits không bắt đầu bằng 03/05/07/08/09, hoặc digits có độ dài ≠ 10, `validatePhone` trả về `valid: false`

- [x] 9. Implement validatePassword và password hints UI
  - Implement `validatePassword(value)`: trả về `{ valid: bool, error: string|null, warnings: string[] }`
  - Logic: length < 8 → `valid: false`, error "Mật khẩu phải có ít nhất 8 ký tự." (blocking); các tiêu chí hoa/thường/số/ký tự đặc biệt → chỉ là warnings (không block)
  - Cập nhật `#pwHints` UI: show/hide hints div, đổi màu từng hint item khi tiêu chí được đáp ứng (xanh) hoặc chưa (slate)
  - Gắn `input` event: debounce 500ms → validatePassword + cập nhật hints
  - Gắn `blur` event: validatePassword + setFieldState
  - _Requirements: 4.1, 4.2, 4.7, 4.8_

  - [x] 9.1 Viết property test cho Property 9: Mật khẩu dưới 8 ký tự bị block
    - **Property 9: Mật khẩu dưới 8 ký tự bị block**
    - **Validates: Requirements 4.2**
    - Dùng fast-check: với bất kỳ string độ dài 0-7, `validatePassword` trả về `valid: false` với error về độ dài

- [x] 10. Implement validateConfirm
  - Implement `validateConfirm(value, passwordValue)`: trả về `{ valid: bool, error: string|null }`
  - Logic: rỗng (sau blur) → lỗi "Vui lòng xác nhận mật khẩu."; không khớp → lỗi "Mật khẩu xác nhận không khớp."
  - Gắn `blur` event trên `#password_confirmation`: validateConfirm
  - Gắn `input` event trên `#password`: nếu `#password_confirmation` đã có giá trị (đã tương tác), re-validate confirm ngay
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

  - [x] 10.1 Viết property test cho Property 10: Xác nhận mật khẩu không khớp bị từ chối
    - **Property 10: Xác nhận mật khẩu không khớp bị từ chối**
    - **Validates: Requirements 5.2, 5.3**
    - Dùng fast-check: với bất kỳ cặp (password, confirm) mà `password !== confirm`, `validateConfirm` trả về `valid: false`; nếu bằng nhau và không rỗng → `valid: true`

- [x] 11. Implement submit handler
  - Implement `handleSubmit(event)` trong RegisterValidator:
    - Chạy validate tất cả fields (name, email, phone nếu có, password, confirm, checkbox)
    - Nếu có lỗi: `showErrorBox(errors)`, scroll đến field lỗi đầu tiên, KHÔNG submit
    - Nếu hợp lệ: trim tất cả text fields trước khi submit, disable submit button, hiển thị loading state "Đang xử lý..."
  - Xử lý server response lỗi (422): re-enable button, hiển thị server errors trong ErrorBox
  - Xử lý network error / HTTP 5xx: re-enable button, hiển thị thông báo lỗi tương ứng
  - _Requirements: 8.1, 8.2, 9.1, 9.2, 9.3, 9.5, 10.1, 10.2_

  - [x] 11.1 Viết property test cho Property 12: Submit button chỉ disable sau khi bấm
    - **Property 12: Submit button chỉ disable sau khi bấm**
    - **Validates: Requirements 9.1, 9.5, 10.2**
    - Dùng fast-check: với bất kỳ form state nào trước khi submit, button ở trạng thái enabled; sau khi submit button bị disabled

  - [x] 11.2 Viết property test cho Property 13: Trim whitespace trước khi submit
    - **Property 13: Trim whitespace trước khi submit**
    - **Validates: Requirements 10.1**
    - Dùng fast-check: với text fields có leading/trailing whitespace, giá trị gửi lên server === `value.trim()`

- [x] 12. Checkpoint — JavaScript validators hoàn chỉnh
  - Đảm bảo tất cả property tests và unit tests pass, ask the user if questions arise.

- [x] 13. Cập nhật register.blade.php
  - Thêm `<span>` error message ngay dưới mỗi field: `#error-ho_ten`, `#error-email`, `#error-so_dien_thoai`, `#error-password`, `#error-password_confirmation`
  - Thêm `#errorBox` div (theo markup trong design.md) trước thẻ `<form>` hoặc đầu form, ẩn mặc định
  - Thêm `#pwHints` div (theo markup trong design.md) sau `#pwStrength`, ẩn mặc định
  - Xóa inline `oninvalid`/`oninput` handlers và script `checkFormReady` hiện tại (RegisterValidator sẽ thay thế)
  - Cập nhật `@push('scripts')`: khởi tạo `new RegisterValidator('registerForm').init()` thay cho các hàm cũ
  - _Requirements: 7.1, 8.1, 8.4_

- [x] 14. Final checkpoint — Đảm bảo tất cả tests pass
  - Chạy PHPUnit: `php artisan test --filter RegisterTest`
  - Chạy Vitest: `npx vitest run resources/js/auth`
  - Đảm bảo tất cả tests pass, ask the user if questions arise.

## Notes

- Tasks đánh dấu `*` là optional, có thể bỏ qua để triển khai MVP nhanh hơn
- Mỗi task tham chiếu requirements cụ thể để đảm bảo traceability
- Property tests dùng **fast-check** (JS) — cần cài: `npm install --save-dev fast-check`
- Integration tests dùng **PHPUnit** (có sẵn trong Laravel)
- Giữ nguyên các hàm `togglePassword`, `handleAuthFormSubmit`, `initPasswordStrength` để không break login form
- `RegisterValidator` class được khởi tạo trong Blade, không phải auto-init khi import
