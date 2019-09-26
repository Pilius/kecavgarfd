
<html>
<body>
<br>
<br>Grižti į pagrindi puslapi: 
<a href="index.html">index</a>
<meta http-equiv="Refresh" content="60">

<?php
require 'prisijungimas.php';

$specialistas = "";
$kiekis       = 0;

echo "<h2>svieslente</h2>";
echo "Rodoma laukianciuju eilė<br>
    Filtuojama pagal pasirinktą specialistą<br>
    Atvaizduoajas ribotas kiekis įrašų<br><br>";





//Lentelės braižymas
echo '<table border="1" cellspacing="2" cellpadding="2"> 
      <tr> 
          <td> <font face="Arial">Eiles Nr.</font> </td> 
          <td> <font face="Arial">Pasirinktas Spec.</font> </td> 
          <td> <font face="Arial">Kliento vardas</font> </td> 
          <td> <font face="Arial">Liko laukti</font> </td> 
      </tr>';
//lenteles duomenų gavimas

$query = "SELECT * FROM klientai WHERE aptarnautas='0'";
if (isset($_GET['filtruotiButt'])) {
    //Filtruojama pagal pasirinktą specialistą
    $specialistas = $_GET["specForm"];
    if (strcmp($specialistas, "visi") != 0)
        $query .= " AND specialistas='$specialistas'";
    
    //Filtruojama pagal pasirinktą įrašų kiekį
    $kiekis = $_GET["rodytiForm"];
    if ($kiekis > 0)
        $query .= " LIMIT $kiekis";
}
$result = mysqli_query($connection, $query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr> 
                  <td>' . $row["id"] . '</td> 
                  <td>' . $row["specialistas"] . '</td> 
                  <td>' . $row["vardas"] . '</td> 
                  <td>' . $row["liko_laukti"] . '</td> 
              </tr>';
    }
    $result->free();
} else {
    echo "DB klaida";
}
?>



<form method="get" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">  

  <select name="specForm">
    <option value="visi">Visi specialistai</option>
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
  &nbsp&nbsp
  <select name="rodytiForm">
    <option <?php
if ($kiekis == 0)
    echo 'selected';
?> value=0>Rodyti visus</option>
    <option <?php
if ($kiekis == 3)
    echo 'selected';
?> value=3>Rodyti pirmus 3</option>
    <option <?php
if ($kiekis == 5)
    echo 'selected';
?>  value=5>Rodyti pirmus 5</option>
  </select>
    &nbsp&nbsp
  <input type="submit" name="filtruotiButt" value="Filtruoti">  
</form>



</body>
</html>

