<h1> PRIJECT ERP HRD </h1>

<h3>README BACKEND </h3>

---

# 📘 README.md (Setup + SMTP)

`markdown
# 🚀 Backend ERP HRD - CodeIgniter 4

Backend ERP untuk sistem **Human Resource Development (HRD)** menggunakan **CodeIgniter 4**.  
Dokumentasi ini berisi langkah-langkah **setup project** dan **konfigurasi SMTP Gmail**.

---

##⚙ Persyaratan Sistem

- **PHP** >= 8.1
- **Composer**
- **MySQL/MariaDB**
- **Apache/Nginx** (opsional, bisa juga dengan `php spark serve`)
- **Akun Gmail dengan App Password**

---

## 📥 Instalasi

1. **Clone Repository**
   bash
   git clone 
   cd backend_erp
`

2. **Install Dependensi**

   bash
   composer install
   

3. **Salin & Konfigurasi Environment**

   bash
   cp .env.example .env
   

---

## ⚡ Konfigurasi `.env`

### 🔹 Mode Aplikasi

ini
CI_ENVIRONMENT = development


### 🔹 Base URL

ini
app.baseURL = 'http://localhost:8080/'


### 🔹 Database

ini
database.default.hostname = localhost
database.default.database = erp
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306


Buat database di MySQL:

sql
CREATE DATABASE erp;


### 🔹 Email (SMTP Gmail)

ini
email.fromEmail = "youremail@gmail.com"
email.fromName  = "HRD Perusahaan"

email.protocol  = smtp
email.SMTPHost  = smtp.gmail.com
email.SMTPUser  = youremail@gmail.com
email.SMTPPass  = gpxxxxxxxxxyyyyyyyyyzzzzzz
email.SMTPPort  = 587
email.SMTPCrypto = tls

email.mailType  = html
email.charset   = utf-8
email.wordWrap  = true


---

## 🔑 Cara Mendapatkan Password SMTP Gmail (App Password)

1. **Aktifkan 2-Step Verification** di akun Google Anda.
   👉 [https://myaccount.google.com/security](https://myaccount.google.com/security)

2. Buka halaman **Google App Passwords**
   👉 [https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)

3. Pada bagian **Select App**, pilih **Mail**.
   Pada bagian **Select Device**, pilih **Other (Custom name)** → masukkan nama misalnya `ERP HRD`.

4. Klik **Generate** → akan muncul **password 16 digit**.
   Contoh: `gpxxxxxxxxxyyyyyyyyyzzzzzz`

5. Gunakan password ini sebagai nilai `email.SMTPPass` di file `.env`.

## ▶ Menjalankan Server

Jalankan server pengembangan:

bash
php spark serve
```

Aplikasi dapat diakses di:
👉 [http://localhost:8080](http://localhost:8080)
