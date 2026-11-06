<?php
require_once 'db.php';


if (isset($_GET['delete_klasse'])) {
    $klasse_kode = $_GET['delete_klasse'];
    $sql = "DELETE FROM klasse WHERE klasse_kode = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $klasse_kode);

    if ($stmt->execute()) {
        header("Location: klasse.php");
        exit();
    } else {
        echo "Feil ved sletting: " . $stmt->error;
    }
    $stmt->close();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
    $klasse_kode = $_POST['klasse_kode'];
    $klasse_navn = $_POST['klasse_navn'];

    $sql = "INSERT INTO klasse (klasse_kode, klasse_navn) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $klasse_kode, $klasse_navn);

    if ($stmt->execute()) {
        header("Location: klasse.php");
        exit();
    } else {
        echo "Feil ved tilsetting: " . $stmt->error;
    }
    $stmt->close();
}
$sql = "SELECT * FROM klasse";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrer Klasser</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Administrer Klasser</h1>
    <form method="POST">
        <h2>Legg til ny klasse</h2>
        <label for="klasse_kode">Klasse Kode:</label>
        <input type="text" name="klasse_kode" required><br>

        <label for="klasse_navn">Klasse Navn:</label>
        <input type="text" name="klasse_navn" required><br>

        <button type="submit" name="add_class">Legg til klasse</button>
    </form>

    <hr>

    <h2>Eksisterende klasser</h2>
    <table>
        <tr>
            <th>Klasse Kode</th>
            <th>Klasse Navn</th>
            <th>Handling</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['klasse_kode']) ?></td>
                <td><?= htmlspecialchars($row['klasse_navn']) ?></td>
                <td>
                    <a href="?delete_klasse=<?= $row['klasse_kode'] ?>" onclick="return confirm('Er du sikker på at du vil slette denne klassen?')">Slett</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>