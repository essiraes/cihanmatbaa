<?php
require_once 'backend/baglan.php'; 

$urun_kategorileri = $db->query("SELECT * FROM kategoriler WHERE modul = 'urun' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$urunler = $db->query("
    SELECT urunler.*, kategoriler.id as cat_id, kategoriler.kategori_adi 
    FROM urunler 
    LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id 
    WHERE urunler.durum = 'aktif'
    ORDER BY urunler.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cihan Matbaa | Eskişehir Dijital Baskı ve Promosyon Çözümleri</title>
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
</head>
<body class="antialiased relative">

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
                    <li><a href="#hero" class="hover:text-cmykCyan transition-colors">Ana Sayfa</a></li>
                    <li><a href="#about" class="hover:text-cmykCyan transition-colors">Hakkımızda</a></li>
                    
                    <li class="group h-full flex items-center">
                        <a href="#services" class="hover:text-cmykCyan transition-colors flex items-center gap-1">
                            Hizmetlerimiz <i class="fa-solid fa-chevron-down text-xs"></i>
                        </a>
                        
                        <div class="mega-menu absolute left-0 top-[80px] w-full bg-white shadow-2xl border-t border-gray-100 p-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                            <?php if(count($urun_kategorileri) > 0): ?>
                                <?php foreach($urun_kategorileri as $kat): ?>
                                <div>
                                    <h4 class="font-bold text-appleDark mb-4 border-b pb-2"><?= htmlspecialchars($kat['kategori_adi']) ?></h4>
                                    <ul class="space-y-2 text-sm text-gray-500">
                                        <?php 
                                        $urun_var_mi = false;
                                        foreach($urunler as $urun): 
                                            if($urun['cat_id'] == $kat['id']): 
                                                $urun_var_mi = true;
                                        ?>
                                            <li><a href="#services" class="hover:text-cmykCyan transition-colors"><?= htmlspecialchars($urun['urun_adi']) ?></a></li>
                                        <?php 
                                            endif; 
                                        endforeach; 
                                        if(!$urun_var_mi): 
                                        ?>
                                            <li class="italic text-xs text-gray-300">Henüz ürün eklenmedi</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-span-full text-center py-4 text-gray-500 text-sm">
                                    Sistemde henüz kategori bulunmuyor.
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>

                    <li>
                        <a href="galeri.php" class="hover:text-cmykCyan transition-colors font-medium relative group flex items-center gap-1">
                            <i class="fa-solid fa-image text-cmykCyan"></i> Çalışmalarımız
                        </a>
                    </li>
                    <li><a href="#contact" class="hover:text-cmykCyan transition-colors">İletişim</a></li>
                </ul>
                
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="mobile-menu" class="hidden absolute w-full bg-white border-t border-gray-100 shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1 text-center mobile-nav-links">
                <a href="#hero" class="block px-3 py-2 text-appleDark">Ana Sayfa</a>
                <a href="#about" class="block px-3 py-2 text-appleDark">Hakkımızda</a>
                <a href="#services" class="block px-3 py-2 text-appleDark">Tüm Hizmetlerimiz</a>
                <a href="galeri.php" class="block px-3 py-2 text-appleDark">Çalışmalarımız</a>
                <a href="#contact" class="block px-3 py-2 text-appleDark">İletişim</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden reveal">
        <div class="hero-bg absolute inset-0 z-0">
            <img src="menu_bg.jpg" alt="Matbaa Arka Plan">
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="hero-title text-5xl md:text-7xl font-bold tracking-tight mb-6 leading-tight">
                En Özel Günlerinize, <br>En 
                <span class="gradient-text" data-text=" Güzel"> Güzel</span> Dokunuş.
            </h1>
            <p class="hero-subtitle mt-4 text-xl max-w-2xl mx-auto mb-10 font-light">
                Yüksek çözünürlüklü dijital baskıdan, davetiye, ofset, grafik ve kaşeye kadar tüm ihtiyaçlarınız için hızlı ve kusursuz çözümler Cihan Matbaacılıkta.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="https://wa.me/905320000000?text=Merhaba,%20web%20sitenizden%20yazıyorum.%20Fiyat%20teklifi%20almak%20istiyorum." target="_blank" class="btn-primary animate-pulse-soft px-8 py-4 rounded-full font-semibold text-lg w-full sm:w-auto flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-xl"></i> WhatsApp'tan Fiyat Al
                </a>
                <a href="#services" class="btn-secondary px-8 py-4 rounded-full font-semibold text-lg w-full sm:w-auto">
                    Hizmetlerimizi İncele
                </a>
            </div>
            <p class="mt-4 text-sm text-gray-500 font-medium">Bizi Hemen Arayın: <a href="tel:+905320000000" class="text-appleDark hover:text-cmykCyan transition-colors">0532 XXX XX XX</a></p>
        </div>
    </section>

    <!-- Hakkımızda Section -->
    <section id="about" class="py-20 bg-appleGray reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-appleDark">Teknoloji ve Ustalığın Buluşma Noktası</h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6 font-light">
                        Yılların getirdiği ustalıkla, modern teknolojiyi harmanlıyoruz. Kartvizitten devasa dış mekan afişlerine kadar her projeye aynı özeni gösteriyor, markanızın renklerini en doğru şekilde kağıda yansıtıyoruz.
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="apple-card p-6 text-center">
                        <div class="text-4xl font-bold text-cmykCyan mb-2">10+</div>
                        <div class="text-sm text-gray-500 font-medium">Yıllık Tecrübe</div>
                    </div>
                    <div class="apple-card p-6 text-center">
                        <div class="text-4xl font-bold text-appleDark mb-2">500+</div>
                        <div class="text-sm text-gray-500 font-medium">Marka İşbirliği</div>
                    </div>
                    <div class="apple-card p-6 text-center">
                        <div class="text-4xl font-bold text-cmykYellow mb-2">%100</div>
                        <div class="text-sm text-gray-500 font-medium">Baskı Garantisi</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HİZMETLERİMİZ SECTION -->
    <section id="services" class="py-24 bg-white reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h2 class="text-3xl md:text-5xl font-bold section-title mb-4">Ürün ve Hizmetlerimiz</h2>
                <p class="text-gray-500 text-lg font-light">İhtiyacınız olan kategoriyi seçin ve ürünlerimizi inceleyin.</p>
            </div>
            
            <div class="filter-wrapper flex overflow-x-auto hide-scrollbar space-x-3 pb-6 mb-8 snap-x">
                <button class="filter-btn active shrink-0 px-6 py-2 rounded-full border border-gray-200 text-sm font-medium transition-all" data-filter="all">Tümü</button>
                <?php foreach($urun_kategorileri as $kat): ?>
                    <button class="filter-btn shrink-0 px-6 py-2 rounded-full border border-gray-200 text-sm font-medium transition-all" data-filter="<?= $kat['id'] ?>">
                        <?= htmlspecialchars($kat['kategori_adi']) ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- ÜRÜN IZGARASI VE MODAL TETİKLEYİCİLERİ -->
            <!-- DİNAMİK ÜRÜN IZGARASI -->
            <!-- DİNAMİK ÜRÜN IZGARASI -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="product-grid">
                
                <?php if(count($urunler) > 0): 
                    $urun_sayac = 0;
                    foreach($urunler as $urun): 
                        $urun_sayac++;
                        // İlk 6 üründen sonrakilere 'extra-product' class'ı verip gizliyoruz
                        $gizli_mi = ($urun_sayac > 6) ? 'hidden extra-product' : '';
                ?>
                        <div class="product-item apple-card p-4 transition-all duration-300 cursor-pointer hover:shadow-lg hover:-translate-y-1 <?= $gizli_mi ?>" 
                             data-category="<?= $urun['cat_id'] ?>" 
                             onclick="openProductModal('<?= $urun['id'] ?>', '<?= htmlspecialchars($urun['urun_adi'], ENT_QUOTES) ?>', '<?= htmlspecialchars($urun['kategori_adi'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($urun['fiyat'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($urun['malzeme'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($urun['gorsel_yolu'], ENT_QUOTES) ?>')">
                            
                            <div class="w-full h-48 rounded-xl overflow-hidden mb-4 bg-gray-100 relative group">
                                <img src="<?= htmlspecialchars($urun['gorsel_yolu']) ?>" alt="<?= htmlspecialchars($urun['urun_adi']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            
                            <h3 class="text-lg font-bold text-appleDark"><?= htmlspecialchars($urun['urun_adi']) ?></h3>
                            <p class="text-sm text-cmykCyan font-medium mb-3"><?= htmlspecialchars($urun['kategori_adi'] ?? 'Kategorisiz') ?></p>
                            
                            <?php if(!empty($urun['malzeme'])): ?>
                                <p class="text-xs text-gray-500 mb-3 line-clamp-2"><i class="fa-solid fa-circle-info mr-1"></i> <?= htmlspecialchars($urun['malzeme']) ?></p>
                            <?php endif; ?>

                            <?php if(!empty($urun['fiyat'])): ?>
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-xs text-gray-400">Başlayan fiyatlarla</span>
                                    <span class="font-bold text-appleDark"><?= htmlspecialchars($urun['fiyat']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-12 text-center bg-gray-50 rounded-2xl border border-gray-100">
                        <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-600">Henüz ürün eklenmemiş.</h3>
                        <p class="text-sm text-gray-400 mt-1">Admin panelinden yeni ürünler ekleyebilirsiniz.</p>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Daha Fazla / Tüm Ürünleri Gör Butonu (Sadece 6'dan fazla ürün varsa görünür) -->
            </div>

            <!-- TEK VE NET: Daha Fazla Ürün Göster Butonu -->
            <?php if(count($urunler) > 6): ?>
            <div class="text-center mt-12">
                <button id="loadMoreProductsBtn" onclick="toggleExtraProducts()" class="bg-cmykCyan hover:bg-[#0098d4] text-white px-8 py-3 rounded-full font-semibold transition shadow-md">
                    Daha Fazla Ürün Göster
                </button>
            </div>
            <?php endif; ?>
            </div>

        </div>
    </section>

    <!-- İletişim / Footer Section -->
    <section id="contact" class="bg-white text-gray-800 pt-20 pb-10 border-t border-gray-100 reveal active">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12 border-b border-gray-200 pb-12">
                
                <div>
                    <img class="h-12 w-auto mb-4" src="logo.jpeg" alt="Cihan Matbaa Logo" loading="lazy">
                    <p class="text-gray-500 font-light max-w-sm text-sm">
                        Eskişehir Tepebaşı merkezli markamızla, yüksek kalite standartları ve hızlı teslimat anlayışımızla Türkiye’nin 81 iline güvenilir hizmet ve kargo imkanı sunuyoruz.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4 text-appleDark">Bize Ulaşın</h4>
                    <ul class="space-y-3 text-gray-500 font-light text-sm">
                        <li class="flex items-start">
                            <i class="fa-solid fa-location-dot mt-1 mr-3 text-cmykCyan"></i>
                            <a href="https://maps.google.com/?q=Cumhuriye,+Tutal+Sk.+No:17+D:B,+26130+Tepebaşı/Eskişehir" target="_blank" class="hover:text-cmykCyan transition cursor-pointer">Cumhuriye, Tutal Sk. No:17 D:B, 26130 Tepebaşı/Eskişehir</a>
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

                <div>
                    <h4 class="text-lg font-bold mb-4 text-appleDark">Konum</h4>
                    <a href="https://www.google.com/maps/place/Cihan+Matbaac%C4%B1l%C4%B1k/@39.7781726,30.5185234,18.5z/data=!4m15!1m8!3m7!1s0x14cc15fd0bdd099d:0x8e5c987f2683d6d3!2zQ3VtaHVyaXllLCTu3GFsIFNrLiBObzoxNyBELkIsIDI2MTMwIFRlcGViYcWfxLEvRXNracWfZWhpcg!3b1!8m2!3d39.7782013!4d30.519011!16s%2Fg%2F11rgfjs5kb!3m5!1s0x14cc15fd0bc5fe0b:0x19087df3549e9f60!8m2!3d39.7781605!4d30.5187969!16s%2Fg%2F1hc865fbz?hl=tr&entry=ttu&g_ep=EgoyMDI2MDgwOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="block w-full h-48 rounded-xl overflow-hidden shadow-sm border border-gray-200 relative group cursor-pointer">
                        <div class="absolute inset-0 z-10 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 flex items-center justify-center">
                            <div class="bg-white text-cmykCyan px-5 py-2.5 rounded-lg font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center gap-2 shadow-lg transform scale-95 group-hover:scale-100">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Haritada Aç
                            </div>
                        </div>
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

    <!-- ÜRÜN DETAY MODAL (POP-UP) -->
    <div id="productModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 md:p-8 relative shadow-2xl transform scale-95 transition-transform duration-300" id="modalContent">
            <button onclick="closeProductModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div class="bg-gray-50 rounded-2xl overflow-hidden h-64 flex items-center justify-center border border-gray-100">
                    <img id="modalImg" src="" alt="Ürün Görseli" class="w-full h-full object-cover">
                </div>
                <div class="flex flex-col justify-center">
                    <span id="modalCategory" class="text-xs font-semibold text-cmykCyan mb-1 uppercase tracking-wider"></span>
                    <h3 id="modalTitle" class="text-2xl font-bold text-appleDark mb-3"></h3>
                    
                    <div id="modalMaterialContainer" class="mb-4 hidden">
                        <p class="text-xs text-gray-500 bg-appleGray p-3 rounded-xl" id="modalMaterial"></p>
                    </div>

                    <div id="modalPriceContainer" class="mb-6 border-t border-gray-100 pt-4">
                        <span class="text-xs text-gray-400 block mb-1">Fiyatlandırma</span>
                        <span id="modalPrice" class="text-2xl font-bold text-appleDark"></span>
                    </div>

                    <a id="modalWhatsapp" href="#" target="_blank" class="bg-[#25D366] hover:bg-[#1EBE5C] text-white font-semibold py-3 px-6 rounded-xl text-center transition shadow-lg shadow-green-500/20 flex justify-center items-center gap-2 text-sm">
                        <i class="fa-brands fa-whatsapp text-xl"></i> WhatsApp ile Sipariş Ver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <a href="https://wa.me/905320000000" target="_blank" class="fixed bottom-6 right-6 bg-[#25D366] text-white w-14 h-14 rounded-full flex items-center justify-center text-3xl shadow-2xl hover:scale-110 transition-transform z-50 animate-pulse-soft">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- JavaScript -->
    <script src="app.js"></script>
    <script>

		function toggleExtraProducts() {
        const extraProducts = document.querySelectorAll('.extra-product');
        const btn = document.getElementById('loadMoreProductsBtn');
        
        extraProducts.forEach(prod => prod.classList.toggle('hidden'));
        
        if (btn.innerText.includes('Daha Fazla')) {
            btn.innerText = 'Daha Az Göster';
        } else {
            btn.innerText = 'Daha Fazla Ürün Göster';
        }
    }

        function openProductModal(id, adi, kategori, fiyat, malzeme, gorsel) {
            document.getElementById('modalTitle').innerText = adi;
            document.getElementById('modalCategory').innerText = kategori ? kategori : 'Matbaa Ürünü';
            document.getElementById('modalImg').src = gorsel;
            
            const priceContainer = document.getElementById('modalPriceContainer');
            if (fiyat && fiyat.trim() !== "") {
                document.getElementById('modalPrice').innerText = fiyat;
                priceContainer.style.display = "block";
            } else {
                priceContainer.style.display = "none";
            }

            const matContainer = document.getElementById('modalMaterialContainer');
            const matText = document.getElementById('modalMaterial');
            if (malzeme && malzeme.trim() !== "") {
                matText.innerHTML = '<i class="fa-solid fa-circle-info mr-1"></i> ' + malzeme;
                matContainer.style.display = "block";
            } else {
                matContainer.style.display = "none";
            }

            const mesaj = "Merhaba, web sitenizden " + adi + " ürünü hakkında bilgi ve fiyat almak istiyorum.";
            document.getElementById('modalWhatsapp').href = "https://wa.me/905320000000?text=" + encodeURIComponent(mesaj);

            const modal = document.getElementById('productModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('modalContent').classList.remove('scale-95');
                document.getElementById('modalContent').classList.add('scale-100');
            }, 10);
        }

        function closeProductModal() {
            const modal = document.getElementById('productModal');
            document.getElementById('modalContent').classList.remove('scale-100');
            document.getElementById('modalContent').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target == modal) {
                closeProductModal();
            }
        }
    </script>
</body>
</html>