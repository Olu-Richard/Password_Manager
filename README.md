# Password Manager

A secure, modern, and user-friendly web-based password manager built with PHP and MySQL.

![Password Manager Screenshot](screenshots/dashboard.png)

## Features

- 🔐 Secure Password Storage
  - Encrypted password storage
  - Automatic password masking
  - Copy passwords with one click
  - Show/hide password toggle

- 🎨 Modern User Interface
  - Clean, responsive design
  - Collapsible sidebar navigation
  - Website favicons display
  - Bootstrap 5 styling
  - Font Awesome icons

- 👤 User Management
  - User registration and authentication
  - Profile settings management
  - Change password functionality
  - Account deletion option

- ⚙️ Customization Options
  - Dark mode support
  - Auto-logout settings
  - Two-factor authentication (coming soon)
  - Biometric authentication (coming soon)

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP/WAMP/MAMP or any PHP development environment
- Modern web browser

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/password-manager.git
   ```

2. Move the project to your web server directory:
   ```bash
   mv password-manager /path/to/your/www/directory
   ```

3. Create a new MySQL database:
   ```sql
   CREATE DATABASE password_manager;
   ```

4. Import the database structure:
   ```bash
   mysql -u root password_manager < database/structure.sql
   ```

5. Configure the database connection:
   - Open `includes/db.php`
   - Update the database credentials if needed

6. Set up your web server:
   - Configure your web server to point to the project directory
   - Ensure PHP has write permissions to necessary directories

## Project Structure

```
password-manager/
├── api/                 # API endpoints
├── config/             # Configuration files
├── database/           # Database scripts
├── includes/           # PHP includes
│   └── db.php         # Database connection
├── public/             # Public files
│   ├── add_password.php
│   ├── dashboard.php
│   ├── edit_password.php
│   ├── login.php
│   ├── register.php
│   └── settings.php
├── assets/             # Static assets
│   ├── css/
│   ├── js/
│   └── img/
└── README.md          # This file
```

## Security Features

- Password hashing using PHP's password_hash()
- SQL injection prevention with prepared statements
- XSS protection with htmlspecialchars()
- CSRF protection
- Secure session handling
- Password masking in the UI

## Browser Extension

A companion browser extension is available for:
- Google Chrome
- Mozilla Firefox
- Microsoft Edge

Features include:
- Auto-fill credentials
- Save new passwords
- Update existing passwords
- Quick access to stored passwords

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [Bootstrap](https://getbootstrap.com/) - Frontend framework
- [Font Awesome](https://fontawesome.com/) - Icons
- [Google Favicon Service](https://www.google.com/s2/favicons) - Website favicons

## Support

For support, please open an issue in the GitHub repository or contact the maintainers at:
- Email: support@passwordmanager.com
- Twitter: [@PasswordManager](https://twitter.com/passwordmanager)

## Roadmap

- [ ] Implement two-factor authentication
- [ ] Add biometric authentication support
- [ ] Create mobile applications
- [ ] Add password strength meter
- [ ] Implement password sharing
- [ ] Add secure notes feature
- [ ] Create backup/restore functionality
