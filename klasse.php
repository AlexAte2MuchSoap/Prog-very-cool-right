<?php
include("db.php");

if (isset($_POST["registrerKlasseKnapp"]))
{
    $klassekode = mysqli_real_escape_string($db, $_POST["klassekode"]);
    $klassenavn = mysqli_real_escape_string($db, $_POST["klassenavn"]);
    $studiumkode = mysqli_real_escape_string($db, $_POST["studiumkode"]);

    $sqlSetning = "INSERT INTO klasse VALUES('$klassekode', '$klassenavn', '$studiumkode')";
    mysqli_query($db, $sqlSetning) or die("Ikke mulig å registrere klasse");
    print("Klassen er registrert");
}

if (isset($_POST["slettKlasseKnapp"]))
{
    $klassekode = mysqli_real_escape_string($db, $_POST["klassekode"]);
    $sqlSetning = "DELETE FROM klasse WHERE klassekode='$klassekode'";
    mysqli_query($db, $sqlSetning) or die("Ikke mulig å slette klasse. Sjekk om studenter tilhører klassen.");
    print("Klassen er slettet");
}

$sqlSetning = "SELECT * FROM klasse";
$sqlResultat = mysqli_query($db, $sqlSetning) or die("Ikke mulig å hente klasser");
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Administrer klasser</title>
    <style>
        body {background-color: #c2dbb8;}
    </style>
</head>
<body>
    <h1>Administrer klasser</h1>
    <p><a href="index.html">Til forsiden</a> | <a href="student.php">Administrer studenter</a></p>

    <h2>Registrer klasse</h2>
    <form method="post">
        <p>Klassekode: <input type="text" name="klassekode" required></p>
        <p>Klassenavn: <input type="text" name="klassenavn" required></p>
        <p>Studiumkode: <input type="text" name="studiumkode" required></p>
        <p><input type="submit" name="registrerKlasseKnapp" value="Registrer klasse"></p>
    </form>

    <h2>Alle klasser</h2>
    <table border="1">
        <tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th><th></th></tr>
        <?php while ($rad = mysqli_fetch_array($sqlResultat)) { ?>
            <tr>
                <td><?php print(htmlspecialchars($rad["klassekode"])); ?></td>
                <td><?php print(htmlspecialchars($rad["klassenavn"])); ?></td>
                <td><?php print(htmlspecialchars($rad["studiumkode"])); ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Er du sikker på at du vil slette denne klassen?');">
                        <input type="hidden" name="klassekode" value="<?php print(htmlspecialchars($rad["klassekode"])); ?>">
                        <input type="submit" name="slettKlasseKnapp" value="Slett">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>