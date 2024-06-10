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


$type = isset($_GET['type']) ? $_GET['type'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$bedrooms = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
$bathrooms = isset($_GET['bathrooms']) ? $_GET['bathrooms'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';


$sql = "SELECT * FROM properties WHERE 1=1";

if ($type) {
    $sql .= " AND type = '" . $conn->real_escape_string($type) . "'";
}
if ($min_price) {
    $sql .= " AND price >= " . (float)$min_price;
}
if ($max_price) {
    $sql .= " AND price <= " . (float)$max_price;
}
if ($bedrooms) {
    $sql .= " AND bedrooms >= " . (int)$bedrooms;
}
if ($bathrooms) {
    $sql .= " AND bathrooms >= " . (int)$bathrooms;
}
if ($location) {
    $sql .= " AND location LIKE '%" . $conn->real_escape_string($location) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Объекты недвижимости</title>
    <link rel="stylesheet" href="properties.css">
</head>
<body>
    <div class="properties-container">
        <h2>Объекты недвижимости</h2>
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label for="type">Тип:</label>
                <select id="type" name="type">
                    <option value="">Все</option>
                    <option value="Купить" <?php if ($type == 'Купить') echo 'selected'; ?>>Купить</option>
                    <option value="Снять" <?php if ($type == 'Снять') echo 'selected'; ?>>Снять</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="min_price">Минимальная стоимость:</label>
                <input type="number" step="0.01" id="min_price" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>">
            </div>
            <div class="filter-group">
                <label for="max_price">Максимальная стоимость:</label>
                <input type="number" step="0.01" id="max_price" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>">
            </div>
            <div class="filter-group">
                <label for="bedrooms">Количество спален:</label>
                <input type="number" id="bedrooms" name="bedrooms" value="<?php echo htmlspecialchars($bedrooms); ?>">
            </div>
            <div class="filter-group">
                <label for="bathrooms">Количество ванных комнат:</label>
                <input type="number" id="bathrooms" name="bathrooms" value="<?php echo htmlspecialchars($bathrooms); ?>">
            </div>
            <div class="filter-group">
                <label for="location">Местоположение:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>">
            </div>
            <button type="submit">Применить фильтры</button>
        </form>
        <div class="properties-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='property-item'>";
                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p><strong>Тип:</strong> " . htmlspecialchars($row['type']) . "</p>";
                    echo "<p><strong>Стоимость:</strong> " . htmlspecialchars($row['price']) . " руб.</p>";
                    echo "<p><strong>Описание:</strong> " . nl2br(htmlspecialchars($row['description'])) . "</p>";
                    echo "<p><strong>Местоположение:</strong> " . htmlspecialchars($row['location']) . "</p>";
                    echo "<p><strong>Количество спален:</strong> " . htmlspecialchars($row['bedrooms']) . "</p>";
                    echo "<p><strong>Количество ванных комнат:</strong> " . htmlspecialchars($row['bathrooms']) . "</p>";
                    echo "<p><strong>Размер:</strong> " . htmlspecialchars($row['size']) . " кв.м</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Нет объектов недвижимости для отображения.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
