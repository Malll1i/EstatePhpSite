<?php
session_start();

$servername = "localhost";
$username = "root"; // Ваше имя пользователя для доступа к MySQL
$password = ""; // Ваш пароль для доступа к MySQL
$dbname = "estate";

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = hash('sha256', $_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $input_username, $input_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $input_username;
        $login_success = true;
        header("Location: admin.php");
        exit();
    } else {
        $login_success = false;
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
    <title>Авторизация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Вход в систему</h2>
            <?php if ($login_success === true): ?>
                <div class="success">Успех! Вы вошли в систему.</div>
            <?php elseif ($login_success === false): ?>
                <div class="error">Неудача. Неверное имя пользователя или пароль.</div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="input-group">
                    <input type="text" id="username" name="username" required>
                    <label for="username">Имя пользователя</label>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Пароль</label>
                </div>
                <button type="submit">Войти</button>
                <div class="to-reg">
                    <a href="#">Зарегистрироваться</a>
                </div>
            </form>
            <div class="link-to-properties">
                <a href="properties.php">Посмотреть объекты недвижимости</a>
            </div>
        </div>
    </div>
</body>
</html>
