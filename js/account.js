document.addEventListener('DOMContentLoaded', function () {
    const editInfoBtn = document.getElementById('editInfoBtn');
    const changePasswordBtn = document.getElementById('changePasswordBtn');
    const editInfoForm = document.getElementById('editInfoForm');
    const changePasswordForm = document.getElementById('changePasswordForm');
    const cancelTicketForm = document.getElementById('cancelTicketForm');
    const warningForm = document.getElementById('warningForm');
    const successForm = document.getElementById('successForm');

    if (editInfoBtn) {
        editInfoBtn.addEventListener('click', function () {
            editInfoForm.style.display = 'block';
            changePasswordForm.style.display = 'none';
        });
    }

    if (changePasswordBtn) {
        changePasswordBtn.addEventListener('click', function () {
            changePasswordForm.style.display = 'block';
            editInfoForm.style.display = 'none';
        });
    }

    document.querySelectorAll('.cancel-ticket-btn').forEach(button => {
        button.addEventListener('click', function () {
            const ticketID = this.getAttribute('data-ticket-id');
            document.getElementById('cancelTicketID').value = ticketID;
            cancelTicketForm.style.display = 'block';
        });
    });

    document.getElementById('closeCancelTicket').addEventListener('click', function () {
        cancelTicketForm.style.display = 'none';
    });

    document.getElementById('confirmCancelTicket').addEventListener('click', function () {
        const reasonSelected = document.querySelector('input[name="reason"]:checked');
        if (!reasonSelected) {
            warningForm.style.display = 'block';
            setTimeout(() => {
                warningForm.style.display = 'none';
            }, 3000);
        } else {
            const form = document.getElementById('cancelTicket');
            const formData = new FormData(form);
            fetch('cancel_ticket.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    cancelTicketForm.style.display = 'none';
                    successForm.style.display = 'block';
                    setTimeout(() => {
                        successForm.style.display = 'none';
                        updateTicketList(data.tickets);
                    }, 2000);
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            });
        }
    });

    function updateTicketList(tickets) {
        const ticketTable = document.querySelector('table');
        ticketTable.innerHTML = '<tr><th>Điểm đi</th><th>Điểm đến</th><th>Ngày khởi hành</th><th>Giờ khởi hành</th><th>Chỗ ngồi</th><th>Giá vé</th><th>Thời gian đặt vé</th><th>Hành động</th></tr>';
        tickets.forEach(ticket => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${ticket.DiemDi}</td>
                <td>${ticket.DiemDen}</td>
                <td>${ticket.NgayKhoiHanh}</td>
                <td>${ticket.GioKhoiHanh}</td>
                <td>${ticket.SoGhe}</td>
                <td>${ticket.GiaVe} VND</td>
                <td>${ticket.ThoiGianDatVe}</td>
                <td><button class='cancel-ticket-btn' data-ticket-id='${ticket.VeXeID}'>Hủy vé</button></td>
            `;
            ticketTable.appendChild(row);
        });
        document.querySelectorAll('.cancel-ticket-btn').forEach(button => {
            button.addEventListener('click', function () {
                const ticketID = this.getAttribute('data-ticket-id');
                document.getElementById('cancelTicketID').value = ticketID;
                cancelTicketForm.style.display = 'block';
            });
        });
    }
});
