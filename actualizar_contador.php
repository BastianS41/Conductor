<?php
define("PG_DB", "proyecto_s3");
define("PG_HOST", "sig3t2.cdapoqmcj4re.us-east-1.rds.amazonaws.com");
define("PG_USER", "postgres");
define("PG_PSWD", "equipamiento");
define("PG_PORT", "5432");

$conexion = pg_connect("dbname=" . PG_DB . " host=" . PG_HOST . " user=" . PG_USER . " password=" . PG_PSWD . " port=" . PG_PORT . "");

// Verificar la conexión
if (!$conexion) {
    die("Error en la conexión a la base de datos");
}

// Obtener el ID del bus desde los parámetros de la URL
$busId = isset($_GET['bus_id']) ? $_GET['bus_id'] : null;

// Verificar si se proporcionó un ID de bus
if ($busId !== null) {
    // Determinar la operación a realizar: incrementar, decrementar o restablecer a cero
    $operacion = isset($_GET['operacion']) ? $_GET['operacion'] : null;

    // Actualizar el contador según la operación especificada
    switch ($operacion) {
        case 'incrementar':
            $sqlActualizar = "UPDATE public.bus SET contador = contador + 1 WHERE id = $busId";
            break;
        case 'decrementar':
            $sqlActualizar = "UPDATE public.bus SET contador = GREATEST(contador - 1, 0) WHERE id = $busId";
            break;
        case 'restablecer':
            $sqlActualizar = "UPDATE public.bus SET contador = 0 WHERE id = $busId";
            break;
        default:
            $response = array("error" => "Operación no válida.");
            echo json_encode($response);
            exit();
    }

    // Ejecutar la consulta de actualización
    $resultadoActualizar = pg_query($conexion, $sqlActualizar);

    if ($resultadoActualizar) {
        // Consulta SQL para obtener el nuevo valor del contador
        $sqlConsulta = "SELECT contador FROM public.bus WHERE id = $busId";
        $resultadoConsulta = pg_query($conexion, $sqlConsulta);

        if ($resultadoConsulta) {
            // Obtener el resultado como un array asociativo
            $filaConsulta = pg_fetch_assoc($resultadoConsulta);

            // Obtener el nuevo valor del contador (o establecer un valor predeterminado si es undefined)
            $nuevoValorContador = isset($filaConsulta['contador']) ? $filaConsulta['contador'] : 0;

            // Enviar el nuevo valor del contador como respuesta JSON
            $response = array("nuevo_valor_contador" => $nuevoValorContador);
            echo json_encode($response);
        } else {
            $response = array("error" => "Error al obtener el nuevo valor del contador.");
            echo json_encode($response);
        }
    } else {
        $response = array("error" => "Error al actualizar el contador para el bus seleccionado.");
        echo json_encode($response);
    }
} else {
    $response = array("error" => "No se proporcionó un ID de bus.");
    echo json_encode($response);
}

// Cerrar la conexión
pg_close($conexion);
?>
