<?php
$conn = new mysqli("localhost", "root", "", "db");
$has_type = 0;
$has_style = 0;
$has_num = 0;
$has_pos = 0;
$has_date = 0;
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
require_once('checkLogin.php');

// if (!empty($_POST)) {
//     $dropquery = "DELETE FROM printorder WHERE orderid = '" . $_POST['orderid'] . "'";
//     $conn->query($dropquery);

//     $searchquery = "SELECT count(*) as total
//                     FROM printorder
//                     WHERE studentid = '$studentId'";
//     $res = $conn->query($searchquery);

//     $totalRows = $res->fetch_assoc()['total'];
//     $totalPages = ceil($totalRows / $limit);

//     $currentPage = min($currentPage, $totalPages);
// }

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

    $limit = 5; 
    
    $searchquery = "SELECT count(*) as total
                    FROM printorder
                    WHERE studentid = '$studentId' AND NOT payed";
    $res = $conn->query($searchquery);

    $totalRows = $res->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $limit;
        
    $searchquery = "SELECT * FROM printorder 
                        LEFT JOIN printdetails ON printdetails.orderid = printorder.orderid 
                        LEFT JOIN printer ON printorder.orderedprinter = printer.printerid 
                        WHERE studentid = '$studentId' AND NOT payed";
} else {
    echo "Truy vấn thất bại: " . $conn->error;
}

if (!empty($_GET)) {
    $searchConditions = [];

    if (array_key_exists("prints-style", $_GET)) {
        if ($_GET["prints-style"] == "color") $searchConditions[] = "printdetails.colorused = 'true'";
        if ($_GET["prints-style"] == "black-white") $searchConditions[] = "printdetails.colorused = 'false'";
    }

    if (array_key_exists("number-prints", $_GET)) {
        if ($_GET["number-prints"] == "1") $searchConditions[] = "printdetails.pagesperpaper = 1";
        if ($_GET["number-prints"] == "2") $searchConditions[] = "printdetails.pagesperpaper = 2";
    }

    if (array_key_exists("position", $_GET)) {
        if ($_GET["position"] == "H1") $searchConditions[] = "printer.buildingid = 'H1'";
        if ($_GET["position"] == "H2") $searchConditions[] = "printer.buildingid = 'H2'";
        if ($_GET["position"] == "H3") $searchConditions[] = "printer.buildingid = 'H3'";
    }

    if (!empty($searchConditions)) {
        $searchquery .= " AND " . implode(" AND ", $searchConditions);
    }
}
    //$searchquery .= " LIMIT $limit OFFSET $offset";

    $res = $conn->query($searchquery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/styles/DanhSachCuocHen.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <script src="../templates/script/router.js" defer></script>
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
            <span class="header_name">  Danh sách cuộc hẹn</span>
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
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" class="search-bar" 
                placeholder="Search ID Order" id="search-order"
                oninput="chooseOrder()">
            </div>

            <div class="filter-container">
                <button class="modal-link" data-modal="test">
                    <i class="fa-solid fa-filter" style="color: #cfcfcf;"></i>
                    <span class="button-text">
                        Filter
                    </span>
                </button>
            </div> 

            <div class="modal-overlay" data-modal="test">
                <div class="modal-table">
                    <div class="modal-table-cell">
                    <div class="modal">
                        <!-- <button class="modal__close"></button> -->
                        <span class="modal__close">&times;</span>
                        <div class="modal__header">Tất cả bộ lọc</div>
                        <div class="modal__content">
                        <!-- Content-->
                        <form method="get" action="./DanhSachCuocHen.php" onsubmit="submitFilterForm()">
                            <p class="typelist"> Kiểu in</p>
                            <div class="checkbox-container">
                                <input type="radio" name="prints-style" value="color" id="color">
                                <label for="color">Màu</label>
                            </div>
                            <div class="checkbox-container">
                                <input type="radio" name="prints-style" value="black-white" id="black-white">
                                <label for="black-white">Trắng đen</label>
                            </div>
                            <br>
                            <p class="typelist"> Số mặt in</p>
                            <div class="checkbox-container">
                                <input type="radio" name="number-prints" value="1" id="1">
                                <label for="1mat">1 mặt</label>
                            </div>
                            <div class="checkbox-container">
                                <input type="radio" name="number-prints" value="2" id="2">
                                <label for="2mat">2 mặt</label>
                            </div>
                            <br>
                            <p class="typelist"> Địa điểm in</p>
                            <div class="checkbox-container">
                                <input type="radio" name="position" value="H1" id="H1">
                                <label for="H1">H1</label>
                            </div>
                            <div class="checkbox-container">
                                <input type="radio" name="position" value="H2" id="H2">
                                <label for="H2">H2</label>
                            </div>
                            <div class="checkbox-container">
                                <input type="radio" name="position" value="H3" id="H3">
                                <label for="H3">H3</label>
                            </div>
                            <p class="typelist"> Ngày in</p>
                            <input type="date" name="orderdate" id="orderdate">
                            <br>
                            <br>
                            <br>
                            <div class="button-group">
                                <input type="reset" value="Xoá tất cả" class="style_button1">
                                <input type="submit" value="Xem kết quả" class="style_button2">
                            </div>
                        </form>

                        </div>
                    </div>
                    </div>
                </div>
    </div>

            <span class="table-name">CÁC CUỘC HẸN ĐÃ TẠO</span>
            <div class="table-container" style="height: 300px;width: 900px; overflow-y: auto;">
                    <table class = "table table-green table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>ID</th>
                                    <th>Địa điểm</th>
                                    <th>Thời gian tạo</th>
                                    <th>Tình trạng</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($res->num_rows > 0)
                            {
                                $stt = 1;
                                while ($row = $res->fetch_assoc())
                                {
                                    if ($row['payed'])
                                        $p = 'Đã trả tiền';
                                    else $p = 'Chưa xử lý';
                                    echo '<tr>
                                            <td>'. $stt .'</td>
                                            <td>'. $row['orderid'] .'</td>
                                            <td>'. $row['buildingid'] .'</td>
                                            <td>'. $row['orderdate'] .'</td>
                                            <td> <a href="./ChiTietCuocHen.php?id='.$row['orderid'].'">'.$p.'</a></td>
                                            
                                        </tr>';
                                        $stt++;
                                }
                            }
                            else
                            {
                                echo "<tr><td colspan='5'>Chưa có đơn hẹn nào được tạo</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                        <!-- <?php
                        // echo '<div class="pagination-container">';
                        // if ($currentPage > 1) {
                        //     echo '  <div class="pagination-prev" onclick="changePage(' . ($currentPage - 1) . ')">&lt;</div>';
                        // } else {
                        //     echo '  <div class="pagination-prev disabled">&lt;</div>';
                        // }
                        // echo '  <div class="pagination">';
                        // for ($i = 1; $i <= $totalPages; $i++) {
                        //     $activeAttr = ($i == $currentPage) ? 'active' : '';
                        //     echo ' 
                        //     <a href="./DanhSachCuocHen.php?page='.$i.'"
                        //     class="'.$activeAttr.'">
                        //         '.$i.'
                        //     </a> ';
                        // }
                        // echo '  </div>';
                        // if ($currentPage < $totalPages) {
                        //     echo '  <div class="pagination-next" onclick="changePage(' . ($currentPage + 1) . ')">&gt;</div>';
                        // } else {
                        //     echo '  <div class="pagination-next disabled">&gt;</div>';
                        // }
                        // echo '</div>';
                        ?> -->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    function changePage(newPage) {
        var filterURL = window.location.href.split('?')[0];
        var printStyle = document.querySelector('input[name="prints-style"]:checked');
        var numPrints = document.querySelector('input[name="number-prints"]:checked');
        var position = document.querySelector('input[name="position"]:checked');
        var orderDate = document.getElementById("orderdate").value;

        filterURL += '?';
        if (printStyle) filterURL += '&prints-style=' + printStyle.value;
        if (numPrints) filterURL += '&number-prints=' + numPrints.value;
        if (position) filterURL += '&position=' + position.value;
        if (orderDate) filterURL += '&orderdate=' + orderDate;

        filterURL += '&page=' + newPage;

        window.location.href = filterURL;
    }


    $(document).ready(function () {

    $(".modal-link").on("click", function () {
    $('.modal-overlay[data-modal="' + $(this).data("modal") + '"]').addClass(
        "modal-overlay_visible"
    );
    });


    $(".modal__close").on("click", function () {
    $(".modal-overlay").removeClass("modal-overlay_visible");
    });


    $(document).on("click", function (e) {
    if ($(".modal-overlay_visible").length) {

        if (
        !$(e.target).closest(".modal").length &&
        !$(e.target).closest(".modal-link").length
        ) {

        $(".modal-overlay").removeClass("modal-overlay_visible");
        }
    }
    });
    });

    function submitFilterForm() {
        var printStyle = document.querySelector('input[name="prints-style"]:checked');
        var numPrints = document.querySelector('input[name="number-prints"]:checked');
        var position = document.querySelector('input[name="position"]:checked');
        var orderDate = document.getElementById("orderdate").value;

        var filterURL = './DanhSachCuocHen.php?page=' + <?php echo $currentPage; ?>;

        if (printStyle) filterURL += '&prints-style=' + printStyle.value;
        if (numPrints) filterURL += '&number-prints=' + numPrints.value;
        if (position) filterURL += '&position=' + position.value;
        if (orderDate) filterURL += '&orderdate=' + orderDate;

        window.location.href = filterURL;
    }

</script>

