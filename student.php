
<?php
include("db.php");

$melding = "";

if (isset($_POST["registrerStudentKnapp"]))
{
    $brukernavn = mysqli_real_escape_string($db, $_POST["brukernavn"]);
    $fornavn = mysqli_real_escape_string($db, $_POST["fornavn"]);
    $etternavn = mysqli_real_escape_string($db, $_POST["etternavn"]);
    $klassekode = mysqli_real_escape_string($db, $_POST["klassekode"]);

    $sqlSetning = "INSERT INTO student VALUES('$brukernavn', '$fornavn', '$etternavn', '$klassekode')";

    try
    {
        mysqli_query($db, $sqlSetning);
        $melding = "Studenten er registrert!";
    }
    catch (mysqli_sql_exception $e)
    {
        if ($e->getCode() == 1062)
        {
            $melding = "Det er ikke mulig å registrere studenten. Brukernavnet finnes allerede! Velg et annet brukernavn.";
        }
        else
        {
            $melding = "Det oppstod en feil ved registrering av studenten. Kontroller brukernavn og klasse.";
        }
    }
}

if (isset($_POST["slettStudentKnapp"]))
{
    $brukernavn = mysqli_real_escape_string($db, $_POST["brukernavn"]);

    $sqlSetning = "DELETE FROM student WHERE brukernavn='$brukernavn'";

    try
    {
        mysqli_query($db, $sqlSetning);
        $melding = "Studenten er slettet!";
    }
    catch (mysqli_sql_exception $e)
    {
        $melding = "Det oppstod en feil ved sletting av studenten. Prøv igjen.";
    }
}

// Viser popup-melding dersom noe har skjedd
if ($melding != "")
{
    echo "<script>alert(" . json_encode($melding) . ");</script>";
}

$klasseResultat = mysqli_query($db, "SELECT * FROM klasse")
    or die("Ikke mulig å hente klasser");

$studentResultat = mysqli_query($db, "SELECT * FROM student")
    or die("Ikke mulig å hente studenter");
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Administrer studenter</title>
    <style>
        body {background-color: #c2dbb8;}
    </style>
</head>
<body>
    <h1>Administrer studenter</h1>
    <p><a href="index.html">Til forsiden</a> | <a href="klasse.php">Administrer klasser</a></p>

    <h2>Registrer student</h2>
    <form method="post">
        <p>Brukernavn: <input type="text" name="brukernavn" required></p>
        <p>Fornavn: <input type="text" name="fornavn" required></p>
        <p>Etternavn: <input type="text" name="etternavn" required></p>
        <p>
            Klasse:
            <select name="klassekode" required>
                <?php while ($klasse = mysqli_fetch_array($klasseResultat)) { ?>
                    <option value="<?php print(htmlspecialchars($klasse["klassekode"])); ?>">
                        <?php print(htmlspecialchars($klasse["klassekode"] . " - " . $klasse["klassenavn"])); ?>
                    </option>
                <?php } ?>
            </select>
        </p>
        <p><input type="submit" name="registrerStudentKnapp" value="Registrer student"></p>
    </form>

    <h2>Alle studenter</h2>
    <table border="1">
        <tr><th>Brukernavn</th><th>Fornavn</th><th>Etternavn</th><th>Klassekode</th><th></th></tr>
        <?php while ($rad = mysqli_fetch_array($studentResultat)) { ?>
            <tr>
                <td><?php print(htmlspecialchars($rad["brukernavn"])); ?></td>
                <td><?php print(htmlspecialchars($rad["fornavn"])); ?></td>
                <td><?php print(htmlspecialchars($rad["etternavn"])); ?></td>
                <td><?php print(htmlspecialchars($rad["klassekode"])); ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Er du sikker på at du vil slette denne studenten?');">
                        <input type="hidden" name="brukernavn" value="<?php print(htmlspecialchars($rad["brukernavn"])); ?>">
                        <input type="submit" name="slettStudentKnapp" value="Slett">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>