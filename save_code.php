<?php
// save_code.php
header('Content-Type: application/json');

// පාලක පැනලයෙන් එවන දත්ත ලබා ගැනීම
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data received"]);
    exit;
}

$page = $data['page']; // INDEX හෝ STORE
$newCode = $data['code']; 
$filename = ($page === 'INDEX') ? 'index.html' : 'store.html';

// ෆයිල් එක තිබේදැයි පරීක්ෂා කිරීම
if (file_exists($filename)) {
    $currentContent = file_get_contents($filename);
    
    // මුල් කේතයේ අගට (</body> ට කලින්) AI එක දෙන අලුත් කේතය එකතු කිරීම
    $updatedContent = str_replace('</body>', $newCode . "\n</body>", $currentContent);
    
    // වෙනස් කළ කේතය නැවත ෆයිල් එකට සේව් කිරීම
    if (file_put_contents($filename, $updatedContent)) {
        echo json_encode(["status" => "success", "message" => "File updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Permission denied to write file"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "File $filename not found"]);
}
?>