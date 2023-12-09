<?php
session_start();

require_once('../includes/connect.php');

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM ACCOUNT WHERE username = '$username' AND password = '$password'";
    $res = $conn->query($query);

    if ($res) {
        if ($res->num_rows == 1) {
            $_SESSION['username'] = $username; 
            header("Location: ../index.php");
            exit();
        } else {
            $error_message = "Thông tin đăng nhập không đúng.";
        }
    } else {
        $error_message = "Đã xảy ra lỗi trong quá trình kiểm tra đăng nhập.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../templates/styles/DangNhap.css"> 
    <link rel="stylesheet" href="../templates/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../templates/icon/fontawesome-free-6.2.0-web/fontawesome-free-6.2.0-web/css/all.min.css">
    <title>Đăng Nhập</title>
</head>
<body>
   <main>
       <div class="content">
            <div class="login-form">
                <form method="post" action="">
                    <h3>Đăng nhập</h3>
                    <div class="form-group">
                        <label for="username">Tên đăng nhập:</label>
                        <div class="input-wrap">
                            <input type="text" name="username" placeholder="Nhập tên đăng nhập" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <div class="input-wrap">

                            <input type="password" id="password-field" name="password" placeholder="Nhập mật khẩu" required>

                            <span class="fa fa-fw fa-eye field-icon toggle-password" id="togglePassword"></span>
                        </div>  
                    </div>

                    <button type="submit">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập  
                    </button>

                    <?php
                        if (!empty($error_message)) {
                            echo "<p class='error-message'>$error_message</p>";
                        }
                    ?>
                </form>

                <script>
                    const togglePassword = document.getElementById('togglePassword');
                    const password = document.getElementById('password-field');

                    togglePassword.addEventListener('click', function () {
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);

                        if (type === 'password') {
                            this.classList.remove('fa-eye-slash');
                            this.classList.add('fa-eye');
                        } else {
                            this.classList.remove('fa-eye');
                            this.classList.add('fa-eye-slash');
                        }
                    });
                </script>


            </div>
        </div>
   </main>
   
</body>
</html>
