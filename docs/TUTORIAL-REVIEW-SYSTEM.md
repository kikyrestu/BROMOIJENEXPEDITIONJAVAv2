# 📋 Tutorial Review System - Bromo Ijen Expedition Java

Panduan lengkap untuk mengelola sistem review customer.

---

## 🔗 Cara Buat Link Review untuk Customer

1. Buka **https://bromoijenexpeditionjava.com/review-panel**
2. Login pakai akun admin seperti biasa
3. Di menu sebelah kiri, klik **"Review Links"**
4. Klik tombol **"Generate New Link"** (warna biru di atas)
5. Isi form:
   - **Label** → tulis nama customer biar gampang dilacak (contoh: "Pak Budi - Trip 10 Maret")
   - **Link Valid For** → pilih berapa hari link aktif (default 30 hari)
6. Klik **Submit**
7. Akan muncul notifikasi berisi link review → **copy link-nya**
8. Kirim link tersebut ke customer via WhatsApp/email

> ⚠️ **Penting:** Setiap link hanya bisa dipakai **1x**. Setelah customer submit review, link otomatis mati. Kalau butuh review dari customer lain, generate link baru.

---

## 📊 Cara Pantau & Moderasi Review

1. Buka **https://bromoijenexpeditionjava.com/review-panel**
2. Di **Dashboard** langsung terlihat:
   - Total review yang masuk
   - Rata-rata rating (bintang)
   - Review yang belum dimoderasi
   - Jumlah link aktif
   - Grafik distribusi bintang
   - 5 review terbaru

3. Klik menu **"Reviews"** untuk lihat semua review
4. Di setiap review ada tombol aksi:
   - ✅ **Approve** → review tampil di website
   - ❌ **Reject** → review ditolak/disembunyikan
   - 👁️ **View** → lihat detail review
   - 🗑️ **Delete** → hapus review

5. Bisa juga **moderasi massal**: centang beberapa review → pilih "Approve Selected" atau "Reject Selected"

---

## 📎 Cara Cek Status Link Review

Di halaman **Review Links**, setiap link ada statusnya:

| Status | Keterangan |
|--------|------------|
| 🟢 **Active** | Link masih bisa dipakai customer |
| 🟡 **Used** | Customer sudah submit review lewat link ini |
| 🔴 **Expired** | Link sudah kedaluwarsa (belum dipakai) |

Kalau link sudah **Used**, bisa langsung lihat siapa yang review dan berapa bintangnya di kolom "Reviewed By" dan "Rating".

---

## 💡 Tips

- Biasakan kasih **label** waktu generate link, biar gampang tracking siapa yang sudah/belum review
- Kirim link review ke customer **setelah trip selesai** biar reviewnya fresh
- Cek dashboard secara berkala untuk approve review yang masuk biar langsung tampil di website

---

## 🔑 Akses

| Halaman | URL |
|---------|-----|
| Review Panel (Admin) | https://bromoijenexpeditionjava.com/review-panel |
| CMS Admin Panel | https://bromoijenexpeditionjava.com/admin |
