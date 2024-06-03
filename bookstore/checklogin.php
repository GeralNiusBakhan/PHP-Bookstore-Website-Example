<?php
session_start();
include 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['pwd'];

    $sql = "SELECT * FROM users WHERE UserName='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['userid'] = $row['UserID'];
            $_SESSION['username'] = $row['UserName'];
            $_SESSION['role'] = $row['Role'];
            header("Location: index.php");
        } else {
            header("Location: login.php?errcode=1");
        }
    } else {
        header("Location: login.php?errcode=1");
    }
    $conn->close();
} else {
    header("Location: login.php?errcode=2");
}
?>
