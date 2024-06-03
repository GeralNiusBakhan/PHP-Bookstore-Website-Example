<?php
include 'connectDB.php';

$username = "admin";  // Change this to your desired admin username
$password = "geral";  // Change this to your desired admin password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$role = "admin";

$sql = "INSERT INTO users (UserName, Password, Role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashed_password, $role);

if ($stmt->execute()) {
    echo "Admin user added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
