<?php session_start(); ?>
<!DOCTYPE html >
<html>
<br>Grižti į pagrindi puslapi:
<a href="index.html">index</a>
<br>
Prisijungimu:<br>
vardai "Aspecialistas"..."Dspecialistas"<br>
slaptazodis "a123"..."d123"<br>

<body id="body_bg">

<?php
global $username;
$username = "";
?>
<h3>Specialisto prisijungimas</h3>
    <form id="login-form" method="post" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">
        <table border="3.5" >
            <tr>
                <td><label for="user_id">User Name</label></td>
                <td><input type="text" name="user_id" id="user_id"</td>
                
            </tr>    
            <tr>
                <td><label for="user_pass">Password</label></td>
                <td><input type="password" name="user_pass" id="user_pass"></input></td>
            </tr>
            
            <tr>
                <td><input type="submit" name="prisijungtiButt" value="Prisijungti" />
                <td><input type="submit" name="atsijungtiButt" value="Atsijungti" />
            </tr>
        </table>
    </form>
        </div>
</body>
</html>

<?php

require 'prisijungimas.php';

if (isset($_SESSION["prisijungimo_vardas"])) {
    echo "<h4><font color='green'>Prisijungta kaip: " . $_SESSION["prisijungimo_vardas"] . "<br></font></h4>";
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

function laiko_suma($t_suma, $trukme)
{
    $nauja_suma = 0;
    $nauja_suma += substr($t_suma, 0, 2) * 3600 + substr($t_suma, 3, 2) * 60 + substr($t_suma, 6, 2);
    $nauja_suma += substr($trukme, 0, 2) * 3600 + substr($trukme, 3, 2) * 60 + substr($trukme, 6, 2);
    return laiko_formatavimas($nauja_suma);
}

function laiko_vidurkis($t, $n)
{
    $suma = 0;
    $suma += substr($t, 0, 2) * 3600 + substr($t, 3, 2) * 60 + substr($t, 6, 2);
    $vidurkis = intdiv($suma, $n);
    return laiko_formatavimas($vidurkis);
}

function laiko_kiekis($t, $n)
{
    $suma = 0;
    $suma += $n * (substr($t, 0, 2) * 3600 + substr($t, 3, 2) * 60 + substr($t, 6, 2));
    return laiko_formatavimas($suma);
}
function liko_laukti($specialistas, $connection)
{
    
    $kliento_id     = array();
    $kliento_laikas = array();
    
    $query_skaityti = "SELECT t_vid, klientai.id 
        FROM darbuotojai
        INNER JOIN klientai
        ON darbuotojas = specialistas 
        WHERE specialistas = '$specialistas' AND aptarnautas = '0'";
    
    
    $result_skaityti = mysqli_query($connection, $query_skaityti);
    if ($result_skaityti) {
        $k = 0;
        while ($row_skaityti = $result_skaityti->fetch_assoc()) {
            $kliento_id[$k]     = $row_skaityti["id"];
            $kliento_laikas[$k] = laiko_kiekis($row_skaityti["t_vid"], $k + 1);
            $k++;
        }
        
        $sekmingai        = 0;
        $numatomas_laikas = "";
        for ($i = 0; $i < $k; $i++) {
            $numatomas_laikas = "";
            $numatomas_laikas = laiko_suma($kliento_laikas[$i], date("H:i:s"));
            $query            = "UPDATE klientai SET liko_laukti ='$kliento_laikas[$i]', numatomas_laikas='$numatomas_laikas' WHERE id = $kliento_id[$i]";
            $result           = mysqli_query($connection, $query);
            if ($result)
                $sekmingai++;
        }
        
        if ($sekmingai == $k) {
        } else {
            echo " [Liko_laukti] atnaujinti NEVISI laikai: " . $sekmingai . " (vnt)<br>";
        }
    } else {
        echo "[Liko_laukti] Nepavyko nuskaityti duomenų";
    }
    
}


if (isset($_POST['atsijungtiButt']))
    session_unset();
if (isset($_POST['prisijungtiButt']) and isset($_POST['user_id']) and isset($_POST['user_pass'])) {
    // Assigning POST values to variables.
    $username = $_POST['user_id'];
    $password = $_POST['user_pass'];
    
    // CHECK FOR THE RECORD FROM TABLE
    $query = "SELECT * FROM darbuotojai WHERE darbuotojas='$username' and slaptazodis='$password'";
    
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    $count = mysqli_num_rows($result);
    
    if ($count == 1) {
        //išsaugoti prisijungimo vardą:
        $_SESSION["prisijungimo_vardas"] = $username;
        echo "Prisijungta vardu: " . $_SESSION["prisijungimo_vardas"] . "<br>";
    } else {
        echo "Neteisingas prisijungimo vardas";
        session_unset();
        session_destroy();
    }
}

// Priimti ir paleisti mygtukai
if (isset($_SESSION["prisijungimo_vardas"])) {
    echo '<form id="darbuotojo_skiltis" method="post" action="specialisto_psl.php">
        <input type="submit" name="priimti" value="Priimti klientą" />
         &nbsp &nbsp
        <input type="submit" name="baigti" value="Paleisti klientą" />
        <br>';
}

//Priimti mygtukas
if (isset($_POST['priimti'])) {
    $username = $_SESSION["prisijungimo_vardas"];
    
    //Patikrinti ar specialistas neturi priskirto kliento
    $query_patikrinti  = "SELECT * FROM darbuotojai WHERE darbuotojas='$username'";
    $result_patikrinti = mysqli_query($connection, $query_patikrinti);
    $row_patikrinti    = $result_patikrinti->fetch_assoc();
    $esamo_kliento_id  = $row_patikrinti['kliento_id'];
    if ($esamo_kliento_id == 0) {
        //imamas  laisvas klientas is duomenų bazes
        $query_klientas  = "SELECT * FROM klientai WHERE aptarnautas='0' AND specialistas='$username' LIMIT 1 ";
        $result_klientas = mysqli_query($connection, $query_klientas);
        $row_klientas    = $result_klientas->fetch_assoc();
        
        //patikrinama ar specialistui yra laisvų klientų
        if (strlen($row_klientas['vardas']) > 0) { //Yra laisvas klientas DB
            //Klientui priskiriamas darbo pradžios laikas ir jo ID įrašomas į darbuotojo kortelę
            $naujo_kliento_id = $row_klientas['id'];
            $pradzios_laikas  = date("H:i:s"); // 09:08:07
            $pradzios_data    = date("Y-m-d"); //2019-09-22
            $query_pradzia    = "UPDATE darbuotojai, klientai SET darbuotojai.kliento_id ='$naujo_kliento_id', klientai.pradzia = '$pradzios_laikas', klientai.data = '$pradzios_data' WHERE darbuotojai.darbuotojas = '$username' and klientai.id = $naujo_kliento_id";
            $result_pradzia   = mysqli_query($connection, $query_pradzia);
            
            if ($result_pradzia) {
                echo "Bus dirbama su (" . $row_klientas['id'] . ") " . $row_klientas['vardas'] . " " . $row_klientas['pavarde'] . "<br>";
            } else {
                echo "Klaida, nepavyko priskirti naujo kliento pasirinktam darbuotojui<br>";
            }
        } else { //nera laisvas klientas DB
            echo "Nera laisvo kliento<br>";
        }
    } else {
        //Parodomas priskirto kliento ID ir vardas
        $query_klientas  = "SELECT * FROM klientai WHERE id='$esamo_kliento_id'";
        $result_klientas = mysqli_query($connection, $query_klientas);
        $row_klientas    = $result_klientas->fetch_assoc();
        echo "Šiuo metu dirbama su (" . $row_klientas['id'] . ") " . $row_klientas['vardas'] . " " . $row_klientas['pavarde'] . "<br>";
        $result_klientas->free();
    }
    $result_patikrinti->free();
    
}

//Baigti mygtukas
if (isset($_POST['baigti'])) {
    $pradzios_laikas   = "00:00:00";
    $username          = $_SESSION["prisijungimo_vardas"];
    //Patikrinti ar specialistas turi priskirtą klientą
    $query_patikrinti  = "SELECT * FROM darbuotojai WHERE darbuotojas='$username'";
    $result_patikrinti = mysqli_query($connection, $query_patikrinti);
    $row_patikrinti    = $result_patikrinti->fetch_assoc();
    $esamo_kliento_id  = $row_patikrinti['kliento_id'];
    if ($esamo_kliento_id > 0) { //Specialistas turi klientą
        $pabaigos_laikas = date("H:i:s");
        $query_laikas    = "SELECT pradzia FROM klientai WHERE id='$esamo_kliento_id'";
        $result_laikas   = mysqli_query($connection, $query_laikas);
        $row_laikas      = $result_laikas->fetch_assoc();
        $pradzios_laikas = $row_laikas['pradzia'];
        
        //Apsilnkymo trukmės ir vidutinio laiko skaičiavimas
        $t_kiekis_naujas = $row_patikrinti['t_kiekis'] + 1;
        $trukme          = laiko_formatavimas((strtotime($pabaigos_laikas) - strtotime($pradzios_laikas))); //Paskutinio kliento trukme
        $t_suma_nauja    = laiko_suma($row_patikrinti['t_suma'], $trukme); //Trukme pridedama prie darbuotojo laiko sumos
        $t_vid_naujas    = laiko_vidurkis($t_suma_nauja, $t_kiekis_naujas); //apskaičiuojamas naujas vidurkis
        $query_pabaigti  = "UPDATE darbuotojai, klientai 
                            SET darbuotojai.kliento_id ='0', darbuotojai.t_vid='$t_vid_naujas', darbuotojai.t_suma='$t_suma_nauja', darbuotojai.t_kiekis ='$t_kiekis_naujas',
                            klientai.pabaiga = '$pabaigos_laikas', klientai.trukme = '$trukme', klientai.aptarnautas = '1' 
                            WHERE darbuotojai.darbuotojas = '$username' and klientai.id = $esamo_kliento_id";
        if (mysqli_query($connection, $query_pabaigti)) {
            echo "<br> Užbaigtas darbas su (" . $esamo_kliento_id . ") klientu. Trukme: " . $trukme . "<br>";
            liko_laukti($username, $connection);
        } else {
            echo "Error updating record: " . mysqli_error($connection);
        }
        $result_laikas->free();
    }
    
    $result_patikrinti->free();
    
    
}


?>
