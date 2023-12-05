<?php
require_once "vendor/autoload.php";
use thiagoalessio\TesseractOCR\TesseractOCR;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scraper";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process each image
$images = glob('image/*.png'); // Assuming images are stored in the 'image' directory
foreach ($images as $image) {

    // Preprocess the image
    $preprocessedImage = 'processimage/preprocessed.png';
    $originalImage = imagecreatefrompng($image);
    imagepng($originalImage, $preprocessedImage);
    imagedestroy($originalImage);

    // Perform OCR on the preprocessed image
    $imageId = pathinfo($image, PATHINFO_FILENAME); // Get the image file name without extension

    $tesseract = new TesseractOCR($preprocessedImage);
    $tesseract->setLanguage('eng');
    $text = $tesseract->run();
   
    echo "Image: $imageId\n";
    echo "Extracted Text: $text\n";
    echo "<br>";

    // Search for text in questions
    $matchFound = false;
    $text = trim(preg_replace('/\s+/', ' ', $text));
    $sql = "SELECT * FROM questions WHERE question = '".$text."'";
    $result = $conn->query($sql);
   
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Match found for image: $imageId\n";
        // Rename the image file with the corresponding ID
        $newImageName = 'image/' . $row['id'] . '.png';
        rename($image, $newImageName);
        $matchFound = true;
    }

    if (!$matchFound) {
        echo "No matching question found for this text.\n";
        echo "<br>";
    } else {
        echo "rename succesfully.\n";
        echo "<br>";
    }
    
}

$conn->close();
