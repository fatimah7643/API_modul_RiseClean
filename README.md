# RiseClean API Documentation

RiseClean adalah aplikasi manajemen gamifikasi edukasi lingkungan yang dibangun dengan PHP dan MySQL. Sistem ini menyediakan fungsi CRUD (Create, Read, Update, Delete) untuk mengelola berbagai aspek dari platform edukasi lingkungan seperti pengguna, modul pendidikan, tantangan, kuis, dan sistem hadiah.

## Deskripsi
Aplikasi ini menyediakan berbagai endpoint untuk mengelola data terkait platform RiseClean:

- **CREATE**: Menambahkan data baru ke sistem
- **READ**: Membaca/menampilkan data dari sistem
- **UPDATE**: Memperbarui data yang sudah ada
- **DELETE**: Menghapus data dari sistem

Semua endpoint mengembalikan respons dalam format JSON.

## Struktur Tabel

Berikut adalah struktur tabel-tabel dalam database RiseClean:

### 1. Users
Tabel users menyimpan informasi pengguna platform.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | INT | Primary Key, Auto Increment |
| username | VARCHAR | Username unik pengguna |
| email | VARCHAR | Email unik pengguna |
| password | VARCHAR | Password hash pengguna |
| first_name | VARCHAR | Nama depan pengguna |
| last_name | VARCHAR | Nama belakang pengguna |
| phone | VARCHAR | Nomor telepon pengguna |
| role_id | INT | Foreign Key ke tabel roles |
| total_xp | INT | Total poin pengalaman pengguna |
| total_points | INT | Total poin yang dimiliki pengguna |
| current_level | INT | Level saat ini pengguna |
| is_active | TINYINT | Status aktif pengguna (1=aktif, 0=tidak aktif) |

### 2. Roles
Tabel roles menyimpan informasi peran pengguna dalam sistem.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | INT | Primary Key, Auto Increment |
| role_name | VARCHAR | Nama peran (admin, user, moderator) |
| description | TEXT | Deskripsi peran |

### 3. EducationModules
Tabel education_modules menyimpan informasi modul-modul pendidikan.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| module_id | INT | Primary Key, Auto Increment |
| title | VARCHAR | Judul modul pendidikan |
| content | TEXT | Isi konten modul |
| xp_reward | INT | Poin pengalaman yang diberikan |
| point_reward | INT | Poin yang diberikan |
| difficulty | VARCHAR | Tingkat kesulitan (easy, medium, hard) |
| category | VARCHAR | Kategori modul |
| duration_minutes | INT | Durasi estimasi dalam menit |
| is_active | TINYINT | Status aktif modul (1=aktif, 0=tidak aktif) |

### 4. QuizQuestions
Tabel quiz_questions menyimpan pertanyaan-pertanyaan kuis untuk modul pendidikan.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| question_id | INT | Primary Key, Auto Increment |
| module_id | INT | Foreign Key ke tabel education_modules |
| question_text | TEXT | Teks pertanyaan kuis |
| question_type | VARCHAR | Jenis pertanyaan (multiple_choice, true_false) |
| xp_reward | INT | Poin pengalaman yang diberikan |
| point_reward | INT | Poin yang diberikan |
| difficulty | VARCHAR | Tingkat kesulitan (easy, medium, hard) |
| is_active | TINYINT | Status aktif pertanyaan (1=aktif, 0=tidak aktif) |

### 5. QuizChoices
Tabel quiz_choices menyimpan pilihan jawaban untuk pertanyaan kuis.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| choice_id | INT | Primary Key, Auto Increment |
| question_id | INT | Foreign Key ke tabel quiz_questions |
| choice_text | TEXT | Teks pilihan jawaban |
| is_correct | TINYINT | Apakah ini jawaban benar (1=benar, 0=salah) |
| choice_order | INT | Urutan tampilan pilihan jawaban |

### 6. Challenges
Tabel challenges menyimpan informasi tantangan harian, mingguan, atau spesial.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| challenge_id | INT | Primary Key, Auto Increment |
| title | VARCHAR | Judul tantangan |
| description | TEXT | Deskripsi tantangan |
| xp_reward | INT | Poin pengalaman yang diberikan |
| point_reward | INT | Poin yang diberikan |
| difficulty | VARCHAR | Tingkat kesulitan (easy, medium, hard) |
| challenge_type | VARCHAR | Jenis tantangan (daily, special, weekly) |
| start_date | DATE | Tanggal mulai tantangan |
| end_date | DATE | Tanggal akhir tantangan |
| is_active | TINYINT | Status aktif tantangan (1=aktif, 0=tidak aktif) |

### 7. Levels
Tabel levels menyimpan informasi tingkatan dalam sistem.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| level_id | INT | Primary Key, Auto Increment |
| level_name | VARCHAR | Nama level (Beginner, Intermediate, Expert) |
| min_xp | INT | Minimum XP yang dibutuhkan untuk mencapai level ini |

### 8. Rewards
Tabel rewards menyimpan informasi hadiah yang dapat ditukarkan pengguna.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| reward_id | INT | Primary Key, Auto Increment |
| reward_name | VARCHAR | Nama hadiah |
| point_cost | INT | Biaya penukaran dalam poin |
| description | TEXT | Deskripsi hadiah |
| image | VARCHAR | URL gambar hadiah |
| stock | INT | Stok tersedia |
| is_active | TINYINT | Status aktif hadiah (1=aktif, 0=tidak aktif) |

### 9. UserProgress
Tabel user_progress menyimpan kemajuan pengguna dalam menyelesaikan modul atau tantangan.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| progress_id | INT | Primary Key, Auto Increment |
| user_id | INT | Foreign Key ke tabel users |
| item_id | INT | ID item (modul atau tantangan) |
| item_type | VARCHAR | Jenis item ('module' atau 'challenge') |
| completed_at | DATETIME | Waktu penyelesaian |
| verified_at | DATETIME | Waktu verifikasi (jika diperlukan) |
| is_verified | TINYINT | Status verifikasi (1=terverifikasi, 0=belum) |
| submission_text | TEXT | Teks pengiriman (jika ada) |
| submission_image | VARCHAR | Gambar pengiriman (jika ada) |

### 10. UserQuizAnswers
Tabel user_quiz_answers menyimpan jawaban pengguna terhadap pertanyaan kuis.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| answer_id | INT | Primary Key, Auto Increment |
| user_id | INT | Foreign Key ke tabel users |
| module_id | INT | Foreign Key ke tabel education_modules |
| question_id | INT | Foreign Key ke tabel quiz_questions |
| selected_choice_id | INT | Foreign Key ke tabel quiz_choices (jika pilihan ganda) |
| answer_text | TEXT | Jawaban teks (untuk pertanyaan esai) |
| is_correct | TINYINT | Apakah jawaban benar (1=benar, 0=salah) |
| points_earned | INT | Poin yang diperoleh |
| xp_earned | INT | XP yang diperoleh |

### 11. UserRewards
Tabel user_rewards menyimpan informasi hadiah yang telah ditukarkan oleh pengguna.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| user_reward_id | INT | Primary Key, Auto Increment |
| user_id | INT | Foreign Key ke tabel users |
| reward_id | INT | Foreign Key ke tabel rewards |
| quantity | INT | Jumlah hadiah yang ditukarkan |

### 12. ActivityLogs
Tabel activity_logs menyimpan log aktivitas pengguna dalam sistem.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | INT | Primary Key, Auto Increment |
| user_id | INT | Foreign Key ke tabel users |
| activity_type | VARCHAR | Jenis aktivitas (login, complete_module, etc.) |
| description | TEXT | Deskripsi aktivitas |
| ip_address | VARCHAR | Alamat IP pengguna |
| created_at | TIMESTAMP | Waktu aktivitas terjadi |

### 13. LoginAttempts
Tabel login_attempts menyimpan informasi percobaan login.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| attempt_id | INT | Primary Key, Auto Increment |
| ip_address | VARCHAR | Alamat IP dari percobaan login |
| attempt_time | DATETIME | Waktu percobaan login |
| is_success | TINYINT | Apakah login berhasil (1=berhasil, 0=gagal) |

### 14. FailedLoginAttempts
Tabel failed_login_attempts menyimpan informasi percobaan login yang gagal.

| Kolom | Tipe Data | Deskripsi |
|-------|-----------|-----------|
| id | INT | Primary Key, Auto Increment |
| username | VARCHAR | Username yang digunakan dalam percobaan |
| ip_address | VARCHAR | Alamat IP dari percobaan login |
| attempts | INT | Jumlah percobaan gagal |
| last_attempt | DATETIME | Waktu percobaan terakhir |
| blocked_until | DATETIME | Waktu hingga pengguna diblokir |

## Endpoint

### 1. Users

#### CREATE - Menambahkan Data Pengguna Baru
**URL**: `/API_Modules/Users/create.php`

**Metode**: POST

**Parameter**:
- username (string) - Username unik pengguna
- email (string) - Email unik pengguna
- password (string) - Password pengguna
- first_name (string) - Nama depan pengguna
- last_name (string) - Nama belakang pengguna
- phone (string) - Nomor telepon pengguna
- role_id (integer) - ID peran pengguna (default: 4=user)
- total_xp (integer) - Total poin pengalaman (default: 0)
- total_points (integer) - Total poin (default: 0)
- current_level (integer) - Level saat ini (default: 1)
- is_active (integer) - Status aktif (default: 1)

**Contoh Request**:
```bash
curl -X POST \
  -d "username=johndoe" \
  -d "email=john@example.com" \
  -d "password=securepassword" \
  -d "first_name=John" \
  -d "last_name=Doe" \
  -d "phone=081234567890" \
  http://localhost/API_modul_RiseClean/API_Modules/Users/create.php
```

**Contoh Respons Sukses**:
```json
{
  "status": "success",
  "message": "User berhasil didaftarkan",
  "data": {
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "first_name": "John",
    "last_name": "Doe",
    "total_xp": 0,
    "total_points": 0,
    "current_level": 1
  }
}
```

**Contoh Respons Error**:
```json
{
  "status": "error",
  "message": "Error message here"
}
```

#### READ - Membaca Data Pengguna
**URL**: `/API_Modules/Users/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data pengguna berdasarkan ID

Jika tidak ada parameter, maka akan mengembalikan semua data pengguna.

**Contoh Request (Semua Data)**:
```bash
curl http://localhost/API_modul_RiseClean/API_Modules/Users/read.php
```

**Contoh Request (Spesifik ID)**:
```bash
curl http://localhost/API_modul_RiseClean/API_Modules/Users/read.php?id=1
```

**Contoh Respons Sukses (Semua Data)**:
```json
{
  "status": "success",
  "message": "Data ditemukan",
  "data": [
    {
      "id": 1,
      "username": "johndoe",
      "email": "john@example.com",
      "first_name": "John",
      "last_name": "Doe",
      "total_xp": 150,
      "total_points": 100,
      "current_level": 2,
      "is_active": 1
    }
  ]
}
```

#### UPDATE - Memperbarui Data Pengguna
**URL**: `/API_Modules/Users/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID pengguna yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

**Contoh Request**:
```bash
curl -X POST \
  -d "id=1" \
  -d "first_name=John Updated" \
  -d "total_xp=200" \
  http://localhost/API_modul_RiseClean/API_Modules/Users/update.php
```

#### DELETE - Menghapus Data Pengguna
**URL**: `/API_Modules/Users/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID pengguna yang akan dihapus

**Contoh Request**:
```bash
curl -X POST \
  -d "id=1" \
  http://localhost/API_modul_RiseClean/API_Modules/Users/delete.php
```

### 2. Roles

#### CREATE - Menambahkan Peran Baru
**URL**: `/API_Modules/Roles/create.php`

**Metode**: POST

**Parameter**:
- role_name (string) - Nama peran
- description (string) - Deskripsi peran

**Contoh Request**:
```bash
curl -X POST \
  -d "role_name=admin" \
  -d "description=Administrator role" \
  http://localhost/API_modul_RiseClean/API_Modules/Roles/create.php
```

#### READ - Membaca Data Peran
**URL**: `/API_Modules/Roles/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data peran berdasarkan ID

#### UPDATE - Memperbarui Data Peran
**URL**: `/API_Modules/Roles/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID peran yang akan diperbarui
- role_name (string) - Nama peran baru (opsional)
- description (string) - Deskripsi baru (opsional)

#### DELETE - Menghapus Data Peran
**URL**: `/API_Modules/Roles/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID peran yang akan dihapus

### 3. EducationModules

#### CREATE - Menambahkan Modul Pendidikan Baru
**URL**: `/API_Modules/EducationModules/create.php`

**Metode**: POST

**Parameter**:
- title (string) - Judul modul pendidikan
- content (string) - Isi konten modul
- xp_reward (integer) - Poin pengalaman yang diberikan (default: 10)
- point_reward (integer) - Poin yang diberikan (default: 5)
- difficulty (string) - Tingkat kesulitan (default: medium)
- category (string) - Kategori modul
- duration_minutes (integer) - Durasi estimasi dalam menit (default: 10)
- is_active (integer) - Status aktif (default: 1)

**Contoh Request**:
```bash
curl -X POST \
  -d "title=Pengelolaan Sampah" \
  -d "content=Modul tentang cara mengelola sampah..." \
  -d "xp_reward=20" \
  -d "point_reward=10" \
  http://localhost/API_modul_RiseClean/API_Modules/EducationModules/create.php
```

#### READ - Membaca Data Modul Pendidikan
**URL**: `/API_Modules/EducationModules/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data modul berdasarkan ID

#### UPDATE - Memperbarui Data Modul Pendidikan
**URL**: `/API_Modules/EducationModules/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID modul yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Modul Pendidikan
**URL**: `/API_Modules/EducationModules/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID modul yang akan dihapus

### 4. QuizQuestions

#### CREATE - Menambahkan Pertanyaan Kuis Baru
**URL**: `/API_Modules/QuizQuestions/create.php`

**Metode**: POST

**Parameter**:
- module_id (integer) - ID modul terkait
- question_text (string) - Teks pertanyaan kuis
- question_type (string) - Jenis pertanyaan (default: multiple_choice)
- xp_reward (integer) - Poin pengalaman yang diberikan (default: 10)
- point_reward (integer) - Poin yang diberikan (default: 5)
- difficulty (string) - Tingkat kesulitan (default: easy)
- is_active (integer) - Status aktif (default: 1)

**Contoh Request**:
```bash
curl -X POST \
  -d "module_id=1" \
  -d "question_text=Apa itu 3R?" \
  -d "xp_reward=5" \
  http://localhost/API_modul_RiseClean/API_Modules/QuizQuestions/create.php
```

#### READ - Membaca Data Pertanyaan Kuis
**URL**: `/API_Modules/QuizQuestions/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data pertanyaan berdasarkan ID

#### UPDATE - Memperbarui Data Pertanyaan Kuis
**URL**: `/API_Modules/QuizQuestions/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID pertanyaan yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Pertanyaan Kuis
**URL**: `/API_Modules/QuizQuestions/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID pertanyaan yang akan dihapus

### 5. QuizChoices

#### CREATE - Menambahkan Pilihan Jawaban Baru
**URL**: `/API_Modules/QuizChoices/create.php`

**Metode**: POST

**Parameter**:
- question_id (integer) - ID pertanyaan terkait
- choice_text (string) - Teks pilihan jawaban
- is_correct (integer) - Apakah ini jawaban benar (default: 0)
- choice_order (integer) - Urutan tampilan pilihan (default: 0)

**Contoh Request**:
```bash
curl -X POST \
  -d "question_id=1" \
  -d "choice_text=Reduce, Reuse, Recycle" \
  -d "is_correct=1" \
  http://localhost/API_modul_RiseClean/API_Modules/QuizChoices/create.php
```

#### READ - Membaca Data Pilihan Jawaban
**URL**: `/API_Modules/QuizChoices/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data pilihan berdasarkan ID

#### UPDATE - Memperbarui Data Pilihan Jawaban
**URL**: `/API_Modules/QuizChoices/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID pilihan yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Pilihan Jawaban
**URL**: `/API_Modules/QuizChoices/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID pilihan yang akan dihapus

### 6. Challenges

#### CREATE - Menambahkan Tantangan Baru
**URL**: `/API_Modules/Challenges/create.php`

**Metode**: POST

**Parameter**:
- title (string) - Judul tantangan
- description (string) - Deskripsi tantangan
- xp_reward (integer) - Poin pengalaman yang diberikan (default: 20)
- point_reward (integer) - Poin yang diberikan (default: 10)
- difficulty (string) - Tingkat kesulitan (default: medium)
- challenge_type (string) - Jenis tantangan (default: daily)
- start_date (string) - Tanggal mulai (default: tanggal hari ini)
- end_date (string) - Tanggal akhir
- is_active (integer) - Status aktif (default: 1)

**Contoh Request**:
```bash
curl -X POST \
  -d "title=Tantangan Pengurangan Sampah" \
  -d "description=Lakukan pemilahan sampah selama seminggu" \
  -d "xp_reward=50" \
  -d "point_reward=25" \
  http://localhost/API_modul_RiseClean/API_Modules/Challenges/create.php
```

#### READ - Membaca Data Tantangan
**URL**: `/API_Modules/Challenges/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data tantangan berdasarkan ID

#### UPDATE - Memperbarui Data Tantangan
**URL**: `/API_Modules/Challenges/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID tantangan yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Tantangan
**URL**: `/API_Modules/Challenges/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID tantangan yang akan dihapus

### 7. Levels

#### CREATE - Menambahkan Level Baru
**URL**: `/API_Modules/Levels/create.php`

**Metode**: POST

**Parameter**:
- level_name (string) - Nama level
- min_xp (integer) - Minimum XP yang dibutuhkan (default: 0)

**Contoh Request**:
```bash
curl -X POST \
  -d "level_name=Intermediate" \
  -d "min_xp=100" \
  http://localhost/API_modul_RiseClean/API_Modules/Levels/create.php
```

#### READ - Membaca Data Level
**URL**: `/API_Modules/Levels/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data level berdasarkan ID

#### UPDATE - Memperbarui Data Level
**URL**: `/API_Modules/Levels/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID level yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Level
**URL**: `/API_Modules/Levels/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID level yang akan dihapus

### 8. Rewards

#### CREATE - Menambahkan Hadiah Baru
**URL**: `/API_Modules/Rewards/create.php`

**Metode**: POST

**Parameter**:
- reward_name (string) - Nama hadiah
- point_cost (integer) - Biaya penukaran dalam poin
- description (string) - Deskripsi hadiah
- image (string) - URL gambar hadiah
- stock (integer) - Stok tersedia (default: 0)
- is_active (integer) - Status aktif (default: 1)

**Contoh Request**:
```bash
curl -X POST \
  -d "reward_name=Stiker Lingkungan" \
  -d "point_cost=50" \
  -d "description=Set stiker peduli lingkungan" \
  http://localhost/API_modul_RiseClean/API_Modules/Rewards/create.php
```

#### READ - Membaca Data Hadiah
**URL**: `/API_Modules/Rewards/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data hadiah berdasarkan ID

#### UPDATE - Memperbarui Data Hadiah
**URL**: `/API_Modules/Rewards/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID hadiah yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Hadiah
**URL**: `/API_Modules/Rewards/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID hadiah yang akan dihapus

### 9. UserProgress

#### CREATE - Menambahkan Kemajuan Pengguna Baru
**URL**: `/API_Modules/UserProgress/create.php`

**Metode**: POST

**Parameter**:
- user_id (integer) - ID pengguna
- item_id (integer) - ID item (modul atau tantangan)
- item_type (string) - Jenis item ('module' atau 'challenge')
- completed_at (string) - Waktu penyelesaian (default: waktu saat ini)
- verified_at (string) - Waktu verifikasi
- is_verified (integer) - Status verifikasi (default: 0)
- submission_text (string) - Teks pengiriman
- submission_image (string) - Gambar pengiriman

**Contoh Request**:
```bash
curl -X POST \
  -d "user_id=1" \
  -d "item_id=1" \
  -d "item_type=module" \
  http://localhost/API_modul_RiseClean/API_Modules/UserProgress/create.php
```

#### READ - Membaca Data Kemajuan Pengguna
**URL**: `/API_Modules/UserProgress/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data kemajuan berdasarkan ID

#### UPDATE - Memperbarui Data Kemajuan Pengguna
**URL**: `/API_Modules/UserProgress/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID kemajuan yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Kemajuan Pengguna
**URL**: `/API_Modules/UserProgress/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID kemajuan yang akan dihapus

### 10. UserQuizAnswers

#### CREATE - Menambahkan Jawaban Kuis Pengguna Baru
**URL**: `/API_Modules/UserQuizAnswers/create.php`

**Metode**: POST

**Parameter**:
- user_id (integer) - ID pengguna
- module_id (integer) - ID modul
- question_id (integer) - ID pertanyaan
- selected_choice_id (integer) - ID pilihan yang dipilih
- answer_text (string) - Jawaban teks
- is_correct (integer) - Apakah jawaban benar
- points_earned (integer) - Poin yang diperoleh (default: 0)
- xp_earned (integer) - XP yang diperoleh (default: 0)

**Contoh Request**:
```bash
curl -X POST \
  -d "user_id=1" \
  -d "module_id=1" \
  -d "question_id=1" \
  -d "selected_choice_id=1" \
  -d "is_correct=1" \
  http://localhost/API_modul_RiseClean/API_Modules/UserQuizAnswers/create.php
```

#### READ - Membaca Data Jawaban Kuis Pengguna
**URL**: `/API_Modules/UserQuizAnswers/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data jawaban berdasarkan ID

#### UPDATE - Memperbarui Data Jawaban Kuis Pengguna
**URL**: `/API_Modules/UserQuizAnswers/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID jawaban yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Jawaban Kuis Pengguna
**URL**: `/API_Modules/UserQuizAnswers/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID jawaban yang akan dihapus

### 11. UserRewards

#### CREATE - Menambahkan Hadiah Pengguna Baru
**URL**: `/API_Modules/UserRewards/create.php`

**Metode**: POST

**Parameter**:
- user_id (integer) - ID pengguna
- reward_id (integer) - ID hadiah
- quantity (integer) - Jumlah hadiah (default: 1)

**Contoh Request**:
```bash
curl -X POST \
  -d "user_id=1" \
  -d "reward_id=1" \
  -d "quantity=1" \
  http://localhost/API_modul_RiseClean/API_Modules/UserRewards/create.php
```

#### READ - Membaca Data Hadiah Pengguna
**URL**: `/API_Modules/UserRewards/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data hadiah pengguna berdasarkan ID

#### UPDATE - Memperbarui Data Hadiah Pengguna
**URL**: `/API_Modules/UserRewards/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID hadiah pengguna yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Hadiah Pengguna
**URL**: `/API_Modules/UserRewards/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID hadiah pengguna yang akan dihapus

### 12. ActivityLogs

#### CREATE - Menambahkan Log Aktivitas Baru
**URL**: `/API_Modules/ActivityLogs/create.php`

**Metode**: POST

**Parameter**:
- user_id (integer) - ID pengguna
- activity_type (string) - Jenis aktivitas
- description (string) - Deskripsi aktivitas
- ip_address (string) - Alamat IP (default: IP pengguna saat ini)

**Contoh Request**:
```bash
curl -X POST \
  -d "user_id=1" \
  -d "activity_type=complete_module" \
  -d "description=User completed education module" \
  http://localhost/API_modul_RiseClean/API_Modules/ActivityLogs/create.php
```

#### READ - Membaca Data Log Aktivitas
**URL**: `/API_Modules/ActivityLogs/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data log berdasarkan ID

#### UPDATE - Memperbarui Data Log Aktivitas
**URL**: `/API_Modules/ActivityLogs/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID log yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Log Aktivitas
**URL**: `/API_Modules/ActivityLogs/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID log yang akan dihapus

### 13. LoginAttempts

#### CREATE - Menambahkan Percobaan Login Baru
**URL**: `/API_Modules/LoginAttempts/create.php`

**Metode**: POST

**Parameter**:
- ip_address (string) - Alamat IP dari percobaan login (default: IP pengguna saat ini)
- attempt_time (string) - Waktu percobaan (default: waktu saat ini)
- is_success (integer) - Apakah login berhasil (default: 0)

**Contoh Request**:
```bash
curl -X POST \
  -d "ip_address=192.168.1.1" \
  -d "is_success=1" \
  http://localhost/API_modul_RiseClean/API_Modules/LoginAttempts/create.php
```

#### READ - Membaca Data Percobaan Login
**URL**: `/API_Modules/LoginAttempts/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data percobaan berdasarkan ID

#### UPDATE - Memperbarui Data Percobaan Login
**URL**: `/API_Modules/LoginAttempts/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID percobaan yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Percobaan Login
**URL**: `/API_Modules/LoginAttempts/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID percobaan yang akan dihapus

### 14. FailedLoginAttempts

#### CREATE - Menambahkan Percobaan Login Gagal Baru
**URL**: `/API_Modules/FailedLoginAttempts/create.php`

**Metode**: POST

**Parameter**:
- username (string) - Username yang digunakan dalam percobaan
- ip_address (string) - Alamat IP dari percobaan (default: IP pengguna saat ini)
- attempts (integer) - Jumlah percobaan gagal (default: 1)
- last_attempt (string) - Waktu percobaan terakhir (default: waktu saat ini)
- blocked_until (string) - Waktu hingga pengguna diblokir

**Contoh Request**:
```bash
curl -X POST \
  -d "username=johndoe" \
  -d "attempts=3" \
  http://localhost/API_modul_RiseClean/API_Modules/FailedLoginAttempts/create.php
```

#### READ - Membaca Data Percobaan Login Gagal
**URL**: `/API_Modules/FailedLoginAttempts/read.php`

**Metode**: GET

**Parameter (Opsional)**:
- id (integer) - Untuk mendapatkan data percobaan berdasarkan ID

#### UPDATE - Memperbarui Data Percobaan Login Gagal
**URL**: `/API_Modules/FailedLoginAttempts/update.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID percobaan yang akan diperbarui
- (semua kolom lainnya opsional untuk diperbarui)

#### DELETE - Menghapus Data Percobaan Login Gagal
**URL**: `/API_Modules/FailedLoginAttempts/delete.php`

**Metode**: POST

**Parameter**:
- id (integer) - ID percobaan yang akan dihapus

## Instalasi
1. Pastikan Anda memiliki server web dengan PHP dan MySQL
2. Salin semua file ke direktori web server Anda
3. Buat database MySQL dan impor struktur tabel sesuai dengan deskripsi di atas
4. Konfigurasi koneksi database di file `db.php`
5. Akses endpoint sesuai kebutuhan

## Catatan
- Semua endpoint mengembalikan respons dalam format JSON
- Gunakan metode POST untuk CREATE, UPDATE, dan DELETE
- Gunakan metode GET untuk READ
- Gunakan prepared statements untuk mencegah SQL injection
- Pastikan untuk selalu mengecek status respons sebelum memproses data lebih lanjut
- Endpoint diakses melalui `/API_Modules/[NamaModul]/[operasi].php`