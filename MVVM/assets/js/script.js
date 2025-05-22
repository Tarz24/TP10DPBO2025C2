// script.js
document.addEventListener("DOMContentLoaded", function () {
    const confirmLinks = document.querySelectorAll('a[onclick*="confirm"]');

    confirmLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            const message = this.getAttribute("onclick").match(/'([^']+)'/)[1];
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
