// =========================================
// Modal - Login / Auth
// Dùng chung cho tất cả trang
// =========================================

function openModal() {
    document.getElementById('loginModal').classList.add('show');
}

function closeModal() {
    document.getElementById('loginModal').classList.remove('show');
}

// Đóng modal khi click bên ngoài
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('loginModal');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }
});
