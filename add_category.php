<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['KategoriADI']) || empty($data['KategoriADI'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Kategori adı gerekli!'
    ]);
    exit;
}

try {
    $sql = "INSERT INTO kategoriler (KategoriADI) VALUES (:kategori_adi)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':kategori_adi' => $data['KategoriADI']]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Kategori eklendi!'
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Ekleme hatası: ' . $e->getMessage()
    ]);
}
?>