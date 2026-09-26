# Sistem Informasi Pemilihan Mahasiswa

Sistem Informasi Pemilihan Mahasiswa adalah aplikasi berbasis web yang dirancang untuk mendukung proses pemilihan Ketua dan Wakil Ketua organisasi mahasiswa di lingkungan Fakultas Ilmu Komputer.

Sistem mencakup pengelolaan data mahasiswa dan pemilih, pendaftaran bakal calon, verifikasi administrasi, penetapan calon, proses voting, penghitungan suara, publikasi hasil, serta audit aktivitas sistem.

Aplikasi dirancang dengan prinsip utama:

- integritas pemilihan;
- kerahasiaan suara;
- pencegahan voting ganda;
- validasi data pemilih;
- validasi bakal calon;
- keamanan;
- transparansi proses;
- auditability;
- maintainability;
- usability.

---

# 1. Tujuan Sistem

Sistem ini dikembangkan untuk mendigitalisasi proses pemilihan mahasiswa mulai dari tahap persiapan hingga publikasi hasil.

Secara umum sistem mendukung alur:

```text
Persiapan Pemilihan
        ↓
Pendataan Mahasiswa
        ↓
Penetapan Daftar Pemilih
        ↓
Pendaftaran Bakal Calon
        ↓
Verifikasi Administrasi
        ↓
Penetapan Calon
        ↓
Publikasi Kandidat
        ↓
Pelaksanaan Voting
        ↓
Penghitungan Suara
        ↓
Publikasi Hasil
        ↓
Arsip dan Audit
```

---

# 2. Ruang Lingkup Sistem

Sistem memiliki beberapa modul utama:

1. Authentication
2. Master Data Mahasiswa
3. Daftar Pemilih
4. Pengecekan Status Pemilih
5. Election Management
6. Pendaftaran Bakal Calon
7. Verifikasi Bakal Calon
8. Penetapan Kandidat
9. Publikasi Kandidat
10. Voting
11. Monitoring Partisipasi
12. Penghitungan Suara
13. Publikasi Hasil
14. Audit Log
15. System Settings

---

# 3. Jenis Pengguna

## 3.1 Super Admin

Super Admin memiliki hak akses tertinggi terhadap aplikasi.

Fitur utama:

- mengelola administrator;
- mengelola role dan permission;
- mengelola konfigurasi sistem;
- mengelola periode pemilihan;
- melihat audit log;
- mengakses seluruh modul administratif sesuai kewenangan.

---

## 3.2 Admin / Panitia

Admin atau Panitia Pemilihan bertugas mengelola operasional pemilihan.

Fitur utama:

- mengelola data mahasiswa;
- import data mahasiswa;
- mengelola daftar pemilih;
- import data pemilih;
- mengelola periode pemilihan;
- mengelola persyaratan bakal calon;
- memverifikasi pendaftaran;
- meminta perbaikan dokumen;
- menolak atau memverifikasi pendaftaran;
- menetapkan calon;
- menetapkan nomor urut;
- memonitor tingkat partisipasi;
- mengelola hasil pemilihan;
- melihat audit aktivitas.

Admin tidak diperbolehkan melihat hubungan antara identitas pemilih dengan pilihan kandidat.

---

## 3.3 Mahasiswa

Mahasiswa dapat menggunakan sistem untuk:

- login menggunakan akun Google;
- mengecek status sebagai pemilih;
- melihat profil;
- melihat daftar kandidat;
- mendaftar sebagai bakal calon jika memenuhi syarat;
- mengunggah persyaratan;
- melihat status verifikasi;
- memperbaiki dokumen;
- melakukan voting jika terdaftar sebagai pemilih;
- melihat status bahwa suara telah berhasil diberikan.

---

# 4. Teknologi

Stack utama yang direkomendasikan:

## Backend

- Laravel
- PHP
- Laravel Socialite
- Laravel Form Request
- Laravel Policy / Gate
- Laravel Middleware

## Frontend

- Blade
- Tailwind CSS
- Alpine.js
- Vite

## Database

- MySQL
- MariaDB

## Authentication

- Google OAuth 2.0

## Development Tools

- Composer
- NPM
- Git
- GitHub

## Testing

- PHPUnit atau Pest sesuai konfigurasi project

---

# 5. Timezone

Sistem menggunakan timezone:

```text
Asia/Makassar
```

Timezone digunakan pada:

- periode pendaftaran;
- periode verifikasi;
- periode voting;
- pencatatan audit;
- publikasi hasil;
- aktivitas sistem lainnya.

---

# 6. Authentication

Mahasiswa melakukan login menggunakan Google OAuth.

Alur:

```text
Mahasiswa
   ↓
Login dengan Google
   ↓
Google Authentication
   ↓
OAuth Callback
   ↓
Ambil Email Google
   ↓
Cari Email pada Database Mahasiswa
   ↓
Ditemukan?
   ├── Ya → Login ke Sistem
   └── Tidak → Akses Ditolak
```

Keberhasilan login Google tidak otomatis menjadikan mahasiswa sebagai pemilih.

Status pemilih tetap ditentukan berdasarkan database election.

---

# 7. Data Mahasiswa

Master data mahasiswa disimpan pada tabel `students`.

Data utama:

- NIM
- Nama
- Email
- Program Studi
- Angkatan
- Semester
- Status Mahasiswa
- Google ID
- Nomor HP

Constraint utama:

```text
NIM   : UNIQUE
Email : UNIQUE
```

Status mahasiswa dapat berupa:

```text
active
inactive
graduated
suspended
```

---

# 8. Daftar Pemilih

Status mahasiswa sebagai pemilih disimpan terpisah dari master data mahasiswa.

Tabel:

```text
voters
```

Data utama:

- election;
- mahasiswa;
- status voter;
- waktu verifikasi;
- verifier;
- catatan.

Status voter:

```text
eligible
not_eligible
suspended
```

Constraint:

```text
UNIQUE(election_id, student_id)
```

Artinya satu mahasiswa hanya memiliki satu status voter dalam satu periode pemilihan.

---

# 9. Pengecekan Status Pemilih

Mahasiswa dapat mengecek apakah dirinya sudah terdaftar sebagai pemilih melalui:

```text
/cek-pemilih
```

Alur:

```text
Login Google
    ↓
Cari Mahasiswa
    ↓
Cari Voter pada Election
    ↓
Status Eligible?
    ├── Ya
    │    ↓
    │  TERDAFTAR SEBAGAI PEMILIH
    │
    └── Tidak
         ↓
       BELUM TERDAFTAR SEBAGAI PEMILIH
```

Informasi yang dapat ditampilkan:

- Nama
- NIM
- Program Studi
- Angkatan
- Semester
- Email
- Status Pemilih

---

# 10. Import Data

Admin dapat memasukkan data melalui:

- input manual;
- CSV;
- XLSX.

Sebelum import sistem melakukan:

1. validasi file;
2. validasi header;
3. validasi NIM;
4. validasi email;
5. pengecekan data kosong;
6. pengecekan duplikasi;
7. pengecekan data existing;
8. preview sebelum proses import.

Hasil import dapat dikelompokkan menjadi:

```text
Valid
Invalid
Duplicate
Existing
Updated
Skipped
Error
```

---

# 11. Election Management

Setiap periode pemilihan dikelola menggunakan entitas `Election`.

Data utama:

- nama election;
- deskripsi;
- periode pendaftaran;
- periode verifikasi;
- periode kampanye;
- periode voting;
- waktu publikasi hasil;
- status.

Status dapat berupa:

```text
draft
registration
verification
candidate_finalization
upcoming
voting
closed
published
archived
```

Validasi waktu dilakukan pada backend.

---

# 12. Pendaftaran Bakal Calon

Sistem menyediakan modul:

```text
Pendaftaran Bakal Calon Ketua dan Wakil Ketua
```

Alur:

```text
Login
  ↓
Validasi Persyaratan
  ↓
Buat Draft
  ↓
Isi Data Ketua
  ↓
Pilih Wakil
  ↓
Isi Visi dan Misi
  ↓
Isi Program Kerja
  ↓
Upload Dokumen
  ↓
Review
  ↓
Submit
  ↓
Verifikasi Panitia
```

---

# 13. Persyaratan Ketua dan Wakil

Ketua dan Wakil wajib memenuhi ketentuan:

- mahasiswa aktif;
- terdaftar pada database fakultas;
- minimal semester 3;
- maksimal semester 5;
- tidak berstatus suspended;
- tidak menjadi bagian dari pasangan lain dalam election yang sama;
- Ketua dan Wakil bukan mahasiswa yang sama;
- periode pendaftaran sedang aktif;
- memenuhi persyaratan administrasi lainnya.

Validasi semester:

```text
semester >= 3 AND semester <= 5
```

Semester yang valid:

```text
3
4
5
```

Semester yang tidak valid:

```text
1
2
6
7
8
dan seterusnya
```

Semester harus dibaca dari database, bukan dari input frontend.

---

# 14. Status Pendaftaran Bakal Calon

Status pendaftaran:

```text
draft
submitted
under_review
revision_required
resubmitted
verified
rejected
established
```

Alur status utama:

```text
draft
  ↓
submitted
  ↓
under_review
  ↓
┌────────────────────┐
│ revision_required  │
└─────────┬──────────┘
          ↓
     resubmitted
          ↓
     under_review
          ↓
       verified
          ↓
      established
```

Alternatif:

```text
under_review
      ↓
   rejected
```

---

# 15. Form Pendaftaran Bakal Calon

Form menggunakan konsep multi-step.

## Step 1

Data Ketua:

- Nama
- NIM
- Email
- Program Studi
- Angkatan
- Semester
- Nomor HP

## Step 2

Data Wakil:

- Nama
- NIM
- Email
- Program Studi
- Angkatan
- Semester
- Nomor HP

## Step 3

Visi

## Step 4

Misi

## Step 5

Program Kerja

## Step 6

Foto Pasangan

## Step 7

Dokumen Persyaratan

## Step 8

Review dan Submit

Pendaftaran dapat disimpan sebagai draft sebelum dikirim.

---

# 16. Dokumen Persyaratan

Dokumen disimpan melalui modul:

```text
candidate_registration_documents
```

Sistem mendukung:

- dokumen wajib;
- dokumen opsional;
- batas ukuran;
- jenis file;
- validasi MIME;
- status verifikasi;
- catatan panitia.

Status dokumen:

```text
pending
valid
invalid
revision_required
```

---

# 17. Verifikasi Bakal Calon

Panitia dapat:

- membuka detail pendaftaran;
- memeriksa data Ketua;
- memeriksa data Wakil;
- memeriksa dokumen;
- memberikan catatan;
- meminta revisi;
- memverifikasi;
- menolak;
- menetapkan calon.

Alur:

```text
Submitted
    ↓
Under Review
    ↓
Dokumen Lengkap?
    ├── Tidak
    │     ↓
    │ Revision Required
    │     ↓
    │ Resubmitted
    │
    └── Ya
          ↓
       Verified
```

---

# 18. Riwayat Verifikasi

Semua perubahan status disimpan.

Tabel:

```text
candidate_registration_histories
```

Informasi minimal:

- status sebelumnya;
- status baru;
- catatan;
- pengguna yang melakukan perubahan;
- waktu perubahan.

Riwayat tidak boleh dihapus secara sembarangan karena merupakan bagian dari audit proses pemilihan.

---

# 19. Penetapan Calon

Hanya bakal calon dengan status:

```text
verified
```

yang dapat ditetapkan menjadi calon resmi.

Saat penetapan:

```text
Verified Registration
        ↓
Database Transaction
        ↓
Create Candidate
        ↓
Registration = Established
        ↓
Audit Log
```

---

# 20. Nomor Urut

Setiap pasangan calon memiliki nomor urut.

Constraint:

```text
UNIQUE(election_id, candidate_number)
```

Nomor urut tidak boleh ganda dalam satu election.

---

# 21. Publikasi Kandidat

Halaman kandidat menampilkan:

- nomor urut;
- foto;
- Ketua;
- Wakil;
- visi;
- misi;
- program kerja.

Urutan tampilan:

```text
candidate_number ASC
```

Tampilan kandidat harus netral dan tidak memberikan ranking atau rekomendasi.

---

# 22. Voting

Mahasiswa hanya dapat voting jika:

1. berhasil login;
2. ditemukan pada database mahasiswa;
3. terdaftar sebagai voter;
4. status voter = `eligible`;
5. periode voting sedang aktif;
6. belum pernah memilih;
7. kandidat yang dipilih merupakan kandidat aktif pada election tersebut.

---

# 23. Alur Voting

```text
Login Google
      ↓
Validasi Mahasiswa
      ↓
Validasi Voter
      ↓
Election Aktif?
      ↓
Sudah Memilih?
      ├── Ya → Voting Ditolak
      │
      └── Tidak
            ↓
        Lihat Kandidat
            ↓
        Pilih Kandidat
            ↓
         Konfirmasi
            ↓
        Submit Vote
            ↓
    Database Transaction
        ┌──────┴──────┐
        ↓             ↓
 Participation      Ballot
        ↓             ↓
       COMMIT TRANSACTION
            ↓
          Success
```

---

# 24. Kerahasiaan Suara

Sistem harus memisahkan:

```text
siapa yang sudah memilih
```

dengan:

```text
siapa yang dipilih
```

Karena itu digunakan dua tabel terpisah.

## Voting Participation

```text
voting_participations
```

Digunakan untuk mencatat bahwa seorang voter sudah menggunakan hak pilih.

Contoh:

- election_id
- voter_id
- voted_at

Tidak menyimpan kandidat pilihan.

---

## Anonymous Ballot

```text
ballots
```

Digunakan untuk menyimpan suara.

Contoh:

- election_id
- candidate_id
- ballot_uuid
- integrity_hash
- submitted_at

Ballot tidak boleh memiliki:

```text
voter_id
student_id
email
nim
google_id
```

Dengan demikian administrator tidak memiliki hubungan langsung:

```text
Mahasiswa A → memilih Kandidat B
```

---

# 25. Pencegahan Voting Ganda

Gunakan beberapa lapisan perlindungan:

- server-side validation;
- database transaction;
- unique constraint;
- row lock bila diperlukan;
- CSRF;
- idempotency mechanism jika diperlukan.

Constraint:

```text
UNIQUE(election_id, voter_id)
```

Sistem harus aman terhadap:

- double click;
- refresh;
- duplicate POST;
- multiple tab;
- concurrent request;
- replay request.

---

# 26. Hasil Pemilihan

Perhitungan hasil harus berasal dari:

```text
ballots
```

Bukan dari tabel partisipasi.

Perhitungan:

```text
Jumlah Suara Kandidat
Total Suara
Total Pemilih
Sudah Memilih
Belum Memilih
Persentase Kandidat
Tingkat Partisipasi
```

Formula persentase kandidat:

```text
suara_kandidat / total_suara_sah * 100
```

Formula partisipasi:

```text
jumlah_pemilih_yang_memilih / total_pemilih * 100
```

---

# 27. Dashboard Admin

Dashboard minimal menampilkan:

- Total Mahasiswa
- Total Pemilih
- Eligible Voters
- Sudah Memilih
- Belum Memilih
- Persentase Partisipasi
- Jumlah Pendaftaran Bakal Calon
- Submitted
- Under Review
- Revision Required
- Verified
- Kandidat Resmi

Monitoring tidak boleh membocorkan pilihan individu.

---

# 28. Audit Log

Aktivitas administratif penting harus dicatat.

Contoh:

- login admin;
- import mahasiswa;
- perubahan data mahasiswa;
- perubahan voter;
- pembuatan election;
- perubahan jadwal;
- submit registration;
- verifikasi registration;
- revision request;
- resubmit;
- reject;
- verification;
- penetapan candidate;
- nomor urut;
- publikasi hasil;
- perubahan konfigurasi.

Audit log tidak boleh menyimpan hubungan pemilih dengan kandidat pilihan.

---

# 29. Keamanan Sistem

Sistem harus memperhatikan risiko:

- SQL Injection;
- XSS;
- CSRF;
- IDOR;
- Mass Assignment;
- Privilege Escalation;
- Session Hijacking;
- Session Fixation;
- Brute Force;
- Replay Request;
- Race Condition;
- Duplicate Vote;
- File Upload Attack;
- MIME Spoofing;
- Unauthorized Access.

Gunakan:

- middleware;
- policy;
- gate;
- Laravel Form Request;
- server-side validation;
- database constraint;
- transaction;
- authorization.

---

# 30. Struktur Database Utama

Minimal terdapat tabel:

```text
users
students
elections
voters
candidate_requirements
candidate_registrations
candidate_registration_documents
candidate_registration_histories
candidate_programs
candidates
voting_participations
ballots
audit_logs
system_settings
```

---

# 31. Struktur Folder Dokumentasi

Disarankan:

```text
docs/
├── architecture.md
├── erd.md
├── authentication-flow.md
├── candidate-registration-flow.md
├── voting-flow.md
└── security.md
```

---

# 32. Environment

Konfigurasi environment disimpan pada:

```text
.env
```

Contoh:

```env
APP_NAME="Sistem Pemilihan Mahasiswa"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_TIMEZONE=Asia/Makassar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pemilihan_mahasiswa
DB_USERNAME=
DB_PASSWORD=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

File `.env` **tidak boleh di-commit ke GitHub**.

Gunakan:

```text
.env.example
```

sebagai template.

---

# 33. Instalasi Development

Clone repository:

```bash
git clone <repository-url>
```

Masuk ke directory:

```bash
cd sistem-pemilihan-mahasiswa
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Buat environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Konfigurasi database pada `.env`.

Jalankan migration:

```bash
php artisan migrate
```

Jika tersedia development seeder:

```bash
php artisan db:seed
```

atau:

```bash
php artisan migrate --seed
```

Jalankan frontend:

```bash
npm run dev
```

Jalankan aplikasi:

```bash
php artisan serve
```

---

# 34. Menjalankan Test

Jalankan:

```bash
php artisan test
```

Jika project menggunakan tool tambahan, jalankan sesuai konfigurasi:

```bash
./vendor/bin/pint
```

dan static analysis jika tersedia.

---

# 35. Strategi Branch Git

Gunakan struktur:

```text
main
│
└── develop
    │
    ├── feature/google-auth
    ├── feature/voter-management
    ├── feature/candidate-registration
    ├── feature/candidate-verification
    ├── feature/voting
    └── feature/dashboard-results
```

## Main

Digunakan untuk versi stabil.

## Develop

Digunakan sebagai branch integrasi development.

## Feature

Digunakan untuk pengembangan masing-masing fitur.

---

# 36. Alur Pengembangan Tim

Setiap developer mengikuti alur:

```text
develop
   ↓
Pull Latest Changes
   ↓
Create Feature Branch
   ↓
Develop Feature
   ↓
Run Test
   ↓
Commit
   ↓
Push
   ↓
Create Pull Request
   ↓
Code Review
   ↓
Merge to Develop
```

Setelah versi pada `develop` stabil:

```text
develop
   ↓
Pull Request
   ↓
Review
   ↓
main
```

---

# 37. Membuat Feature Branch

Ambil versi terbaru:

```bash
git checkout develop
git pull origin develop
```

Buat branch baru:

```bash
git checkout -b feature/nama-fitur
```

Contoh:

```bash
git checkout -b feature/candidate-registration
```

---

# 38. Commit

Gunakan pesan commit yang jelas.

Contoh:

```text
feat: implement Google authentication
feat: add voter verification
feat: implement candidate registration
feat: add candidate verification workflow
feat: implement anonymous voting
fix: prevent duplicate voting
fix: validate candidate semester eligibility
test: add candidate eligibility tests
docs: update voting architecture
```

---

# 39. Push Branch

```bash
git push -u origin feature/candidate-registration
```

Kemudian buat Pull Request menuju:

```text
develop
```

Jangan langsung merge feature ke `main`.

---

# 40. Pembagian Modul Tim

Contoh pembagian tugas:

| Developer | Modul | Branch |
|---|---|---|
| Developer 1 | Authentication | `feature/google-auth` |
| Developer 2 | Mahasiswa & Pemilih | `feature/voter-management` |
| Developer 3 | Pendaftaran Bakal Calon | `feature/candidate-registration` |
| Developer 4 | Verifikasi & Kandidat | `feature/candidate-verification` |
| Developer 5 | Voting | `feature/voting` |
| Developer 6 | Dashboard & Hasil | `feature/dashboard-results` |

Pembagian dapat disesuaikan berdasarkan jumlah anggota tim.

---

# 41. GitHub Issues

Gunakan GitHub Issues untuk mencatat pekerjaan.

Contoh:

```text
#1 Setup Project
#2 Google Authentication
#3 Student Management
#4 Voter Management
#5 Candidate Registration
#6 Candidate Verification
#7 Candidate Management
#8 Voting Module
#9 Result Calculation
#10 Audit Log
#11 Security Review
#12 Testing
```

Branch dapat dikaitkan dengan issue:

```text
feature/5-candidate-registration
```

---

# 42. Pull Request

Setiap Pull Request sebaiknya menjelaskan:

## Deskripsi

Apa yang dibuat.

## Perubahan

File/modul utama yang berubah.

## Database

Migration yang ditambahkan.

## Testing

Test yang telah dilakukan.

## Security

Dampak keamanan.

## Screenshot

Jika terdapat perubahan UI.

Contoh:

```text
Title:
feat: implement candidate registration

Description:
Menambahkan modul pendaftaran bakal calon Ketua dan Wakil Ketua.

Changes:
- multi-step registration
- semester validation
- document upload
- draft registration
- submit registration

Validation:
Ketua dan Wakil hanya semester 3–5.

Tests:
- semester 2 rejected
- semester 3 accepted
- semester 4 accepted
- semester 5 accepted
- semester 6 rejected
```

---

# 43. Code Review

Sebelum merge, reviewer memeriksa:

- coding convention;
- authorization;
- validation;
- database integrity;
- security;
- test;
- duplicate logic;
- UI consistency;
- migration safety.

Untuk fitur kritis seperti voting, review wajib memeriksa:

- duplicate vote;
- transaction;
- race condition;
- privacy;
- authorization.

---

# 44. Definition of Done

Sebuah fitur dianggap selesai jika:

- requirement terpenuhi;
- code berjalan;
- validation tersedia;
- authorization tersedia;
- automated test tersedia jika relevan;
- test berhasil;
- tidak membocorkan data sensitif;
- documentation diperbarui;
- Pull Request sudah direview;
- sudah merge ke `develop`.

---

# 45. Tahapan Pengembangan

## Phase 1 — Repository & Project Setup

- Laravel setup
- database
- environment
- Git
- GitHub
- base UI

## Phase 2 — Authentication

- Google OAuth
- user session
- role
- authorization

## Phase 3 — Master Data

- students
- import
- voters
- election

## Phase 4 — Pendaftaran Bakal Calon

- eligibility
- semester 3–5
- draft
- multi-step form
- upload
- submit

## Phase 5 — Verifikasi

- admin review
- revision
- resubmit
- reject
- verify

## Phase 6 — Kandidat

- establishment
- nomor urut
- profil kandidat

## Phase 7 — Voting

- eligibility
- transaction
- participation
- anonymous ballot
- duplicate protection

## Phase 8 — Results

- counting
- percentage
- turnout
- publication

## Phase 9 — Audit & Security

- audit log
- authorization review
- privacy review
- vulnerability review

## Phase 10 — Testing & Deployment

- feature tests
- integration tests
- final review
- deployment preparation

---

# 46. Prioritas Pengembangan

Urutan prioritas:

```text
1. Integritas Pemilihan
2. Kerahasiaan Suara
3. Pencegahan Voting Ganda
4. Validitas Pemilih
5. Validitas Bakal Calon
6. Authorization
7. Auditability
8. Security
9. Correctness
10. Maintainability
11. Usability
12. Visual Design
```

Keamanan dan integritas tidak boleh dikorbankan hanya untuk mempercepat implementasi UI.

---

# 47. Roadmap Ringkas

```text
Project Setup
    ↓
Authentication
    ↓
Data Mahasiswa
    ↓
Daftar Pemilih
    ↓
Election
    ↓
Pendaftaran Bakal Calon
    ↓
Verifikasi
    ↓
Penetapan Kandidat
    ↓
Voting
    ↓
Result
    ↓
Audit
    ↓
Security Review
    ↓
Testing
    ↓
Deployment
```

---

# 48. Aturan Penting

Developer tidak diperbolehkan:

- commit `.env`;
- commit credential;
- menyimpan Google Client Secret dalam source code;
- membuat ballot memiliki `voter_id`;
- menyimpan pilihan kandidat pada tabel voter;
- membuat candidate dari pendaftaran yang belum verified;
- mempercayai semester dari frontend;
- menghapus audit history;
- bypass authorization;
- langsung push fitur ke `main`;
- merge Pull Request tanpa review untuk modul kritis.

---

# 49. Status Project

Project dikembangkan secara bertahap.

Checklist utama:

```text
[ ] Project Setup
[ ] Google Authentication
[ ] Student Management
[ ] Student Import
[ ] Voter Management
[ ] Voter Verification
[ ] Election Management
[ ] Candidate Requirements
[ ] Candidate Registration
[ ] Candidate Document Upload
[ ] Candidate Verification
[ ] Candidate Revision
[ ] Candidate Establishment
[ ] Candidate Number
[ ] Candidate Public Profile
[ ] Voting
[ ] Duplicate Vote Protection
[ ] Anonymous Ballot
[ ] Voting Monitor
[ ] Result Calculation
[ ] Result Publication
[ ] Audit Log
[ ] Security Review
[ ] Automated Testing
[ ] Documentation
[ ] Deployment
```

---

# 50. Kesimpulan

Sistem Informasi Pemilihan Mahasiswa dikembangkan sebagai platform yang menangani proses pemilihan mulai dari validasi mahasiswa sampai publikasi hasil.

Prinsip utama sistem adalah:

```text
Pemilih Valid
+
Bakal Calon Valid
+
Voting Aman
+
Suara Rahasia
+
Audit Terjaga
+
Proses Terstruktur
```

Dengan penggunaan GitHub, feature branch, Pull Request, code review, testing, dan dokumentasi yang konsisten, project dapat dikembangkan secara kolaboratif oleh beberapa anggota tim tanpa mengorbankan kualitas maupun keamanan sistem.
