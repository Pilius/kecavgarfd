
<html>
<body>
<br>
<br>Grižti į pagrindi puslapi: 
<a href="index.html">index</a><br><br>

Gali buti keli tos pacios srities specialistai(kirpejai)<br>
INNER JOIN sujungiamos lenteles pagal "_specialistas" ir pateikiama darbo su kiekvienu klientu trukme<br>

<br>
<br>


<?php
require 'prisijungimas.php';

$profesija = "";

echo "<h2>Profesiju ataskaita</h2>";

//Lentelės braižymas
echo '<table border="1" cellspacing="2" cellpadding="2"> 
      <tr> 
          <td> <font face="Arial">Specialisto vardas</font> </td> 
          <td> <font face="Arial">Kliento ID</font> </td> 
          <td> <font face="Arial">Kliento pavarde</font> </td> 
          <td> <font face="Arial">Kliento vardas</font> </td>  
          <td> <font face="Arial">Aptarnavimo trukme</font> </td>  
          
      </tr>';
//lenteles duomenų gavimas


if (isset($_GET['ataskaitaButt'])) {
    
    $profesija = $_GET["profForm"];
    
    $query = "SELECT darbuotojas, klientai.id, vardas, pavarde, trukme 
FROM darbuotojai
INNER JOIN klientai
ON darbuotojas = specialistas 
WHERE profesija = '$profesija'
ORDER BY pavarde, vardas";
    
    
    //$query = "SELECT  FROM darbuotojai WHERE aptarnautas='0'";
    
    $result = mysqli_query($connection, $query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr> 
                      <td>' . $row["darbuotojas"] . '</td> 
                      <td>' . $row["id"] . '</td> 
                      <td>' . $row["pavarde"] . '</td> 
                      <td>' . $row["vardas"] . '</td> 
                      <td>' . $row["trukme"] . '</td> 
                  </tr>';
        }
        $result->free();
    } else {
        echo "DB klaida";
    }
    
}
?>



<form method="get" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">  

  <select name="profForm">
    <option <?php
if ($profesija == "kirpejas")
    echo 'selected';
?> value="kirpejas">kirpejas</option>
    <option <?php
if ($profesija == "siuvejas")
    echo 'selected';
?>  value="siuvejas">siuvejas</option>
    <option <?php
if ($profesija == "pjovejas")
    echo 'selected';
?>  value="pjovejas">pjovejas</option>
    <option <?php
if ($profesija == "nesejas")
    echo 'selected';
?>  value="nesejas">nesejas</option>
  </select>

  <br>
  <input type="submit" name="ataskaitaButt" value="ataskaita">  
</form>



</body>
</html>
 
