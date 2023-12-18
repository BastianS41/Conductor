<?php
include("base_de_datos\configuracion.php");
// Verificar si se recibió un dato con el nombre 'id'
if (isset($_POST['id']) && isset($_POST['tarifa']) && isset($_POST['hora']) ) {

    
    //Obtener el valor enviado en el campo 'id'
    $idRecibido = $_POST['id'];
    $tarifa= $_POST['tarifa'];
    $hora= $_POST['hora'];

    //echo "id " . $idRecibido . "tarifa " . $tarifa .  "hora " . $hora;

    $sql = "UPDATE ruta
    SET tarifa = '$tarifa', horario = '$hora'
    WHERE id = $idRecibido; -- Cambiar el 3 por el ID específico que desees actualizar
    
    ";

    $query = pg_query($conexion, $sql);
    $row = pg_fetch_row($query);
    echo "datos: nice";

} else {
    echo "No se recibió ningún dato 'id' en la solicitud.";
}

