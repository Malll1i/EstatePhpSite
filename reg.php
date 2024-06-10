<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "estate";


$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$registration_success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = hash('sha256', $_POST['password']);

    // Проверяем, существует ли пользователь с таким именем
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $registration_success = false; // Имя пользователя уже занято
    } else {
        // Вставляем нового пользователя
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $input_username, $input_password);
        if ($stmt->execute()) {
            $registration_success = true;
        } else {
            $registration_success = false;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Регистрация</h2>
            <?php if ($registration_success === true): ?>
                <div class="success">Успех! Вы зарегистрированы.</div>
            <?php elseif ($registration_success === false): ?>
                <div class="error">Неудача. Имя пользователя уже занято или произошла ошибка.</div>
            <?php endif; ?>
            <form method="POST" action="reg.php">
                <div class="input-group">
                    <input type="text" id="username" name="username" required>
                    <label for="username">Имя пользователя</label>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Пароль</label>
                </div>
                <button type="submit">Зарегестрироваться</button>
                <div class="to-reg">
                    <a href="authorization.html">Войти</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
