// payment.html - auth UI update on DOMContentLoaded

document.addEventListener('DOMContentLoaded', function () {
    if (window.authManager) {
        var currentUser = authManager.getCurrentUser();
        var accountLink = document.getElementById('payment-account-link');
        var userBox = document.getElementById('payment-user-box');
        var accountName = document.getElementById('payment-account-name');
        var logoutBtn = document.getElementById('payment-logout-btn');

        if (currentUser && currentUser.isLoggedIn) {
            if (accountLink) accountLink.style.display = 'none';
            if (userBox) userBox.style.display = 'inline';
            if (accountName) accountName.textContent = currentUser.username;
            if (logoutBtn) {
                logoutBtn.onclick = function () {
                    authManager.logout();
                    alert('Đăng xuất thành công!');
                    window.location.href = 'index.html';
                };
            }
        } else {
            if (accountLink) accountLink.style.display = 'inline';
            if (userBox) userBox.style.display = 'none';
        }
    }
});
