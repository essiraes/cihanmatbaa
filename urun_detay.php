<?php
require_once 'backend/baglan.php';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sorgu = $db->prepare("
    SELECT urunler.*, kategoriler.kategori_adi 
    FROM urunler 
    LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id 
    WHERE urunler.id = ? AND urunler.durum = 'aktif'
");
$sorgu->execute([$id]);
$urun = $sorgu->fetch(PDO::FETCH_ASSOC);

if(!$urun) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($urun['urun_adi']) ?> | Cihan Matbaa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { appleDark: '#1d1d1f', appleGray: '#F5F5F7', cmykCyan: '#00AEEF' } } } }
    </script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-[#FAFAFA] antialiased pt-28 pb-20">

    <!-- Navbar (Sadeleştirilmiş Geri Dönüş Menüsü) -->
    <nav class="glass-nav fixed w-full top-0 z-50 h-20 flex items-center border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 text-gray-500 hover:text-cmykCyan transition font-medium">
                <i class="fa-solid fa-arrow-left"></i> Ana Sayfaya Dön
            </a>
            <img class="h-12 w-auto" src="logo.jpeg" alt="Logo">
        </div>
    </nav>

    <!-- Ürün Detay Alanı -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            
            <!-- Sol: Görsel -->
            <div class="md:w-1/2 bg-gray-50 p-8 flex items-center justify-center">
                <img src="<?= htmlspecialchars($urun['gorsel_yolu']) ?>" alt="<?= htmlspecialchars($urun['urun_adi']) ?>" class="max-w-full h-auto rounded-xl shadow-md hover:scale-105 transition-transform duration-500">
            </div>
            
            <!-- Sağ: Bilgiler -->
            <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <span class="text-sm font-semibold text-cmykCyan mb-2 uppercase tracking-wider"><?= htmlspecialchars($urun['kategori_adi'] ?? 'Kategorisiz') ?></span>
                <h1 class="text-3xl md:text-4xl font-bold text-appleDark mb-4"><?= htmlspecialchars($urun['urun_adi']) ?></h1>
                
                <?php if(!empty($urun['malzeme'])): ?>
                <div class="mb-6 p-4 bg-appleGray rounded-xl">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Malzeme / Özellik:</h3>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($urun['malzeme']) ?></p>
                </div>
                <?php endif; ?>

                <?php if(!empty($urun['fiyat'])): ?>
                <div class="mb-8 border-t border-gray-100 pt-6">
                    <p class="text-sm text-gray-400 mb-1">Fiyatlandırma</p>
                    <div class="text-3xl font-bold text-appleDark"><?= htmlspecialchars($urun['fiyat']) ?></div>
                </div>
                <?php endif; ?>

                <!-- Özel WhatsApp Mesajı -->
                <?php 
                    $mesaj = "Merhaba, web sitenizden " . htmlspecialchars($urun['urun_adi']) . " ürünü hakkında bilgi ve fiyat almak istiyorum.";
                    $whatsapp_link = "https://wa.me/905320000000?text=" . urlencode($mesaj);
                ?>
                <a href="<?= $whatsapp_link ?>" target="_blank" class="bg-[#25D366] hover:bg-[#1EBE5C] text-white font-semibold py-4 px-6 rounded-xl text-center transition shadow-lg shadow-green-500/30 flex justify-center items-center gap-3 text-lg">
                    <i class="fa-brands fa-whatsapp text-2xl"></i> Bu Ürün İçin Sipariş Ver
                </a>
            </div>
        </div>
    </div>
</body>
</html>