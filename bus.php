<?php
define("PG_DB", "proyecto_s3");
define("PG_HOST", "sig3t2.cdapoqmcj4re.us-east-1.rds.amazonaws.com");
define("PG_USER", "postgres");
define("PG_PSWD", "equipamiento");
define("PG_PORT", "5432");

$conexion = pg_connect("dbname=" . PG_DB . " host=" . PG_HOST . " user=" . PG_USER . " password=" . PG_PSWD . " port=" . PG_PORT . "");

session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['pwd']);

    $sql = "SELECT * FROM conductor WHERE correo='$email' AND contraseña='$password'";
    $result = pg_query($conexion, $sql);
    $row = pg_fetch_assoc($result);

    if ($row) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['p_nombre'];

        // Redirigir a rut.html después del inicio de sesión exitoso
        header("Location: index1.php");
        exit();
    } else {
        $error_message = "Credenciales incorrectas. Inténtalo de nuevo.";
    }
}

// Verificar si se envió el formulario de relación de conductor con bus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['relacionar'])) {
    relacionarConductorConBus();
}

// Mostrar la página de buses
mostrarPaginaBuses();

function mostrarPaginaBuses()
{
    global $conexion;

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        // Si no está autenticado, redirigir al inicio de sesión
        header("Location: index.php");
        exit();
    }

    // Obtener el ID y el nombre del conductor desde la sesión
    $conductorId = $_SESSION['user_id'];
    $conductorNombre = $_SESSION['user_name'];

    // Mostrar la información del conductor con algunos estilos
    echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Seleccion de Bus</title>
    <meta name="keywords" content="PHP, PostgreSQL, Bus">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/stylebus.css">

</head>
<body>
    <div class="container">
        <h1>Selección de Bus para Utilizar</h1>
        <p>Bienvenido, conductor $conductorNombre</p>
HTML;

    // Ejecutar la consulta para obtener todos los buses disponibles
    $sqlBuses = "SELECT id, placa FROM bus";
    $resultBuses = pg_query($conexion, $sqlBuses);

    // Verificar si la consulta se ejecutó correctamente
    if (!$resultBuses) {
        echo "Error al obtener buses disponibles: " . pg_last_error($conexion);
        exit();
    }

    // Cargar dinámicamente las opciones del formulario
    echo <<<HTML
        <form method="post" action="">
            <div class="form-group">
                <label for="id_bus">Seleccione el bus con placa:</label>
                <select class="form-control" id="id_bus" name="id_bus" required>
HTML;

    while ($rowBus = pg_fetch_assoc($resultBuses)) {
        echo "<option value='{$rowBus['id']}'>{$rowBus['placa']}</option>";
    }

    echo <<<HTML
                </select>
            </div>

            <input type="submit" name="relacionar" class="btn1" value="Seleccionar bus">
        </form>
HTML;

    // Agregar formulario para registrar un nuevo bus
    echo <<<HTML
        <h2>Registrar Nuevo Bus</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="placa_nueva">Placa del Nuevo Bus:</label>
                <input type="text" class="form-control" id="placa_nueva" name="placa_nueva" required>
            </div>

            <div class="form-group">
                <label for="capacidad_nueva">Capacidad del Nuevo Bus:</label>
                <input type="number" class="form-control" id="capacidad_nueva" name="capacidad_nueva" required>
            </div>

            <input type="submit" name="registrar_nuevo_bus" class="btn2" value="Registrar Nuevo Bus">
        </form>
    </div>
</body>
</html>
HTML;

    // Procesar el formulario para registrar un nuevo bus
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_nuevo_bus'])) {
        registrarNuevoBus();
    }
}

function relacionarConductorConBus()
{
    global $conexion;

    // Obtener el ID del conductor y el ID del bus desde el formulario
    $conductorId = $_SESSION['user_id'];
    $busId = $_POST['id_bus'];

    // Almacenar el ID del bus en la sesión
    $_SESSION['bus_id'] = $busId;

    // Actualizar el atributo id_conductor en la tabla bus
    $sqlActualizarConductor = "UPDATE bus SET id_conductor = $conductorId WHERE id = $busId";
    $resultActualizarConductor = pg_query($conexion, $sqlActualizarConductor);

    if (!$resultActualizarConductor) {
        echo "Error al actualizar el atributo 'id_conductor': " . pg_last_error($conexion);
        exit();
    }

    // Redirigir a rut.html con el ID del bus en la URL
    header("Location: index1.php?bus_id=$busId");
    exit();
}

function registrarNuevoBus()
{
    global $conexion;

    // Obtener el ID del conductor desde la sesión
    $conductorId = $_SESSION['user_id'];
    $placaNueva = $_POST['placa_nueva'];
    $capacidadNueva = $_POST['capacidad_nueva'];

    // Verificar si ya existe un bus con esa placa
    $sqlVerificarPlaca = "SELECT COUNT(*) FROM bus WHERE placa = '$placaNueva'";
    $resultVerificarPlaca = pg_query($conexion, $sqlVerificarPlaca);

    if ($resultVerificarPlaca) {
        $count = pg_fetch_result($resultVerificarPlaca, 0, 0);

        if ($count > 0) {
            echo "Ya existe un bus con esa placa.";
            return;
        }
    } else {
        echo "Error al verificar la placa: " . pg_last_error($conexion);
        exit();
    }

    // Insertar el nuevo bus en la base de datos
    $sqlInsertarBus = "INSERT INTO bus (placa, capacidad, id_conductor) VALUES ('$placaNueva', $capacidadNueva, $conductorId)";
    $resultInsertarBus = pg_query($conexion, $sqlInsertarBus);

    if ($resultInsertarBus) {
        // Redirigir a la página "rut.html" después del registro exitoso
        header("Location: index1.php");
        exit();
    } else {
        echo "Error al registrar el nuevo bus: " . pg_last_error($conexion);
        exit();
    }
}

// Obtener el ID del bus registrado o seleccionado desde la sesión
$busId = isset($_SESSION['bus_id']) ? $_SESSION['bus_id'] : null;

// Imprimir el valor de $busId en una variable de JavaScript
echo '<script>';
echo 'var busId = ' . json_encode($busId) . ';';
echo '</script>';
?>

<!-- Tu script JavaScript -->
<script>
    // Obtener el ID del bus desde la URL
    var urlParams = new URLSearchParams(window.location.search);
    var busId = urlParams.get('bus_id');
    console.log(urlParams);
    // Verificar si hay un ID de bus
    if (busId !== null) {
        // Realizar una solicitud fetch para enviar el ID del bus a contador.php
        fetch('contador.php?id_bus=' + busId)
            .then(response => response.text())
            .then(responseText => {
                // Manejar la respuesta si es necesario
                console.log(responseText);
            })
            .catch(error => console.error('Error en la solicitud AJAX:', error));
    } else {
        console.log('No hay un bus registrado o seleccionado.');
    }
</script>
