<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Obtener último valor
$ultimo = $conexion->query("SELECT * FROM temperaturas ORDER BY fecha DESC LIMIT 1")->fetch_assoc();

// Obtener umbrales
$umbrales = [];
$res = $conexion->query("SELECT * FROM umbrales");
while ($row = $res->fetch_assoc()) {
    $umbrales[$row['tipo']] = $row;
}

// Validar si los valores están dentro de rango
function evaluar_estado($valor, $min, $max) {
    if ($valor < $min || $valor > $max) {
        return 'alerta-roja';
    }
    return 'alerta-ok';
}

// Obtener últimos 10 registros
$resultado = $conexion->query("SELECT * FROM temperaturas ORDER BY fecha DESC LIMIT 10");


// 📧 Si hay algún valor en alerta, enviar correo
$alertaTemp = ($ultimo['valor'] < $umbrales['temperatura']['valor_min'] || $ultimo['valor'] > $umbrales['temperatura']['valor_max']);
$alertaHum = ($ultimo['humedad'] < $umbrales['humedad']['valor_min'] || $ultimo['humedad'] > $umbrales['humedad']['valor_max']);

if ($alertaTemp || $alertaHum) {
    $asunto = "⚠️ ALERTA desde tu sistema ESP32";
    $mensaje = "Se detectó una lectura fuera de rango:\n\n";
    if ($alertaTemp) {
        $mensaje .= "Temperatura: " . $ultimo['valor'] . " °C (Umbral: " . $umbrales['temperatura']['valor_min'] . " - " . $umbrales['temperatura']['valor_max'] . ")\n";
    }
    if ($alertaHum) {
        $mensaje .= "Humedad: " . $ultimo['humedad'] . " % (Umbral: " . $umbrales['humedad']['valor_min'] . " - " . $umbrales['humedad']['valor_max'] . ")\n";
    }
    $mensaje .= "\nFecha: " . $ultimo['fecha'];

    $destinatario = "lcolindresj1@miumg.edu.gt"; // <-- CAMBIA ESTO

    // Enviar correo
    @mail($destinatario, $asunto, $mensaje);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>ESP32 Dashboard</title>
    <meta http-equiv="refresh" content="60">
  <link rel="stylesheet" href="style.css">
  <style>
    .alerta-ok {
      color: green;
      font-weight: bold;
    }
    .alerta-roja {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>

<h2>Bienvenido, <?= $_SESSION['usuario'] ?></h2>
<a href="logout.php">Cerrar sesión</a> |
<a href="reporte.php">Reporte histórico</a> |
<a href="configurar_umbrales.php">Configurar umbrales</a>

<hr>

<h3>📡 Última lectura capturada:</h3>

<?php if ($ultimo): ?>
  <p><strong>Temperatura:</strong>
    <span class="<?= evaluar_estado($ultimo['valor'], $umbrales['temperatura']['valor_min'], $umbrales['temperatura']['valor_max']) ?>">
      <?= $ultimo['valor'] ?> °C
    </span>
  </p>

  <p><strong>Humedad:</strong>
    <span class="<?= evaluar_estado($ultimo['humedad'], $umbrales['humedad']['valor_min'], $umbrales['humedad']['valor_max']) ?>">
      <?= $ultimo['humedad'] ?> %
    </span>
  </p>

  <p><strong>Fecha:</strong> <?= $ultimo['fecha'] ?></p>
<?php else: ?>
  <p>No hay datos disponibles todavía.</p>
<?php endif; ?>

<hr>

<h3>📈 Últimos 10 registros</h3>
<table border="1">
  <tr>
    <th>Temperatura (°C)</th>
    <th>Humedad (%)</th>
    <th>Fecha</th>
  </tr>
  <?php if ($resultado && $resultado->num_rows > 0): ?>
    <?php while($row = $resultado->fetch_assoc()): ?>
      <tr>
        <td><?= $row['valor'] ?></td>
        <td><?= $row['humedad'] ?></td>
        <td><?= $row['fecha'] ?></td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr>
      <td colspan="3">No hay registros en la base de datos.</td>
    </tr>
  <?php endif; ?>
</table>

</body>
</html>
