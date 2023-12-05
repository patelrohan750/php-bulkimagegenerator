<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scraper";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES['jsonFile']) && $_FILES['jsonFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['jsonFile']['tmp_name'];
    $jsonContent = file_get_contents($fileTmpPath);
    $jsonData = json_decode($jsonContent, true);

    if ($jsonData !== null) {
        foreach ($jsonData as $item) {
            $id = $conn->real_escape_string($item['id']);
            $question = $conn->real_escape_string($item['question']);
            $url = $conn->real_escape_string($item['url']);
            $isPublish = $item['isPublish'];

            $sql = "INSERT INTO questions (id, question, url, isPublish) VALUES ('$id', '$question', '$url', $isPublish)";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        echo "JSON data has been successfully uploaded and saved to the database.";
    } else {
        echo "Invalid JSON file.";
    }
} else {
    echo "Error uploading the file.";
}

$conn->close();

?>
