<?php
include 'db.php';
$resultado = $conexion->query("SELECT * FROM temperaturas ORDER BY fecha DESC LIMIT 100");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" type="text/css" href="style.css">
   <a href="index.php">Regresar a Inicio.</a> |
  <title></title>
</head>
<body>

<h2>Reporte Histórico</h2>
<table border="1">
<tr><th>Fecha</th><th>Temperatura (°C)</th><th>Humedad (%)</th></tr>
<?php while ($row = $resultado->fetch_assoc()): ?>
<tr>
  <td><?= $row['fecha'] ?></td>
  <td><?= $row['valor'] ?></td>
  <td><?= $row['humedad'] ?></td>
</tr>
<?php endwhile; ?>
</table>


</body>
</html>
