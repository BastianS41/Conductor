<?php
include("base_de_datos\configuracion.php");

// Verificar si se recibió un dato con el nombre 'id'
if (isset($_POST['id'])) {
    // Obtener el valor enviado en el campo 'id'
    $idRecibido = $_POST['id'];

    $sql = "SELECT row_to_json(fc)
    FROM (
        SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
        FROM (
            SELECT 'Feature' As type
                , ST_AsGeoJSON(lg.geom)::json As geometry
                , row_to_json((SELECT l FROM (SELECT id) As l)) As properties
            FROM parada As lg
            WHERE lg.id_ruta = $idRecibido -- Filtrar por el ID recibido
        ) As f
    ) As fc;";

    $query = pg_query($conexion, $sql);
    $row = pg_fetch_row($query);
    echo $row[0];
} else {
    echo "No se recibió ningún dato 'id' en la solicitud.";
}
