# Requirements Document

## Introduction

Tính năng này nâng cấp toàn bộ hệ thống validation cho form đăng ký tài khoản của THLD Bookstore. Mục tiêu là cải thiện trải nghiệm người dùng thông qua phản hồi lỗi realtime, tăng cường bảo mật mật khẩu, chuẩn hóa dữ liệu đầu vào (họ tên, email, số điện thoại VN), ngăn chặn spam và xử lý trạng thái submit một cách rõ ràng. Validation được thực hiện ở cả phía client (JavaScript) và phía server (Laravel).

## Glossary

- **RegisterForm**: Form đăng ký tài khoản tại `resources/views/auth/register.blade.php`
- **Validator**: Module JavaScript xử lý validation phía client trong `resources/js/auth/auth.js`
- **AuthController**: Controller Laravel xử lý logic đăng ký phía server tại `app/Http/Controllers/AuthController.php`
- **FieldState**: Trạng thái của một input field: `idle` (chưa tương tác), `valid` (hợp lệ), `invalid` (không hợp lệ)
- **ErrorBox**: Khu vực hiển thị tổng hợp tất cả lỗi của form
- **SubmitButton**: Nút "Bắt đầu hành trình ngay" trong RegisterForm
- **TempEmailDomain**: Tên miền email tạm thời (ví dụ: 10minutemail.com, guerrillamail.com, mailinator.com)
- **VNPhonePrefix**: Đầu số điện thoại hợp lệ tại Việt Nam: 03x, 05x, 07x, 08x, 09x

---

## Requirements

### Requirement 1: Validation Họ Tên

**User Story:** As a người dùng, I want nhận phản hồi ngay lập tức khi nhập họ tên không hợp lệ, so that tôi có thể sửa lỗi trước khi submit form.

#### Acceptance Criteria

1. WHEN người dùng để trống field họ tên và rời khỏi field, THE Validator SHALL hiển thị thông báo lỗi "Vui lòng nhập họ và tên."
2. WHEN người dùng nhập họ tên có ít hơn 3 ký tự, THE Validator SHALL hiển thị thông báo lỗi "Họ và tên phải có ít nhất 3 ký tự."
3. WHEN người dùng nhập họ tên chứa chữ số (0-9), THE Validator SHALL hiển thị thông báo lỗi "Họ và tên không được chứa chữ số."
4. WHEN người dùng nhập họ tên chứa ký tự đặc biệt (không phải chữ cái, khoảng trắng, hoặc dấu tiếng Việt), THE Validator SHALL hiển thị thông báo lỗi "Họ và tên không được chứa ký tự đặc biệt."
5. WHEN người dùng nhập họ tên hợp lệ (≥ 3 ký tự, chỉ chữ cái và khoảng trắng), THE Validator SHALL tự động viết hoa chữ cái đầu mỗi từ (title case).
6. WHEN họ tên hợp lệ, THE Validator SHALL hiển thị viền xanh và icon ✔ trên field họ tên.
7. WHEN họ tên không hợp lệ, THE Validator SHALL hiển thị viền đỏ và icon ❌ trên field họ tên.

---

### Requirement 2: Validation Email Nâng Cao

**User Story:** As a người dùng, I want được thông báo ngay khi email không hợp lệ hoặc đã tồn tại, so that tôi không phải chờ đến khi submit mới biết lỗi.

#### Acceptance Criteria

1. WHEN người dùng nhập email chứa khoảng trắng, THE Validator SHALL hiển thị thông báo lỗi "Email không được chứa khoảng trắng."
2. WHEN người dùng nhập email có định dạng không hợp lệ, THE Validator SHALL hiển thị thông báo lỗi "Địa chỉ email không hợp lệ."
3. WHEN người dùng nhập email có tên miền thuộc danh sách TempEmailDomain, THE Validator SHALL hiển thị thông báo lỗi "Không chấp nhận email tạm thời."
4. WHEN người dùng rời khỏi field email với giá trị hợp lệ về định dạng, THE Validator SHALL tự động chuyển toàn bộ email sang chữ thường (lowercase).
5. WHEN người dùng rời khỏi field email với giá trị hợp lệ về định dạng và không thuộc TempEmailDomain, THE Validator SHALL gửi AJAX request đến endpoint `/auth/check-email` để kiểm tra email đã tồn tại chưa.
6. WHEN AJAX request trả về kết quả email đã tồn tại, THE Validator SHALL hiển thị thông báo lỗi "Email này đã được sử dụng."
7. WHEN AJAX request đang xử lý, THE Validator SHALL hiển thị trạng thái loading trên field email.
8. WHEN email hợp lệ và chưa tồn tại, THE Validator SHALL hiển thị viền xanh và icon ✔ trên field email.
9. WHEN email không hợp lệ hoặc đã tồn tại, THE Validator SHALL hiển thị viền đỏ và icon ❌ trên field email.

---

### Requirement 3: Validation Số Điện Thoại Chuẩn VN

**User Story:** As a người dùng, I want được hướng dẫn nhập đúng định dạng số điện thoại Việt Nam, so that tôi không nhập sai mà không biết.

#### Acceptance Criteria

1. WHEN người dùng nhập ký tự không phải số vào field số điện thoại, THE Validator SHALL tự động loại bỏ ký tự đó khỏi input.
2. WHEN người dùng nhập số điện thoại không bắt đầu bằng VNPhonePrefix (03, 05, 07, 08, 09), THE Validator SHALL hiển thị thông báo lỗi "Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09."
3. WHEN người dùng nhập số điện thoại không đủ 10 chữ số, THE Validator SHALL hiển thị thông báo lỗi "Số điện thoại phải có đúng 10 chữ số."
4. WHEN người dùng nhập số điện thoại hợp lệ (10 chữ số, đúng VNPhonePrefix), THE Validator SHALL hiển thị viền xanh và icon ✔ trên field số điện thoại.
5. WHEN người dùng nhập số điện thoại không hợp lệ, THE Validator SHALL hiển thị viền đỏ và icon ❌ trên field số điện thoại.
6. WHERE field số điện thoại để trống, THE Validator SHALL không hiển thị lỗi (field này là tùy chọn).

---

### Requirement 4: Validation Mật Khẩu Bảo Mật Cao

**User Story:** As a người dùng, I want biết mật khẩu của mình có đủ mạnh không ngay khi đang nhập, so that tôi có thể tạo mật khẩu bảo mật hơn.

#### Acceptance Criteria

1. THE Validator SHALL kiểm tra mật khẩu theo 5 tiêu chí: độ dài ≥ 8 ký tự, có chữ hoa (A-Z), có chữ thường (a-z), có chữ số (0-9), có ký tự đặc biệt (!@#$%^&* và tương tự).
2. WHEN người dùng nhập mật khẩu có độ dài dưới 8 ký tự, THE Validator SHALL hiển thị thông báo lỗi "Mật khẩu phải có ít nhất 8 ký tự."
3. WHEN người dùng nhập mật khẩu thiếu chữ hoa, THE Validator SHALL hiển thị thông báo lỗi "Mật khẩu phải chứa ít nhất 1 chữ hoa."
4. WHEN người dùng nhập mật khẩu thiếu chữ thường, THE Validator SHALL hiển thị thông báo lỗi "Mật khẩu phải chứa ít nhất 1 chữ thường."
5. WHEN người dùng nhập mật khẩu thiếu chữ số, THE Validator SHALL hiển thị thông báo lỗi "Mật khẩu phải chứa ít nhất 1 chữ số."
6. WHEN người dùng nhập mật khẩu thiếu ký tự đặc biệt, THE Validator SHALL hiển thị thông báo lỗi "Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt."
7. WHEN mật khẩu đáp ứng tất cả 5 tiêu chí, THE Validator SHALL hiển thị viền xanh và icon ✔ trên field mật khẩu.
8. WHEN mật khẩu không đáp ứng ít nhất 1 tiêu chí, THE Validator SHALL hiển thị viền đỏ và icon ❌ trên field mật khẩu.

---

### Requirement 5: Validation Xác Nhận Mật Khẩu

**User Story:** As a người dùng, I want biết ngay khi mật khẩu xác nhận không khớp, so that tôi có thể sửa trước khi submit.

#### Acceptance Criteria

1. WHEN người dùng để trống field xác nhận mật khẩu và rời khỏi field, THE Validator SHALL hiển thị thông báo lỗi "Vui lòng xác nhận mật khẩu."
2. WHEN người dùng nhập xác nhận mật khẩu không khớp với field mật khẩu, THE Validator SHALL hiển thị thông báo lỗi "Mật khẩu xác nhận không khớp."
3. WHEN người dùng thay đổi giá trị field mật khẩu sau khi đã nhập xác nhận, THE Validator SHALL tự động kiểm tra lại sự khớp của field xác nhận mật khẩu.
4. WHEN xác nhận mật khẩu khớp và không rỗng, THE Validator SHALL hiển thị viền xanh và icon ✔ trên field xác nhận mật khẩu.
5. WHEN xác nhận mật khẩu không khớp hoặc rỗng (sau khi đã tương tác), THE Validator SHALL hiển thị viền đỏ và icon ❌ trên field xác nhận mật khẩu.

---

### Requirement 6: Validation Checkbox Điều Khoản

**User Story:** As a người dùng, I want được nhắc nhở rõ ràng khi chưa đồng ý điều khoản, so that tôi không bỏ qua bước quan trọng này.

#### Acceptance Criteria

1. WHEN người dùng nhấn submit mà chưa tick checkbox điều khoản, THE Validator SHALL highlight checkbox bằng viền đỏ.
2. WHEN người dùng tick checkbox điều khoản, THE Validator SHALL xóa highlight đỏ và hiển thị trạng thái đã đồng ý.
3. WHEN checkbox chưa được tick, THE SubmitButton SHALL ở trạng thái disabled.

---

### Requirement 7: Hiển Thị Lỗi Realtime Từng Field

**User Story:** As a người dùng, I want thấy lỗi ngay dưới từng input khi tôi nhập sai, so that tôi biết chính xác field nào cần sửa.

#### Acceptance Criteria

1. WHEN một field có lỗi, THE Validator SHALL hiển thị thông báo lỗi ngay bên dưới field đó trong vòng 300ms sau khi người dùng dừng nhập.
2. WHEN một field có lỗi, THE Validator SHALL áp dụng viền đỏ (`border-red-500`) cho input đó.
3. WHEN một field hợp lệ, THE Validator SHALL áp dụng viền xanh (`border-green-500`) cho input đó.
4. WHEN một field có lỗi, THE Validator SHALL hiển thị icon ❌ bên trong hoặc bên cạnh input.
5. WHEN một field hợp lệ, THE Validator SHALL hiển thị icon ✔ bên trong hoặc bên cạnh input.
6. WHEN người dùng bắt đầu sửa một field đang có lỗi, THE Validator SHALL xóa thông báo lỗi ngay lập tức.

---

### Requirement 8: ErrorBox Tổng Hợp Lỗi

**User Story:** As a người dùng, I want thấy tổng hợp tất cả lỗi ở một nơi khi submit, so that tôi có cái nhìn tổng quan về những gì cần sửa.

#### Acceptance Criteria

1. WHEN người dùng nhấn submit và có ít nhất 1 field không hợp lệ, THE RegisterForm SHALL hiển thị ErrorBox ở đầu form liệt kê tất cả lỗi hiện tại.
2. WHEN AuthController trả về lỗi validation phía server, THE RegisterForm SHALL hiển thị ErrorBox với các thông báo lỗi từ server.
3. WHEN tất cả lỗi được sửa, THE Validator SHALL ẩn ErrorBox.
4. THE ErrorBox SHALL hiển thị với nền đỏ nhạt, viền đỏ, và danh sách các thông báo lỗi dạng bullet points.

---

### Requirement 9: Quản Lý Trạng Thái Submit

**User Story:** As a người dùng, I want thấy rõ trạng thái của quá trình đăng ký, so that tôi biết hệ thống đang xử lý và không nhấn submit nhiều lần.

#### Acceptance Criteria

1. WHEN người dùng nhấn SubmitButton, THE RegisterForm SHALL disable SubmitButton và thay đổi text thành "Đang xử lý..." kèm spinner loading.
2. WHEN AuthController trả về lỗi server (HTTP 5xx), THE RegisterForm SHALL hiển thị thông báo "Có lỗi xảy ra, vui lòng thử lại." và re-enable SubmitButton.
3. WHEN kết nối mạng bị gián đoạn trong quá trình submit, THE RegisterForm SHALL hiển thị thông báo "Không thể kết nối đến server." và re-enable SubmitButton.
4. WHEN đăng ký thành công, THE AuthController SHALL redirect người dùng đến trang dashboard tài khoản.
5. WHILE RegisterForm đang submit, THE Validator SHALL ngăn người dùng nhấn SubmitButton thêm lần nào nữa.

---

### Requirement 10: Chống Spam và Chuẩn Hóa Dữ Liệu

**User Story:** As a quản trị viên, I want dữ liệu đăng ký được làm sạch và chống spam, so that hệ thống không bị lạm dụng và dữ liệu lưu trữ nhất quán.

#### Acceptance Criteria

1. WHEN người dùng nhấn SubmitButton, THE Validator SHALL trim (xóa khoảng trắng đầu/cuối) tất cả các field text trước khi gửi lên server.
2. WHEN SubmitButton đã được nhấn một lần, THE RegisterForm SHALL disable SubmitButton cho đến khi nhận được phản hồi từ server.
3. THE AuthController SHALL trim và sanitize tất cả dữ liệu đầu vào phía server trước khi lưu vào database.
4. THE AuthController SHALL validate email theo định dạng chuẩn và kiểm tra tính duy nhất trong database.
5. THE AuthController SHALL validate mật khẩu theo đúng 5 tiêu chí bảo mật (độ dài, chữ hoa, chữ thường, số, ký tự đặc biệt) phía server.
6. THE AuthController SHALL validate số điện thoại theo định dạng VNPhonePrefix phía server nếu được cung cấp.

---

### Requirement 11: Trạng Thái SubmitButton

**User Story:** As a người dùng, I want nút submit chỉ được kích hoạt khi form đã hợp lệ hoàn toàn, so that tôi không vô tình submit form chưa đầy đủ.

#### Acceptance Criteria

1. WHILE bất kỳ field bắt buộc nào (họ tên, email, mật khẩu, xác nhận mật khẩu) chưa hợp lệ, THE SubmitButton SHALL ở trạng thái disabled với opacity giảm.
2. WHILE checkbox điều khoản chưa được tick, THE SubmitButton SHALL ở trạng thái disabled.
3. WHEN tất cả field bắt buộc hợp lệ VÀ checkbox điều khoản đã được tick, THE SubmitButton SHALL chuyển sang trạng thái enabled.
4. WHEN SubmitButton ở trạng thái disabled, THE RegisterForm SHALL hiển thị cursor `not-allowed` khi hover lên SubmitButton.
5. WHEN AJAX kiểm tra email đang chạy, THE SubmitButton SHALL ở trạng thái disabled cho đến khi có kết quả.
