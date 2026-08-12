-- Veritabanı yapısı: Cihan Matbaa

-- 1. Ürünler Tablosu
CREATE TABLE `urunler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urun_adi` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `durum` varchar(50) NOT NULL DEFAULT 'aktif',
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Galeri (Çalışmalarımız) Tablosu
CREATE TABLE `galeri` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `baslik` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) NOT NULL,
  `gorsel_yolu` varchar(255) NOT NULL,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;