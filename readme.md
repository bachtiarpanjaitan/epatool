***Requirements***
- PHP versi 7.4+
- Nodejs 20+
- Sudah terinstall composer
- PHP extension php-pgsql (active)
- Database PostgreSQL
- Quasar CLI


***Run backend***
- masuk ke folder backend dan buka terminal/CMD
- [*] install dulu package composernya dengan cara <code>composer install</code>
- [*] copy file .env.example kemudian letakkan di path yang sama dan ganti namanya menjadi .env.
- [*] sesuaikan value database sesuai dengan koneksi postgresql yang terinstall di komputermu
- [*] migrasi databasenya dengan cara <code>php artisan migrate</code>
- [*] generate key dan jwt secret dengan cara <code> php artisan key:generate</code> dan <code>php artisan jwt:secret</code>
- [*] seed data inisiasi <code> php artisan db:seed</code>
- jalankan backend di port 8000<code>php artisan server --port=8000 </code>

***Run Frontend***
- masuk ke folder frontend dan buka terminal/CMD
- [*] install dulu package yang dibutuhkan dengan cara <code> npm install </code>
- jalankan frontend <code> quasar dev</code>
- buka website di frontend url http://localhost:8080

`NOTED: Tanda [*] artinya hanya dilakukan pas pertama kali menjalankan aplikasi saja.`


***Default akun otentikasi***
- Akun Requestor: <code>requestor:Password1234</code>
- Akun Worker: <code>worker:Password1234</code>
