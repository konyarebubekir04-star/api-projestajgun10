<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['UrunID'])) {
    echo json_encode(['success' => false, 'message' => 'ID gerekli!']);
    exit;
}

if (!isset($data['action']) || !in_array($data['action'], ['increase', 'decrease'])) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz işlem!']);
    exit;
}

try {
    $action = $data['action'];
    $id = $data['UrunID'];
    
    if ($action === 'increase') {
        $sql = "UPDATE urunler SET StokAdedi = StokAdedi + 1 WHERE UrunID = :id";
    } else {
        $sql = "UPDATE urunler SET StokAdedi = StokAdedi - 1 WHERE UrunID = :id AND StokAdedi > 0";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => $action === 'increase' ? 'Stok artırıldı!' : 'Stok azaltıldı!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Stok güncellenemedi!'
        ]);
    }
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Hata: ' . $e->getMessage()
    ]);
}
?>