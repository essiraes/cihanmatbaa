<?php
// backend/baglan.php
session_start(); // Güvenlik oturumlarını başlatır

$host = 'localhost';
$dbname = 'cihan_matbaa'; 
$user = 'root';           
$pass = ''; // BURASI KESİNLİKLE BOŞ OLMALI (Boşluk dahi olmamalı)

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>