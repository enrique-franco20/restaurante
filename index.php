<?php
require_once __DIR__ . '/includes/functions.php';

if (isset($_GET['accion']) && isset($_GET['id'])) {
    switch ($_GET['accion']) {
        case 'eliminar':
            $count = eliminarReserva($_GET['id']);
            $mensaje = $count > 0 ? "Reserva eliminada con éxito." : "No se pudo eliminar la reserva.";
            break;
        case 'toggleCancelada':
            $nuevoEstado = toggleReservaCancelada($_GET['id']);
            if ($nuevoEstado !== null) {
                $mensaje = $nuevoEstado ? "Reserva marcada como cancelada." : "Reserva marcada como no cancelada.";
            } else {
                $mensaje = "No se pudo cambiar el estado de la reserva.";
            }
            break;
    }
}

$reservas = obtenerReservas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas del Restaurante</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body style="background-image: url('img/fondo.avif'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="container">
        <center><h1>Gestión de Reservas del Restaurante</h1></center>

        <?php if (isset($mensaje)): ?>
            <div class="<?php echo strpos($mensaje, 'éxito') !== false ? 'success' : 'error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <a href="agregar_reserva.php" class="button">Nueva Reserva</a>

        <h2>Reservas Actuales</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Fecha y Hora</th>
                <th>Número de Comensales</th>
                <th>Cancelada</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($reservas as $reserva): ?>
            <tr>
                <td><?php echo htmlspecialchars($reserva['nombre']); ?></td>
                <td><?php echo formatDate($reserva['fecha_hora']); ?></td>
                <td><?php echo htmlspecialchars($reserva['num_comensales']); ?></td>
                <td>
                    <a href="index.php?accion=toggleCancelada&id=<?php echo $reserva['_id']; ?>"
                       class="button <?php echo $reserva['cancelada'] ? 'Cancelada' : 'no-Cancelada'; ?>">
                        <?php echo $reserva['cancelada'] ? 'Cancelada' : 'No Cancelada'; ?>
                    </a>
                </td>
                <td class="actions">
                    <a href="editar_reserva.php?id=<?php echo $reserva['_id']; ?>" class="button">Editar</a>
                    <a href="index.php?accion=eliminar&id=<?php echo $reserva['_id']; ?>" class="button"
                       onclick="return confirm('¿Estás seguro de que quieres eliminar esta reserva?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
