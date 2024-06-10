<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "estate";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: autorization.html");
    exit();
}

$property_added = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $size = $_POST['size'];

    $stmt = $conn->prepare("INSERT INTO properties (type, title, price, description, location, bedrooms, bathrooms, size) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssiii", $type, $title, $price, $description, $location, $bedrooms, $bathrooms, $size);

    if ($stmt->execute()) {
        $property_added = true;
    } else {
        $property_added = false;
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
    <title>Админ Панель</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Админ Панель</h2>
            <?php if ($property_added === true): ?>
                <div class="success">Недвижимость успешно добавлена!</div>
            <?php elseif ($property_added === false): ?>
                <div class="error">Произошла ошибка при добавлении недвижимости.</div>
            <?php endif; ?>
            <form method="POST" action="admin.php">
                <div class="input-group">
                    <label for="type">Тип</label>
                    <select id="type" name="type" required>
                        <option value="Купить">Купить</option>
                        <option value="Снять">Снять</option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="text" id="title" name="title" required>
                    <label for="title">Заголовок</label>
                </div>
                <div class="input-group">
                    <input type="number" step="0.01" id="price" name="price" required>
                    <label for="price">Стоимость</label>
                </div>
                <div class="input-group">
                    <textarea id="description" name="description" rows="4"></textarea>
                    <label for="description">Описание</label>
                </div>
                <div class="input-group">
                    <input type="text" id="location" name="location">
                    <label for="location">Местоположение</label>
                </div>
                <div class="input-group">
                    <input type="number" id="bedrooms" name="bedrooms">
                    <label for="bedrooms">Количество спален</label>
                </div>
                <div class="input-group">
                    <input type="number" id="bathrooms" name="bathrooms">
                    <label for="bathrooms">Количество ванных комнат</label>
                </div>
                <div class="input-group">
                    <input type="number" id="size" name="size">
                    <label for="size">Размер (кв.м)</label>
                </div>
                <button type="submit">Добавить Недвижимость</button>
            </form>
        </div>
    </div>
</body>
</html>
