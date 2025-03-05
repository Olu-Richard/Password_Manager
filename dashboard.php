<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Password Manager Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .password-field {
            display: flex;
            align-items: center;
        }
        .password-field input {
            flex: 1;
            border: none;
            background: transparent;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center mb-4">Password Manager Dashboard</h2>
        
        <!-- Add New Password Section -->
        <div class="card mb-4">
            <div class="card-header">Add New Password</div>
            <div class="card-body">
                <form id="addPasswordForm">
                    <div class="form-group">
                        <label for="serviceName">Service Name</label>
                        <input type="text" class="form-control" id="serviceName" placeholder="e.g., Gmail, Facebook" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username/Email</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" placeholder="Enter a password" required>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" onclick="generatePassword()">Generate</button>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Save Password</button>
                </form>
            </div>
        </div>

        <!-- Saved Passwords Table -->
        <div class="card">
            <div class="card-header">Stored Passwords</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="passwordList">
                        <!-- Password entries will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function generatePassword() {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let password = "";
            for (let i = 0; i < 12; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            document.getElementById("password").value = password;
        }

        function addPassword(service, username, password) {
            let table = document.getElementById("passwordList");
            let row = table.insertRow();
            row.innerHTML = `
                <td>${service}</td>
                <td>${username}</td>
                <td class="password-field">
                    <input type="password" value="${password}" readonly>
                    <button class="btn btn-sm btn-info ml-2" onclick="togglePassword(this)">Show</button>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editPassword(this)">Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="deletePassword(this)">Delete</button>
                </td>
            `;
        }

        document.getElementById("addPasswordForm").addEventListener("submit", function (event) {
            event.preventDefault();
            let service = document.getElementById("serviceName").value;
            let username = document.getElementById("username").value;
            let password = document.getElementById("password").value;

            addPassword(service, username, password);

            this.reset();
        });

        function togglePassword(button) {
            let inputField = button.parentNode.querySelector("input");
            if (inputField.type === "password") {
                inputField.type = "text";
                button.textContent = "Hide";
            } else {
                inputField.type = "password";
                button.textContent = "Show";
            }
        }

        function editPassword(button) {
            let row = button.parentNode.parentNode;
            let service = row.cells[0].textContent;
            let username = row.cells[1].textContent;
            let password = row.cells[2].querySelector("input").value;

            document.getElementById("serviceName").value = service;
            document.getElementById("username").value = username;
            document.getElementById("password").value = password;

            row.remove();
        }

        function deletePassword(button) {
            let row = button.parentNode.parentNode;
            row.remove();
        }
    </script>

</body>
</html>
