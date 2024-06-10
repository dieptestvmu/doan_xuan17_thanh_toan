$(document).ready(function(){
        $('a[href="#search-section"]').on('click', function(e){
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('#search-section').offset().top -105
}, 1000);
        });

        // Xử lý sự kiện gửi biểu mẫu
        $('#search-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'search_handler.php',
                type: 'GET',
                data: $('#search-form').serialize(),
                success: function(data) {
                    $('#search-results').html(data);
                },
                error: function() {
                    $('#search-results').html('<p class="text-danger">Có lỗi xảy ra. Vui lòng thử lại.</p>');
                }
            });
        });
    });
    