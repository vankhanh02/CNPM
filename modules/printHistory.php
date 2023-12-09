<?php
$mysqli = new mysqli("localhost", "root", "", "db");
$has_type = 0;
$has_style = 0;
$has_num = 0;
$has_pos = 0;
$has_date = 0;

if ($mysqli->connect_error) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ./DangNhap.php");
    exit();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query1 = "SELECT id FROM ACCOUNT WHERE username = '$username'";
    $IDresult = $mysqli->query($query1);

    $idRow = $IDresult->fetch_assoc();
    $idValue = $idRow['id'];

    $query = "SELECT fullname FROM INVENTORY WHERE IDaccount = '$idValue'";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullname = $row['fullname'];
    } else {
        $fullname = 'Khách';
    }
}

    if (!empty($_POST)) {
        $dropquery = "DELETE FROM printhistory WHERE id = '" . $_POST['id'] . "'";
        $mysqli->query($dropquery);
    }

    $username = $_SESSION['username'];
    $query1 = "SELECT id FROM ACCOUNT WHERE username = '$username'";
    $IDresult = $mysqli->query($query1);

    $idRow = $IDresult->fetch_assoc();
    $idValue = $idRow['id'];

    $query = "SELECT studentID FROM INVENTORY WHERE IDaccount = '$idValue'";
    $result = $mysqli->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $studentId = $row['studentID'];

        $searchquery = "SELECT * FROM printhistory 
            LEFT JOIN printdetails ON printhistory.id = printdetails.id
            LEFT JOIN printorder ON printdetails.orderid = printorder.orderid
            LEFT JOIN printer ON printorder.orderedprinter = printer.printerid 
            WHERE studentid = '$studentId'
            ";
    } else {
        echo "Truy vấn thất bại: " . $mysqli->error;
    }
    $result = $mysqli->query($searchquery);

    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/style.css">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .header_list a {
            font-size: 18px; 
            color: #000; 
        }
    </style>
    <title>Print History</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="../templates/img/logo.png" alt="">
    </div>
    <div class="header_wrap">
        <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
        <span class="header_name"> Xem cuộc hẹn cá nhân</span>
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
        <div class="table">
        <div class="topbar">
                <div class="search-container">
                    <form action="#"class="search-bar">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass" style="color: #ababab;"></i></button>
                    <input type="text" placeholder="Search..." name="search">
                    </form>
                </div>
                <div>
                    <div class="filter-container">
                        <button class="modal-link" data-modal="test">
                        <i class="fa-solid fa-filter" style="color: #cfcfcf;"></i>
                        <span class="button-text">
                            Filter
                        </span>
                        </button>
                    </div>  
                </div>
                  
            </div>   
            <table>
                <thead>
                <tr>
                    <th>STT</th>
                    <th>ID</th>
                    <th>Thời gian in</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div class="footer-tb">
                <span>Showing 1 to 5 of 50 entries</span>
                <div class="index_buttons"></div>
            </div>
        </div>
    </div>
</main>
<div class="modal-overlay" data-modal="test">
        <div class="modal-table">
            <div class="modal-table-cell">
            <div class="modal">
                <!-- <button class="modal__close"></button> -->
                <span class="modal__close">&times;</span>
                <div class="modal__header">Tất cả bộ lọc</div>
                <div class="modal__content">
                <!-- Content-->
                    <form method="get"action="./printHistory.php">
                        <p class="typelist"> Loại tệp</p>
                        <div class="checkbox-container">
                            <input type="checkbox" name="files-type" value="pdf" id="pdf">
                            <label for="pdf">PDF</label>
                        </div>
                    
                        <div class="checkbox-container">
                            <input type="checkbox" name="files-type" value="word"id="word">
                            <label for="word">Word</label>
                        </div>
                    
                        <div class="checkbox-container">
                            <input type="checkbox" name="files-type" value="jpg"id="jpg">
                            <label for="jpg">JPG</label>
                        </div>
                        <br>
                        <p class="typelist"> Kiểu in</p>
                        <div class="checkbox-container">
                            <input type="checkbox" name="prints-style" value="color"id="color">
                            <label for="colored" >Màu</label>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" name="prints-style" value="black-white" id="black-white">
                            <label for="blackwhite">Trắng đen</label>
                        </div>
                        <br>
                        <p class="typelist"> Số mặt in</p>
                        <div class="checkbox-container">
                            <input type="checkbox" name="number-prints" value ="1" id="1">
                            <label for="1mat">1 mặt</label>
                        </div>
                    
                        <div class="checkbox-container">
                            <input type="checkbox" name="number-prints" value ="2" id="2">
                            <label for="2mat">2 mặt</label>
                        </div>
                        <br>
                        <p class="typelist"> Địa điểm in</p>
                        <div class="checkbox-container">
                            <input type="checkbox" name="position" value="H1" id="H1">
                            <label for="H1">H1</label>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" name="position" value="H2" id="H2">
                            <label for="H2">H2</label>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" name="position" value="H3" id="H3">
                            <label for="H3">H3</label>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" name="position" value="H6" id="H6">
                            <label for="H6">H6</label>
                        </div>
                        <p class="typelist"> Ngày in</p>
                            <input type="date" id="printday" name="printday">
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
<footer>
    <div class="div"></div>
    <div class="footer_info">
        <p>Copyright 2023 Trường Đại Học Bách Khoa - ĐHQG TP.HCM. All Rights Reserved.</p>
        <p>Địa chỉ: Đông Hòa, TP Dĩ An, Bình Dương.</p>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    var recordsPerPage = 5;
    var current_page = 1;
    var totalRecords = 0;

    <?php
    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    echo "var records = " . json_encode($records) . ";";
    ?>

    function preLoadCalculation() {
        totalRecords = records.length;
        max_index = Math.ceil(totalRecords / recordsPerPage);
    }

    function displayIndexButtons() {
        $(".index_buttons button").remove();
        $(".index_buttons").append('<button class="idx_button" onclick="previousPage()">Previous</button>');
        for (var i = 1; i <= max_index; i++) {
            $(".index_buttons").append('<button index="' + i + '" class="idx_button" onclick="goToPage(' + i + ')">' + i + '</button>');
        }
        $(".index_buttons").append('<button class="idx_button" onclick="nextPage()">Next</button>');
        highlightIndexButton();
    }

    function highlightIndexButton() {
        var start_index = (current_page - 1) * recordsPerPage + 1;
        var end_index = Math.min(start_index + recordsPerPage - 1, totalRecords);

        $(".footer-tb span").text('Showing ' + start_index + ' to ' + end_index + ' of ' + totalRecords + ' entries');
        $(".index_buttons button").removeClass('active');
        $(".index_buttons button[index='" + current_page + "']").addClass('active');

        displayTableRows();
    }

    function displayTableRows() {
        $(".table table tbody tr").remove();

        if (totalRecords === 0) {
            // If there are no records, display a message
            var noRecordRow = '<tr><td colspan="3">There is no history to display</td></tr>';
            $(".table table tbody").append(noRecordRow);
        } else {
            var tab_start = (current_page - 1) * recordsPerPage;
            var tab_end = Math.min(tab_start + recordsPerPage, totalRecords);
            for (var i = tab_start; i < tab_end; i++) {
                var tr = '<tr><td>' + (i + 1) + '</td><td><a href="printDetails.php?id=' + records[i]["id"] + '">' + records[i]["id"] + '</a></td><td>' + records[i]["printtime"] + '</td></tr>';
                $(".table table tbody").append(tr);
            }
        }
    }

    function goToPage(page) {
        current_page = page;
        highlightIndexButton();
    }

    function previousPage() {
        if (current_page > 1) {
            current_page--;
            highlightIndexButton();
        }
    }

    function nextPage() {
        if (current_page < max_index) {
            current_page++;
            highlightIndexButton();
        }
    }

    preLoadCalculation();
    displayIndexButtons();
</script>

<script>
        $(document).ready(function () {

        $(".modal-link").on("click", function () {
        $('.modal-overlay[data-modal="' + $(this).data("modal") + '"]').addClass(
            "modal-overlay_visible"
        );
        });

        // Function to close a modal window by clicking on a button

        $(".modal__close").on("click", function () {
        $(".modal-overlay").removeClass("modal-overlay_visible");
        });

        // Function to close the modal window by clicking outside the window

        $(document).on("click", function (e) {
        if ($(".modal-overlay_visible").length) {
        // If there is an open window

            if (
            !$(e.target).closest(".modal").length &&
            !$(e.target).closest(".modal-link").length
            ) {

            $(".modal-overlay").removeClass("modal-overlay_visible");
            }
        }
        });
        });
    </script>
</body>
</html>