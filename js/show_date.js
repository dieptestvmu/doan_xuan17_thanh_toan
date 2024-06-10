document.addEventListener("DOMContentLoaded", function() {
        var today = new Date().toISOString().split('T')[0];
        var ngayDiInput = document.getElementById('ngayDi');
        ngayDiInput.value = today;
        ngayDiInput.min = today;

        // Thêm sự kiện click vào div bao quanh ô input
        var dateContainer = document.querySelector('.date-container');
        dateContainer.addEventListener('click', function() {
            ngayDiInput.focus();
            ngayDiInput.click();
        });

        // Kích hoạt lịch khi nhấn vào ô input
        ngayDiInput.addEventListener('focus', function() {
            this.showPicker();
        });

        // Đảm bảo lịch hiện ngay lập tức khi nhấn vào ô input
        ngayDiInput.addEventListener('click', function() {
            this.showPicker();
        });
    });