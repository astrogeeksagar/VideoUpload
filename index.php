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

    <?php

    require_once 'db.php';

    // Connect to the database on
    $conn = Database::connect();

    // Handle the file upload
    if (isset($_POST['submit'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $file = $_FILES['video'];

        $fileName = $file['name']; //echo $fileName;exit;
        $fileTmpName = $file['tmp_name']; //echo $fileTmpName;exit;
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt)); //echo $fileActualExt;exit;

        $allowed = array('mp4', 'mkv', 'wmv', 'flv');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 200000000) {
                    //$fileNameNew = uniqid('', true) . "." . $fileActualExt; //echo $fileNameNew; exit;
                    $fileNameNew = $fileName; //echo $fileNameNew; exit;
                    $fileDestination = 'videos/' . $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);

                    // Insert data into the database
                    $query = "INSERT INTO videos (title, author, file_path) VALUES (:title, :author, :file_path)";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':title', $title);
                    $stmt->bindParam(':author', $author);
                    $stmt->bindParam(':file_path', $fileDestination);
                    $stmt->execute();
                    echo "Upload Successful";
                } else {
                    echo "File is too big!";
                }
            } else {
                echo "There was anerror uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type!";
        }
    }

    // Handle the delete
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];


        // Delete the video file from the server
        $query = "SELECT file_path FROM videos WHERE id= :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (file_exists($row['file_path'])) {
                unlink($row['file_path']);
                $query2 = "DELETE FROM videos WHERE id= :id";
                $stmt = $conn->prepare($query2);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                echo 'Record deleted';
            } else {
                echo 'file not found';
            }
        } else {
            echo "Record not found.";
        }
    }



    // Fetch data from the database
    $query = "SELECT * FROM videos";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    ?>


    <div class="container">
        <h1 class="text-center">Upload Video</h1>
        <!-- File upload form -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="author">Author Name</label>
                <input type="text" class="form-control" id="author" name="author">
            </div>
            <div class="form-group">
                <label for="video">Video</label>
                <input type="file" class="form-control-file" id="video" name="video">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Upload</button>
        </form>
        <!-- Video table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row) : ?>
                    <tr>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['author']; ?></td>
                        <td>
                            <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</body>

</html>
