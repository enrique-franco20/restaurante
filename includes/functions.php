<?php
require_once __DIR__ . '/../config/database.php';

/**
 * @param string 
 * @return UTCDateTime|null
 */
function convertirFechaHora($fecha_hora) {
    try {
        $fecha = new DateTime($fecha_hora);
        return new MongoDB\BSON\UTCDateTime($fecha->getTimestamp() * 1000);
    } catch (Exception $e) {
        return null; 
    }
}


/**
 * @param UTCDateTime|string 
 * @return string 
 */
function formatDate($fecha) {
    try {
        if ($fecha instanceof MongoDB\BSON\UTCDateTime) {

            $dateTime = $fecha->toDateTime();
        } elseif (is_string($fecha)) {
        
            $dateTime = new DateTime($fecha);
        } else {
            throw new Exception('Formato de fecha no válido');
        }

        return $dateTime->format('d/m/Y H:i');
    } catch (Exception $e) {
        return 'Fecha inválida';
    }
}

/**
 * @param string 
 * @return ObjectId|null
 */
function convertirObjectId($id) {
    try {
        return new MongoDB\BSON\ObjectId($id);
    } catch (Exception $e) {
        return null; 
    }
}


function obtenerReservas() {
    global $db; 
    $coleccion = $db->reservas;
    return $coleccion->find([], ['sort' => ['fecha_hora' => 1]])->toArray();
}


function agregarReserva($nombre, $fecha_hora, $num_comensales) {
    global $db;
    $coleccion = $db->reservas;

    $fecha_hora_mongo = convertirFechaHora($fecha_hora);
    if (!$fecha_hora_mongo) {
        return false; 
    }

    $documento = [
        'nombre' => $nombre,
        'fecha_hora' => $fecha_hora_mongo,
        'num_comensales' => (int)$num_comensales,
        'cancelada' => false
    ];

    $resultado = $coleccion->insertOne($documento);
    return $resultado->getInsertedCount() > 0;
}

function eliminarReserva($id) {
    global $db;
    $coleccion = $db->reservas;

    $id_mongo = convertirObjectId($id);
    if (!$id_mongo) {
        return false; 
    }

    $resultado = $coleccion->deleteOne(['_id' => $id_mongo]);
    return $resultado->getDeletedCount() > 0;
}

function toggleReservaCancelada($id) {
    global $db;
    $coleccion = $db->reservas;

    $id_mongo = convertirObjectId($id);
    if (!$id_mongo) {
        return null;
    }

    $reserva = $coleccion->findOne(['_id' => $id_mongo]);
    if (!$reserva) return null;

    $nuevoEstado = !$reserva['cancelada'];
    $resultado = $coleccion->updateOne(
        ['_id' => $id_mongo],
        ['$set' => ['cancelada' => $nuevoEstado]]
    );

    return $resultado->getModifiedCount() > 0 ? $nuevoEstado : null;
}


function obtenerReservaPorId($id) {
    global $db;
    $coleccion = $db->reservas;

    $id_mongo = convertirObjectId($id);
    if (!$id_mongo) {
        return null;
    }

    return $coleccion->findOne(['_id' => $id_mongo]);
}


function actualizarReserva($id, $nombre, $fecha_hora, $num_comensales) {
    global $db;
    $coleccion = $db->reservas;

    $id_mongo = convertirObjectId($id);
    if (!$id_mongo) {
        return false; 
    }

    $fecha_hora_mongo = convertirFechaHora($fecha_hora);
    if (!$fecha_hora_mongo) {
        return false; 
    }

    $resultado = $coleccion->updateOne(
        ['_id' => $id_mongo],
        [
            '$set' => [
                'nombre' => $nombre,
                'fecha_hora' => $fecha_hora_mongo,
                'num_comensales' => (int)$num_comensales
            ]
        ]
    );

    return $resultado->getModifiedCount() > 0;
}
