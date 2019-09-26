
<html>

<body>

<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<br>
<br>Grižti į pagrindi puslapi: 
<a href="index.html">index</a><br><br>
Lankytojas iveda savo VARDA ir PIN koda<br>
Jei toks lankytojas uzsiregistravo, jam rodomas likes laukti laikas<br>
<br>
<br>


<?php
require 'prisijungimas.php';
$vardas    = $pin = "";
$vardasErr = $pinErr = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function laiko_formatavimas($sek)
{
    $val = intdiv($sek, (60 * 60));
    $sek -= $val * 60 * 60;
    $min = intdiv($sek, 60);
    $sek -= $min * 60;
    
    if ($val < 10)
        $val = "0" . $val;
    if ($min < 10)
        $min = "0" . $min;
    if ($sek < 10)
        $sek = "0" . $sek;
    
    return $val . ":" . $min . ":" . $sek;
}

echo '<table border="1" cellspacing="2" cellpadding="2"> 
                    <tr> 
                      <td> <font face="Arial">ID</font> </td> 
                      <td> <font face="Arial">Vardas</font> </td> 
                      <td> <font face="Arial">Pavarde</font> </td> 
                      <td> <font face="Arial">Pasirinktas specialistas</font> </td> 
                      <td> <font face="Arial">Numatytias laikas</font> </td> 
                      <td> <font face="Arial">Liko laukti</font> </td> 
                      
                  </tr>';


if (isset($_GET['stabdytiButt'])) {
    unset($vardas);
    unset($pin);
    echo "<meta http-equiv='Refresh' content='3600'>"; //refresh atidedamas kas valandą
}

if (isset($_GET['tikrintiButt'])) {
    if (empty($_GET["vardas"])) {
        $vardasErr = "Būtina įvesti vardą";
    } else {
        $vardas = test_input($_GET["vardas"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $vardas)) {
            $vardasErr = "Vardą turi sudaryti tik raidės";
        }
    }
    
    if (empty($_GET["pin"])) {
        $pinErr = "Būtina įvesti PIN kodą";
    } else {
        $pin = test_input($_GET["pin"]);
        if (!is_numeric($pin)) {
            $pinErr = "PIN kodą turi sudaryti skaičiai";
        }
    }
    
    if (strlen($vardas) > 0 and $pin > 0) {
        
        
        $query  = "SELECT * FROM klientai WHERE vardas='$vardas' and pin_id = $pin";
        $result = mysqli_query($connection, $query);
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row["id"] > 0) {
                
                if ((strtotime($row["numatomas_laikas"]) - strtotime(date("H:i:s"))) > 0) {
                    $liko_laukti = laiko_formatavimas((strtotime($row["numatomas_laikas"]) - strtotime(date("H:i:s"))));
                    echo "<meta http-equiv='Refresh' content='5'>";
                } else {
                    $liko_laukti = "jau aptarnauta";
                    echo "<meta http-equiv='Refresh' content='3600'>"; //refresh atidedamas kas valandą
                }
                echo "<tr> 
                      <td>" . $row["id"] . "</td> 
                      <td>" . $row["vardas"] . "</td> 
                      <td>" . $row["pavarde"] . "</td> 
                      <td>" . $row["specialistas"] . "</td> 
                      <td>" . $row["numatomas_laikas"] . "</td> 
                      <td><font color='green'>" . $liko_laukti . "</font/></td> 
                  </tr>";
            } else {
                $vardasErr = "Nėra užregistruota tokio kliento<br>";
                unset($vardas);
                unset($pin);
            }
        } else {
            echo "Nepavyko prisijungti prie duomenų bazes <br>";
        }
        
    }
}
?>


<h2>Likusio laukti laiko tikrinimo sistema</h2>
<form method="get" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">  
Vardas: <input type="text" name="vardas" >
<span class="error">* <?php
echo $vardasErr;
?></span><br>

PIN kodas ###: <input type="text" name="pin">
<span class="error">* <?php
echo $pinErr;
?></span><br>

<input type="submit" name="tikrintiButt" value="Tikrinti">  
<input type="submit" name="stabdytiButt" value="Stabdyti">  
</form>


</body>

</html>
