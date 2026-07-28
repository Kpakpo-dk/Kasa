<?php
session_start();

// Ensure the uploads directory exists
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

if (isset($_FILES['audio_data'])) {
    $audioFile = $_FILES['audio_data'];
    
    // Create a unique name for the audio file to prevent overwriting
    $fileName = 'audio_' . time() . '.wav';
    $uploadPath = 'uploads/' . $fileName;

    if (move_uploaded_file($audioFile['tmp_name'], $uploadPath)) {
        // Save the file path in session memory for the next pages to use
        $_SESSION['recorded_audio_path'] = $uploadPath;
        
        echo json_encode(['status' => 'success', 'path' => $uploadPath]);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Upload failed']);