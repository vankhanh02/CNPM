<?php
    require_once('class.php');
    require_once('../includes/connect.php');
    require_once('checkLogin.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/styles/adjust-appointment.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <title>Tùy chỉnh lịch hẹn</title>
</head>
<body>
   <header>
        <div class="logo">
            <img src="../templates/img/logo.png" alt="">
        </div>
        <div class="header_wrap">
            <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
            <span class="header_name"> Xem máy in</span>
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
            <div class="content-title">
                <div class="building-group">TÙY CHỈNH ĐƠN HẸN</div>
            </div>
        <?php
            $id = isset($_GET['printerid']) ? $_GET['printerid'] : null;
            if ($id !== null) {
                $sql_query = "SELECT printerid, printername, printerstatus, printertype FROM printer WHERE printerid = ?";
                $stmt = $conn->prepare($sql_query);
                if ($stmt) {
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $result_printer = $stmt->get_result();
                    if ($result_printer->num_rows > 0) {
                        $printer = $result_printer->fetch_assoc(); 
        ?>
            <div class="user-configuration-options">
                <div class="printer-info-wrapper">
                    <img class="printer-icon" alt="" src="../templates/img/printer.png" />
                    <div class="id-and-model-container">
                        <p class="id-and-model">
                            Name: <?php echo $printer['printername'] ?><br/>
                            ID: <?php echo $printer['printerid'] ?><br/>
                            Model: <?php echo $printer['printertype'] ?>
                        </p>
                    </div>
                </div>
                <form class="user-input" action="" method="post" enctype="multipart/form-data">
                    <div class="input-element">
                        <label class="opts" for="file-upload">Chọn file:</label>
                        <div class="file-upload-div">
                            <input type="file" class="inputstyle" id="file-upload" 
                            name="fileToUpload" accept=".pdf,.doc,.docx"
                            required>
                        </div>
                    </div>
                    <div class="input-element">
                        <label class="opts" for="pagetype">Khổ giấy:</label>
                        <select class="inputstyle" id="pagetype" name="pagetype">
                            <option value="A4">A4</option>
                            <option value="A0">A0</option>
                            <option value="A1">A1</option>
                            <option value="A2">A2</option>
                            <option value="A3">A3</option>
                            <option value="A5">A5</option>
                        </select>
                    </div>
                    <div class="input-element">
                        <label class="opts" for="prtcolor">In màu?</label>
                        <select class="inputstyle" id="prtcolor" name="color">
                            <option value="false">Không</option>
                            <option value="true">Có</option>
                        </select>
                    </div>
                    <div class="input-element">
                        <label class="opts" for="numcopy">Số bản sao:</label>
                        <input class="inputstyle" type="number" id="numcopy" min="1" name="copy">
                    </div>
                    <?php
                            if (empty($_POST['copy'])) {
                                echo "<p class='error'>Vui lòng nhập số bản sao</p>";
                            } else {
                                $copy = $_POST['copy'];
                                $pageType = isset($_POST['pagetype']) ? $_POST['pagetype'] : 'A4';
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
                                
                                    $sqlGetQuantityCurrent = "SELECT quantity FROM INVENTORYDETAILS WHERE studentID = ? AND papername = ?";
                                    $quantityCurrent = $conn->prepare($sqlGetQuantityCurrent);

                                    if($quantityCurrent){
                                        $quantityCurrent->bind_param("is", $studentId, $pageType);
                                        $quantityCurrent->execute();

                                        $resultQuantity = $quantityCurrent->get_result();
                                        if ($resultQuantity && $resultQuantity->num_rows > 0) {
                                            $rowQuantityCurrent = $resultQuantity->fetch_assoc();
                                            $currentQuantity = $rowQuantityCurrent['quantity'];

                                            $newQuantity = $currentQuantity - $copy;
                                            if ($currentQuantity - $copy > 0) {
                                                $flagQuantity = true;
                                                $newQuantity = $currentQuantity - $copy;
                                            } else {
                                                $flagQuantity = false;
                                                echo "<p class='error'>Số trang còn lại không đủ</p>";
                                            }
                                        } else {
                                            echo "Truy vấn thất bại: " . $conn->error;
                                        }
                                    }
                                }
                            }

                    ?>
                    <div class="input-element">
                        <label class="opts" for="twosidesopt">In 2 mặt?</label>
                        <select class="inputstyle" id="twosidesopt" name="pagesperpaper">
                            <option value="2">Có</option>
                            <option value="1">Không</option>
                        </select>
                    </div>
                    <div class="input-element">
                        <label class="opts" for="daymonth">Ngày/tháng nhận:</label>
                        <input class="inputstyle" type="date" id="daymonth" name="day">
                    </div>
                    <?php
                            if (empty($_POST['day'])) {
                                echo "<p class='error'>Vui lòng chỉ định ngày nhận tài liệu</p>";
                            }
                    ?>
                    <div class="input-element">
                        <label class="opts" for="prttime">Giờ nhận:</label>
                        <input class="inputstyle" type="time" id="prttime" name="time">
                    </div>
                    <?php
                            if (empty($_POST['day'])) {
                                echo "<p class='error'>Vui lòng chỉ định thời gian nhận tài liệu</p>";
                            }
                    ?>
                    <button type="submit" class="confirm-button" id="confirmButton">Xác nhận</button>
                </form>
            </div>
        <?php            }
                    $stmt->close();
                }
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

<?php
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        return;
    }

    $target_dir = "C:\Users\PC\OneDrive\Documents\Local_Repository_Simulation\Client0\\";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
    $allowed_types = array("pdf", "doc", "docx");
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($file_type, $allowed_types)) {
        echo "Chỉ cho phép upload các loại file: PDF, DOC, DOCX.";
        return;
    }
        
    $file_name = basename($_FILES["fileToUpload"]["name"]);

    if (!file_exists($target_file)) {
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    }
    
    $color = isset($_POST['color']) ? $_POST['color'] : null;
    $pagesPerPaper = isset($_POST['pagesperpaper']) ? $_POST['pagesperpaper'] : null; 
    
    $day = isset($_POST['day']) ? $_POST['day'] : null;
    $time = isset($_POST['time']) ? $_POST['time'] : null;

    if (empty($_POST['copy']) || empty($_POST['day']) || empty($_POST['time'])) {
        return;
    }

    $day = $_POST['day'];
    $time = $_POST['time'];
    $orderPrinter = null;
    $numpages = $copy * $pagesPerPaper;
    $price = 1000 * $copy;

    $daymonth = date('Y-m-d', strtotime($day));
    $prttime = date('H:i:s', strtotime($time)); 
    $timeFormatted = "$daymonth $prttime";

    $creationDate = date('d-m-Y H:i:s');
    
    if($flagQuantity){

    $sqlPrintOrder = "INSERT INTO printorder (studentid, orderedprinter, overallprice, orderdate, receiptdate)
    VALUES ('$studentId' ,'$id', '$price', '$creationDate', '$timeFormatted')";
        
    
    if ($conn->query($sqlPrintOrder) === TRUE) {
        $orderId = $conn->insert_id;
    
        $sqlPrintDetails = "INSERT INTO printdetails (orderid, usedpaper, filename, colorused, price, pagesperpaper, numpages, numofcopies)
                VALUES ('$orderId', '$pageType', '$file_name', '$color', '$price', '$pagesPerPaper', '$numpages', '$copy')";
    
            if ($conn->query($sqlPrintDetails) === TRUE) {
                echo "Đã thêm đơn hẹn thành công.";
        
                $sqlGetInventoryDetails = "SELECT quantity, papername FROM INVENTORYDETAILS WHERE studentID = ? AND papername = ?";
                $stmtGetInventoryDetails = $conn->prepare($sqlGetInventoryDetails);
        
                if ($stmtGetInventoryDetails) {
                    $stmtGetInventoryDetails->bind_param("is", $studentId, $pageType);
                    $stmtGetInventoryDetails->execute();
                    $resultInventoryDetails = $stmtGetInventoryDetails->get_result();
                
                    if ($resultInventoryDetails && $resultInventoryDetails->num_rows > 0) {
                        $rowInventoryDetails = $resultInventoryDetails->fetch_assoc();
                        $currentQuantity = $rowInventoryDetails['quantity'];
                
                        $newQuantity = $currentQuantity - $copy;
                
                        $sqlUpdateQuantity = "UPDATE INVENTORYDETAILS SET quantity = ? WHERE studentID = ? AND papername = ?";
                        $stmtUpdateQuantity = $conn->prepare($sqlUpdateQuantity);
                
                        if ($stmtUpdateQuantity) {
                            $stmtUpdateQuantity->bind_param("iss", $newQuantity, $studentId, $pageType);
                            $stmtUpdateQuantity->execute();
                            $stmtUpdateQuantity->close();
                
                            echo '<div style="position: fixed;  top: 30%; height: 30%; width: 40%; left: 35%">
                            <div style="position: absolute; top: 0%; height: 20%; width: 100%; background: #4CAF50; color: white; text-align: center;">
                                <form method="get" action="TuyChinhDonHen.php">
                                    <input type="hidden" id="printerid" name="printerid" value=' . $_GET['printerid'] . '>
                                    <button type="submit" style="position: absolute; right: 0%; height: 100%; width: 5%">
                                        <img src="../templates/img/close-icon.png" style="position: absolute; width: 100%; height: 100%; top: 0%; left: 0%; cursor: pointer;">
                                    </button>
                                </form>
                            </div>
                        
                            <div style="position: absolute; top: 20%; height: 80%; width: 100%; background: #f9f9f9; font-size: 17px; text-align: center; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);">
                                <p style="padding: 20px;">Đơn hẹn đã được tạo thành công và số dư giấy in của bạn đã được cập nhật.</p>
                                <p style="font-size: 16px; color: #FF0303;">Mã đơn hẹn của bạn: ' . $orderId . '</p>
                            </div>
                        </div>';
                        
                        } else {
                            echo "Lỗi khi chuẩn bị truy vấn cập nhật quantity.";
                        }
                    } else {
                        echo "Không tìm thấy thông tin trong bảng INVENTORYDETAILS.";
                    }
                
                    $stmtGetInventoryDetails->close();
                } else {
                    echo "Lỗi khi chuẩn bị truy vấn lấy thông tin từ INVENTORYDETAILS.";
                }
            } else {
                echo "Lỗi: " . $conn->error;
            }
    
        
    } else {
        echo "Lỗi: " . $conn->error;
    }
    }
    


$conn->close();
?>
