<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['UrunID'])) {
    echo json_encode(['success' => false, 'message' => 'Ürün ID gerekli!']);
    exit;
}

try {
    $sql = "UPDATE urunler 
            SET UrunADI = :urun_adi, 
                Fiyat = :fiyat, 
                StokAdedi = :stok, 
                KritikStokSeviyesi = :kritik_stok,
                KategoriID = :kategori_id 
            WHERE UrunID = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $data['UrunID'],
        ':urun_adi' => $data['UrunADI'],
        ':fiyat' => $data['Fiyat'],
        ':stok' => $data['StokAdedi'],
        ':kritik_stok' => isset($data['KritikStokSeviyesi']) ? $data['KritikStokSeviyesi'] : 5,
        ':kategori_id' => isset($data['KategoriID']) && $data['KategoriID'] > 0 ? $data['KategoriID'] : null
    ]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Ürün güncellendi!'
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Hata: ' . $e->getMessage()
    ]);
}
?>