<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connectDB.php';
    $isbn = $_POST['isbn'];
    $price = $_POST['price'];
    $cover = $_POST['cover'];

    $bookData = file_get_contents("https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data");
    $bookData = json_decode($bookData, true);
    if (!empty($bookData)) {
        $book = current($bookData);
        $title = $book['title'];
        $author = $book['authors'][0]['name'];

        $stmt = $conn->prepare("INSERT INTO book (BookTitle, ISBN, Price, Author, Image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $title, $isbn, $price, $author, $cover);
        if ($stmt->execute()) {
            echo "New book added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Book not found";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Book</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<blockquote>
    <a href="index.php"><img src="image/logo.png"></a>
</blockquote>
</header>
<div class="container">
    <h1>Add a New Book</h1>
    <form method="post" action="addbook.php">
        <label for="isbn">ISBN:</label><br>
        <input type="text" id="isbn" name="isbn" required><br><br>

        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" required><br><br>

        <label for="cover">Cover Image URL:</label><br>
        <input type="text" id="cover" name="cover" required><br><br>

        <input class="button" type="submit" value="Add Book">
        <input class="button" type="button" value="Cancel" onClick="window.location='index.php';">
    </form>
</div>
</body>
</html>
