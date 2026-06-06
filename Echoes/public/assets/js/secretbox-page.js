// secretbox.html - page specific script
// Lưu ngày đã chọn để qua trang mở quà dùng lại

document.addEventListener('DOMContentLoaded', function () {
    var freeDate = document.getElementById('freeDate');
    if (freeDate) {
        freeDate.addEventListener('change', function () {
            localStorage.setItem('secret_free_date', freeDate.value);
        });
    }
});
