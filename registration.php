<?php
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($password)) $errors[] = "Password is required.";
    elseif (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

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
            if (!is_array($currentData)) $currentData = [];
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
    <title>User Registration</title>
</head>
<body>

<h2>User Registration</h2>

<?php
if (!empty($success)) echo "<div style='color: green;'>$success</div>";
if (!empty($errors)) foreach ($errors as $e) echo "<div style='color: red;'>$e</div>";
?>

<form action="" method="POST">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Enter your name"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Enter your email"><br><br>

    <label>Address:</label><br>
    <input type="text" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" placeholder="Enter your address"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" placeholder="Enter password"><br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" placeholder="Confirm password"><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>
