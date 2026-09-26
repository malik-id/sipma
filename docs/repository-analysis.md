# Analisis repository — 26 September 2026

## Existing
`E:\sipma` kosong pada awal pengerjaan. Tidak ada source code, Git, AGENTS.md, dependency, migration, route, autentikasi, test, atau dokumentasi existing.
Runtime tersedia: PHP XAMPP 8.2.12, Composer 2.10.2, Node 24.18.0, NPM 11.16.0; MariaDB tersedia melalui XAMPP.

## Missing
Seluruh modul pada spesifikasi: autentikasi, master mahasiswa/pemilih, election, import, pendaftaran, persyaratan, verifikasi, calon resmi, voting, hasil, audit, pengaturan, dan UI.

## Problem
PHP bawaan tidak memenuhi Laravel 13 (minimum PHP 8.3). Proyek menggunakan PHP 8.4 lokal dalam `.runtime`, tanpa mengubah XAMPP. Runtime dan secret tidak masuk version control.

## Risk
Race condition suara/keanggotaan pasangan, IDOR, kebocoran pilihan melalui log/waktu/urutan record, perubahan aturan setelah pemungutan dimulai, unggahan berbahaya, serta pemilih otomatis dari login.

## Recommendation
Monolit Laravel 13, Blade, Tailwind/Vite, Socialite, MySQL/MariaDB. Validasi server, transaksi, constraint unik dan composite foreign key, dokumen privat, policy, permission per modul, state machine eksplisit. Test cepat memakai SQLite; uji transaksi dan concurrency memakai MariaDB/MySQL terpisah.

## Inventory awal
| Modul | Status awal | Implementasi yang direncanakan |
|---|---|---|
| Foundation | Belum ada | Laravel, dependency, konfigurasi Makassar |
| Auth | Belum ada | Google OAuth stateful, admin password, role/permission |
| Master data | Belum ada | CRUD, CSV/XLSX preview, import atomik |
| Pencalonan | Belum ada | Draft, form delapan langkah, persyaratan, dokumen |
| Verifikasi | Belum ada | Review, revisi, resubmit, reject, verify, establish |
| Voting | Belum ada | Lock, transaksi, participation terpisah dari ballot |
| Hasil | Belum ada | Agregasi dan pembatasan publikasi |
| Operasional | Belum ada | Audit, monitoring, settings, dokumentasi, tests |

## File plan
Baru: `app/Actions`, `Enums`, `Models`, `Policies`, `Http/Requests`, `Http/Middleware`, `Http/Controllers`, migration/factory/seeder, routes, Blade, CSS/JS, tests, README, dokumen arsitektur/ERD/deployment. File skeleton Laravel disesuaikan setelah scaffold. Tidak ada functionality existing dihapus.

## Roadmap
1. Analisis dan arsitektur.
2. Fondasi database, model, enum, factory.
3. Google/admin authentication, permission, policy.
4. Cek pemilih, dashboard, profil.
5. Master data, election, import dengan preview.
6. Draft dan submit bakal calon, dokumen privat.
7. Verifikasi, revisi, penetapan dan nomor urut.
8. Voting transaksional dan privacy.
9. Hasil, audit, monitoring, settings.
10. UI responsive dan aksesibilitas.
11. Test, security review, dokumentasi dan kesiapan E2E.

Referensi versi: https://laravel.com/docs/13.x/releases
