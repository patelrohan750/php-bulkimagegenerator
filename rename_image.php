<?php
if (isset($_POST['image_id'], $_POST['new_text'])) {
    $imageId = $_POST['image_id'];
    $newText = $_POST['new_text'];

    // Logic to rename the image file based on the received image ID
    $oldImagePath = 'image/' . $imageId . '.png';
    
    // New image name with the updated ID
    $newImagePath = 'image/' . $newText . '.png';

    if (rename($oldImagePath, $newImagePath)) {
        // Rename successful
        echo json_encode(['success' => true, 'new_image_id' => $imageId, 'renamed_text' => $newText]);
        // Perform any additional actions or database updates if needed
    } else {
        // Rename failed
        echo json_encode(['success' => false, 'error' => 'Failed to rename image']);
    }
} else {
    // No image ID or extracted text received
    echo json_encode(['success' => false, 'error' => 'No image ID or extracted text received']);
}
