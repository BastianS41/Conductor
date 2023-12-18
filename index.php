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

        header("Location: bus.php?email=" . urlencode($email) . "&conductor_id=" . $row['id']);
        exit();
    } else {
        $error_message = "Credenciales incorrectas. Inténtalo de nuevo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio de sesión</title>
    <meta name="keywords" content="PHP,PostgreSQL,Login">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/stylelog.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
        <form method="post">
            <div class="login-container" id="login">
                <h1>Login</h1>
                <div class="input-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" class="form-control" id="email" placeholder="Ingresa tu correo electrónico" name="email" required>
                </div>
                <div class="input-group">
                    <label for="pwd">Contraseña:</label>
                    <input type="password" class="form-control" id="pwd" placeholder="Ingresa tu contraseña" name="pwd" required>
                    </div>
                <input type="submit" name="login" class="btn-signin" value="Iniciar sesión">
                <br>
                <p>¿No tienes cuenta? <a href="registre.php" class="btn-register">Registrarse</a></p>
            </div>
        </form>
    </div>
</body>
</html>
