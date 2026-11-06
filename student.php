<?php
require_once 'db.php';

// Hent alle klasser for dropdown
$klasse_sql = "SELECT klasse_kode, klasse_navn FROM klasse";
$klasse_result = $conn->query($klasse_sql);

// Håndter sletting av student
if (isset($_GET['delete_student'])) {
    $student_id = $_GET['delete_student'];
    $sql = "DELETE FROM student WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        $message = "Student slettet vellykket!";
    } else {
        $error = "Feil ved sletting: " . $stmt->error;
    }
    $stmt->close();
}

// Håndter legg til ny student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $brukernavn = trim($_POST['brukernavn']);
    $fornavn = trim($_POST['fornavn']);
    $etternavn = trim($_POST['etternavn']);
    $klassekode = $_POST['klassekode'];

    if (empty($brukernavn) || empty($fornavn) || empty($etternavn)) {
        $error = "Alle felt må fylles ut!";
    } else {
        $sql = "INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $brukernavn, $fornavn, $etternavn, $klassekode);

        if ($stmt->execute()) {
            $message = "Student lagt til vellykket!";
        } else {
            $error = "Feil ved tilsetting: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Hent alle studenter med tilhørende klasse-navn
$student_sql = "SELECT s.student_id, s.brukernavn, s.fornavn, s.etternavn, k.klasse_navn
                FROM student s
                LEFT JOIN klasse k ON s.klassekode = k.klasse_kode";
$student_result = $conn->query($student_sql);
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrer Studenter</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { margin-bottom: 20px; }
        input[type="text"], select { padding: 5px; margin-right: 10px; margin-bottom: 10px; }
        button { padding: 5px 10px; cursor: pointer; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Administrer Studenter</h1>

    <!-- Meldinger -->
    <?php if (isset($message)): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Skjema for å legge til ny student -->
    <form method="POST">
        <h2>Legg til ny student</h2>
        <label for="brukernavn">Brukernavn:</label>
        <input type="text" name="brukernavn" id="brukernavn" required>
        
        <label for="fornavn">Fornavn:</label>
        <input type="text" name="fornavn" id="fornavn" required>
        
        <label for="etternavn">Etternavn:</label>
        <input type="text" name="etternavn" id="etternavn" required>
        
        <label for="klassekode">Klasse:</label>
        <select name="klassekode" id="klassekode" required>
            <option value="">Velg en klasse</option>
            <?php
            if ($klasse_result) {
                $klasse_result->data_seek(0);
                while ($klasse = $klasse_result->fetch_assoc()):
            ?>
                <option value="<?= htmlspecialchars($klasse['klasse_kode'] ?? '') ?>">
                    <?= htmlspecialchars($klasse['klasse_navn'] ?? '') ?>
                </option>
            <?php
                endwhile;
            }
            ?>
        </select>
        
        <button type="submit" name="add_student">Legg til student</button>
    </form>

    <hr>

    <!-- Liste over alle studenter -->
    <h2>Eksisterende studenter</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Brukernavn</th>
            <th>Fornavn</th>
            <th>Etternavn</th>
            <th>Klasse</th>
            <th>Handling</th>
        </tr>
        <?php if ($student_result && $student_result->num_rows > 0): ?>
            <?php
            $student_result->data_seek(0);
            while ($student = $student_result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($student['student_id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($student['brukernavn'] ?? '') ?></td>
                    <td><?= htmlspecialchars($student['fornavn'] ?? '') ?></td>
                    <td><?= htmlspecialchars($student['etternavn'] ?? '') ?></td>
                    <td><?= htmlspecialchars($student['klasse_navn'] ?? 'Ingen klasse') ?></td>
                    <td>
                        <a href="?delete_student=<?= urlencode($student['student_id'] ?? '') ?>"
                           onclick="return confirm('Er du sikker på at du vil slette denne studenten?')">Slett</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Ingen studenter funnet.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
