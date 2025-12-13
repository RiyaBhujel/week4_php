<?php
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, save user data
    if (empty($errors)) {
        $userData = [
            "name" => $name,
            "email" => $email,
            "address" => $address,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        $file = 'users.json';

        if (file_exists($file)) {
            $currentData = json_decode(file_get_contents($file), true);
            if (!is_array($currentData)) {
                $currentData = [];
            }
        } else {
            $currentData = [];
        }

        $currentData[] = $userData;
        file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT));

        $success = "Registration successful!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Result</title>
</head>
<body>

<h2>Registration Result</h2>

<?php
if (!empty($success)) {
    echo "<div style='color: green;'>$success</div>";
} else {
    foreach ($errors as $e) {
        echo "<div style='color: red;'>$e</div>";
    }
}
?>

<a href="registration.html">Back</a>

</body>
</html>
