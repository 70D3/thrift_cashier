Cara Menggunakan project kasir Curious Outdoor :
pastikan php yang digunakan versi 8.2
Laravel yang digunakan versi 11

Buka kode editor masing-masing
masuk ke terminal

clone project dari GitHub dengan perintah :
git clone https://github.com/70D3/thrift_cashier.git
masuk ke folder thrift_cashier dengan perintah :
cd -> lokasi folder thrift_cashier
install composer dengan perintah :
composer install
konfigurasi .env
DB_CONNECTION=mysql
DB_DATABASE=thrift_cashier
DB_USERNAME=root
DB_PASSWORD=
jalankan migrate database dengan perintah :
php artisan migrate
kemudian jalankan server Laravel dengan perintah:
php artisan serve


Note: karena pada project saya terdapat payment gateway dan saya menggunakan midtrans, jika ingin menggunakan midtrans daftar dan buat akun midtrans terlebih dulu, kemudian masukkan konfigurasi midtrans kalian di .env

MIDTRANS_SERVER_KEY=<<midtrans server kalian>>
MIDTRANS_CLIENT_KEY=<<midtrans client kalian>>
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
