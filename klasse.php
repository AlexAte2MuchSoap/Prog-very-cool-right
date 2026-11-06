<?php
require_once 'db.php';

// Håndter sletting av klasse
if (isset($_GET['delete_klasse'])) {
    $klasse_kode = $_GET['delete_klasse'];
    $sql = "DELETE FROM klasse WHERE klasse_kode = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $klasse_kode);

    if ($stmt->execute()) {
        $message = "Klasse slettet vellykket!";
    } else {
        $error = "Feil ved sletting: " . $stmt->error;
    }
    $stmt->close();
}

// Håndter legg til ny klasse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
    $klasse_kode = trim($_POST['klasse_kode']);
    $klasse_navn = trim($_POST['klasse_navn']);

    if (empty($klasse_kode) || empty($klasse_navn)) {
        $error = "Alle felt må fylles ut!";
    } else {
        $sql = "INSERT INTO klasse (klasse_kode, klasse_navn) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $klasse_kode, $klasse_navn);

        if ($stmt->execute()) {
            $message = "Klasse lagt til vellykket!";
        } else {
            $error = "Feil ved tilsetting: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Hent alle klasser
$sql = "SELECT * FROM klasse";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrer Klasser</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 5px; margin-right: 10px; }
        button { padding: 5px 10px; cursor: pointer; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Administrer Klasser</h1>

    <!-- Meldinger -->
    <?php if (isset($message)): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Skjema for å legge til ny klasse -->
    <form method="POST">
        <h2>Legg til ny klasse</h2>
        <label for="klasse_kode">Klasse Kode:</label>
        <input type="text" name="klasse_kode" id="klasse_kode" required>
        
        <label for="klasse_navn">Klasse Navn:</label>
        <input type="text" name="klasse_navn" id="klasse_navn" required>
        
        <button type="submit" name="add_class">Legg til klasse</button>
    </form>

    <hr>

    <!-- Liste over alle klasser -->
    <h2>Eksisterende klasser</h2>
    <table>
        <tr>
            <th>Klasse Kode</th>
            <th>Klasse Navn</th>
            <th>Handling</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['klasse_kode'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['klasse_navn'] ?? '') ?></td>
                    <td>
                        <a href="?delete_klasse=<?= urlencode($row['klasse_kode'] ?? '') ?>"
                           onclick="return confirm('Er du sikker på at du vil slette denne klassen?')">Slett</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Ingen klasser funnet.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

