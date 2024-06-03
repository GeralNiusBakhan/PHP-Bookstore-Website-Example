<?php
session_start();
include 'connectDB.php';

if (!isset($_SESSION['userid']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userid'];
$name = $email = $gender = $address = $ic = $contact = "";

$sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerEmail, customer.CustomerPhone, customer.CustomerGender, customer.CustomerAddress
    FROM customer WHERE customer.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $name = $row['CustomerName'];
    $ic = $row['CustomerIC'];
    $email = $row['CustomerEmail'];
    $contact = $row['CustomerPhone'];
    $gender = $row['CustomerGender'];
    $address = $row['CustomerAddress'];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<blockquote>
    <a href="index.php"><img src="image/logo.png"></a>
</blockquote>
</header>
<div class="container">
    <h1>Checkout</h1>
    <form method="post" action="process_checkout.php">
        <label for="name">Full Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label for="ic">IC Number:</label><br>
        <input type="text" id="ic" name="ic" value="<?php echo htmlspecialchars($ic); ?>" required><br><br>

        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <label for="contact">Mobile Number:</label><br>
        <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required><br><br>

        <label>Gender:</label><br>
        <input type="radio" id="male" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>> <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>> <label for="female">Female</label><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address" cols="50" rows="5" required><?php echo htmlspecialchars($address); ?></textarea><br><br>

        <input class="button" type="submit" value="Checkout">
        <input class="button" type="button" value="Cancel" onClick="window.location='index.php';">
    </form>
</div>
</body>
</html>
