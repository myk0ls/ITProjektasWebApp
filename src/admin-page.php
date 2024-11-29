<?php
include('session.php');
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $userId = $_POST['user_id'];
    $conAmount = $_POST['con_amount'];
    $userType = $_POST['type'];

    $updateQuery = "UPDATE users SET con_amount = ?, type = ? WHERE id = ?";
    $stmt = $db->prepare($updateQuery);
    $stmt->bind_param('dsi', $conAmount, $userType, $userId);

    if ($stmt->execute()) {
        echo "<p>Įrašas atnaujintas.</p>";
    } else {
        echo "<p>Įvyko klaida atnaujinant įrašą: " . $db->error . "</p>";
    }
    $stmt->close();
}

$query = "SELECT id, username, con_amount, type FROM users";
$result = $db->query($query);

$query1 = "SELECT user_id, conversion_format, conversion_bitrate, timestamp FROM conversionrequests";
$result1 = mysqli_query($db, $query1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<a href="logout.php" class="logout">Atsijungti</a>
<div class="admin-container">
    <h1>Administratoriaus puslapis</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Slapyvardis</th>
                <th>Tokenų kiekis</th>
                <th>Vartotojo tipas</th>
                <th>Veiksmai</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="number" name="con_amount" value="<?= htmlspecialchars($row['con_amount']) ?>" step="1" min="0">
                        </td>
                        <td>
                                <select name="type">
                                    <option value="normal" <?= $row['type'] === 'normal' ? 'selected' : '' ?>>Normal</option>
                                    <option value="advanced" <?= $row['type'] === 'advanced' ? 'selected' : '' ?>>Advanced</option>
                                    <option value="administrator" <?= $row['type'] === 'administrator' ? 'selected' : '' ?>>Administrator</option>
                                </select>
                        </td>
                        <td>
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <button type="submit" name="update_user">Atnaujinti</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <table>
        <thead>
        <th>Vartotojo ID</th>
        <th>Konversijos formatas</th>
        <th>Konversijos bitrate</th>
        <th>Laikas</th>
        </thead>

        <tbody>
        <?php
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['conversion_format']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['conversion_bitrate']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nėra konversijos užklausų</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// Close database connection
$db->close();
?>
