// =========================================
// Load Layout - Header & Footer
// Dùng chung cho tất cả trang
// =========================================

(function () {
    // Load header
    var headerEl = document.getElementById('header');
    if (headerEl) {
        fetch('components/user_header.html')
            .then(function (res) { return res.text(); })
            .then(function (data) {
                headerEl.innerHTML = data;

                if (window.authManager) {
                    authManager.updateHeaderUI();
                }

                var scrollScript = document.createElement('script');
                scrollScript.src = 'assets/js/scroll_header.js';
                document.body.appendChild(scrollScript);

                var headerScript = document.createElement('script');
                headerScript.src = 'assets/js/header.js';
                headerScript.onload = function () {
                    setTimeout(function () {
                        if (window.mobileMenuFunctions && window.mobileMenuFunctions.init) {
                            window.mobileMenuFunctions.init();
                        }
                    }, 100);
                };
                document.body.appendChild(headerScript);
            })
            .catch(function (err) { console.error('Lỗi header:', err); });
    }

    // Load footer
    var footerEl = document.getElementById('footer');
    if (footerEl) {
        fetch('components/footer.html')
            .then(function (res) { return res.text(); })
            .then(function (data) { footerEl.innerHTML = data; })
            .catch(function (err) { console.error('Lỗi footer:', err); });
    }
})();
