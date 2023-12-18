<?php


include("base_de_datos\configuracion.php"); ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ruteo Fabian</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" defer></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.4.0/easy-button.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.EasyButton/2.4.0/easy-button.js" defer></script>

  <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js" defer></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet-easybutton@2.4.0/src/easy-button.css" />
  <link rel="stylesheet" href="css/styleind.css">
  <link rel="stylesheet" href="resources/plugins/locate_plu/src/L.Control.Locate.scss" />

  <script src="resources/plugins/locate_plu/src/L.Control.Locate.js" defer></script>
  <!-- Bootstrap core CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

  <script src="script.js" defer></script>

  <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh" crossorigin="anonymous"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
    }

    /* Añadir algunos estilos para el contenedor del mapa */
    .map {
      width: 80%;
      height: 60vh;
    }
  </style>
</head>

<body>

  <div class="titulo-anterior">
    <img src="img/1.PNG" class="img" alt="Logo" />
    <div>
      <h1>Molividad Universitaria Palmira - Cali</h1>
      <p>Conductor</p>
    </div>
  </div>

  <div id="map" class="map"></div>
  <!-- Contenedor del formulario -->
  <div id="contador-form">
    <h2 class="custom-h2">Contador de Pasajeros</h2>
    <div id="contador-result"></div>
    <button class="boton-incrementar" onclick="incrementarContador()">Incrementar</button>
    <button class="boton-decrementar" onclick="decrementarContador()">Decrementar</button>
    <button class="boton-restablecer" onclick="resetContador()">Restablecer a Cero</button>
    <button class="boton-cerrar" onclick="toggleForm()">Cerrar</button>

  </div>
<!-- Contenedor del formulario -->


  <!-- Modal  de editar TARIFA Y HORARIO-->
  <div class="modal fade" id="editeModal" tabindex="-1" aria-labelledby="editeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Formulario de cambio de tarifa y horario</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>

            <div class="form-group">
              <label for="input_lat" id="label_id">ID:</label>

            </div>

            <div class="form-group">
              <label for="input_fecha">Hora:</label>
              <input type="time" class="form-control" id="form_hora" placeholder="">
            </div>

            <div class="form-group">
              <label for="input_tipo">Tarifa:</label>
              <input type="number" class="form-control" id="form_tarifa" placeholder="">
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="enviarEditForm">Enviar Formulario</button>
        </div>
      </div>
    </div>
  </div>


</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>