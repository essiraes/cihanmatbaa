<?php
require_once 'backend/baglan.php';

// Eğer zaten giriş yapılmışsa direkt admine at
if(isset($_SESSION['admin_giris'])) {
    header("Location: admin.php");
    exit;
}

$hata = '';

// Sayfa ilk defa açıldığında veya yanlış girildiğinde yeni captcha üretelim
if (!isset($_SESSION['captcha_cevap'])) {
    $sayi1 = rand(1, 9);
    $sayi2 = rand(1, 9);
    $_SESSION['captcha_cevap'] = $sayi1 + $sayi2;
    $_SESSION['captcha_soru'] = "$sayi1 + $sayi2";
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kadi = $_POST['kullanici_adi'] ?? '';
    $sifre = $_POST['sifre'] ?? '';
    $girilen_captcha = $_POST['captcha'] ?? '';

    // Önce Captcha kontrolü
    if(empty($girilen_captcha) || $girilen_captcha != $_SESSION['captcha_cevap']) {
        $hata = "Güvenlik doğrulaması (işlem sonucu) hatalı!";
        // Hatalı girişte yeni soru üret
        $sayi1 = rand(1, 9);
        $sayi2 = rand(1, 9);
        $_SESSION['captcha_cevap'] = $sayi1 + $sayi2;
        $_SESSION['captcha_soru'] = "$sayi1 + $sayi2";
    } else {
        // Captcha doğruysa veritabanı kontrolüne geç
        if(!empty($kadi) && !empty($sifre)) {
            $sorgu = $db->prepare("SELECT * FROM yoneticiler WHERE kullanici_adi = ?");
            $sorgu->execute([$kadi]);
            $yonetici = $sorgu->fetch(PDO::FETCH_ASSOC);

            // Şifre doğrulama
            if($yonetici && password_verify($sifre, $yonetici['sifre'])) {
                // Başarılı girişte captcha oturumunu temizle
                unset($_SESSION['captcha_cevap']);
                unset($_SESSION['captcha_soru']);

                $_SESSION['admin_giris'] = true;
                $_SESSION['admin_kadi'] = $yonetici['kullanici_adi'];
                header("Location: admin.php");
                exit;
            } else {
                $hata = "Kullanıcı adı veya şifre hatalı!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Girişi | Cihan Matbaa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { cmykCyan: '#00AEEF' } } } }
    </script>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-[#00AEEF]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-lock text-2xl text-[#00AEEF]"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Yönetici Girişi</h2>
            <p class="text-gray-500 text-sm mt-1">Lütfen bilgilerinizi giriniz.</p>
        </div>

        <?php if($hata): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 text-center">
                <i class="fa-solid fa-triangle-exclamation"></i> <?= $hata ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#00AEEF] focus:ring-1 focus:ring-[#00AEEF] outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                <input type="password" name="sifre" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#00AEEF] focus:ring-1 focus:ring-[#00AEEF] outline-none transition">
            </div>

            <!-- Matematiksel Güvenlik Doğrulama Alanı -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Güvenlik Doğrulama: <strong class="text-cmykCyan text-base"><?= $_SESSION['captcha_soru'] ?> = ?</strong></label>
                <input type="number" name="captcha" required placeholder="İşlemin sonucunu giriniz" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-[#00AEEF] focus:ring-1 focus:ring-[#00AEEF] outline-none transition text-sm">
            </div>

            <button type="submit" class="w-full bg-[#1d1d1f] hover:bg-black text-white py-3 rounded-lg font-medium transition mt-2">
                Giriş Yap
            </button>
        </form>
        <div class="mt-6 text-center">
            <a href="index.php" class="text-sm text-gray-400 hover:text-gray-600 transition"><i class="fa-solid fa-arrow-left"></i> Siteye Dön</a>
        </div>
    </div>
</body>
</html>