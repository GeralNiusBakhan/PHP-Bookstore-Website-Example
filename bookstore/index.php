<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<body>
<?php
session_start();

function connectToDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bookstore";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

if (isset($_POST['ac'])) {
    $conn = connectToDatabase();
    $bookID = $_POST['ac'];
    $quantity = $_POST['quantity'];
    $customerID = $_SESSION['userid'];

    $stmt = $conn->prepare("SELECT * FROM book WHERE BookID = ?");
    $stmt->bind_param("s", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $price = $row['Price'];

        $stmt = $conn->prepare("INSERT INTO cart(BookID, Quantity, Price, TotalPrice, CustomerID) VALUES(?, ?, ?, ? * ?, ?)");
        $stmt->bind_param("siddii", $bookID, $quantity, $price, $price, $quantity, $customerID);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
}

if (isset($_POST['delc'])) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("DELETE FROM cart WHERE CustomerID = ?");
    $stmt->bind_param("i", $_SESSION['userid']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

$conn = connectToDatabase();

$stmt = $conn->prepare("SELECT * FROM book");
$stmt->execute();
$result = $stmt->get_result();
?>

<header>
<blockquote>
    <a href="index.php"><img src="image/logo.png"></a>
    <?php if (isset($_SESSION['userid'])): ?>
        <form class="hf" action="logout.php"><input class="hi" type="submit" name="submitButton" value="Logout"></form>
        <form class="hf" action="edituser.php"><input class="hi" type="submit" name="submitButton" value="Edit Profile"></form>
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <form class="hf" action="addbook.php"><input class="hi" type="submit" name="submitButton" value="Add Book"></form>
        <?php endif; ?>
    <?php else: ?>
        <form class="hf" action="register.php"><input class="hi" type="submit" name="submitButton" value="Register"></form>
        <form class="hf" action="login.php"><input class="hi" type="submit" name="submitButton" value="Login"></form>
    <?php endif; ?>
</blockquote>
</header>

<blockquote>
<table id='myTable' style='width:80%; float:left'>
    <tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <td>
            <table>
                <tr>
                    <td><img src="<?php echo $row['Image']; ?>" width="80%"></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Title: <?php echo $row['BookTitle']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">ISBN: <?php echo $row['ISBN']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Author: <?php echo $row['Author']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">Type: <?php echo $row['Type']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 5px;">RM<?php echo $row['Price']; ?></td>
                </tr>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin'): ?>
                <tr>
                    <td style="padding: 5px;">
                        <form action="" method="post">
                            Quantity: <input type="number" value="1" name="quantity" style="width: 20%"/><br>
                            <input type="hidden" value="<?php echo $row['BookID']; ?>" name="ac"/>
                            <input class="button" type="submit" value="Add to Cart"/>
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </td>
    <?php endwhile; ?>
    </tr>
</table>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin'): ?>
<?php
$stmt = $conn->prepare("SELECT book.BookTitle, book.Image, cart.Price, cart.Quantity, cart.TotalPrice FROM book, cart WHERE book.BookID = cart.BookID AND cart.CustomerID = ?");
$stmt->bind_param("i", $_SESSION['userid']);
$stmt->execute();
$result = $stmt->get_result();
?>

<table style='width:20%; float:right;'>
    <th style='text-align:left;'><i class='fa fa-shopping-cart' style='font-size:24px'></i> Cart 
        <form style='float:right;' action='' method='post'>
            <input type='hidden' name='delc'/>
            <input class='cbtn' type='submit' value='Empty Cart'>
        </form>
    </th>
    <?php $total = 0; ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <img src="<?php echo $row['Image']; ?>" width="20%"><br>
                <?php echo $row['BookTitle']; ?><br>RM<?php echo $row['Price']; ?><br>
                Quantity: <?php echo $row['Quantity']; ?><br>
                Total Price: RM<?php echo $row['TotalPrice']; ?>
            </td>
        </tr>
        <?php $total += $row['TotalPrice']; ?>
    <?php endwhile; ?>
    <tr>
        <td style='text-align: right;background-color: #f2f2f2;'>
            Total: <b>RM<?php echo $total; ?></b>
            <center>
                <form action='checkout.php' method='post'>
                    <input class='button' type='submit' name='checkout' value='CHECKOUT'>
                </form>
            </center>
        </td>
    </tr>
</table>
<?php endif; ?>
</blockquote>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
