# Password Manager

A secure, modern, and user-friendly web-based password manager built with PHP and MySQL.

## Screenshots

![image](https://github.com/user-attachments/assets/214dd8d8-313b-4dea-af3d-09bcbe949103)

![image](https://github.com/user-attachments/assets/9e95071b-9367-46dc-85f1-929f3bb18753)

![image](https://github.com/user-attachments/assets/d57b3ae1-234f-4423-b7b5-6026d38d5cbd)

![image](https://github.com/user-attachments/assets/0fc62517-cbe5-4753-89fb-658d2cc78c7c)

![image](https://github.com/user-attachments/assets/eb7a0285-0d22-4a9f-8164-334c58896f3f)

![image](https://github.com/user-attachments/assets/89ad8486-3952-413f-8f3d-e8835848c86e)

![image](https://github.com/user-attachments/assets/64ac5d6f-b0a0-441f-b318-349a3d486856)

![image](https://github.com/user-attachments/assets/e8b054f4-a41c-44e2-940c-323d7e9d02b1)

![image](https://github.com/user-attachments/assets/56fd0bc9-6220-40c8-839e-23eb5b0c9f0d)

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
├── database/            # Database scripts
├── Browser extension/  # browser extension folder         
├── includes/
│   ├── auth.php
│   ├── footer.php
│   ├── header.php           # PHP includes
│   └── db.php         # Database connection
├── public/             # Public files
│   ├── add_password.php
│   ├── dashboard.php
│   ├── edit_password.php
│   ├── logout.php
│   ├── register.php
│   ├── index.php
│   ├── delete_password.php
│   ├── add credentials.html
│   └── settings.php
├── assets/             # Static assets
│   ├── css/
│   └── js/
└── README.md          # This file
```

## Security Features

- Password hashing using PHP's password\_hash()
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

For support, please open an issue in the GitHub repository or contact:

- Email: [richardolummanuel51@gmail.com](mailto:richardolummanuel51@gmail.com)

## Roadmap

- [ ] Implement two-factor authentication
- [ ] Add biometric authentication support
- [ ] Create mobile applications
- [ ] Add password strength meter
- [ ] Implement password sharing
- [ ] Add secure notes feature
- [ ] Create backup/restore functionality
- [ ] Implement a password breach checker
- [ ] Add multi-user support with access control
- [ ] Introduce self-hosted deployment options

## Contributors

- Richard Olummanuel
- Dinesh Meshram

