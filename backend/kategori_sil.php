<?php
require_once 'baglan.php';
session_start();

if(isset($_GET['id']) && isset($_SESSION['admin_giris'])) {
    $sorgu = $db->prepare("DELETE FROM kategoriler WHERE id = ?");
    $sorgu->execute([$_GET['id']]);
    
    header("Location: ../admin.php?islem=basarili");
}
?>