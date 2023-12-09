<?php
require_once('../includes/connect.php');
require_once('checkLogin.php');
require_once('class.php');

        $username = $_SESSION['username'];
        $query1 = "SELECT id FROM ACCOUNT WHERE username = '$username'";
        $IDresult = $conn->query($query1);

        $idRow = $IDresult->fetch_assoc();
        $idValue = $idRow['id'];

        $query = "SELECT studentID FROM INVENTORY WHERE IDaccount = '$idValue'";
        $result = $conn->query($query);

        $query2 = "SELECT fullname FROM INVENTORY WHERE IDaccount = '$idValue'";
        $result2 = $conn->query($query2);

        if ($result && $result2) {
            $row1 = $result->fetch_assoc();
            $studentId = $row1['studentID'];

            $row2 = $result2->fetch_assoc();
            $studentName = $row2['fullname'];
        } else {
            echo "Truy vấn thất bại: " . $conn->error;
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/main.css">
    <link rel="stylesheet" href="../templates/styles/MuaGiay.css">
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400&display=swap" rel="stylesheet">
    <title>Mua thêm trang giấy</title>
</head>
<body>
   <header>
        <div class="logo">
            <img src="../templates/img/logo.png" alt="">
        </div>
        <div class="header_wrap">
            <a href=""><i class="header_menu fa-solid fa-bars"></i></a>
            <span class="header_name"> Mua thêm trang giấy</span>
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

            <div class="form-mua-giay">
                <form action="" class="inputmuagiay" method="post">
                    <div class="form-element">
                        <label class="khogiay muagiay" for="size">Khổ giấy:</label>
                        <select oninput="updateHiddenSize(); showDongia(); showTongGia()" id="size" class="select" class="khogiayinput" name="size">
                            <option value="6"></option>
                            <option value="0">A5</option>
                            <option value="1">A4</option>
                            <option value="2">A3</option>
                            <option value="3">A2</option>
                            <option value="4">A1</option>
                            <option value="5">A0</option>
                        </select>
                        <input type="hidden" name="size" value="0" id="hiddenSize">
                    </div>
    
                    <div class="form-element">
                        <label class="soluong muagiay" for="quantity">Số lượng:</label>
                        <input oninput="showTongGia(); updateHiddenQuantity()"  type="number" class="select2" id="quantity" name="quantity" min="1">
                    </div>

                        <div class="form-element">
                            <label class="dongia muagiay" for="pricePerPaper">Đơn giá:</label>
                            <div class="output1" style="border: solid;">0</div>
                        </div>
        
                        <div class="form-element">
                            <label class="tonggia muagiay" for="cost">Tổng giá:</label>
                            <div class="output2"  style="border: solid;">0</div>
                        </div>
                
                    <script>
                    function updateHiddenSize() {
                        const loaigiay = document.getElementById('size');
                        const hiddenSize = document.getElementById('hiddenSize');
                        hiddenSize.value = loaigiay.value;
                    }

                    function updateHiddenQuantity() {
                        const hiddenQuantity = document.getElementById('hiddenQuantity');
                        const quantity = document.getElementById('quantity').value;
                        hiddenQuantity.value = quantity;
                    }

                    const loaigiay = document.getElementById('size');
                    const quantity = document.getElementById('quantity');
                    const showdongia = document.querySelector('.output1');
                    const showtonggia = document.querySelector('.output2');
                    function showDongia(){
                        var arr=[500,1000,2000,5000,10000,20000,0]
                        showdongia.innerText = arr[loaigiay.value];
                    }
                    function showTongGia(){
                        var arr=[500,1000,2000,5000,10000,20000,0]
                        const tonggia = arr[loaigiay.value]*quantity.value;
                        showtonggia.innerText = tonggia
                    }
                    </script>
                        <form method="post" action="./ThanhToan.php" class="acceptform">
                            <input type="hidden" name="quantity" id="hiddenQuantity" value="">
                            <button type="button" name="acceptPayment" class="accept-btn" onclick="updateHiddenQuantity(); redirectToThanhToan()">Đồng ý</button>
                        </form>


                    <script>
                        function validate() {
                            const size = document.getElementById('size').value;
                            const quantity = document.getElementById('quantity').value;

                            if(!size) {
                                alert("Vui lòng chọn khổ giấy");
                                return false;
                            }

                            if(!quantity) {
                                alert("Vui lòng nhập số lượng"); 
                                return false;
                            }

                            return true;
                        }
                        function redirectToThanhToan() {
                            const quantity = document.getElementById('hiddenQuantity').value;
                            updateHiddenSize(); 
                            if(!validate()) return;
                            const size = document.getElementById('hiddenSize').value;
                            window.location.href = "./ThanhToan.php?quantity=" + quantity + "&size=" + size;
                        }
                    </script>
                </form>
            </div>
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



