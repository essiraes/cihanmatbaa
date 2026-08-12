<?php
session_start();
require_once 'baglan.php';

if(!isset($_SESSION['admin_giris'])) {
    die("Yetkisiz erişim!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $urun_adi = $_POST['urun_adi'] ?? '';
    $kategori_id = $_POST['kategori_id'] ?? 1;
    
    // YENİ: Fiyat ve Adedi alıp otomatik birleştiriyoruz
    $gelen_fiyat = $_POST['fiyat'] ?? '';
    $gelen_adet = $_POST['adet'] ?? '';
    
    $fiyat = '';
    if(!empty($gelen_fiyat) && !empty($gelen_adet)) {
        $fiyat = $gelen_fiyat . " ₺ / " . $gelen_adet . " Adet";
    } elseif(!empty($gelen_fiyat)) {
        $fiyat = $gelen_fiyat . " ₺"; // Sadece fiyat girildiyse
    }

    $malzeme = $_POST['malzeme'] ?? '';
    $durum = $_POST['durum'] ?? 'aktif';
    
    $db_yolu = ''; 

    if (isset($_FILES['gorsel']) && $_FILES['gorsel']['error'] == 0) {
        $dosya_adi = $_FILES['gorsel']['name'];
        $yeni_isim = time() . '_' . basename($dosya_adi);
        $hedef_klasor = '../uploads/' . $yeni_isim;

        if (move_uploaded_file($_FILES['gorsel']['tmp_name'], $hedef_klasor)) {
            $db_yolu = 'uploads/' . $yeni_isim;
        }
    }

    if (!empty($urun_adi)) {
        try {
            $sorgu = $db->prepare("INSERT INTO urunler (urun_adi, kategori_id, fiyat, malzeme, gorsel_yolu, durum) VALUES (?, ?, ?, ?, ?, ?)");
            $sorgu->execute([$urun_adi, $kategori_id, $fiyat, $malzeme, $db_yolu, $durum]);
            
            header("Location: ../admin.php?islem=basarili");
            exit;
        } catch (PDOException $e) {
            die("Ekleme hatası: " . $e->getMessage());
        }
    } else {
        die("Ürün adı boş olamaz.");
    }
}
?>