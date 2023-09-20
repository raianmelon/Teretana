<?php
require_once 'config.php';

if (isset($_SESSION['admin_id'])) {
    header('location: admin_dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT admin_id, password FROM admins WHERE username = ?";

    $run = $conn->prepare($sql);
    $run->bind_param("s", $username);
    $run->execute();

    $results = $run->get_result();

    if ($results->num_rows == 1) {
        $admin = $results->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $conn->close();
            $_SESSION['success_message'] = 'Uspjesno ste se logirali u dashboard!';
            header('location: admin_dashboard.php');
        } else {
            $_SESSION['error'] = 'Krivi password';
            $conn->close();
            header('location: index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Krivi username';
        $conn->close();
        header('location: index.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Admin Login</title>
    <style>
        .form-signin {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            margin: 0 auto;
        }

        .btn-block {
            display: block;
            width: 100%;
        }
    </style>
</head>

<body>

    <form action="" method="POST" class="form-signin d-flex align-items-center justify-content-center"
        style="height: 100vh;">
        <div>
            <h1 class="h2 mb-3">Prijavite se u Dashboard</h1>
            <input type="text" class="form-control" name="username" placeholder="Username"><br>
            <input type="password" class="form-control" name="password" placeholder="Password"><br>
            <input type="submit" class="btn btn-success btn-block" value="Prijava"><br>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="text-danger">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
            ?>
        </div>
    </form>
</body>

</html>