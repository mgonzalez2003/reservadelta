<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conn = new mysqli("localhost", "server", "server1234", "reserva_ceibal");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Obtener los datos enviados por AJAX
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos']);
    exit;
}

// Inicializar una variable para el éxito
$allSuccess = true;
$errores = [];

// Procesar cada cambio
foreach ($data as $idCelda => $nuevoValor) {
    list($hora, $laptopNum) = explode('-', $idCelda);
    $laptopColumna = 'laptop' . $laptopNum;

    // Obtener la fecha desde la URL
    $date = $_GET['fecha'];

    // Actualizar la base de datos
    $sql = "UPDATE `$date` SET `$laptopColumna` = ? WHERE `hora` = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('si', $nuevoValor, $hora);
        if (!$stmt->execute()) {
            $allSuccess = false;
            $errores[] = "Error en la celda $idCelda: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $allSuccess = false;
        $errores[] = "Error preparando la consulta para la celda $idCelda: " . $conn->error;
    }
}

$conn->close();

if ($allSuccess) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
}
