@echo off
"C:\xampp\mysql\bin\mysql" -u root password_manager -e "INSERT INTO passwords (user_id, service, username, password) VALUES (1, 'irishnews.com', 'test@example.com', 'testpassword123');" 