<?php
require_once 'baglan.php';
session_start();

if(isset($_POST['kategori_adi']) && isset($_SESSION['admin_giris'])) {
    $ad = $_POST['kategori_adi'];
    $modul = $_POST['modul'];
    
    $sorgu = $db->prepare("INSERT INTO kategoriler (kategori_adi, modul) VALUES (?, ?)");
    $sorgu->execute([$ad, $modul]);
    
    header("Location: ../admin.php?islem=basarili");
}
?>