<?php
session_start();
require_once 'baglan.php';

if(!isset($_SESSION['admin_giris'])) {
    die("Yetkisiz erişim!");
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Önce görseli sunucudan silelim (isteğe bağlı ama temizlik için iyi olur)
    $sorgu = $db->prepare("SELECT gorsel_yolu FROM urunler WHERE id = ?");
    $sorgu->execute([$id]);
    $urun = $sorgu->fetch(PDO::FETCH_ASSOC);
    
    if($urun && !empty($urun['gorsel_yolu']) && file_exists('../' . $urun['gorsel_yolu'])) {
        unlink('../' . $urun['gorsel_yolu']);
    }

    // Veritabanından sil
    $sil = $db->prepare("DELETE FROM urunler WHERE id = ?");
    $sil->execute([$id]);
}

header("Location: ../admin.php?islem=silindi");
exit;
?>