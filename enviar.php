<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $temp = $_POST['temperatura'] ?? null;
    $hum = $_POST['humedad'] ?? null;

    if (is_numeric($temp) && is_numeric($hum)) {
        // Guardar en base de datos
        $stmt = $conexion->prepare("INSERT INTO temperaturas (valor, humedad) VALUES (?, ?)");
        $stmt->bind_param("dd", $temp, $hum);
        $stmt->execute();

        // Verificar umbrales
        $alertas = [];

        // Temperatura
        $resT = $conexion->query("SELECT * FROM umbrales WHERE tipo = 'temperatura' LIMIT 1");
        if ($row = $resT->fetch_assoc()) {
            if ($temp < $row['valor_min'] || $temp > $row['valor_max']) {
                $alertas[] = "⚠️ Alerta de TEMPERATURA: $temp °C";
            }
        }

        // Humedad
        $resH = $conexion->query("SELECT * FROM umbrales WHERE tipo = 'humedad' LIMIT 1");
        if ($row = $resH->fetch_assoc()) {
            if ($hum < $row['valor_min'] || $hum > $row['valor_max']) {
                $alertas[] = "⚠️ Alerta de HUMEDAD: $hum %";
            }
        }

        echo empty($alertas) ? "OK" : implode("\n", $alertas);
    } else {
        echo "Datos inválidos";
    }
}
?>
