<?php
session_start();
include 'connectDB.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userid'];
$userRole = $_SESSION['role'];

$nameErr = $emailErr = $genderErr = $addressErr = $icErr = $contactErr = $usernameErr = $passwordErr = "";
$name = $email = $gender = $address = $ic = $contact = $uname = $upassword = "";
$oUserName = $oPassword = $oName = $oIC = $oEmail = $oPhone = $oAddress = $oGender = "";

if ($userRole == 'user') {
    $sql = "SELECT users.UserName, users.Password, customer.CustomerName, customer.CustomerIC, customer.CustomerEmail, customer.CustomerPhone, customer.CustomerGender, customer.CustomerAddress
        FROM users LEFT JOIN customer ON users.UserID = customer.UserID WHERE users.UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $oUserName = $row['UserName'];
        $oPassword = $row['Password'];
        $oName = $row['CustomerName'];
        $oIC = $row['CustomerIC'];
        $oEmail = $row['CustomerEmail'];
        $oPhone = $row['CustomerPhone'];
        $oAddress = $row['CustomerAddress'];
        $oGender = $row['CustomerGender'];
    }
} else {
    $sql = "SELECT UserName, Password FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $oUserName = $row['UserName'];
        $oPassword = $row['Password'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["upassword"])) {
        $upassword = password_hash(test_input($_POST["upassword"]), PASSWORD_BCRYPT);
    }

    if ($userRole == 'user') {
        if (empty($_POST["name"])) {
            $nameErr = "Please enter your name";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }

        if (empty($_POST["uname"])) {
            $usernameErr = "Please enter your Username";
        } else {
            $uname = test_input($_POST["uname"]);
        }

        if (empty($_POST["ic"])) {
            $icErr = "Please enter your IC number";
        } else {
            $ic = test_input($_POST["ic"]);
            if (!preg_match("/^[0-9 -]*$/", $ic)) {
                $icErr = "Please enter a valid IC number";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Please enter your email address";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        if (empty($_POST["contact"])) {
            $contactErr = "Please enter your phone number";
        } else {
            $contact = test_input($_POST["contact"]);
            if (!preg_match("/^[0-9 -]*$/", $contact)) {
                $contactErr = "Please enter a valid phone number";
            }
        }

        if (empty($_POST["gender"])) {
            $genderErr = "* Gender is required!";
        } else {
            $gender = test_input($_POST["gender"]);
        }

        if (empty($_POST["address"])) {
            $addressErr = "Please enter your address";
        } else {
            $address = test_input($_POST["address"]);
        }

        if (empty($nameErr) && empty($usernameErr) && empty($emailErr) && empty($icErr) && empty($contactErr) && empty($genderErr) && empty($addressErr)) {
            if (empty($upassword)) {
                $sql = "UPDATE users SET UserName = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $uname, $userID);
            } else {
                $sql = "UPDATE users SET UserName = ?, Password = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $uname, $upassword, $userID);
            }

            if ($stmt->execute()) {
                $sql = "UPDATE customer SET CustomerName = ?, CustomerPhone = ?, CustomerIC = ?, CustomerEmail = ?, CustomerAddress = ?, CustomerGender = ? WHERE UserID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssi", $name, $contact, $ic, $email, $address, $gender, $userID);
                $stmt->execute();

                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    } else { // Admin case
        if (empty($upassword)) {
            $passwordErr = "Please enter your Password";
        }

        if (empty($passwordErr)) {
            $sql = "UPDATE users SET Password = ? WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $upassword, $userID);
            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<html>
<link rel="stylesheet" href="style.css">
<body>
<header>
<blockquote>
    <a href="index.php"><img src="image/logo.png"></a>
</blockquote>
</header>
<blockquote>
<div class="container">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h1>Edit Profile:</h1>

    <?php if ($userRole == 'user'): ?>
        Full Name:<br><input type="text" name="name" value="<?php echo htmlspecialchars($oName); ?>" required>
        <span class="error" style="color: red; font-size: 0.8em;"><?php echo $nameErr; ?></span><br><br>

        User Name:<br><input type="text" name="uname" value="<?php echo htmlspecialchars($oUserName); ?>" required>
        <span class="error" style="color: red; font-size: 0.8em;"><?php echo $usernameErr; ?></span><br><br>

        IC Number:<br><input type="text" name="ic" value="<?php echo htmlspecialchars($oIC); ?>" required>
        <span class="error" style="color: red; font-size: 0.8em;"><?php echo $icErr; ?></span><br><br>

        E-mail:<br><input type="email" name="email" value="<?php echo htmlspecialchars($oEmail); ?>" required>
        <span class="error" style="color: red; font-size: 0.8em;"><?php echo $emailErr; ?></span><br><br>

        Mobile Number:<br><input type="text" name="contact" value="<?php echo htmlspecialchars($oPhone); ?>" required>
        <span class="error" style="color: red; font-size: 0.8em;"><?php echo $contactErr; ?></span><br><br>

        <label>Gender:</label><br>
        <input type="radio" name="gender" value="Male" <?php if ($oGender == "Male") echo "checked"; ?>> Male
        <input type="radio" name="gender" value="Female" <?php if ($oGender == "Female") echo "checked"; ?>> Female
        <span class="error" style="color: red; font-size: 0.8em;"><?php echo $genderErr; ?></span><br><br>

        <label>Address:</label><br>
        <textarea name="address" cols="50" rows="5" required><?php echo htmlspecialchars($oAddress); ?></textarea>
        <span class="error" style="color: red; font-size: 0.8em;"><?php echo $addressErr; ?></span><br><br>
    <?php else: ?>
        User Name:<br><input type="text" name="uname" value="<?php echo htmlspecialchars($oUserName); ?>" readonly><br><br>
    <?php endif; ?>

    New Password:<br><input type="password" name="upassword" placeholder="Leave blank if not changing">
    <span class="error" style="color: red; font-size: 0.8em;"><?php echo $passwordErr; ?></span><br><br>

    <input class="button" type="submit" name="submitButton" value="Edit">
    <input class="button" type="button" name="cancel" value="Cancel" onClick="window.location='index.php';" />
</form>
</div>
</blockquote>
</body>
</html>
