<?php
define("PG_DB", "proyecto_s3");
define("PG_HOST", "sig3t2.cdapoqmcj4re.us-east-1.rds.amazonaws.com");
define("PG_USER", "postgres");
define("PG_PSWD", "equipamiento");
define("PG_PORT", "5432");

$conexion = pg_connect("dbname=" . PG_DB . " host=" . PG_HOST . " user=" . PG_USER . " password=" . PG_PSWD . " port=" . PG_PORT . "");

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
    $sql = "INSERT INTO conductor (p_nombre, s_nombre, p_apellido, s_apellido, tipo_documento, documento, licencia, contacto, correo, contraseña) VALUES (
        '" . $_POST['p_nombre'] . "',
        '" . $_POST['s_nombre'] . "',
        '" . $_POST['p_apellido'] . "',
        '" . $_POST['s_apellido'] . "',
        '" . $_POST['tipo_documento'] . "',
        '" . $_POST['documento'] . "',
        '" . $_POST['licencia'] . "',
        '" . $_POST['contacto'] . "',
        '" . $_POST['correo'] . "',
        '" . md5($_POST['contraseña']) . "'
    ) RETURNING id";
    $ret = pg_query($conexion, $sql);

    if ($ret) {
        $row = pg_fetch_assoc($ret);
        echo "Datos guardados exitosamente. ID del conductor: " . $row['id'];
    } else {
        echo "Algo salió mal: " . pg_last_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registro de Conductor</title>
    <meta name="keywords" content="PHP,PostgreSQL,Insert,Login">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/stylereg.css">
    <script>
        function mostrarMensaje() {
            alert("Registrado");
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Regístrate como conductor</h2>
    <form method="post">

        <div class="form-group">
            <label for="p_nombre">Primer Nombre:</label>
            <input type="text" class="form-control" id="p_nombre" placeholder="Ingresa tu primer nombre" name="p_nombre" required>
        </div>

        <div class="form-group">
            <label for="s_nombre">Segundo Nombre:</label>
            <input type="text" class="form-control" id="s_nombre" placeholder="Ingresa tu segundo nombre" name="s_nombre">
        </div>

        <div class="form-group">
            <label for="p_apellido">Primer Apellido:</label>
            <input type="text" class="form-control" id="p_apellido" placeholder="Ingresa tu primer apellido" name="p_apellido" required>
        </div>

        <div class="form-group">
            <label for="s_apellido">Segundo Apellido:</label>
            <input type="text" class="form-control" id="s_apellido" placeholder="Ingresa tu segundo apellido" name="s_apellido">
        </div>

        <div class="form-group">
            <label for="tipo_documento">Tipo de Documento:</label>
            <select class="form-control" id="tipo_documento" name="tipo_documento" required>
                <option value="cedula extranjera">Cédula Extranjera</option>
                <option value="tarjeta identidad">Tarjeta de Identidad</option>
                <option value="cedula ciudadania">Cédula de Ciudadanía</option>
            </select>
        </div>

        <div class="form-group">
            <label for="documento">Documento:</label>
            <input type="text" class="form-control" id="documento" placeholder="Ingresa tu documento" name="documento">
        </div>

        <div class="form-group">
            <label for="licencia">Licencia:</label>
            <input type="text" class="form-control" id="licencia" placeholder="Ingresa tu licencia" name="licencia">
        </div>

        <div class="form-group">
            <label for="contacto">Contacto:</label>
            <input type="text" class="form-control" id="contacto" placeholder="Ingresa tu contacto" name="contacto">
        </div>

        <div class="form-group">
            <label for="correo">Correo electrónico:</label>
            <input type="email" class="form-control" id="correo" placeholder="Ingresa tu correo electrónico" name="correo">
        </div>

        <div class="form-group">
            <label for="contraseña">Contraseña:</label>
            <input type="password" class="form-control" id="contraseña" placeholder="Ingresa tu contraseña" name="contraseña" required>
        </div>

        <input type="submit" name="submit" class="btn1" value="Registrar" onclick="mostrarMensaje()">
        <button type="button" class="btn2" onclick="window.location.href='index.php'">Iniciar sesión</button>
    </form>
</div>

</body>
</html>
