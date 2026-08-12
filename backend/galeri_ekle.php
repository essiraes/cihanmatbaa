<?php
// backend/galeri_ekle.php
require_once 'baglan.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baslik = $_POST['baslik'] ?? '';
    $kategori = $_POST['kategori'] ?? '';

    // Görsel seçilmiş mi ve hata var mı kontrol et
    if (isset($_FILES['gorsel']) && $_FILES['gorsel']['error'] == 0) {
        
        $gecici_yol = $_FILES['gorsel']['tmp_name'];
        $dosya_adi = $_FILES['gorsel']['name'];
        
        // Benzersiz bir isim oluştur (Aynı isimli dosyalar çakışmasın diye)
        $yeni_isim = time() . '_' . basename($dosya_adi);
        $hedef_klasor = '../uploads/' . $yeni_isim; 
        
        // Dosyayı sunucuya (uploads klasörüne) yükle
        if (move_uploaded_file($gecici_yol, $hedef_klasor)) {
            
            // Veritabanına sadece dosyanın yolunu kaydediyoruz
            $db_yolu = 'uploads/' . $yeni_isim;
            
            try {
                $sorgu = $db->prepare("INSERT INTO galeri (baslik, kategori, gorsel_yolu) VALUES (?, ?, ?)");
                $sorgu->execute([$baslik, $kategori, $db_yolu]);
                
                header("Location: ../admin.php?islem=basarili");
                exit;
            } catch (PDOException $e) {
                die("Veritabanı hatası: " . $e->getMessage());
            }
        } else {
            die("Dosya yüklenirken bir sorun oluştu.");
        }
    } else {
        die("Lütfen geçerli bir görsel seçin.");
    }
}
?>