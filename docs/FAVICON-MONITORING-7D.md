# Favicon Monitoring 7 Hari

Tujuan: memastikan favicon sudah stabil dan konsisten terbaca crawler Google selama 7 hari berturut-turut.

## File yang dipakai
- Script: scripts/favicon-monitor.sh
- Log CSV: docs/favicon-monitor.csv

## Jalankan cek harian
```bash
bash scripts/favicon-monitor.sh
```

## Jalankan cek manual dengan parameter custom
```bash
bash scripts/favicon-monitor.sh https://bromoijenexpeditionjava.com /favicon-v2.ico /
```

## Checklist target harian
- favicon_http_status = 200
- favicon_content_type = image/x-icon
- favicon_declared_in_home = yes
- home_http_status = 200

## Evaluasi setelah 7 hari
- Lolos jika semua hari memenuhi 4 target di atas.
- Jika ada gagal, cek cache edge (Cloudflare), tag head, dan respons origin.

## Opsional: jadwalkan otomatis via cron (1x/hari)
Tambahkan ke crontab server:
```bash
15 1 * * * cd /root/BROMOIJENEXPEDITIONJAVAv2 && /usr/bin/env bash scripts/favicon-monitor.sh >> storage/logs/favicon-monitor.log 2>&1
```

## Tindak lanjut SEO
Setelah log 2-3 hari stabil, lakukan:
1. URL Inspection homepage di Google Search Console.
2. Request Indexing untuk homepage dan halaman penting.
3. Pantau perubahan favicon di hasil pencarian.
