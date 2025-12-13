<?php
// Initialize variables
$name = $email = $address = "";
$password = $confirmPassword = "";
$nameErr = $emailErr = $addressErr = $passwordErr = $confirmPasswordErr = "";
$successMsg = "";
$fileErrorMsg = "";

// Check form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Name validation
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = htmlspecialchars($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Address validation
    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
    } else {
        $address = htmlspecialchars($_POST["address"]);
    }

    // Password validation
    if (empty($_POST["psw"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["psw"];
        if (strlen($password) < 6) {
            $passwordErr = "Password must be at least 6 characters";
        } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $passwordErr = "Password must include at least one special character";
        }
    }

    // Confirm password validation
    if (empty($_POST["psw-repeat"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirmPassword = $_POST["psw-repeat"];
        if ($password !== $confirmPassword) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    // If all validations pass
    if ($nameErr == "" && $emailErr == "" && $addressErr == "" && $passwordErr == "" && $confirmPasswordErr == "") {

        try {
            // Ensure users.json exists
            if (!file_exists("users.json")) {
                file_put_contents("users.json", json_encode([]));
            }

            // Read current users
            $jsonData = file_get_contents("users.json");
            if ($jsonData === false) {
                throw new Exception("Error reading users.json file.");
            }

            $users = json_decode($jsonData, true);
            if (!is_array($users)) {
                $users = [];
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // New user array
            $newUser = [
                "name" => $name,
                "email" => $email,
                "address" => $address,
                "password" => $hashedPassword
            ];

            // Append new user
            $users[] = $newUser;

            // Save back to users.json
            $result = file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));
            if ($result === false) {
                throw new Exception("Error writing to users.json file.");
            }

            $successMsg = "Registration successful!";
            // Clear form
            $name = $email = $address = $password = $confirmPassword = "";

        } catch (Exception $e) {
            $fileErrorMsg = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Result</title>
</head>
<body>
    <?php if($successMsg != ""): ?>
        <div style="color:green;"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <?php if($fileErrorMsg != ""): ?>
        <div style="color:red;"><?php echo $fileErrorMsg; ?></div>
    <?php endif; ?>

    <a href="form.html">Go back to Registration Form</a>
</body>
</html>
