# Safe Demo Deployment (No Downtime for Main Site)

Dokumen ini untuk bikin instance demo tanpa mengganggu web utama.

## Kenapa perlu cara ini
- Compose utama memakai nama container tetap dan port 8080.
- Kalau diduplikasi mentah, service bisa bentrok dan berisiko mematikan produksi.

## Prinsip aman
- Jalankan demo dari folder clone terpisah.
- Pakai compose file demo terpisah.
- Pakai project name berbeda.
- Pakai database, volume, network, dan port terpisah.

## 1) Clone proyek ke folder baru
Contoh:
- /root/BROMOIJENEXPEDITIONJAVAv2-demo

## 2) Siapkan env demo
Di folder demo:
1. Copy .env.demo.example menjadi .env.demo
2. Ubah APP_URL ke domain demo
3. Pastikan DB_DATABASE, DB_USERNAME, DB_PASSWORD khusus demo

## 3) Jalankan stack demo (isolated)
Di folder demo jalankan:
- docker compose -f docker-compose.demo.yml --env-file .env.demo -p bromoijen_demo up -d --build

Penting:
- Selalu pakai -f docker-compose.demo.yml
- Selalu pakai -p bromoijen_demo
- Jangan jalankan docker compose down tanpa file/project name yang benar

## 4) Inisialisasi aplikasi demo
- docker compose -f docker-compose.demo.yml --env-file .env.demo -p bromoijen_demo exec -T app php artisan key:generate
- docker compose -f docker-compose.demo.yml --env-file .env.demo -p bromoijen_demo exec -T app php artisan migrate --force
- docker compose -f docker-compose.demo.yml --env-file .env.demo -p bromoijen_demo exec -T app php artisan storage:link

Opsional seed data:
- docker compose -f docker-compose.demo.yml --env-file .env.demo -p bromoijen_demo exec -T app php artisan db:seed --force

## 5) Akses demo
- http://SERVER_IP:8081

## 6) Verifikasi web utama tetap hidup
Di server utama cek:
- docker compose ps
- pastikan container utama tetap Up

## 7) Stop hanya demo (aman)
- docker compose -f docker-compose.demo.yml --env-file .env.demo -p bromoijen_demo down

## Checklist anti-nyentuh produksi
- Folder kerja sudah folder demo
- Perintah selalu bawa -f docker-compose.demo.yml
- Perintah selalu bawa -p bromoijen_demo
- APP_URL dan DB_* memakai nilai demo
