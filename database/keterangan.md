# Cara Import Database

## Dengan PHP My Admin

1. Buat database baru dengan nama 'nama_database';
2. Klik database 'nama_database', pilih menu import;
3. Klik browse cari file sql 'nama_database' dalam explorer;
4. Klik go, database sukses terimport;

## Dengan menggunakan Terminal

> Kekurangan: Tidak dapat mengimport trigger

1. Buka terminal masuk ke path folder project kalian dan cd ke `/database`;
   > Misal: cd /xampp/htdocs/app_stok2/database
2. Jalankan perintah berikut

   > php import-sql.php -d {nama_database} -b {nama_backup_database.sql}

   > `nama_backup_database` adalah optional, gunakan jika nama backup file tidak sama dengan nama database

3. Jika terjadi error, pastikan database anda sudah dibuat

4. Selesai, database anda telah selesai di import.
