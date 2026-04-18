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
        if (this.value.length >= 6) score++;
        if (this.value.length >= 10) score++;
        if (/[A-Z]/.test(this.value) && /[a-z]/.test(this.value)) score++;
        if (/[0-9]/.test(this.value) && /[^A-Za-z0-9]/.test(this.value)) score++;
        score = Math.max(1, score);
        bars.forEach((bar, i) => { if (bar) bar.style.background = i < score ? colors[score - 1] : '#e2e8f0'; });
        if (label) { label.textContent = labels[score - 1]; label.style.color = colors[score - 1]; }
    });
};
