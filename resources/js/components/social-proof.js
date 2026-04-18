/**
 * Social Proof Toast — random purchase notifications
 * Migrated from public/js/components/social-proof.js
 */
export function initSocialProof() {
    const toast = document.getElementById('social-proof-toast');
    if (!toast) return;

    const names = ['Anh Tùng', 'Chị Lan', 'Anh Hoàng', 'Chị Mai', 'Anh Minh', 'Bạn Linh', 'Anh Quốc', 'Chị Thư'];
    const books = ['Đắc Nhân Tâm', 'Nhà Giả Kim', 'Thám Tử Lừng Danh Conan', 'Doraemon tập 45',
        'Cha Giàu Cha Nghèo', 'Tôi Tự Học', 'Người Giàu Nhất Thành Babylon', 'Kinh Tế Học Hài Hước'];
    const times = ['1 phút trước', '3 phút trước', '5 phút trước', 'vừa xong', 'mới đây'];
    const pick = arr => arr[Math.floor(Math.random() * arr.length)];

    const show = () => {
        toast.querySelector('#sp-user').innerText = pick(names);
        toast.querySelector('#sp-book').innerText = `"${pick(books)}"`;
        toast.querySelector('#sp-time').innerText = pick(times);
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 5000);
    };

    setTimeout(() => {
        show();
        setInterval(show, Math.random() * 20000 + 20000);
    }, 10000);
}
