<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $clave = hash('sha256', $_POST['password']);

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$clave'";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        header("Location: index.php");
        exit;
    } else {
        echo "Credenciales incorrectas.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form method="POST">
  Usuario: <input type="text" name="usuario" required><br>
  Contraseña: <input type="password" name="password" required><br>
  <input type="submit" value="Iniciar sesión">
</form>

</body>
</html>
