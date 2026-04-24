/**
 * Auth — password toggle + form loading state + strength indicator
 * Migrated from public/js/auth/auth.js
 */
window.togglePassword = (id, btn) => {
    const input = document.getElementById(id);
    const icon = btn.querySelector('.material-symbols-outlined');
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.textContent = input.type === 'password' ? 'visibility' : 'visibility_off';
};

window.handleAuthFormSubmit = (formId, btnId, loadingText = 'Đang xử lý...') => {
    document.getElementById(formId)?.addEventListener('submit', () => {
        const btn = document.getElementById(btnId);
        if (!btn) return;
        btn.querySelector('.btn-text')?.textContent && (btn.querySelector('.btn-text').textContent = loadingText);
        btn.querySelector('.btn-icon') && (btn.querySelector('.btn-icon').style.display = 'none');
        btn.querySelector('.btn-loader') && (btn.querySelector('.btn-loader').style.display = 'flex');
        btn.disabled = true;
    });
};

window.initRegisterValidation = (passwordInputId, submitBtnId, errorElementId) => {
    const passwordInput = document.getElementById(passwordInputId);
    const submitBtn = document.getElementById(submitBtnId);
    const errorEl = document.getElementById(errorElementId);
    if (!passwordInput || !submitBtn || !errorEl) return;
    passwordInput.addEventListener('input', function () {
        const len = this.value.length;
        if (len === 0) {
            errorEl.style.display = 'none';
            return;
        }
        if (len < 8) {
            errorEl.textContent = 'Mật khẩu tối thiểu 8 ký tự';
            errorEl.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            errorEl.style.display = 'none';
            submitBtn.disabled = false;
        }
    });
};

window.initPasswordStrength = (inputId, strengthId, barsIds, labelId) => {
    const input = document.getElementById(inputId);
    const strength = document.getElementById(strengthId);
    if (!input || !strength) return;
    const bars = barsIds.map(id => document.getElementById(id));
    const label = document.getElementById(labelId);
    const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
    const labels = ['Yếu', 'Trung bình', 'Khá', 'Mạnh'];
    input.addEventListener('input', function () {
        if (!this.value) { strength.style.display = 'none'; return; }
        strength.style.display = 'flex';
        let score = 0;
        if (this.value.length >= 8) score++;
        if (this.value.length >= 10) score++;
        if (/[A-Z]/.test(this.value) && /[a-z]/.test(this.value)) score++;
        if (/[0-9]/.test(this.value) && /[^A-Za-z0-9]/.test(this.value)) score++;
        score = Math.max(1, score);
        bars.forEach((bar, i) => { if (bar) bar.style.background = i < score ? colors[score - 1] : '#e2e8f0'; });
        if (label) { label.textContent = labels[score - 1]; label.style.color = colors[score - 1]; }
    });
};

export class RegisterValidator {
  constructor(formId) {
    this.form = document.getElementById(formId);
    this.isSubmitting = false;
    this._emailCheckController = null;
  }

  init() {
    if (!this.form) return this;

    // ho_ten
    const hoTenInput = this.form.querySelector('#ho_ten');
    if (hoTenInput) {
      hoTenInput.addEventListener('blur', () => {
        const result = this.validateName(hoTenInput.value);
        this.setFieldState('ho_ten', result.valid ? 'valid' : 'invalid', result.error || '');
      });
      hoTenInput.addEventListener('input', this._debounce(() => {
        if (hoTenInput.value.trim() === '') return;
        const result = this.validateName(hoTenInput.value);
        this.setFieldState('ho_ten', result.valid ? 'valid' : 'invalid', result.error || '');
      }, 500));
    }

    // email
    const emailInput = this.form.querySelector('#email');
    if (emailInput) {
      emailInput.addEventListener('blur', async () => {
        const val = emailInput.value.toLowerCase().trim();
        emailInput.value = val; // normalize to lowercase
        const result = this.validateEmail(val);
        if (!result.valid) {
          this.setFieldState('email', 'invalid', result.error || '');
          return;
        }
        // AJAX check
        this.setFieldState('email', 'loading');
        const exists = await this.checkEmailExists(val);
        if (exists) {
          this.setFieldState('email', 'invalid', 'Email này đã được sử dụng.');
        } else {
          this.setFieldState('email', 'valid');
        }
      });
      emailInput.addEventListener('input', this._debounce(() => {
        const val = emailInput.value;
        if (val.trim() === '') return;
        const result = this.validateEmail(val);
        this.setFieldState('email', result.valid ? 'valid' : 'invalid', result.error || '');
        // Don't call AJAX on input, only on blur
      }, 500));
    }

    // so_dien_thoai
    const phoneInput = this.form.querySelector('#so_dien_thoai');
    if (phoneInput) {
      // Strip non-digits immediately on input (no debounce)
      phoneInput.addEventListener('input', () => {
        const stripped = phoneInput.value.replace(/\D/g, '');
        if (phoneInput.value !== stripped) {
          phoneInput.value = stripped;
        }
      });
      // Debounced validation after stripping
      phoneInput.addEventListener('input', this._debounce(() => {
        if (phoneInput.value === '') return;
        const result = this.validatePhone(phoneInput.value);
        if (result.skip) {
          this.setFieldState('so_dien_thoai', 'idle');
        } else {
          this.setFieldState('so_dien_thoai', result.valid ? 'valid' : 'invalid', result.error || '');
        }
      }, 500));
      phoneInput.addEventListener('blur', () => {
        const result = this.validatePhone(phoneInput.value);
        if (result.skip) {
          this.setFieldState('so_dien_thoai', 'idle');
        } else {
          this.setFieldState('so_dien_thoai', result.valid ? 'valid' : 'invalid', result.error || '');
        }
      });
    }

    // password
    const passwordInput = this.form.querySelector('#password');
    if (passwordInput) {
      passwordInput.addEventListener('input', this._debounce(() => {
        this._updatePasswordHints(passwordInput.value);
        if (passwordInput.value === '') return;
        const result = this.validatePassword(passwordInput.value);
        this.setFieldState('password', result.valid ? 'valid' : 'invalid', result.error || '');
        // Re-validate confirm if it has a value
        const confirmInput = this.form.querySelector('#password_confirmation');
        if (confirmInput && confirmInput.value) {
          const confirmResult = this.validateConfirm(confirmInput.value, passwordInput.value);
          this.setFieldState('password_confirmation', confirmResult.valid ? 'valid' : 'invalid', confirmResult.error || '');
        }
      }, 500));
      passwordInput.addEventListener('blur', () => {
        this._updatePasswordHints(passwordInput.value);
        if (!passwordInput.value) return;
        const result = this.validatePassword(passwordInput.value);
        this.setFieldState('password', result.valid ? 'valid' : 'invalid', result.error || '');
      });
    }

    // password_confirmation
    const confirmInput = this.form.querySelector('#password_confirmation');
    if (confirmInput) {
      confirmInput.addEventListener('blur', () => {
        const pwVal = this.form.querySelector('#password')?.value || '';
        const result = this.validateConfirm(confirmInput.value, pwVal);
        this.setFieldState('password_confirmation', result.valid ? 'valid' : 'invalid', result.error || '');
      });
    }

    // submit
    this.form.addEventListener('submit', (e) => this.handleSubmit(e));

    return this;
  }

  _debounce(fn, delay) {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  }

  // Validation methods (implemented in Tasks 6-10)
  validateName(value) {
    if (!value || value.trim() === '') {
      return { valid: false, error: 'Vui lòng nhập họ và tên.' };
    }
    if (value.trim().length < 3) {
      return { valid: false, error: 'Họ và tên phải có ít nhất 3 ký tự.' };
    }
    if (/\d/.test(value)) {
      return { valid: false, error: 'Họ và tên không được chứa chữ số.' };
    }
    if (!/^[\p{L}\s]+$/u.test(value)) {
      return { valid: false, error: 'Họ và tên không được chứa ký tự đặc biệt.' };
    }
    return { valid: true, error: null };
  }
  validateEmail(value) {
    if (!value || value.trim() === '') {
      return { valid: false, error: 'Vui lòng nhập địa chỉ email.' };
    }
    if (/\s/.test(value)) {
      return { valid: false, error: 'Email không được chứa khoảng trắng.' };
    }
    // Basic email format check
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      return { valid: false, error: 'Địa chỉ email không hợp lệ.' };
    }
    return { valid: true, error: null };
  }
  validatePhone(value) {
    if (!value || value.trim() === '') {
      return { valid: true, error: null, skip: true }; // optional field
    }
    const digits = value.replace(/\D/g, '');
    if (!/^(03|05|07|08|09)/.test(digits)) {
      return { valid: false, error: 'Số điện thoại phải bắt đầu bằng 03, 05, 07, 08 hoặc 09.', skip: false };
    }
    if (digits.length !== 10) {
      return { valid: false, error: 'Số điện thoại phải có đúng 10 chữ số.', skip: false };
    }
    return { valid: true, error: null, skip: false };
  }
  validatePassword(value) {
    if (!value || value.length < 8) {
      return { valid: false, error: 'Mật khẩu phải có ít nhất 8 ký tự.', warnings: [] };
    }
    const warnings = [];
    if (!/[A-Z]/.test(value)) warnings.push('Mật khẩu phải chứa ít nhất 1 chữ hoa.');
    if (!/[a-z]/.test(value)) warnings.push('Mật khẩu phải chứa ít nhất 1 chữ thường.');
    if (!/[0-9]/.test(value)) warnings.push('Mật khẩu phải chứa ít nhất 1 chữ số.');
    if (!/[^A-Za-z0-9]/.test(value)) warnings.push('Mật khẩu phải chứa ít nhất 1 ký tự đặc biệt.');
    return { valid: true, error: null, warnings };
  }

  _updatePasswordHints(value) {
    const hintsDiv = document.getElementById('pwHints');
    if (!hintsDiv) return;
    if (!value) { hintsDiv.classList.add('hidden'); return; }
    hintsDiv.classList.remove('hidden');
    const criteria = [
      { id: 'hint-upper', test: /[A-Z]/.test(value) },
      { id: 'hint-lower', test: /[a-z]/.test(value) },
      { id: 'hint-digit', test: /[0-9]/.test(value) },
      { id: 'hint-special', test: /[^A-Za-z0-9]/.test(value) },
    ];
    criteria.forEach(({ id, test }) => {
      const el = document.getElementById(id);
      if (!el) return;
      el.classList.toggle('text-green-500', test);
      el.classList.toggle('text-slate-400', !test);
    });
  }
  validateConfirm(value, passwordValue) {
    if (!value || value.trim() === '') {
      return { valid: false, error: 'Vui lòng xác nhận mật khẩu.' };
    }
    if (value !== passwordValue) {
      return { valid: false, error: 'Mật khẩu xác nhận không khớp.' };
    }
    return { valid: true, error: null };
  }

  // UI state management
  setFieldState(fieldId, state, message = '') {
    const input = document.getElementById(fieldId);
    if (!input) return;
    const wrapper = input.closest('.relative.group');

    // Remove existing state classes
    input.classList.remove('border-green-500', 'border-red-500', 'border-slate-100');
    // Remove existing icons
    wrapper?.querySelectorAll('.field-state-icon').forEach(el => el.remove());

    if (state === 'idle') {
      input.classList.add('border-slate-100');
      // Remove error message
      const errEl = document.getElementById('error-' + fieldId);
      if (errEl) errEl.textContent = '';
      return;
    }

    if (state === 'valid') {
      input.classList.add('border-green-500');
      this._injectIcon(wrapper, '✔', 'text-green-500');
      const errEl = document.getElementById('error-' + fieldId);
      if (errEl) errEl.textContent = '';
    } else if (state === 'invalid') {
      input.classList.add('border-red-500');
      this._injectIcon(wrapper, '✕', 'text-red-500');
      const errEl = document.getElementById('error-' + fieldId);
      if (errEl) errEl.textContent = message;
    } else if (state === 'loading') {
      input.classList.add('border-slate-100');
      this._injectSpinner(wrapper);
    }
  }

  _injectIcon(wrapper, symbol, colorClass) {
    if (!wrapper) return;
    const span = document.createElement('span');
    span.className = `field-state-icon absolute right-3.5 top-1/2 -translate-y-1/2 text-sm font-bold ${colorClass} pointer-events-none`;
    span.textContent = symbol;
    wrapper.appendChild(span);
  }

  _injectSpinner(wrapper) {
    if (!wrapper) return;
    const span = document.createElement('span');
    span.className = 'field-state-icon absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none';
    span.innerHTML = `<svg class="animate-spin h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
    wrapper.appendChild(span);
  }

  showErrorBox(errors) {
    const box = document.getElementById('errorBox');
    if (!box) return;
    const list = box.querySelector('ul');
    if (!list) return;
    list.innerHTML = '';
    const shown = errors.slice(0, 3);
    shown.forEach(err => {
      const li = document.createElement('li');
      li.className = 'text-sm text-red-600';
      li.textContent = err;
      list.appendChild(li);
    });
    box.classList.remove('hidden');
    // Scroll to first invalid field
    const firstInvalid = this.form?.querySelector('.border-red-500');
    firstInvalid?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  hideErrorBox() {
    document.getElementById('errorBox')?.classList.add('hidden');
  }

  async checkEmailExists(email) {
    if (this._emailCheckController) {
      this._emailCheckController.abort();
    }
    this._emailCheckController = new AbortController();
    try {
      const url = `/auth/check-email?email=${encodeURIComponent(email)}`;
      const response = await fetch(url, {
        signal: this._emailCheckController.signal,
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!response.ok) return false;
      const data = await response.json();
      return data.exists === true;
    } catch (e) {
      // Silent fail on network error or abort
      return false;
    }
  }

  async handleSubmit(event) {
    event.preventDefault();
    if (this.isSubmitting) return;

    // Collect all field values
    const hoTenInput = this.form.querySelector('#ho_ten');
    const emailInput = this.form.querySelector('#email');
    const phoneInput = this.form.querySelector('#so_dien_thoai');
    const passwordInput = this.form.querySelector('#password');
    const confirmInput = this.form.querySelector('#password_confirmation');
    const checkbox = this.form.querySelector('#agreeTerms');

    // Trim text fields
    if (hoTenInput) hoTenInput.value = hoTenInput.value.trim();
    if (emailInput) emailInput.value = emailInput.value.toLowerCase().trim();
    if (phoneInput) phoneInput.value = phoneInput.value.trim();

    // Validate all fields
    const errors = [];

    const nameResult = this.validateName(hoTenInput?.value || '');
    if (!nameResult.valid) {
      this.setFieldState('ho_ten', 'invalid', nameResult.error || '');
      errors.push(nameResult.error);
    }

    const emailResult = this.validateEmail(emailInput?.value || '');
    if (!emailResult.valid) {
      this.setFieldState('email', 'invalid', emailResult.error || '');
      errors.push(emailResult.error);
    }

    const phoneResult = this.validatePhone(phoneInput?.value || '');
    if (!phoneResult.skip && !phoneResult.valid) {
      this.setFieldState('so_dien_thoai', 'invalid', phoneResult.error || '');
      errors.push(phoneResult.error);
    }

    const passwordResult = this.validatePassword(passwordInput?.value || '');
    if (!passwordResult.valid) {
      this.setFieldState('password', 'invalid', passwordResult.error || '');
      errors.push(passwordResult.error);
    }

    const confirmResult = this.validateConfirm(confirmInput?.value || '', passwordInput?.value || '');
    if (!confirmResult.valid) {
      this.setFieldState('password_confirmation', 'invalid', confirmResult.error || '');
      errors.push(confirmResult.error);
    }

    if (checkbox && !checkbox.checked) {
      checkbox.closest('label')?.querySelector('div')?.classList.add('border-red-500');
      errors.push('Bạn cần đồng ý với điều khoản dịch vụ.');
    }

    if (errors.length > 0) {
      this.showErrorBox(errors);
      return;
    }

    // All valid — disable button and submit
    this.hideErrorBox();
    this.isSubmitting = true;
    const submitBtn = this.form.querySelector('#submitBtn');
    if (submitBtn) {
      submitBtn.disabled = true;
      const btnText = submitBtn.querySelector('.btn-text');
      const btnLoader = submitBtn.querySelector('.btn-loader');
      if (btnText) btnText.textContent = 'Đang xử lý...';
      if (btnLoader) btnLoader.classList.remove('hidden');
    }

    try {
      const formData = new FormData(this.form);
      const response = await fetch(this.form.action, {
        method: 'POST',
        body: formData,
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      });

      if (response.ok || response.redirected) {
        // Success — follow redirect
        window.location.href = response.url || '/account';
        return;
      }

      if (response.status === 422) {
        const data = await response.json();
        const serverErrors = [];
        if (data.errors) {
          Object.values(data.errors).forEach(errs => errs.forEach(e => serverErrors.push(e)));
        } else if (data.message) {
          serverErrors.push(data.message);
        }
        this.showErrorBox(serverErrors.length ? serverErrors : ['Dữ liệu không hợp lệ.']);
      } else {
        this.showErrorBox(['Có lỗi xảy ra, vui lòng thử lại.']);
      }
    } catch (e) {
      this.showErrorBox(['Không thể kết nối đến server.']);
    } finally {
      this.isSubmitting = false;
      const submitBtn = this.form.querySelector('#submitBtn');
      if (submitBtn) {
        submitBtn.disabled = false;
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoader = submitBtn.querySelector('.btn-loader');
        if (btnText) btnText.textContent = 'Bắt đầu hành trình ngay';
        if (btnLoader) btnLoader.classList.add('hidden');
      }
    }
  }
}

// Make available globally for Blade
window.RegisterValidator = RegisterValidator;
