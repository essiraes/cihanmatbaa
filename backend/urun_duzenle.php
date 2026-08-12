<?php
session_start();
require_once 'baglan.php';

if(!isset($_SESSION['admin_giris'])) {
    die("Yetkisiz erişim!");
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $urun_adi = $_POST['urun_adi'];
    $kategori_id = $_POST['kategori_id'];
    $fiyat = $_POST['fiyat'];
    $malzeme = $_POST['malzeme'];

    // Yeni görsel yüklendiyse
    if(isset($_FILES['gorsel']) && $_FILES['gorsel']['error'] == 0) {
        $dosya_adi = $_FILES['gorsel']['name'];
        $yeni_isim = time() . '_' . basename($dosya_adi);
        $hedef_klasor = '../uploads/' . $yeni_isim;

        if(move_uploaded_file($_FILES['gorsel']['tmp_name'], $hedef_klasor)) {
            $db_yolu = 'uploads/' . $yeni_isim;
            
            // Veritabanını yeni görselle güncelle
            $guncelle = $db->prepare("UPDATE urunler SET urun_adi = ?, kategori_id = ?, fiyat = ?, malzeme = ?, gorsel_yolu = ? WHERE id = ?");
            $guncelle->execute([$urun_adi, $kategori_id, $fiyat, $malzeme, $db_yolu, $id]);
        }
    } else {
        // Görsel değişmediyse sadece metinleri güncelle
        $guncelle = $db->prepare("UPDATE urunler SET urun_adi = ?, kategori_id = ?, fiyat = ?, malzeme = ? WHERE id = ?");
        $guncelle->execute([$urun_adi, $kategori_id, $fiyat, $malzeme, $id]);
    }

    header("Location: ../admin.php?islem=guncellendi");
    exit;
}
?>