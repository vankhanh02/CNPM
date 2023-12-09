<?php
require_once('../includes/connect.php');
require_once('checkLogin.php');

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
        
            $searchquery = "SELECT * FROM printorder WHERE studentid = '$studentId' AND orderid ='" . $_GET['id'] . "'";
            $res = $conn->query($searchquery);
            $row = $res->fetch_assoc();
            $searchquery2 = "SELECT * FROM printdetails WHERE orderid ='" . $_GET['id'] . "'";
            $res2 = $conn->query($searchquery2);

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderid'])) {
                $cancelOrderId = $_POST['orderid'];

                $conn->begin_transaction();

                try {
                    $copiesQuery = "SELECT numofcopies, usedpaper FROM printdetails WHERE orderid = '$cancelOrderId'";

                    $copiesResult = $conn->query($copiesQuery);
                    
                    if ($copiesResult->num_rows > 0) {
                      $copiesRow = $copiesResult->fetch_assoc();
                      
                      $numCopies = $copiesRow['numofcopies'];
                      $usedPaper = $copiesRow['usedpaper'];
                    
                      $incrementPagesQuery = "UPDATE INVENTORYDETAILS 
                                              SET quantity = quantity + $numCopies  
                                              WHERE studentID = '$studentId' AND papername = '$usedPaper'";
                                              
                      $conn->query($incrementPagesQuery);
                    }

                    $deleteDetailsQuery = "DELETE FROM printdetails WHERE orderid = '$cancelOrderId'";
                    $conn->query($deleteDetailsQuery);

                    $deleteOrderQuery = "DELETE FROM printorder WHERE orderid = '$cancelOrderId'";
                    $conn->query($deleteOrderQuery);

                    $conn->commit();

                    header('Location: ./DanhSachCuocHen.php');
                    exit();
                } catch (Exception $e) {
                    $conn->rollback();

                    echo "Error: " . $e->getMessage();
                }
            }
        } else {
            echo "Truy vấn thất bại: " . $conn->error;
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/TBHuyDon.css">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <style>
        .header_list a {
            font-size: 18px; 
            color: #000; 
        }
    </style>
    <title>Document</title>
</head>
<body>
   <header>
        <div class="logo">
        <img src="../templates/img/logo.png" alt="">
        </div>
        <div class="header_wrap">
            <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
            <span class="header_name">  Chi tiết cuộc hẹn</span>
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
            <div class="icon_paper">
                <span class="icon_name">CHI TIẾT ĐƠN HẸN</span>
                <img src="../templates/img/quill-paper.png" alt="">
            </div>
            <?php
            echo'<div class="infor">
                ID đơn hẹn: '.$row['orderid'].'
                <br/>
                MSSV: '.$row['studentid'].'
                <br/>
                ID máy in: '.$row['orderedprinter'].'
                <br/>
                Các thuộc tính:';
                if ($res2->num_rows>0)
                {
                    while($row2 = $res2->fetch_assoc()) {
                        $id = isset($row2['id']) ? $row2['id'] : 'N/A';
                        $filename = isset($row2['filename']) ? $row2['filename'] : 'N/A';
                        $tmp = isset($row2['colorused']) && $row2['colorused'] == 'Black' ? "In màu" : "In đen trắng";
                        $usedPaper = isset($row2['usedpaper']) ? $row2['usedpaper'] : 'N/A';
                        $pagesPerPaper = isset($row2['pagesperpaper']) ? $row2['pagesperpaper'] : 'N/A';
                        $numPages = isset($row2['numpages']) ? $row2['numpages'] : 'N/A';
                        $numOfCopies = isset($row2['numofcopies']) ? $row2['numofcopies'] : 'N/A';
                        $price = isset($row2['price']) ? $row2['price'] : 'N/A';
                    
                        echo '<br/>
                        + ID: ' . $id . '
                        <br/>
                        + Tên tệp: ' . $filename . '
                        <br/>
                        + Kiểu in: ' . $tmp . '
                        <br/>
                        + Khổ trang in: ' . $usedPaper . '
                        <br/>
                        + Số mặt in: ' . $pagesPerPaper . '
                        <br/>
                        + Số trang muốn in: ' . $numPages . '
                        <br/>
                        + Số bản sao: ' . $numOfCopies . '
                        <br/>
                        + Giá: ' . $price . '
                        <br/>
                        </div>';
                    }
                    
                }
                else
                {
                    echo'<br/>
                        + ID: 0
                        <br/>
                        + Loại tệp: 0
                        <br/>
                        + Kiểu in: 0
                        <br/>
                        + Khổ trang in: 0
                        <br/>
                        + Số mặt in: 0
                        <br/>
                        + Số trang muốn in: 0
                        <br/>
                        + Số bản sao: 0
                        <br/>
                        + Giá: 0
                        <br/>
                    </div>';
                }
            ?>
                </div>
                <?php
                    echo'<div class="modal-box">
                        <a href="./ChiTietCuocHen.php?id='.$row['orderid'].'"><i class="fa-solid fa-x"></i></a>
                        <img src="../templates/img/quest_mark.png" alt="" class="mark">
                        <p>Bạn có chắc chắn gửi yêu cầu huỷ đơn này ?</p>';
                ?>
                        <div class="buttons">
                            <?php
                            echo '<a href="./ChiTietCuocHen.php?id=' . $row['orderid'] . '">
                                    <button class="close-btn">Quay lại</button>
                                </a>';
                            
                            echo '<form method="post" action="./TBHuyDon.php" class="acceptform">
                                    <input type="hidden" id="orderid" name="orderid" value="' . $row['orderid'] . '">
                                    <button type="submit" class="accept-btn">Đồng ý</button>
                                </form>';
                            ?>
                        </div>

                </div>
   </main>
   <footer>
    <div class="div"> </div>
    <div class="footer_info">
        <p>Copyright 2023 Trường Đại Học Bách Khoa - ĐHQG TP.HCM. All Rights Reserved.</p>
        <p>Địa chỉ: Đông Hòa, TP Dĩ An, Bình Dương.</p>
    </div>
   </footer>
</body>
</html>