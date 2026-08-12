# PHP 8.2 ve Apache (web sunucusu) içeren hazır bir sistem alıyoruz
FROM php:8.2-apache

# baglan.php'de PDO kullandığın için MySQL eklentilerini kuruyoruz
RUN docker-php-ext-install pdo pdo_mysql

# Senin klasöründeki tüm proje dosyalarını sunucunun ana dizinine kopyalıyoruz
COPY . /var/www/html/

# Sunucuya dosyaları okuma/yazma izni veriyoruz
RUN chown -R www-data:www-data /var/www/html

# Dışarıya 80 portundan (standart web portu) yayın yapıyoruz
EXPOSE 80