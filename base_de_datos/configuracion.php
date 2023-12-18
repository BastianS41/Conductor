<?php
 #Archivo de configuracion de la base de datos
 
    define("PG_DB"  , "proyecto_s3");
	define("PG_HOST", "sig3t2.cdapoqmcj4re.us-east-1.rds.amazonaws.com");
	define("PG_USER", "postgres");
	define("PG_PSWD", "equipamiento");
	define("PG_PORT", "5432");
	
	$conexion = pg_connect("dbname=".PG_DB." host=".PG_HOST." user=".PG_USER ." password=".PG_PSWD." port=".PG_PORT."");


?>
