<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../db.php';

try {
    $sql = "SELECT u.UrunID, u.UrunADI, u.Fiyat, u.StokAdedi, u.KritikStokSeviyesi, 
                   u.KategoriID, k.KategoriADI
            FROM urunler u
            LEFT JOIN kategoriler k ON u.KategoriID = k.KategoriID
            ORDER BY u.UrunID DESC";
    
    $stmt = $conn->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Veriler alınamadı: ' . $e->getMessage()
    ]);
}
?>