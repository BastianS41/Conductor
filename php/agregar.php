<?php
// Incluir el archivo de conexión a la base de datos
include("C:/xampp/htdocs/sigWeb/Sierra_files/ejemplosc6/base_de_datos/configuracion.php");

if (isset($_POST["dataS"])) {
    // Imprimir el valor recibido para verificar
    //echo 'cadena recibida: ' . $_POST["dataS"];

    $id_bus = 2;

    // Construir la consulta SQL para insertar la línea en la tabla polilineas
    $cadena = $_POST["dataS"]; // Asigna el valor recibido a una variable para usar en la consulta

    $sql = "INSERT INTO ruta (id_bus, geom) VALUES ('$id_bus', ST_GeomFromGeoJSON('$cadena'))";

    // Ejecutar la consulta SQL
    $query = pg_query($conexion, $sql);

    $sql_p = "SELECT id FROM ruta ORDER BY id DESC LIMIT 1;";

    // Ejecutar la consulta SQL
    $query_p = pg_query($conexion, $sql_p);


    if ($query) {

        
        // Obtener el valor de id de la última fila insertada
        if ($query_p) {
            $fila = pg_fetch_assoc($query_p);
            if ($fila) {
                $id_insertado = $fila['id'];
                echo "$id_insertado";
            } else {
                echo "No se pudo obtener el ID insertado.";
            }
        } else {
            echo "Error al obtener el ID insertado.";
        }
    } else {
        echo "Error al insertar datos en la base de datos.";
    }
} else {
    echo "Error: no se recibió 'dataS'";
}
