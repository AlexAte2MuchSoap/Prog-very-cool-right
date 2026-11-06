<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_class'])) {
        $klasse_kode = $_POST['klasse_kode'];
        $klasse_navn = $_POST['klasse_navn'];

        $sql = "INSERT INTO KLASSE (klasse_kode, klasse_navn) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $klasse_kode, $klasse_navn);

        if ($stmt->execute()) {
            echo "Klasse lagt til!";
        } else {
            echo "Feil: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrer Klasser</title>
</head>
<body>
    <h1>Legg til ny klasse</h1>
    <form method="POST">
        <label for="klasse_kode">Klasse Kode:</label>
        <input type="text" name="klasse_kode" required><br>

        <label for="klasse_navn">Klasse Navn:</label>
        <input type="text" name="klasse_navn" required><br>

        <button type="submit" name="add_class">Legg til klasse</button>
    </form>
</body>
</html>