<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../db.php';

try {
    $stmt = $conn->query("SELECT KategoriID, KategoriADI FROM kategoriler ORDER BY KategoriADI");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $categories
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Kategoriler alınamadı: ' . $e->getMessage()
    ]);
}
?>