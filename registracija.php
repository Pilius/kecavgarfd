<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  
<br>Grižti į pagrindi puslapi: 
<a href="index.html">index</a>
<br>

<?php

require 'prisijungimas.php';

$vardasErr = $pavardeErr = $specialistas = "";
$vardas    = $pavarde = $specialistasErr = $naujas_pin_id = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["vardas"])) {
        $vardasErr = "Būtina įvesti vardą";
    } else {
        $vardas = test_input($_POST["vardas"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $vardas)) {
            $vardasErr = "Vardą turi sudaryti tik raidės";
        }
    }
    
    if (empty($_POST["pavarde"])) {
        $pavardeErr = "Būtina įvesti pavardę";
    } else {
        $pavarde = test_input($_POST["pavarde"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $pavarde)) {
            $pavardeErr = "Pavardę turi sudaryti tik raidės";
        }
    }
    
    if (strcmp($_POST["specForm"], "rinktis") == 0) {
        $specialistasErr = "Būtina pasirinkti specialistą";
    } else {
        $specialistas = $_POST["specForm"];
    }
    
    //mygtukas
    if (isset($_POST['registruotisButt'])) {
        
        $query_numeriai  = "SELECT pin_id FROM klientai";
        $result_numeriai = mysqli_query($connection, $query_numeriai);
        $row_numeriai    = $result_numeriai->fetch_assoc();
        
        
        if ($result_numeriai) {
            $unikalus = false;
            while (!$unikalus) {
                $naujas_pin_id = mt_rand(101, 999);
                while ($row = $result_numeriai->fetch_assoc()) {
                    if ($naujas_pin_id == $row["pin_id"]) {
                        $unikalus = false;
                        break;
                    } else {
                        $unikalus = true;
                    }
                }
            }
        } else {
            echo "DB klaida nuskaitand klientu pin_id";
        }
        
        
        $query = "INSERT INTO klientai (vardas, pavarde, specialistas, pin_id)
                            VALUES('$vardas', '$pavarde', '$specialistas', $naujas_pin_id)";
        
        if (mysqli_query($connection, $query) and $result_numeriai) {
            echo "<h3><font color='green'>Užregistruota sėkmingai</font></h3>";
        } else {
            echo "<h3><font color='red'>Įvyko klaida, kreipkitės telefonu</font></h3>";
        }
        
    }
    
    
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>Naujo kliento registracija</h2>
<p><span class="error">* būtini laukeliai</span></p>
<form method="post" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">  
Vardas: <input type="text" name="vardas" value="<?php
echo $vardas;
?>">
<span class="error">* <?php
echo $vardasErr;
?></span>
<br><br>
Pavardė: <input type="text" name="pavarde" value="<?php
echo $pavarde;
?>">
<span class="error">* <?php
echo $pavardeErr;
?></span>
<br><br>

Prašome pasirinkt specialistą:        
<br>
<select name="specForm">
<option value="rinktis">*Rinktis*</option>
<option <?php
if ($specialistas == "Aspecialistas")
    echo 'selected';
?> value="Aspecialistas">Aspecialistas</option>
<option <?php
if ($specialistas == "Bspecialistas")
    echo 'selected';
?>  value="Bspecialistas">Bspecialistas</option>
<option <?php
if ($specialistas == "Cspecialistas")
    echo 'selected';
?>  value="Cspecialistas">Cspecialistas</option>
<option <?php
if ($specialistas == "Dspecialistas")
    echo 'selected';
?>  value="Dspecialistas">Dspecialistas</option>
</select>
<span class="error">* <?php
echo $specialistasErr;
?></span>

<br><br>
<input type="submit" name="registruotisButt" value="Registruotis">  
</form>

<?php




echo "<h2>Jūsų duomenys:</h2>";
echo "Ivestas vardas            : " . $vardas . "<br>";
echo "Ivesta pavarde            : " . $pavarde . "<br>";
echo "Pasirinktas specialistas    : " . $specialistas . "<br>";
echo "UNIKALUS lankytojo PIN kodas    : " . $naujas_pin_id . "<br>";


?>
