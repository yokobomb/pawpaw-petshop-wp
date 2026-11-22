# Panduan Tema WordPress PawPaw Petshop

## 1. Pasang tema
1. Butuh WordPress versi 6.5 atau lebih baru (hosting biasa seperti Hostinger, Niagahoster, Rumahweb sudah bisa).
2. Di dashboard: **Tampilan > Tema > Tambah Tema > Unggah Tema**, pilih `pawpaw-theme.zip`, lalu **Aktifkan**.
3. Saat pertama aktif, tema otomatis membuat:
   - 5 halaman: Beranda, Produk, Grooming, Tentang Kami, Kontak (Beranda jadi halaman depan)
   - menu navigasi
   - 5 kategori, 15 produk contoh, dan 4 layanan grooming
   Halaman atau produk yang sudah ada tidak akan ditimpa.
4. Supaya link rapi (`/produk/`, bukan `?page_id=`), buka **Pengaturan > Permalink**, pilih **Nama pos**, lalu simpan.

## 2. Yang perlu diisi pertama kali
Buka menu **Pengaturan Toko** (ikon toko di menu kiri):
- Nomor WhatsApp (boleh `0812...`, otomatis diubah ke `62812...`)
- Nomor yang ditampilkan, alamat, dan jam buka (format: `Senin – Jumat | 09.00 – 18.00`, satu baris satu jadwal)
- Peta: dari Google Maps klik **Bagikan > Sematkan peta**, salin kodenya, tempel di sini
- Pesan WhatsApp otomatis untuk tiap jenis tombol

Semua tombol WA, footer, dan halaman Kontak langsung ikut berubah.

## 3. Mengedit isi
| Mau ganti apa | Di mana |
|---|---|
| Produk (nama, harga, foto, deskripsi, label hewan, produk pilihan) | Menu **Produk** |
| Kategori (nama, emoji, warna, urutan, teks kecil) | **Produk > Kategori** |
| Layanan grooming | Menu **Layanan Grooming** |
| Teks dan foto halaman | **Halaman**, klik halamannya, edit langsung seperti mengetik di Word |
| FAQ dan "Kenapa PawPaw?" | Edit di salah satu halaman saja, otomatis berubah di semua halaman yang memakainya (pola tersinkron) |
| Menu atas & footer | **Tampilan > Editor > Navigasi** |
| Logo | **Tampilan > Editor**, klik header. Kalau Logo Situs belum diisi, tampil logo jejak kaki + nama situs |
| Nama toko | **Pengaturan > Umum > Judul Situs** |
| Warna & font | **Tampilan > Editor > Gaya** |

Urutan produk dan layanan diatur lewat kolom **Urutan** (angka kecil tampil duluan).

**Ganti foto hero kucing/anjing:** buka halaman Beranda, klik gambarnya, pilih **Ganti** di toolbar. Sama untuk foto di Tentang Kami.

## 4. Membuat tombol WhatsApp sendiri
Tambahkan blok **Tombol**, lalu isi link-nya dengan salah satu kode ini:
- `#wa-umum`: pesan umum
- `#wa-tanya`: tanya produk
- `#wa-grooming`: booking grooming

Gaya tombol: pilih tombol, di panel kanan ada pilihan **Biru** dan **Putih**.

## 5. Bagian siap pakai
Klik tombol **+** di editor, tab **Pola**, kategori **PawPaw Petshop**. Ada 16 bagian (hero, kategori, produk pilihan, layanan, FAQ, cara booking, kontak, peta, dll.) yang bisa ditambahkan ke halaman mana pun.

Di tab **Blok** ada kategori **PawPaw Petshop** berisi blok otomatis: Katalog Produk, Produk Pilihan, Kartu Kategori, Daftar Layanan, Alamat, Jam Buka, Nomor WA, Form WhatsApp, Peta, dan lainnya.

## 6. Yang tidak bisa diedit dari dashboard
- Animasi, bentuk lingkaran "blob", dan jejak kaki dekorasi (ada di `style.css`)
- Cara kerja filter katalog dan form WhatsApp (ada di `assets/js/front.js`)
- Belum ada keranjang/checkout. Kalau nanti butuh, perlu WooCommerce (proyek terpisah)
