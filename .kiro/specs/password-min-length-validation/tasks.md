# Implementation Plan

- [x] 1. Write bug condition exploration test
  - **Property 1: Bug Condition** - Mật khẩu dưới 8 ký tự bị từ chối
  - **CRITICAL**: This test MUST FAIL on unfixed code - failure confirms the bug exists
  - **DO NOT attempt to fix the test or the code when it fails**
  - **NOTE**: This test encodes the expected behavior - it will validate the fix when it passes after implementation
  - **GOAL**: Surface counterexamples that demonstrate the bug exists
  - **Scoped PBT Approach**: Scope the property to concrete failing cases — passwords with length 6 and 7 (the range accepted by current `min:6` rule but should be rejected)
  - Test that `POST /register` with `password = "abc123"` (6 ký tự) returns HTTP 422 with message containing "8 ký tự" (from Bug Condition in design: `isBugCondition(X)` where `length(X.password) < 8`)
  - Also test `password = "abc1234"` (7 ký tự) — same expectation
  - Also test `password = "abc12"` (5 ký tự) — expect message "8 ký tự", not "6 ký tự"
  - Run test on UNFIXED code (`AuthController@register` still has `min:6`)
  - **EXPECTED OUTCOME**: Test FAILS — server returns HTTP 302 (redirect) instead of 422 for 6–7 char passwords, and returns "6 ký tự" message for 5-char passwords (this proves the bug exists)
  - Document counterexamples found (e.g., `register("abc123")` returns 302 instead of 422)
  - Mark task complete when test is written, run, and failure is documented
  - _Requirements: 1.1, 1.2, 1.3_

- [x] 2. Write preservation property tests (BEFORE implementing fix)
  - **Property 2: Preservation** - Mật khẩu hợp lệ (≥ 8 ký tự) vẫn hoạt động bình thường
  - **IMPORTANT**: Follow observation-first methodology
  - Observe: `POST /register` with `password = "abcd1234"` (8 ký tự, valid confirmation) → HTTP 302 redirect to dashboard on unfixed code
  - Observe: `POST /register` with `password = "abcdefgh12345"` (13 ký tự) → HTTP 302 redirect to dashboard on unfixed code
  - Observe: `POST /register` with `password = "abcd1234"`, `password_confirmation = "abcd5678"` → HTTP 422 with "Xác nhận mật khẩu không khớp" on unfixed code
  - Observe: `POST /login` with password 6 ký tự → login still works (login uses `min:6`, must not be affected)
  - Write property-based test: for all passwords with `length >= 8` and valid confirmation, registration succeeds (from Preservation Requirements in design: `NOT isBugCondition(X)` → `register(X) = register'(X)`)
  - Property-based testing generates many test cases (lengths 8–100, various character sets) for stronger guarantees
  - Run tests on UNFIXED code
  - **EXPECTED OUTCOME**: Tests PASS (confirms baseline behavior to preserve)
  - Mark task complete when tests are written, run, and passing on unfixed code
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [x] 3. Fix password minimum length validation

  - [x] 3.1 Cập nhật server-side validation trong `AuthController@register`
    - Đổi rule: `'password' => 'required|min:6|confirmed'` → `'password' => 'required|min:8|confirmed'`
    - Đổi message: `'password.min' => 'Mật khẩu tối thiểu 6 ký tự.'` → `'password.min' => 'Mật khẩu tối thiểu 8 ký tự.'`
    - File: `app/Http/Controllers/AuthController.php`, function `register()`
    - KHÔNG thay đổi rule `min:6` trong function `login()` — login không thuộc scope fix
    - _Bug_Condition: `isBugCondition(X)` where `length(X.password) < 8`_
    - _Expected_Behavior: `result.isRejected = true AND result.errorMessage CONTAINS "8 ký tự"`_
    - _Preservation: Mật khẩu ≥ 8 ký tự vẫn được chấp nhận; login không bị ảnh hưởng_
    - _Requirements: 2.2, 2.3, 3.4, 3.5_

  - [x] 3.2 Thêm client-side validation trong `auth.js`
    - Thêm hàm `window.initRegisterValidation(passwordInputId, submitBtnId, errorElementId)` vào `resources/js/auth/auth.js`
    - Hàm lắng nghe sự kiện `input` trên field password, kiểm tra `value.length < 8`
    - Khi `length < 8`: disable nút submit, hiển thị thông báo lỗi inline "Mật khẩu tối thiểu 8 ký tự"
    - Khi `length >= 8`: enable nút submit, ẩn thông báo lỗi
    - Cập nhật ngưỡng trong `initPasswordStrength`: đổi `this.value.length >= 6` thành `this.value.length >= 8`
    - KHÔNG thay đổi `togglePassword`, `handleAuthFormSubmit`, hay bất kỳ logic nào khác
    - _Bug_Condition: `isBugCondition(X)` where `length(X.password) < 8`_
    - _Expected_Behavior: submit bị ngăn phía client khi `length < 8`_
    - _Preservation: Các hàm khác trong `auth.js` không thay đổi_
    - _Requirements: 2.1, 3.1, 3.2_

  - [x] 3.3 Cập nhật `register.blade.php`
    - Thêm `minlength="8"` vào input `password` và `password_confirmation`
    - Gọi `initRegisterValidation(...)` trong script block cuối trang
    - File: `resources/views/auth/register.blade.php`
    - _Requirements: 2.1_

  - [x] 3.4 Verify bug condition exploration test now passes
    - **Property 1: Expected Behavior** - Mật khẩu dưới 8 ký tự bị từ chối
    - **IMPORTANT**: Re-run the SAME test from task 1 - do NOT write a new test
    - The test from task 1 encodes the expected behavior
    - When this test passes, it confirms the expected behavior is satisfied
    - Run bug condition exploration test from step 1
    - **EXPECTED OUTCOME**: Test PASSES — server returns HTTP 422 with "8 ký tự" for passwords with length < 8 (confirms bug is fixed)
    - _Requirements: 2.1, 2.2, 2.3_

  - [x] 3.5 Verify preservation tests still pass
    - **Property 2: Preservation** - Mật khẩu hợp lệ vẫn hoạt động bình thường
    - **IMPORTANT**: Re-run the SAME tests from task 2 - do NOT write new tests
    - Run preservation property tests from step 2
    - **EXPECTED OUTCOME**: Tests PASS — passwords ≥ 8 ký tự vẫn được chấp nhận, login không bị ảnh hưởng, confirmed mismatch vẫn trả về đúng lỗi (confirms no regressions)

- [x] 4. Checkpoint - Ensure all tests pass
  - Chạy toàn bộ test suite để đảm bảo không có regression
  - Kiểm tra thủ công: điền form đăng ký với password 7 ký tự → nút submit bị disable hoặc hiển thị lỗi client-side
  - Kiểm tra thủ công: điền form đăng ký với password 8 ký tự → submit thành công, redirect dashboard
  - Kiểm tra thủ công: form đăng nhập với password 6 ký tự → vẫn hoạt động bình thường
  - Ensure all tests pass, ask the user if questions arise.
