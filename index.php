<!DOCTYPE html>
<html>
<head>
  <title>JSON Data Upload and Image Generation</title>
</head>
<body>
  <h2>Upload JSON Data</h2>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="jsonFile" accept=".json" />
    <input type="submit" name="submit" value="Upload JSON Data" />
  </form>

  <h2>Generate Images</h2>
  <p><a href="generate-images">Generate Images</a></p>
</body>
</html>
