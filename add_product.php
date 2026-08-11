<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Veri alınamadı!']);
    exit;
}

if (!isset($data['UrunADI']) || empty($data['UrunADI'])) {
    echo json_encode(['success' => false, 'message' => 'Ürün adı gerekli!']);
    exit;
}

if (!isset($data['Fiyat']) || $data['Fiyat'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Geçerli fiyat girin!']);
    exit;
}

if (!isset($data['StokAdedi']) || $data['StokAdedi'] < 0) {
    echo json_encode(['success' => false, 'message' => 'Geçerli stok girin!']);
    exit;
}

if (!isset($data['KategoriID']) || $data['KategoriID'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Kategori seçin!']);
    exit;
}

try {
    $sql = "INSERT INTO urunler (UrunADI, Fiyat, StokAdedi, KritikStokSeviyesi, KategoriID) 
            VALUES (:urun_adi, :fiyat, :stok, :kritik_stok, :kategori_id)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':urun_adi' => $data['UrunADI'],
        ':fiyat' => $data['Fiyat'],
        ':stok' => $data['StokAdedi'],
        ':kritik_stok' => isset($data['KritikStokSeviyesi']) ? $data['KritikStokSeviyesi'] : 5,
        ':kategori_id' => $data['KategoriID']
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Ürün eklendi!'
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Hata: ' . $e->getMessage()
    ]);
}
?>