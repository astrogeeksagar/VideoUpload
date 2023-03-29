<?php
require_once 'db.php';

// Connect to the database
$conn = Database::connect();

// Get the current video data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM videos WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $oldFilePath = $result['file_path']; //echo $oldFilePath;exit;
}

// Update the video data
if (isset($_POST['submit'])) {
    $id = $_POST['oldid'];
    $title = $_POST['title']; //echo $title; exit;
    $author = $_POST['author'];
    $file = $_FILES['video'];

    // Check if a new video file was uploaded
    if ($file['name'] != '') {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt)); //echo $fileActualExt; exit;

        $allowed = array('mp4', 'mkv', 'wmv',);
        if (isset($_FILES['video']['name']) && !empty($_FILES['video']['name'])) {
            // check file type and size and upload process
            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    if ($fileSize < 200000000) {
                        $fileNameNew = $fileName; //echo $fileNameNew; exit;
                        $fileDestination = 'videos/' . $fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);
                        //update query with file path
                        // Delete the old video file from the server
                    if ($fileDestination != $oldFilePath) {
                        unlink($oldFilePath);
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
        } else {
            //update query without file path
        }
    }

        if (!empty($title) && !empty($author)) {
            $oldFilePath = $result['file_path'];
            $fileDestination = $oldFilePath;
            $query = "UPDATE videos SET title = :title, author = :author, file_path = :file_path WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':file_path', $fileDestination);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: index.php");
            // Check if a new video file was selected
            if ($fileError === 0) {
                if ($fileSize < 200000000) {
                    // $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $fileNameNew = $fileName; //echo $fileNameNew; exit;
                    $fileDestination = 'videos/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
        
                    // Delete the old video file from the server
                    if ($fileDestination != $oldFilePath) {
                        // unlink($oldFilePath);
                    }
                }
            }
            $query = "UPDATE videos SET title = :title, author = :author, file_path = :file_path WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':file_path', $fileDestination);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: index.php");
        }
        
    
}
$query = "SELECT * FROM videos WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$result = $stmt->fetch();

$ids = $result['id'];
$title = $result['title'];
$author = $result['author'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">Edit Video</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $ids; ?>" name="oldid" >
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>">
            </div>
    <div class="form-group">
        <label for="author">Author</label>
        <input type="text" class="form-control" id="author" name="author" value="<?php echo $author; ?>">
    </div>
    <div class="form-group">
        <label for="video">Video</label>
        <input type="file" class="form-control-file" id="video" name="video">
    </div>
    <input type="submit" name="submit" value="Update" class="btn btn-primary">
    </form>
    </div>

</body>

</html>
<?php

// Close the connection
Database::disconnect();

?>