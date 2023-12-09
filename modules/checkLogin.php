<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: ./DangNhap.php");
        exit();
    }
        
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $query1 = "SELECT id FROM ACCOUNT WHERE username = '$username'";
        $IDresult = $conn->query($query1);

        $idRow = $IDresult->fetch_assoc();
        $idValue = $idRow['id'];

        $query = "SELECT fullname FROM INVENTORY WHERE IDaccount = '$idValue'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fullname = $row['fullname'];
        } else {
            $fullname = 'Khách';
        }
    }
?>