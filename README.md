# 🍕 PizzaHub - Online Pizza Ordering System

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.2%2B-EF4223?logo=codeigniter)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql)

A complete pizza ordering platform built with CodeIgniter 4 for restaurants and small businesses.

![PizzaHub](https://github.com/user-attachments/assets/5eacf46a-40df-4da4-8f28-253df386cf5a)

## ✨ Features

### Customer Side
- 🧑‍🤝‍🧑 User registration/login
- 🍕 Interactive menu with categories
- 🛒 Real-time cart system
- 📊 Order tracking (6 status stages)

### Admin Panel
- 📈 Sales analytics dashboard
- 📦 Inventory management
- 📝 Order management console
- 👥 User administration

## 🛠️ Installation

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Composer

### Setup
1. Clone the repo:
   ```bash
   git clone https://github.com/yourusername/pizzahub.git
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure environment:
   ```bash
   cp env .env
   ```
   Edit database settings in `.env`:
   ```ini
   database.default.hostname = localhost
   database.default.database = pizzahub
   database.default.username = root
   database.default.password = 
   ```

4. Create a key:
   ```bash
   php spark key:generate
   ```

4. Run migrations:
   ```bash
   php spark migrate
   ```
   
5. Seed sample data:
   ```bash
   php spark db:seed DatabaseSeeder
   ```

## 🚀 Usage

1. Access the site:
   ```
   http://localhost/pizzahub/public
   ```
2. Admin login:
   ```
   Email: admin@pizzahub.com
   Password: Admin@123
   ```

## 📂 Project Structure

```
pizzahub/
├── app/                # Core application
│   ├── Config/         # Configuration files
│   ├── Controllers/    # Application logic
│   ├── Models/         # Database operations
│   └── Views/          # Frontend templates
├── public/             # Web root
│   └── assets/         # CSS, JS, images
└── writable/           # Logs, cache, uploads
```

## 🤝 Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📧 Contact

Project Maintainer - [Emmar Caber](mailto:caberemmar@gmail.com)  
Project Link: [https://github.com/emmarcaber/pizzahub](https://github.com/emmarcaber/pizzahub)

To customize:
1. Replace all `yourusername` references with your GitHub profile
2. Add actual screenshot (upload to repo or use CDN)
3. Update contact information
4. Modify license if not using MIT
5. Add additional deployment notes if needed (Docker, etc.)
