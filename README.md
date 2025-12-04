# 💰 Personal Expense Tracker (Laravel API + Vue.js SPA)

A simple and powerful **Personal Expense Tracking** system built with **Laravel 12 REST API** and **Vue.js 3 SPA**, featuring category-based income/expense tracking, real-time dashboard analytics, PDF exporting, and secure authentication.

---

## 🛠️ Technology Stack

| Layer      | Technology            |
| ---------- | --------------------- |
| Backend    | Laravel 11 (REST API) |
| Frontend   | Vue.js 3 (SPA)        |
| Database   | PostgreSQL            |
| Auth       | Laravel Sanctum       |
| UI         | Tailwind CSS          |
| Charts     | Chart.js              |
| PDF Export | Barryvdh DomPDF       |

---

## 🔗 API Endpoints (Main)

| Method | Endpoint               | Description                    |
| ------ | ---------------------- | ------------------------------ |
| POST   | /api/register          | Register user                  |
| POST   | /api/login             | Login & get token              |
| GET    | /api/dashboard         | Dashboard summary + chart data |
| GET    | /api/transactions      | List all transactions          |
| POST   | /api/transactions      | Create a transaction           |
| PUT    | /api/transactions/{id} | Update transaction             |
| DELETE | /api/transactions/{id} | Delete transaction             |
| GET    | /api/categories        | List categories                |
| POST   | /api/categories        | Create category                |
| GET    | /api/export-pdf        | Export monthly PDF report      |
| POST   | /api/profile/update    | Update profile                 |
| POST   | /api/profile/password  | Change password                |

---

## 🌍 Main Routes (Frontend)

| Page             | Route           |
| ---------------- | --------------- |
| Login            | `/login`        |
| Register         | `/register`     |
| Dashboard        | `/`             |
| Transactions     | `/transactions` |
| Categories       | `/categories`   |
| Profile Settings | `/profile`      |
| PDF Export       | `/report`       |

---

## 📌 Core Features Summary

- Track **Income & Expenses**
- Category-based filtering
- Real-time dashboard with charts
- PDF monthly report
- Profile and image update
- Supports multiple currencies
- Dark / Light Mode

---

## 👨‍💻 Developer

**Md Shakil Khan**  
Full Stack Developer
