<?php
session_start();
require_once('connection.php');

if (isset($_POST['register'])) {
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $address = $_POST['address'];
    $bday = $_POST['bday'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = "Customer";
    $created = date('Y-m-d H:i:s');

    $connection = $newConnection->openConnection();

    $stmnt = $connection->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmnt->execute([$username, $password]);
    $user = $stmnt->fetch();

    if ($user) {
        echo "Username or password already exists. Please choose another one.";
        header('Location: register.php');
    } else {
        try {
            $query = "INSERT INTO users (first_name, last_name, address, birthdate, gender, username, password, role, date_created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmnt = $connection->prepare($query);
            $stmnt->execute([$firstname, $lastname, $address, $bday, $gender, $username, $password, $role, $created]);

            header('Location: index.php');
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F4F6F7;
            /* Same light background as dashboard */
            margin-top: 50px;
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
            border-radius: 15px;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            /* White background for the container */
        }

        .login-link {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 30px;
            color: #ECF0F1;
            font-weight: 600;
        }

        /* Registration Form Labels and Inputs */
        .form-label {
            color: #2C3E50;
            /* Darker text for labels */
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #BDC3C7;
        }

        .btn-warning {
            background-color: #2C3E50;
            border-color: #2C3E50;
            color: white;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-warning:hover {
            background-color: #1A2632;
            border-color: #1A2632;
        }

        .text-center {
            margin-top: 10px;
        }

        /* Change button styles for the login link */
        .login-link {
            background-color: #2C3E50;
            border-color: #2C3E50;
            color: #ECF0F1;
            font-weight: 500;
            padding: 8px 20px;
        }

        .login-link:hover {
            background-color: #1A2632;
            border-color: #1A2632;
        }
    </style>
</head>

<body>
    <a href="index.php" class="login-link btn">Login</a>
    <div class="container mt-4">
        <h2 class="text-center mb-4" style="color: #2C3E50;">Register</h2>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="fname" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lname" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" required>
            </div>
            <div class="mb-3">
                <label for="inputDate" class="form-label">Birthdate</label>
                <input type="date" class="form-control" id="inputDate" name="bday" required>
            </div>
            <div class="mb-3">
                <label for="inputState" class="form-label">Gender</label>
                <select id="inputState" class="form-select" name="gender" required>
                    <option selected disabled>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-warning" name="register">Register</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>