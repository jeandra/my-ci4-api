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