<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['temperatura', 'humedad'] as $tipo) {
        $min = $_POST[$tipo.'_min'];
        $max = $_POST[$tipo.'_max'];
        $conexion->query("UPDATE umbrales SET valor_min=$min, valor_max=$max WHERE tipo='$tipo'");
    }
    echo "Umbrales actualizados.";
}

$umbrales = [];
$res = $conexion->query("SELECT * FROM umbrales");
while ($row = $res->fetch_assoc()) {
    $umbrales[$row['tipo']] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title></title>
</head>
<body>
<form method="POST">
  <h3>Temperatura</h3>
  Mín: <input type="number" step="0.1" name="temperatura_min" value="<?= $umbrales['temperatura']['valor_min'] ?>">
  Máx: <input type="number" step="0.1" name="temperatura_max" value="<?= $umbrales['temperatura']['valor_max'] ?>">

  <h3>Humedad</h3>
  Mín: <input type="number" step="0.1" name="humedad_min" value="<?= $umbrales['humedad']['valor_min'] ?>">
  Máx: <input type="number" step="0.1" name="humedad_max" value="<?= $umbrales['humedad']['valor_max'] ?>">

  <br><br>
  <input type="submit" value="Guardar Umbrales">
</form>
<a href="index.php">Regresar a Inicio.</a> |
</body>
</html>

