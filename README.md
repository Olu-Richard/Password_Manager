
# Password Manager

A secure password manager web application designed to store and manage your passwords. The app is built with PHP, MySQL, and Bootstrap for a responsive user interface. It allows users to register, log in, view, add, edit, and delete passwords.

## Features

- User registration and authentication
- Add, view, edit, and delete passwords
- Secure password storage in a MySQL database
- Password management organized by website
- User-friendly UI with a sidebar navigation
- Website logos automatically fetched from the links

## Technologies Used

- **Frontend**: HTML, CSS, Bootstrap 5
- **Backend**: PHP
- **Database**: MySQL
- **Session Management**: PHP Sessions
- **Web Scraping**: PHP script to get website logos from URLs (optional)
  
## Installation

### Prerequisites

- PHP >= 7.4
- MySQL or MariaDB
- Apache or Nginx Web Server
- Composer (Optional for dependency management)

### Steps

1. **Clone the repository**:
   
   ```bash
   git clone https://github.com/your-username/password-manager.git
   ```

2. **Set up the database**:
   
   - Create a database in MySQL:
   
     ```sql
     CREATE DATABASE password_manager;
     ```
   
   - Import the database schema (you can use a SQL file with your tables) for the passwords table. Example:

     ```sql
     CREATE TABLE passwords (
         id INT AUTO_INCREMENT PRIMARY KEY,
         user_id INT NOT NULL,
         website VARCHAR(255) NOT NULL,
         username VARCHAR(255),
         password TEXT NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (user_id) REFERENCES users(id)
     );
     
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         email VARCHAR(255) UNIQUE NOT NULL,
         password VARCHAR(255) NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```

3. **Configure the database connection**:
   - Edit the `includes/db.php` file with your database credentials:

     ```php
     <?php
     $host = 'localhost'; // your database host
     $db = 'password_manager'; // your database name
     $user = 'root'; // your database username
     $pass = ''; // your database password

     try {
         $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
         // Set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch (PDOException $e) {
         echo "Connection failed: " . $e->getMessage();
     }
     ?>
     ```

4. **Set up the web server**:
   - For **Apache**, ensure `mod_rewrite` is enabled and the server points to the `public` folder as the root directory.
   - For **Nginx**, ensure the server block is set correctly to serve PHP files.

5. **Access the application**:
   - Open the browser and go to `http://localhost/your-project-folder` to see the login page and begin using the application.

## Usage

### 1. **Login**:
   - Go to the login page and enter your email and password to log in.
   - Once logged in, you will be redirected to the dashboard, where you can view and manage your saved passwords.

### 2. **Add New Password**:
   - Click on "Add New Password" in the sidebar to save a new password.
   - Enter the website, username, and password.

### 3. **Edit Password**:
   - On the dashboard, click on the "Edit" button next to any saved password to modify the details.

### 4. **Delete Password**:
   - To delete a password, click on the "Delete" button next to the password.

### 5. **Logout**:
   - Click "Logout" in the sidebar to securely log out of your account.

## Security Considerations

- Passwords are stored securely in the database, but it's recommended to apply additional measures like password hashing (using `password_hash()` and `password_verify()` functions in PHP) for better security.
- You can implement encryption for passwords before storing them in the database to enhance security.

## Future Enhancements

- Implement password encryption before storing them in the database.
- Add password strength indicators and validation rules.
- Provide multi-factor authentication (MFA) for increased security.
- Add the ability to categorize passwords for easier organization.
- Integrate more web scraping features to fetch and display favicon images for websites.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contributing

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Create a new Pull Request.

## Contact

If you have any questions or suggestions, feel free to reach out:

- **Email**: [your-email@example.com](mailto:your-email@example.com)
- **GitHub**: [https://github.com/your-username](https://github.com/your-username)

---

Thank you for using Password Manager! Feel free to contribute and improve the project.
