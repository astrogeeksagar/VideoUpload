<?php

// Connect to the database
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_dbname";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the file upload
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $file = $_FILES['video'];

    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('mp4', 'mkv', 'wmv', 'flv');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 200000000) {
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = 'videos/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);

                // Insert data into the database
                $sql = "INSERT INTO videos (title, author, file_path) VALUES ('$title', '$author', '$fileDestination')";
                if ($conn->query($sql) === true) {
                    echo "Upload Successful";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "File is too big!";
            }
        } else {
            echo "There was an error uploading your file!";
        }
    } else {
        echo "You cannot upload files of this type!";
    }
}

// Fetch data from the database
$sql = "SELECT * FROM videos";
$result = $conn->query($sql);

?>
