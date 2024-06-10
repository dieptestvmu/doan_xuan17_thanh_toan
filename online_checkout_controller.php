<?php

class PaymentController {
    public function online_checkout() {
        if(isset($_POST['redirect'])) {
           
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $timestamp = time(); // Lấy thời gian hiện tại            
            $timestamp += 900; // Cộng thêm 15 phút (900 giây)            
            $new_time_string = date("YmdHis", $timestamp); // Chuyển đổi timestamp thành chuỗi thời gian
        
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "https://localhost/doan_xuan17_thanh_toan/ket_qua_dat_ve.php";
            $vnp_TmnCode = "E8JUE5GS"; // Mã website tại VNPAY 
            $vnp_HashSecret = "ORK8JE8AECEAZB0U4JW99J7OC8EG7240"; // Chuỗi bí mật
            
             // Nhận các biến từ form trong payment.php
             $vnp_TxnRef = isset($_POST['codeVeXe']) ? $_POST['codeVeXe'] : ''; // Mã đơn hàng
             $vnp_OrderInfo = isset($_POST['noiDungThanhToan']) ? $_POST['noiDungThanhToan'] : 'billpayment';
             $vnp_Amount = isset($_POST['soTien']) ? intval($_POST['soTien']) * 100 : 0; // Đơn vị tính là VND, nhân với 100 để chuyển đổi
 
            $vnp_OrderType = 'billpayment';
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';

            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            $vnp_ExpireDate = $new_time_string;
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate"=>$vnp_ExpireDate
            );
            
            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }
            
            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array('code' => '00'
                , 'message' => 'success'
                , 'data' => $vnp_Url);
            if (isset($_POST['redirect'])) {

                // Sau khi chuyển hướng từ VNPay về, cập nhật trạng thái thanh toán
                if (isset($_GET['vnp_ResponseCode']) && $_GET['vnp_ResponseCode'] == '00') {
                    $veXeIDList = explode(',', $_POST['veXeIDs']);
                    foreach ($veXeIDList as $veXeID) {
                        $sqlUpdateThanhToan = "UPDATE thanhtoan SET TrangThaiThanhToan = 'Đã thanh toán' WHERE VeXeID = $veXeID";
                        $conn->query($sqlUpdateThanhToan);
                    }
                }
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
        }
    }
}

$paymentController = new PaymentController();
$paymentController->online_checkout();
?>
