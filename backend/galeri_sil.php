<?php
require_once 'baglan.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Önce resmi sunucudan (klasörden) silelim ki yer kaplamasın
    $resim_sorgu = $db->prepare("SELECT gorsel_yolu FROM galeri WHERE id = ?");
    $resim_sorgu->execute([$id]);
    $resim = $resim_sorgu->fetch(PDO::FETCH_ASSOC);
    
    if($resim && file_exists("../" . $resim['gorsel_yolu'])) {
        unlink("../" . $resim['gorsel_yolu']); // Dosyayı sil
    }

    // Sonra veritabanından silelim
    $sorgu = $db->prepare("DELETE FROM galeri WHERE id = ?");
    $sorgu->execute([$id]);
}
header("Location: ../admin.php?silme=basarili");
exit;
?>