document.addEventListener('DOMContentLoaded', function () {
    const seatForm = document.getElementById('seatForm');
    const totalDiv = document.getElementById('total');
    const giaVeElement = document.getElementById('giaVe');
    const giaVe = parseInt(giaVeElement.value, 10); // Chuyển giá vé thành số nguyên

    let selectedSeats = [];

    const seats = document.querySelectorAll('.seat');
    seats.forEach(function (seat) {
        if (seat.getAttribute('data-seat-status') === 'Trống' || seat.getAttribute('data-seat-status') === 'Đang chọn') {
            seat.addEventListener('click', function () {
                const seatId = seat.getAttribute('data-seat-id');
                const seatStatus = seat.getAttribute('data-seat-status');

                if (seatStatus === 'Trống') {
                    seat.setAttribute('data-seat-status', 'Đang chọn');
                    seat.querySelector('img').src = 'images/logo_ghe_dang_chon.png';
                    selectedSeats.push(seatId);
                } else if (seatStatus === 'Đang chọn') {
                    seat.setAttribute('data-seat-status', 'Trống');
                    seat.querySelector('img').src = 'images/logo_ghe_trong.png';
                    selectedSeats = selectedSeats.filter(id => id !== seatId);
                }

                updateTotal();
            });
        }
    });

    function updateTotal() {
        let totalSeats = selectedSeats.length;
        let totalPrice = totalSeats * giaVe;
        totalDiv.innerHTML = `Số chỗ đã chọn: ${totalSeats}, Tổng tiền: ${totalPrice} VND`;
    }

    seatForm.addEventListener('submit', function (event) {
        const seatsInput = document.createElement('input');
        seatsInput.type = 'hidden';
        seatsInput.name = 'seats';
        seatsInput.value = JSON.stringify(selectedSeats);
        seatForm.appendChild(seatsInput);
    });
});


// JavaScript để xử lý form đăng nhập và đăng ký

// document.addEventListener('DOMContentLoaded', function() {
//     const loginButton = document.getElementById('loginButton');
//     const loginFormContainer = document.getElementById('loginFormContainer');
//     const closeBtn = document.querySelector('.close');
//     const loginForm = document.getElementById('loginForm');
//     const registerForm = document.getElementById('registerForm');
//     const showRegisterFormBtn = document.getElementById('showRegisterForm');
//     const showLoginFormBtn = document.getElementById('showLoginForm');
//     const loginError = document.getElementById('loginError');
//     const registerError = document.getElementById('registerError');

//     loginButton.onclick = function() {
//         loginFormContainer.style.display = 'block';
//     }

//     closeBtn.onclick = function() {
//         loginFormContainer.style.display = 'none';
//     }

//     window.onclick = function(event) {
//         if (event.target == loginFormContainer) {
//             loginFormContainer.style.display = 'none';
//         }
//     }

//     showRegisterFormBtn.onclick = function() {
//         loginForm.style.display = 'none';
//         registerForm.style.display = 'block';
//     }

//     showLoginFormBtn.onclick = function() {
//         registerForm.style.display = 'none';
//         loginForm.style.display = 'block';
//     }

//     loginForm.onsubmit = function(event) {
//         event.preventDefault();
//         const formData = new FormData(loginForm);
//         fetch('login.php', {
//             method: 'POST',
//             body: formData
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 loginFormContainer.style.display = 'none';
//                 // Hiển thị avatar và cập nhật trạng thái đăng nhập
//                 // Tạo avatar giả định
//                 const avatar = document.createElement('img');
//                 avatar.src = 'images/avatar.png';
//                 avatar.alt = 'Avatar';
//                 avatar.id = 'userAvatar';
//                 document.querySelector('header').appendChild(avatar);
//             } else {
//                 loginError.textContent = data.message;
//             }
//         });
//     }

//     registerForm.onsubmit = function(event) {
//         event.preventDefault();
//         const formData = new FormData(registerForm);
//         fetch('register.php', {
//             method: 'POST',
//             body: formData
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 registerForm.style.display = 'none';
//                 loginForm.style.display = 'block';
//                 loginError.textContent = 'Đăng ký thành công! Vui lòng đăng nhập.';
//             } else {
//                 registerError.textContent = data.message;
//             }
//         });
//     }
// });


// đoạn 2
document.addEventListener('DOMContentLoaded', function () {
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginBtn.addEventListener('click', function () {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
    });

    registerBtn.addEventListener('click', function () {
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
    });

    // Existing code for seat selection and updating total price
    // ...
});


// hiển thị form sửa thông tin và đổi mật khẩu

// document.addEventListener('DOMContentLoaded', function () {
//     const editInfoBtn = document.getElementById('editInfoBtn');
//     const changePasswordBtn = document.getElementById('changePasswordBtn');
//     const editInfoForm = document.getElementById('editInfoForm');
//     const changePasswordForm = document.getElementById('changePasswordForm');

//     if (editInfoBtn) {
//         editInfoBtn.addEventListener('click', function () {
//             editInfoForm.style.display = 'block';
//             changePasswordForm.style.display = 'none';
//         });
//     }

//     if (changePasswordBtn) {
//         changePasswordBtn.addEventListener('click', function () {
//             changePasswordForm.style.display = 'block';
//             editInfoForm.style.display = 'none';
//         });
//     }
// });


// Hiển thị form đăng nhập
$(document).ready(function() {
    $('#loginBtn').click(function() {
        $('#login-container').addClass('active').show();
    });

    $('#forgot-password-btn').click(function() {
        $('#login-container').removeClass('active').hide();
        $('#forgot-password-container').addClass('active').show();
    });

    $('#register-btn').click(function() {
        $('#login-container').removeClass('active').hide();
        $('#register-container').addClass('active').show();
    });

    $('#back-to-login-from-forgot').click(function() {
        $('#forgot-password-container').removeClass('active').hide();
        $('#login-container').addClass('active').show();
    });

    $('#back-to-login-from-register').click(function() {
        $('#register-container').removeClass('active').hide();
        $('#login-container').addClass('active').show();
    });

    $('#login-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'login.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    if (data.role === '1') {
                        window.location.href = 'admin/index.php';
                    } else {
                        $('#login-container').removeClass('active').hide();
                        $('#authSection').html('<a href="account.php"><img src="images/login.png" alt="Avatar" style="width: 50px; height: 50px;"></a><span>Xin chào, ' + data.username + '</span><form id="logoutForm" action="logout.php" method="POST" style="display:inline;"><button type="submit">Đăng xuất</button></form>');
                    }
                } else {
                    alert('Tên đăng nhập hoặc mật khẩu không đúng!');
                }
            }
        });
    });

    $('#register-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'register.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#register-container').removeClass('active').hide();
                    $('#login-container').addClass('active').show();
                } else {
                    alert('Đăng ký không thành công!');
                }
            }
        });
    });

    $('#forgot-password-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'forgot_password.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#forgot-password-container').removeClass('active').hide();
                    $('#login-container').addClass('active').show();
                } else {
                    alert('Có lỗi xảy ra!');
                }
            }
        });
    });
});
