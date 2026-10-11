# 🚀 CodeIgniter 4 REST API Provider

REST API backend service built with **CodeIgniter 4** and **MySQL**, designed to serve data seamlessly to client-side applications (React, Mobile Apps, etc.) with built-in CORS configuration.

## 🛠️ Tech Stack
- **Framework:** CodeIgniter 4
- **Database:** MySQL
- **Architecture:** RESTful API Architecture
- **Protocol:** HTTP/JSON with CORS Support

## 📌 API Endpoints

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/post` | Retrieve all posts |
| `POST` | `/api/post` | Create a new post |
| `PUT` | `/api/post/(:id)` | Update existing post by ID |
| `DELETE` | `/api/post/(:id)` | Delete post by ID |

## ⚡ Quick Setup

1. **Clone repository:**
   ```bash
   git clone [https://github.com/jeandra/my-ci4-api.git](https://github.com/jeandra/my-ci4-api.git)
   cd my-ci4-api
   ```

## Auth

Atur `JWT_SECRET` di file `.env` menggunakan secret acak minimal 32 karakter, misalnya hasil `php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"`. Jalankan migrasi dengan `php spark migrate`.

Kirim request JSON ke `POST /api/auth/register` dengan `username`, `email`, dan `password` (minimal 8 karakter). Untuk login, kirim `email` dan `password` ke `POST /api/auth/login`. Keduanya mengembalikan JWT bearer token yang berlaku selama satu jam; gunakan token tersebut pada header `Authorization: Bearer <token>`. Semua method pada `/api/posts` dan route lama `/api/post` membutuhkan token JWT yang valid.