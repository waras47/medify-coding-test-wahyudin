# Handoff Documentation — Medify Coding Test

Dokumen ini buat siapapun yang lanjutin kerjaan di project ini. Isinya: cara setup, arsitektur singkat, bug yang udah ketemu, dan kerjaan yang belum selesai.

## 1. Stack

- Laravel 9, PHP ^8.2
- MySQL (koneksi default `mysql` + koneksi kedua `hospital`, lihat `config/database.php`)
- Auth: `Auth::routes()` bawaan Laravel (login/register/reset password)
- Frontend: Blade + Bootstrap + jQuery DataTables (CDN, lihat `resources/views/master_items/index/js.blade.php`)

## 2. Setup dari Nol

1. `composer install`
   - Kalau muncul error "package X is not present in the lock file" → `composer.json` dan `composer.lock` ga sinkron. Fix: `composer update <package> --with-all-dependencies`.
2. Copy `.env.example` → `.env`, isi kredensial DB.
   - **Perhatian**: kalau password DB ada karakter `#`, WAJIB dikasih tanda kutip. Contoh salah:
     ```
     DB_PASSWORD=medify@#$
     ```
     dotenv parser anggap `#` sebagai awal komentar kalau value ga dikutip → password ke-truncate jadi `medify@` doang, sisanya `#$` ilang. Efeknya: `php artisan migrate` gagal "Access denied" padahal password di MySQL sama persis. Fix:
     ```
     DB_PASSWORD="medify@#$"
     ```
   - `APP_KEY` udah keisi di repo ini, ga perlu `php artisan key:generate` lagi kecuali kosong.
3. Bikin DB + user MySQL sesuai `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), grant privileges.
4. `php artisan config:clear` (kalau abis ubah `.env` dan config sempat ke-cache)
5. `php artisan migrate`
6. `php artisan serve`, buka `/register` buat bikin akun, lanjut login.

Catatan: `php artisan recreate-db` (custom command di `app/Console/Commands/RecreateDatabase.php`) itu drop+create DB `medify_hospital` di koneksi `hospital` — **beda** dari DB utama di `.env`. Jangan dipakai buat setup DB utama, dia bukan buat migrasi tabel `master_items`/`users`.

## 3. Struktur Fitur

### Master Items (`app/Http/Controllers/MasterItemsController.php`)
Modul utama yang udah jalan. CRUD barang dengan field: `kode`, `nama`, `harga_beli`, `laba` (persen), `supplier`, `jenis`. Model pakai `SoftDeletes`.

Routes (`routes/web.php`):
- `GET /master-items` — index (halaman list + filter)
- `GET /master-items/search` — AJAX JSON, dipanggil DataTables
- `GET|POST /master-items/form/{method}/{id?}` — form tambah/edit (`method` = `new`|`edit`)
- `GET /master-items/view/{kode}` — detail
- `GET /master-items/delete/{id}` — soft delete
- `GET /master-items/update-random-data` — randomize data row yang **sudah ada** (bukan buat insert data baru)

Views: `resources/views/master_items/{index,form,single}`.

### Pasien (`app/Models/Pasien.php`)
Model doang, kosong. **Belum ada migration, controller, route, view.** Ini kandidat kuat buat task live-coding (lihat section 5).

## 4. Bug yang Sudah Ketemu (belum diperbaiki, sengaja dibiarin buat live-code)

| # | Lokasi | Masalah |
|---|--------|---------|
| 1 | `resources/views/master_items/form/form.blade.php:34` | Typo `<optio ...>Blublu</option>` — harusnya `<option>`. Opsi supplier "Blublu" ga kebentuk bener di dropdown. |
| 2 | `app/Http/Controllers/MasterItemsController.php:26` | `hargamax` cuma diproses di dalam `if (!empty($hargamin))`. Kalau user isi hargamax doang (hargamin kosong), filter max diabaikan total. Kalau isi hargamin doang (hargamax kosong string), query jadi `harga_beli <= ''` → MySQL cast ke 0 → hasil kosong. Fix: pisah jadi dua `if` independen. |
| 3 | `app/Http/Controllers/MasterItemsController.php:59-62` | Generate `kode` barang baru pakai `MasterItem::count('id') + 1`. Rawan collision: (a) abis ada row ke-soft-delete, `count()` Eloquent default exclude row itu, jadi angka hasil hitung lebih kecil dari seharusnya, bisa nabrak kode yang udah dipakai; (b) race condition — dua submit form bersamaan bisa baca `count()` sama, hasil kode dobel. `sleep(3)` yang ada di situ malah memperbesar window race, bukan nyegah. Fix yang lebih aman: pakai `id` auto-increment (dijamin unik oleh DB) sebagai basis kode, bukan `count()`. |
| 4 | Semua field form | Ga ada validasi di backend (`Request` rules / Form Request). Cuma andalin `required` di HTML, gampang dilewatin lewat DevTools / direct POST. |

## 5. Kerjaan yang Kemungkinan Diminta (belum dikerjain, sengaja)

- **Modul Pasien** — bikin migration, controller, routes, views buat CRUD Pasien, pola sama kayak Master Items. Draft migration + controller (belum ditulis ke file, tinggal sesuaikan) ada di riwayat chat sesi ini — field yang dipakai: `no_rm`, `nama`, `nik`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_telp`.
- **Export PDF** — `barryvdh/laravel-dompdf` ada di `composer.json` (`^3.1`) tapi belum dipakai di kode manapun. Sinyal kuat bakal diminta fitur export/print PDF (list Master Items atau Pasien, atau detail single item).
- **Testing** — `tests/Feature/ExampleTest.php` dan `tests/Unit/ExampleTest.php` masih stub bawaan Laravel, belum ada test buat `MasterItemsController`.

## 6. Yang Sengaja Ga Diubah

Semua bug di section 4 dan kerjaan di section 5 sengaja **belum diperbaiki/dikerjain** di kode — disiapkan buat sesi live-coding, biar bisa dikerjain langsung pas tes. Dokumen ini cuma peta, bukan fix.
