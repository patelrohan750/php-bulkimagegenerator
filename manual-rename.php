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
    try{
        $tesseract = new TesseractOCR($preprocessedImage);
        $text = $tesseract->run();
    } catch (Exception $e){
        $text = '';
    }
   
    echo "<div>";
    echo "<img src='$image' alt='Image $imageId'><br>";
    echo "Image ID: $imageId<br>";
    echo "Extracted Text: <span class='extracted-text'>$text</span><br>";

    // Add an input field for the user to enter the new text
    echo "<input type='text' class='new-text' placeholder='Enter new text'><br>";

    // Add a button for manual rename for each image with a unique identifier
    echo "<button class='rename-btn' data-image-id='$imageId'>Manual Rename for Image $imageId</button>";
    
    echo "</div><br>";

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
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('.rename-btn').click(function() {
        var imageId = $(this).data('image-id');
        var newText = $(this).closest('div').find('.new-text').val();
        $.ajax({
            url: 'rename_image.php',
            type: 'POST',
            data: { image_id: imageId, new_text: newText },
            success: function(response) {
                // Handle success response (if any)
                console.log('Image ' + imageId + ' renamed successfully to ' + newText);
                // Optionally handle the response to update the UI
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    });
});
</script>