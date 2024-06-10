document.querySelectorAll('.toggle-submenu').forEach(function(toggle) {
    toggle.addEventListener('click', function() {
        var submenu = this.nextElementSibling;
        if (submenu.style.display === 'block') {
            submenu.style.display = 'none';
        } else {
            submenu.style.display = 'block';
        }
    });
});

// Xử lý sự kiện nhấn vào các mục lớn và hiện/ẩn các mục con:
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.toggle-submenu').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            var submenu = this.nextElementSibling;
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
                this.classList.remove('active');
            } else {
                submenu.style.display = 'block';
                this.classList.add('active');
            }
        });
    });
});
