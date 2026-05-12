# Bugfix Requirements Document

## Introduction

Form đăng ký tài khoản hiện tại không thực thi đúng yêu cầu mật khẩu tối thiểu 8 ký tự. Cụ thể:

- **Phía client (JavaScript)**: Không có validation nào kiểm tra độ dài mật khẩu trước khi submit — nút "Bắt đầu hành trình ngay" luôn có thể bấm được dù mật khẩu chưa đủ 8 ký tự.
- **Phía server (Laravel)**: Rule `min:6` trong `AuthController@register` cho phép mật khẩu 6–7 ký tự vượt qua validation, thay vì phải là `min:8`.

Lỗi này ảnh hưởng đến bảo mật tài khoản người dùng vì mật khẩu ngắn hơn mức tối thiểu quy định vẫn được chấp nhận.

---

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN người dùng nhập mật khẩu có độ dài nhỏ hơn 8 ký tự (ví dụ: "12345") vào form đăng ký THEN hệ thống không hiển thị thông báo lỗi phía client và nút submit vẫn hoạt động bình thường

1.2 WHEN người dùng submit form đăng ký với mật khẩu có độ dài từ 6 đến 7 ký tự THEN hệ thống chấp nhận và tạo tài khoản thành công (do server-side rule là `min:6`)

1.3 WHEN người dùng submit form đăng ký với mật khẩu có độ dài nhỏ hơn 6 ký tự THEN hệ thống trả về thông báo lỗi "Mật khẩu tối thiểu 6 ký tự" thay vì "Mật khẩu tối thiểu 8 ký tự"

### Expected Behavior (Correct)

2.1 WHEN người dùng nhập mật khẩu có độ dài nhỏ hơn 8 ký tự vào form đăng ký THEN hệ thống SHALL hiển thị thông báo lỗi rõ ràng phía client và ngăn không cho submit form

2.2 WHEN người dùng submit form đăng ký với mật khẩu có độ dài từ 6 đến 7 ký tự THEN hệ thống SHALL từ chối và trả về thông báo lỗi "Mật khẩu tối thiểu 8 ký tự"

2.3 WHEN người dùng submit form đăng ký với mật khẩu có độ dài nhỏ hơn 6 ký tự THEN hệ thống SHALL từ chối và trả về thông báo lỗi "Mật khẩu tối thiểu 8 ký tự"

### Unchanged Behavior (Regression Prevention)

3.1 WHEN người dùng nhập mật khẩu có độ dài đúng bằng 8 ký tự THEN hệ thống SHALL CONTINUE TO chấp nhận mật khẩu và cho phép submit form

3.2 WHEN người dùng nhập mật khẩu có độ dài lớn hơn 8 ký tự THEN hệ thống SHALL CONTINUE TO chấp nhận mật khẩu và cho phép submit form

3.3 WHEN người dùng nhập mật khẩu hợp lệ (≥ 8 ký tự) nhưng không khớp với trường xác nhận mật khẩu THEN hệ thống SHALL CONTINUE TO hiển thị lỗi "Xác nhận mật khẩu không khớp"

3.4 WHEN người dùng đăng ký thành công với mật khẩu hợp lệ (≥ 8 ký tự) THEN hệ thống SHALL CONTINUE TO tạo tài khoản và chuyển hướng đến dashboard

3.5 WHEN người dùng đăng nhập với mật khẩu hợp lệ THEN hệ thống SHALL CONTINUE TO xác thực với rule `min:6` hiện tại (không thay đổi form login)

---

## Bug Condition

```pascal
FUNCTION isBugCondition(X)
  INPUT: X of type RegisterFormInput
  OUTPUT: boolean

  // Trả về true khi mật khẩu không đủ 8 ký tự
  RETURN length(X.password) < 8
END FUNCTION
```

```pascal
// Property: Fix Checking — Mật khẩu dưới 8 ký tự phải bị từ chối
FOR ALL X WHERE isBugCondition(X) DO
  result ← register'(X)
  ASSERT result.isRejected = true
    AND result.errorMessage CONTAINS "8 ký tự"
END FOR

// Property: Preservation Checking — Mật khẩu đủ 8 ký tự vẫn hoạt động bình thường
FOR ALL X WHERE NOT isBugCondition(X) DO
  ASSERT register(X) = register'(X)
END FOR
```
