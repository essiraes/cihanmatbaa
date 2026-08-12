<?php 
// Veritabanı bağlantısı
require_once 'backend/baglan.php'; 

// 1. Galerideki Görselleri Çekelim
$galeriler = $db->query("SELECT * FROM galeri ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// 2. Galeri Kategorilerini Çekelim (Filtre butonları için)
$galeri_kategorileri = $db->query("SELECT * FROM kategoriler WHERE modul = 'galeri' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// 3. Üst Mega Menünün bozulmaması için Ürün ve Ürün Kategorilerini de çekelim
$urun_kategorileri = $db->query("SELECT * FROM kategoriler WHERE modul = 'urun' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$urunler = $db->query("SELECT * FROM urunler WHERE durum = 'aktif' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çalışmalarımız | Cihan Matbaa</title>
    <link rel="icon" type="image/jpeg" href="logo.jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { appleDark: '#1d1d1f', appleGray: '#F5F5F7', cmykCyan: '#00AEEF', cmykYellow: '#FFF200' } } }
        }
    </script>
    <link rel="stylesheet" href="style.css">
    <style>
        .gallery-item { transition: opacity 0.4s ease, transform 0.4s ease; }
        .gallery-image-wrapper { overflow: hidden; border-radius: 20px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .gallery-image-wrapper img { transition: transform 0.5s ease; }
        .gallery-item:hover .gallery-image-wrapper img { transform: scale(1.05); }
    </style>
</head>
<body class="antialiased relative bg-[#FAFAFA]">

    <!-- Navbar -->
    <nav class="glass-nav fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center cursor-pointer">
    <a href="#hero" class="flex items-center gap-3 group">
        <img class="h-16 w-auto transition-transform duration-300 group-hover:scale-105" src="logo.jpeg" alt="Cihan Matbaa Logo">
        <span class="text-2xl font-bold tracking-tight text-appleDark">
            Cihan<span class="font-light text-cmykCyan ml-1.5">Matbaacılık</span>
        </span>
    </a>
</div>
                
                <ul class="hidden md:flex space-x-8 nav-links items-center h-full">
                    <!-- Linkler index.php sayfasına yönlendirildi -->
                    <li><a href="index.php#hero" class="hover:text-cmykCyan transition-colors">Ana Sayfa</a></li>
                    <li><a href="index.php#about" class="hover:text-cmykCyan transition-colors">Hakkımızda</a></li>
                    
                    <!-- DİNAMİK MEGA MENÜ TETİKLEYİCİSİ -->
                    <li class="group h-full flex items-center">
                        <a href="index.php#services" class="hover:text-cmykCyan transition-colors flex items-center gap-1">
                            Hizmetlerimiz <i class="fa-solid fa-chevron-down text-xs"></i>
                        </a>
                        
                        <!-- DİNAMİK AÇILIR MEGA MENÜ PANELİ (index.php ile aynı) -->
                        <!-- DİNAMİK AÇILIR MEGA MENÜ PANELİ -->
<div class="mega-menu absolute left-0 top-[80px] w-full bg-white shadow-2xl border-t border-gray-100 p-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
    <?php 
    $limit = 4; // Ekranda görünecek maksimum kategori sayısı
    $sayac = 0;
    
    if(count($urun_kategorileri) > 0): 
        foreach($urun_kategorileri as $kat): 
            $sayac++;
            // Eğer limit aşıldıysa kategoriyi gizle (hidden class'ı ile)
            $hidden_class = ($sayac > $limit) ? 'hidden extra-cat' : '';
    ?>
        <div class="<?= $hidden_class ?>">
            <h4 class="font-bold text-appleDark mb-4 border-b pb-2"><?= htmlspecialchars($kat['kategori_adi']) ?></h4>
            <ul class="space-y-2 text-sm text-gray-500">
                <?php 
                foreach($urunler as $urun): 
                    if($urun['kategori_id'] == $kat['id']): 
                ?>
                    <li><a href="urun_detay.php?id=<?= $urun['id'] ?>" class="hover:text-cmykCyan transition-colors"><?= htmlspecialchars($urun['urun_adi']) ?></a></li>
                <?php 
                    endif; 
                endforeach; 
                ?>
            </ul>
        </div>
        <?php endforeach; ?>
        
        <!-- Eğer kategori sayısı limiti aştıysa "Tümünü Gör" butonu ekle -->
        <?php if($sayac > $limit): ?>
            <div class="flex items-center justify-center">
                <button id="showMoreBtn" onclick="toggleExtraCats()" class="text-cmykCyan font-bold hover:underline">
                    + Tüm Kategorileri Gör
                </button>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="col-span-full text-center py-4 text-gray-500 text-sm">Sistemde henüz kategori bulunmuyor.</div>
    <?php endif; ?>
</div>

<script>
    function toggleExtraCats() {
        const extras = document.querySelectorAll('.extra-cat');
        const btn = document.getElementById('showMoreBtn');
        extras.forEach(cat => cat.classList.toggle('hidden'));
        btn.innerText = btn.innerText.includes('+') ? '- Kategorileri Gizle' : '+ Tüm Kategorileri Gör';
    }
</script>
                    </li>
                    <li>
                        <a href="galeri.php" class="text-cmykCyan transition-colors font-medium relative group flex items-center gap-1">
                            <i class="fa-solid fa-image"></i> Çalışmalarımız
                        </a>
                    </li>
                    <li><a href="index.php#contact" class="hover:text-cmykCyan transition-colors">İletişim</a></li>
                </ul>
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900"><i class="fa-solid fa-bars text-2xl"></i></button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden absolute w-full bg-white border-t border-gray-100 shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1 text-center mobile-nav-links">
                <a href="index.php" class="block px-3 py-2 text-appleDark">Ana Sayfa</a>
                <a href="index.php#services" class="block px-3 py-2 text-appleDark">Tüm Hizmetlerimiz</a>
                <a href="galeri.php" class="block px-3 py-2 text-cmykCyan font-medium">Çalışmalarımız</a>
            </div>
        </div>
    </nav>

    <!-- Sayfa Başlığı -->
    <section class="pt-36 pb-12 text-center reveal active">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-appleDark mb-4 tracking-tight">Çalışmalarımız</h1>
            <p class="text-gray-500 text-lg font-light">Matbaamızda özenle hazırladığımız profesyonel baskı ve tasarım örnekleri.</p>
        </div>
    </section>

    <!-- Filtreler ve Galeri -->
    <section class="pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 reveal active">
        
        <!-- DİNAMİK GALERİ FİLTRE BUTONLARI -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button class="gal-filter-btn active px-6 py-2.5 rounded-full bg-cmykCyan text-white text-sm font-medium transition shadow-sm border border-cmykCyan" data-filter="all">Tümü</button>
            
            <?php foreach($galeri_kategorileri as $kat): ?>
                <button class="gal-filter-btn px-6 py-2.5 rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-medium transition" data-filter="<?= htmlspecialchars($kat['kategori_adi']) ?>">
                    <?= htmlspecialchars($kat['kategori_adi']) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="gallery-grid">
            
            <?php if(count($galeriler) > 0): ?>
                <?php foreach($galeriler as $gorsel): ?>
                <!-- Veritabanından gelen görsel kartı -->
                <div class="gallery-item" data-category="<?= htmlspecialchars($gorsel['kategori']) ?>">
                    <!-- ONCLICK EKLENDİ -->
                    <div class="gallery-image-wrapper h-64 w-full cursor-pointer group" 
                         onclick="openImageModal('<?= htmlspecialchars($gorsel['gorsel_yolu'], ENT_QUOTES) ?>')">
                        <img src="<?= htmlspecialchars($gorsel['gorsel_yolu']) ?>" 
                             alt="<?= htmlspecialchars($gorsel['baslik']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <?php if(!empty($gorsel['baslik'])): ?>
                    <h3 class="mt-4 text-lg font-semibold text-appleDark px-2"><?= htmlspecialchars($gorsel['baslik']) ?></h3>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Veritabanı boşken gösterilecek mesaj -->
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <i class="fa-solid fa-images text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-600">Henüz çalışma yüklenmedi.</h3>
                    <p class="text-sm text-gray-400 mt-1">Admin panelinden yeni çalışmalar eklediğinizde burada görünecektir.</p>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- İletişim / Footer Section (Açık Renk Tema) -->
    <section id="contact" class="bg-white text-gray-800 pt-20 pb-10 border-t border-gray-100 reveal active">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 border-b border-gray-200 pb-12">
                
                <!-- 1. Marka -->
                <div>
                    <img class="h-12 w-auto mb-4" src="logo.jpeg" alt="Cihan Matbaa Logo" loading="lazy">
                    <p class="text-gray-500 font-light max-w-sm text-sm">
                        Eskişehir Tepebaşı merkezli markamızla, yüksek kalite standartları ve hızlı teslimat anlayışımızla Türkiye’nin 81 iline güvenilir hizmet ve kargo imkanı sunuyoruz.
                    </p>
                </div>
                
                <!-- 2. İletişim Bilgileri -->
                <!-- 2. İletişim Bilgileri -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-appleDark">Bize Ulaşın</h4>
                    <ul class="space-y-3 text-gray-500 font-light text-sm">
                        <li class="flex items-start">
                            <i class="fa-solid fa-location-dot mt-1 mr-3 text-cmykCyan"></i>
                            <span class="hover:text-cmykCyan transition cursor-default">Cumhuriye, Tutal Sk. No:17 D:B, 26130 Tepebaşı/Eskişehir</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-phone mr-3 text-cmykCyan"></i>
                            <a href="tel:+905320000000" class="hover:text-cmykCyan transition">0 532 XXX XX XX</a> 
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-phone mr-3 text-cmykCyan"></i>
                            <a href="tel:+902222345207" class="hover:text-cmykCyan transition">0 222 234 52 07</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-envelope mr-3 text-cmykCyan"></i>
                            <a href="mailto:siparis@cihanmatbaa.com" class="hover:text-cmykCyan transition">siparis@cihanmatbaa.com</a>
                        </li>
                    </ul>
                </div>
                
                <!-- 3. Çalışma Saatleri -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-appleDark">Çalışma Saatleri</h4>
                    <ul class="space-y-3 text-gray-500 font-light text-sm">
                        <li class="flex justify-between border-b border-gray-100 pb-2">
                            <span>Pazartesi - Cuma</span>
                            <span class="font-medium text-appleDark">08:30 - 18:30</span>
                        </li>
                        <li class="flex justify-between pb-2">
                            <span>Cumartesi - Pazar</span>
                            <span class="font-medium text-appleDark">09:00 - 14:00</span>
                        </li>
                    </ul>
                </div>

                <!-- 4. Konum / Harita -->
               <!-- 4. Konum / Harita -->
                <div>
                    <h4 class="text-lg font-bold mb-4 text-appleDark">Konum</h4>
                    
                    <!-- Haritayı tamamen saran ve senin verdiğin linke giden yapı -->
                    <a href="https://www.google.com/maps/place/Cihan+Matbaac%C4%B1l%C4%B1k/@39.7781726,30.5185234,18.5z/data=!4m15!1m8!3m7!1s0x14cc15fd0bdd099d:0x8e5c987f2683d6d3!2zQ3VtaHVyaXllLCBUdXRhbCBTay4gTm86MTcsIDI2MTMwIFRlcGViYcWfxLEvRXNracWfZWhpcg!3b1!8m2!3d39.7782013!4d30.519011!16s%2Fg%2F11rgfjs5kb!3m5!1s0x14cc15fd0bc5fe0b:0x19087df3549e9f60!8m2!3d39.7781605!4d30.5187969!16s%2Fg%2F1hc865fbz?hl=tr&entry=ttu&g_ep=EgoyMDI2MDgwOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="block w-full h-48 rounded-xl overflow-hidden shadow-sm border border-gray-200 relative group cursor-pointer">
                        
                        <!-- Üzerine gelince çıkan şık "Haritada Aç" efekti -->
                        <div class="absolute inset-0 z-10 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 flex items-center justify-center">
                            <div class="bg-white text-cmykCyan px-5 py-2.5 rounded-lg font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center gap-2 shadow-lg transform scale-95 group-hover:scale-100">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Haritada Aç
                            </div>
                        </div>

                        <!-- Harita İframe (Tıklamayı linkin alması için pointer-events-none eklendi) -->
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3067.147986066735!2d30.5144139!3d39.78129!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cc15e219468087%3A0x633daeaabdc31d10!2zQ3VtaHVyaXllLCBUdXRhbCBTay4gTm86MTcsIDI2MTMwIFRlcGViYcWfxLEvRXNracWfZWhpcg!5e0!3m2!1str!2str!4v1715000000000!5m2!1str!2str" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="pointer-events-none">
                        </iframe>
                    </a>
                </div>

            </div>
            <div class="text-center text-gray-400 text-sm font-light">
                &copy; 2026 Cihan Matbaacılık Tüm hakları saklıdır.
            </div>
        </div>
    </section>

    <!-- Sabit Whatsapp Butonu -->
    <a href="https://wa.me/905320000000" target="_blank" class="fixed bottom-6 right-6 bg-[#25D366] text-white w-14 h-14 rounded-full flex items-center justify-center text-3xl shadow-2xl hover:scale-110 transition-transform z-50 animate-pulse-soft">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <script src="app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const galFilterBtns = document.querySelectorAll('.gal-filter-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');
            galFilterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Stilleri sıfırla
                    galFilterBtns.forEach(b => {
                        b.classList.remove('bg-cmykCyan', 'text-white', 'shadow-sm', 'border-cmykCyan');
                        b.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                    });
                    
                    // Tıklananı aktif yap
                    btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
                    btn.classList.add('bg-cmykCyan', 'text-white', 'shadow-sm', 'border-cmykCyan');
                    
                    const filterValue = btn.getAttribute('data-filter');
                    
                    // Ürünleri filtrele
                    galleryItems.forEach(item => {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                                item.style.display = 'block';
                                setTimeout(() => {
                                    item.style.opacity = '1';
                                    item.style.transform = 'scale(1)';
                                }, 50);
                            } else {
                                item.style.display = 'none';
                            }
                        }, 300);
                    });
                });
            });
        });
    </script>

    <!-- Görsel Büyütme Modal -->
<div id="imageModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4" onclick="closeImageModal()">
    <img id="modalFullImg" src="" alt="Tam Boy Görsel" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
    <button class="absolute top-6 right-6 text-white text-3xl hover:text-cmykCyan transition">&times;</button>
</div>

<script>
    function openImageModal(imgSrc) {
        document.getElementById('modalFullImg').src = imgSrc;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
</body>
</html>