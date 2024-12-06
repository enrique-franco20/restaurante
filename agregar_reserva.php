<?php
require_once __DIR__ . '/includes/functions.php';

// Manejar el envío del formulario de reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $fecha_hora = $_POST['fecha_hora'];
    $num_comensales = $_POST['num_comensales'];

    $resultado = agregarReserva($nombre, $fecha_hora, $num_comensales);

    if ($resultado) {
        $mensaje = "Reserva agregada con éxito.";
    } else {
        $mensaje = "No se pudo agregar la reserva.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Reserva</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body style="background-image: url('img/fondo.avif'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="container">
        <center><h1>Agregar Reserva</h1></center>

        <?php if (isset($mensaje)): ?>
            <div class="<?php echo strpos($mensaje, 'éxito') !== false ? 'success' : 'error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="agregar_reserva.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="fecha_hora">Fecha y Hora:</label>
            <input type="datetime-local" id="fecha_hora" name="fecha_hora" required>

            <label for="num_comensales">Número de Comensales:</label>
            <input type="number" id="num_comensales" name="num_comensales" min="1" required>

            <button type="submit" class="button">Agregar Reserva</button>
            <a href="index.php" class="button">Volver</a>
        </form>
    </div>
</body>
</html>
