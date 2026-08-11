<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');

require_once __DIR__ . '/../db.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID gerekli!']);
    exit;
}

try {
    $sql = "DELETE FROM urunler WHERE UrunID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Ürün silindi!'
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Hata: ' . $e->getMessage()
    ]);
}
?>