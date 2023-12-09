<?php
require_once('../includes/connect.php');
require_once('checkLogin.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/styles/TrangCaNhan.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <title>TRANG CHU</title>
</head>
<body>
   <header>
        <div class="logo">
            <img src="../templates/img/logo.png" alt="">
        </div>
        <div class="header_wrap">
            <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
            <span class="header_name"> THÔNG TIN CÁ NHÂN</span>
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
            <img class="profile-img" src="../templates/img/user-profile-icon-free-vector.jpg"></img>
            <?php
            $username = $_SESSION['username'];
            $query1 = "SELECT id FROM ACCOUNT WHERE username = '$username'";
            $IDresult = $conn->query($query1);
    
            $idRow = $IDresult->fetch_assoc();
            $idValue = $idRow['id'];
    
            $query = "SELECT studentID FROM INVENTORY WHERE IDaccount = '$idValue'";
            $result = $conn->query($query);
    
            if ($result) {
                $row = $result->fetch_assoc();
                $studentId = $row['studentID'];
            
                $sql_personal_info = "SELECT i.fullname, i.studentid, i.email, i.phone, i.numberpages, d.papername, d.quantity
                                      FROM INVENTORY i
                                      LEFT JOIN INVENTORYDETAILS d ON i.studentID = d.studentID
                                      WHERE i.studentid = ?";
                                      
                $stmt_personal_info = $conn->prepare($sql_personal_info);
    
                if ($stmt_personal_info) {
                    $stmt_personal_info->bind_param("s", $studentId);
                    $stmt_personal_info->execute();
                    $result_personal_info = $stmt_personal_info->get_result();
    
                    if ($result_personal_info->num_rows > 0) {
                        $personal_info = $result_personal_info->fetch_assoc();
                        ?>
    
                        <div class="user-personal-info">
                            <h2>Thông tin sinh viên</h2>
                            <p>Họ và tên: <?php echo $personal_info['fullname']; ?></p>
                            <p>Mã sinh viên: <?php echo $personal_info['studentid']; ?></p>
                            <p>Email: <?php echo $personal_info['email']; ?></p>
                            <p>Số điện thoại: <?php echo $personal_info['phone']; ?></p>
                            <P>Số trang còn lại cho từng loại giấy:</P>
                            <ul>
                                <?php
                                    do {
                                        echo "<li>{$personal_info['papername']}: {$personal_info['quantity']}</li>";
                                    } while ($personal_info = $result_personal_info->fetch_assoc());
                                ?>
                            </ul>
                        </div>
    
                        <?php
                    } else {
                        echo "<p class='error'>Không tìm thấy thông tin cá nhân.</p>";
                    }
    
                    $stmt_personal_info->close();
                }
            } else {
                echo "Truy vấn thất bại: " . $conn->error;
            }
            ?>
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
