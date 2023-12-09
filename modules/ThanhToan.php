<?php
require_once('../includes/connect.php');
require_once('checkLogin.php');

$paymentFlag = false;

$username = $_SESSION['username'];
$query1 = "SELECT id FROM ACCOUNT WHERE username = ?";
$stmtQuery1 = $conn->prepare($query1);
$stmtQuery1->bind_param("s", $username);
$stmtQuery1->execute();
$resultQuery1 = $stmtQuery1->get_result();

if ($resultQuery1 && $resultQuery1->num_rows > 0) {
    $idRow = $resultQuery1->fetch_assoc();
    $idValue = $idRow['id'];

    $query2 = "SELECT studentID, fullname FROM INVENTORY WHERE IDaccount = ?";
    $stmtQuery2 = $conn->prepare($query2);
    $stmtQuery2->bind_param("i", $idValue);
    $stmtQuery2->execute();
    $resultQuery2 = $stmtQuery2->get_result();

    if ($resultQuery2 && $resultQuery2->num_rows > 0) {
        $row1 = $resultQuery2->fetch_assoc();
        $studentId = $row1['studentID'];
        $studentName = $row1['fullname'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/styles/ThanhToan.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400&display=swap" rel="stylesheet">
    <title>Thanh toán</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="../templates/img/logo.png" alt="">
    </div>
    <div class="header_wrap">
        <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
        <span class="header_name"> CHỌN PHƯƠNG THỨC THANH TOÁN</span>
        <div class="header_list">
            <a href="TrangCaNhan.php"><?php echo $fullname; ?><i class="fa-solid fa-user"></i> </a>
            <a href="logout.php?action=logout"><i class="fa-solid fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </div>
</header>
<main>
    <div class="nav">
        <ul class="nav_list">
            <li class="nav_item"><a href="DanhSachToaNha.php"><i class="fa-solid fa-pen-to-square"></i> Danh sách máy in</a></li>
            <li class="nav_item"><a href="DanhSachCuocHen.php"><i class="fa-solid fa-pen-to-square"></i> Danh sách cuộc hẹn</a></li>
            <li class="nav_item"><a href="printHistory.php"><i class="fa-solid fa-pen-to-square"></i> Lịch sử in ấn</a></li>
            <li class="nav_item"><a href="MuaGiay.php"><i class="fa-solid fa-pen-to-square"></i> Mua thêm trang</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="student-information">
            <img class="profile-img" src="../templates/img/user-profile-icon-free-vector.jpg"></img>
            <div class="name-id">
                <p>Họ và tên: <?php echo $studentName; ?></p>
                <p>Mã sinh viên: <?php echo $studentId; ?></p>
            </div>
        </div>
        <div class="payment-images">
            <div class="payment-item" id="momo">
                <img class="momo" src="../templates/img/momo.jpg" alt="Momo">
                <span class="payment-name">Ví điện tử MOMO</span>
            </div>
            <div class="payment-item" id="vnpay">
                <img class="vnpay" src="../templates/img/vnpayqr.png" alt="VNPayQR">
                <span class="payment-name">Cổng thanh toán VNPayQR</span>
            </div>
        </div>


        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var momoImage = document.querySelector('.momo');
                var vnpayImage = document.querySelector('.vnpay');

                momoImage.addEventListener('click', function () {
                    displayPaymentSuccess('Ví điện tử MOMO');
                });

                vnpayImage.addEventListener('click', function () {
                    displayPaymentSuccess('Cổng thanh toán VNPayQR');
                });

                function displayPaymentSuccess(paymentMethod) {
                    alert('Thanh toán thành công qua ' + paymentMethod);
                    
                    <?php $paymentFlag = true ?>
                }
            });
        </script>
    </div>
</main>
<footer>
    <div class="div">
    </div>
    <div class="footer_info">
        <p>Copyright 2023 Trường Đại Học Bách Khoa - ĐHQG TP.HCM. All Rights Reserved.</p>
        <p>Địa chỉ: Đông Hòa, TP Dĩ An, Bình Dương.</p>
    </div>
</footer>
</body>
</html>


<?php
if($paymentFlag){
    if (!empty($_GET) && isset($_GET['quantity']) && isset($_GET['size'])) {
        $quantity = $_GET['quantity'];
        $quantity = intval($quantity);

        $size = intval($_GET['size']);
        $sizeToPapername = [
            0 => 'A5',
            1 => 'A4',
            2 => 'A3',
            3 => 'A2',
            4 => 'A1',
            5 => 'A0'
        ];
        $papername = $sizeToPapername[$size];

        $sqlUpdateQuantity = "UPDATE INVENTORYDETAILS SET quantity = quantity + ? WHERE studentID = ? AND papername = ?";
        $stmtUpdateNumberPages = $conn->prepare($sqlUpdateQuantity);

        $stmtUpdateNumberPages->bind_param("iss", $quantity, $studentId, $papername);
        $stmtUpdateNumberPages->execute();
    }
}
?>