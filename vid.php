<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>Video Upload</title>
</head>
<body>
  <div class="container">
    <h1 class="text-center">Upload Video</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
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
      <button type="submit" class="btn btn-primary">Upload</button>
    </form>
    <h1 class="text-center">Uploaded Videos</h1>
   <table class="table">
  <thead>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['title']; ?></td>
      <td><?php echo $row['author']; ?></td>
      <td>
        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Update</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<table class="table">
  <thead>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['title']; ?></td>
      <td><?php echo $row['author']; ?></td>
      <td>
        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Update</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

  </div>
</body>
</html>
