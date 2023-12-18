<?php
define("PG_DB", "proyecto_s3");
define("PG_HOST", "sig3t2.cdapoqmcj4re.us-east-1.rds.amazonaws.com");
define("PG_USER", "postgres");
define("PG_PSWD", "equipamiento");
define("PG_PORT", "5432");

// Obtener el ID del bus desde los parámetros de la URL
$busId = isset($_GET['bus_id']) ? $_GET['bus_id'] : null;

session_start();

// Verificar si se proporcionó un ID de bus
if ($busId !== null) {
    $conexion = pg_connect("dbname=" . PG_DB . " host=" . PG_HOST . " user=" . PG_USER . " password=" . PG_PSWD . " port=" . PG_PORT . "");

    // Verificar la conexión
    if (!$conexion) {
        // Manejar errores de conexión de manera segura
        $response = array("error" => "Error en la conexión a la base de datos");
        echo json_encode($response);
    } else {
        $conductorId= $_SESSION['user_id'];
        // Consulta SQL para obtener la capacidad del bus seleccionado
        $query = "SELECT capacidad FROM public.bus WHERE id = $busId and id_conductor = $conductorId";
        $result = pg_query($conexion, $query);

        if (!$result) {
            // Manejar errores de consulta de manera segura
            $response = array("error" => "Error al obtener la capacidad del bus.");
            echo json_encode($response);
        } else {
            // Obtener el resultado como un array asociativo
            $row = pg_fetch_assoc($result);

            // Crear un array asociativo con la información de la capacidad
            $capacidadInfo = array("id_bus_seleccionado" => $busId, "capacidad" => $row['capacidad']);

            // Cerrar la conexión antes de enviar la respuesta
            pg_close($conexion);

            // Establecer el encabezado y enviar la respuesta JSON
            header('Content-Type: application/json');
            echo trim(json_encode($capacidadInfo)) ;
        }
    }
} else {
    // Manejar el caso en que no se proporcionó un ID de bus
    $response = array("error" => "No se proporcionó un ID de bus.");
    echo json_encode($response);
}
?>