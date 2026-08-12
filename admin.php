<?php 
require_once 'backend/baglan.php'; 

if(!isset($_SESSION['admin_giris'])) {
    header("Location: login.php");
    exit;
}

// Kategorileri, Ürünleri ve Galeriyi Çekelim
$kategoriler_urun = $db->query("SELECT * FROM kategoriler WHERE modul = 'urun'")->fetchAll(PDO::FETCH_ASSOC);
$kategoriler_galeri = $db->query("SELECT * FROM kategoriler WHERE modul = 'galeri'")->fetchAll(PDO::FETCH_ASSOC);

// Ürünleri kategorileriyle eşleştirerek (JOIN) çekiyoruz
$urunler = $db->query("
    SELECT urunler.*, kategoriler.kategori_adi 
    FROM urunler 
    LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id 
    ORDER BY urunler.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

$galeriler = $db->query("SELECT * FROM galeri ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelişmiş Yönetim Paneli | Cihan Matbaa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { appleDark: '#1d1d1f', cmykCyan: '#00AEEF' } } } }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FAFAFA; }
        .sidebar-link.active { background-color: #F5F5F7; border-left: 3px solid #00AEEF; color: #1d1d1f; }
        .modal { opacity: 0; visibility: hidden; transition: all 0.3s ease; }
        .modal.active { opacity: 1; visibility: visible; }
        .tab-section { display: none; animation: fadeIn 0.3s ease; }
        .tab-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <!-- SOL MENÜ -->
    <aside id="admin-sidebar" class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between absolute md:relative z-30 h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div>
            <div class="h-20 flex items-center justify-between px-6 border-b border-gray-50">
                <h1 class="text-xl font-bold text-appleDark">Cihan<span class="text-cmykCyan">Admin</span></h1>
            </div>
            <nav class="mt-6 flex flex-col gap-2 px-4">
                <a onclick="switchTab('urunler', 'Ürün Yönetimi', this)" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-600 cursor-pointer hover:bg-gray-50">
                    <i class="fa-solid fa-layer-group w-5"></i> Ürünler
                </a>
                <a onclick="switchTab('galeri', 'Çalışmalarımız', this)" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-600 cursor-pointer hover:bg-gray-50">
                    <i class="fa-solid fa-images w-5"></i> Galeri
                </a>
                <a onclick="switchTab('kategori', 'Kategoriler', this)" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-gray-600 cursor-pointer hover:bg-gray-50">
                    <i class="fa-solid fa-tags w-5"></i> Kategoriler
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-gray-50">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition font-medium">
                <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Çıkış Yap
            </a>
        </div>
    </aside>

    <!-- ANA İÇERİK -->
    <main class="flex-1 flex flex-col h-screen relative w-full overflow-hidden">
        
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 z-10 w-full">
            <h1 id="page-title" class="text-2xl font-bold text-appleDark">Ürün Yönetimi</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">Merhaba, <strong><?= htmlspecialchars($_SESSION['admin_kadi']) ?></strong></span>
                <div class="w-10 h-10 rounded-full bg-[#00AEEF]/10 flex items-center justify-center text-cmykCyan"><i class="fa-solid fa-user-shield"></i></div>
            </div>
        </header>

        <div class="p-6 overflow-y-auto h-full w-full">
    
            <?php if(isset($_GET['islem']) && $_GET['islem'] == 'basarili'): ?>
                <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> İşlem başarıyla gerçekleştirildi.
                </div>
            <?php endif; ?>

            <!-- 1. ÜRÜNLER SEKMESİ -->
            <div id="urunler" class="tab-section active bg-white p-6 rounded-2xl shadow-sm border border-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <div class="relative w-full sm:w-72">
                        <input type="text" id="urunArama" onkeyup="canliArama('urunArama', 'urunTablosu')" placeholder="Ürünlerde canlı ara..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 focus:border-cmykCyan outline-none text-sm transition">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button onclick="toggleModal('addProductModal')" class="w-full sm:w-auto bg-appleDark text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-black transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus"></i> Yeni Ürün
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="urunTablosu">
                        <thead>
                            <tr class="text-gray-400 text-sm border-b border-gray-100">
                                <th class="pb-3 font-medium w-16">Görsel</th>
                                <th class="pb-3 font-medium">Ürün Adı</th>
                                <th class="pb-3 font-medium">Kategori</th>
                                <th class="pb-3 font-medium">Fiyat</th>
                                <th class="pb-3 font-medium text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php foreach($urunler as $urun): ?>
                            <tr class="border-b border-gray-50 urun-satir">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="<?= htmlspecialchars($urun['gorsel_yolu']) ?>" alt="" class="w-12 h-12 object-cover rounded-lg">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-appleDark urun-isim"><?= htmlspecialchars($urun['urun_adi']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($urun['kategori_adi'] ?? 'Kategorisiz') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($urun['fiyat']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" onclick="openEditModal('<?= $urun['id'] ?>', '<?= addslashes($urun['urun_adi']) ?>', '<?= $urun['kategori_id'] ?>', '<?= addslashes($urun['fiyat'] ?? '') ?>', '<?= addslashes($urun['malzeme'] ?? '') ?>')" class="text-cmykCyan hover:text-[#0088bd] mr-3 font-semibold">
                                        <i class="fa-solid fa-pen-to-square"></i> Düzenle
                                    </button>
                                    
                                    <a href="backend/urun_sil.php?id=<?= $urun['id'] ?>" onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?');" class="text-red-500 hover:text-red-700 font-semibold">
                                        <i class="fa-solid fa-trash"></i> Sil
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. GALERİ SEKMESİ -->
            <div id="galeri" class="tab-section bg-white p-6 rounded-2xl shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-bold text-lg">Çalışmalar Galerisi</h2>
                    <button onclick="toggleModal('addGaleriModal')" class="bg-appleDark text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-black transition"><i class="fa-solid fa-plus"></i> Yeni Çalışma</button>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php foreach($galeriler as $g): ?>
                    <div class="border rounded-xl p-2 bg-gray-50">
                        <img src="<?= htmlspecialchars($g['gorsel_yolu']) ?>" class="w-full h-32 object-cover rounded-lg mb-2">
                        <div class="flex justify-between items-center px-1">
                            <span class="text-xs font-medium truncate"><?= htmlspecialchars($g['baslik']) ?></span>
                            <a href="backend/galeri_sil.php?id=<?= $g['id'] ?>" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 3. KATEGORİ SEKMESİ -->
            <div id="kategori" class="tab-section bg-white p-6 rounded-2xl shadow-sm border border-gray-50">
                <h2 class="font-bold text-lg mb-6">Kategori Yönetimi</h2>
                <form action="backend/kategori_ekle.php" method="POST" class="flex gap-2 mb-6">
                    <input type="text" name="kategori_adi" placeholder="Yeni kategori adı..." class="flex-1 px-4 py-2 border rounded-lg text-sm outline-none" required>
                    <select name="modul" class="px-4 py-2 border rounded-lg text-sm bg-white">
                        <option value="urun">Ürün Kategorisi</option>
                        <option value="galeri">Galeri Kategorisi</option>
                    </select>
                    <button type="submit" class="bg-cmykCyan text-white px-6 py-2 rounded-lg text-sm font-medium">Ekle</button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                    <div>
                        <h3 class="font-semibold text-appleDark border-b pb-2 mb-3"><i class="fa-solid fa-layer-group text-cmykCyan mr-2"></i> Ürün Kategorileri</h3>
                        <table class="w-full text-sm">
                            <?php foreach($kategoriler_urun as $k): ?>
                            <tr class="border-b border-gray-50">
                                <td class="py-3"><?= htmlspecialchars($k['kategori_adi']) ?></td>
                                <td class="py-3 text-right"><a href="backend/kategori_sil.php?id=<?= $k['id'] ?>" class="text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                    <div>
                        <h3 class="font-semibold text-appleDark border-b pb-2 mb-3"><i class="fa-solid fa-images text-cmykCyan mr-2"></i> Galeri Kategorileri</h3>
                        <table class="w-full text-sm">
                            <?php foreach($kategoriler_galeri as $k): ?>
                            <tr class="border-b border-gray-50">
                                <td class="py-3"><?= htmlspecialchars($k['kategori_adi']) ?></td>
                                <td class="py-3 text-right"><a href="backend/kategori_sil.php?id=<?= $k['id'] ?>" class="text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash"></i></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- YENİ ÜRÜN EKLEME MODALI -->
    <div id="addProductModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 relative">
            <button onclick="toggleModal('addProductModal')" class="absolute top-4 right-4 text-gray-400 hover:text-appleDark transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h2 class="text-xl font-bold text-appleDark mb-6">Gelişmiş Ürün Ekle</h2>
            
            <form action="backend/urun_ekle.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ürün Adı</label>
                    <input type="text" name="urun_adi" required class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori_id" required class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm bg-white">
                        <?php foreach($kategoriler_urun as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['kategori_adi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat / Bilgi</label>
                    <input type="text" name="fiyat" placeholder="Örn: 1200 ₺ / 200 Adet" class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Malzeme / Özellik (Opsiyonel)</label>
                    <input type="text" name="malzeme" placeholder="Örn: 350gr Mat Kuşe, Kabartma Laklı" class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ürün Görseli</label>
                    <input type="file" name="gorsel" accept="image/*" required class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm bg-gray-50 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#00AEEF]/10 file:text-cmykCyan hover:file:bg-[#00AEEF]/20 transition cursor-pointer">
                </div>

                <input type="hidden" name="durum" value="aktif">

                <button type="submit" class="w-full bg-appleDark text-white py-2.5 rounded-lg font-medium hover:bg-black transition mt-2 text-sm shadow-md">
                    Ürünü Sisteme Kaydet
                </button>
            </form>
        </div>
    </div>

    <!-- ÜRÜN DÜZENLEME MODALI -->
    <div id="editModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 md:p-8 relative shadow-2xl">
            <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <h3 class="text-2xl font-bold text-appleDark mb-6">Ürünü Düzenle</h3>

            <form action="backend/urun_duzenle.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" id="edit_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ürün Adı</label>
                    <input type="text" name="urun_adi" id="edit_urun_adi" required class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori_id" id="edit_kategori_id" class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm bg-white">
                        <option value="">Lütfen Kategori Seçin</option>
                        <?php foreach($kategoriler_urun as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['kategori_adi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat / Bilgi</label>
                    <input type="text" name="fiyat" id="edit_fiyat" class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Malzeme / Özellik</label>
                    <textarea name="malzeme" id="edit_malzeme" rows="2" class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yeni Görsel (İsteğe Bağlı)</label>
                    <input type="file" name="gorsel" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cmykCyan/10 file:text-cmykCyan hover:file:bg-cmykCyan/20">
                </div>

                <button type="submit" class="w-full bg-appleDark hover:bg-black text-white font-semibold py-3 rounded-xl transition mt-4">
                    Değişiklikleri Kaydet
                </button>
            </form>
        </div>
    </div>

    <!-- YENİ GALERİ EKLEME MODALI -->
    <div id="addGaleriModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6 relative">
            <button onclick="toggleModal('addGaleriModal')" class="absolute top-4 right-4 text-gray-400 hover:text-appleDark transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h2 class="text-xl font-bold text-appleDark mb-6">Yeni Çalışma Ekle (Galeri)</h2>
            
            <form action="backend/galeri_ekle.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Çalışma Başlığı (Opsiyonel)</label>
                    <input type="text" name="baslik" placeholder="Örn: X Firması Kartvizit Çalışması" class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Filtresi</label>
                    <select name="kategori" class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm bg-white">
                        <option value="all">Tümü (Kategorisiz)</option>
                        <?php foreach($kategoriler_galeri as $kat): ?>
                            <option value="<?= htmlspecialchars($kat['kategori_adi']) ?>"><?= htmlspecialchars($kat['kategori_adi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Çalışma Görseli</label>
                    <input type="file" name="gorsel" accept="image/*" required class="w-full px-4 py-2 border rounded-lg focus:border-cmykCyan outline-none text-sm bg-gray-50 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#00AEEF]/10 file:text-cmykCyan hover:file:bg-[#00AEEF]/20 transition cursor-pointer">
                </div>

                <button type="submit" class="w-full bg-appleDark text-white py-2.5 rounded-lg font-medium hover:bg-black transition mt-2 text-sm shadow-md">
                    Çalışmayı Sisteme Yükle
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(modalID) { 
            document.getElementById(modalID).classList.toggle('active'); 
        }
        
        function switchTab(tabId, titleText, clickedElement) {
            document.querySelectorAll('.tab-section').forEach(sec => sec.classList.remove('active'));
            document.querySelectorAll('.sidebar-link').forEach(link => link.classList.remove('active'));
            
            document.getElementById(tabId).classList.add('active');
            document.getElementById('page-title').innerText = titleText;
            clickedElement.classList.add('active');

            localStorage.setItem('aktifSekme', tabId);
        }

        document.addEventListener("DOMContentLoaded", () => {
            const kayitliSekme = localStorage.getItem('aktifSekme');
            if (kayitliSekme) {
                const sekmeButonu = document.querySelector(`.sidebar-link[onclick*="${kayitliSekme}"]`);
                if (sekmeButonu) {
                    sekmeButonu.click();
                }
            }
        });

        // CANLI ARAMA
        function canliArama(inputId, tabloId) {
            var input = document.getElementById(inputId).value.toLowerCase();
            var satirlar = document.getElementById(tabloId).getElementsByClassName('urun-satir');
            
            for (var i = 0; i < satirlar.length; i++) {
                var urunAdi = satirlar[i].getElementsByClassName('urun-isim')[0].innerText.toLowerCase();
                if (urunAdi.indexOf(input) > -1) {
                    satirlar[i].style.display = "";
                } else {
                    satirlar[i].style.display = "none";
                }
            }
        }

        function openEditModal(id, adi, kategoriId, fiyat, malzeme) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_urun_adi').value = adi;
            
            const kategoriSelect = document.getElementById('edit_kategori_id');
            if (kategoriSelect) {
                kategoriSelect.value = kategoriId;
            }

            document.getElementById('edit_fiyat').value = fiyat;
            document.getElementById('edit_malzeme').value = malzeme;
            
            document.getElementById('editModal').classList.remove('active'); // modal class kontrolü
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>