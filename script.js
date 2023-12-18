var map = L.map("map").setView([3.45, -76.48], 15);
var tileLayer = L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
    attribution: "OSM",
}).addTo(map);

L.control.locate({
    icon: '<i class="bi bi-geo-alt-fill"></i>'
}).addTo(map);


var busIcon = L.icon({
    iconUrl: 'img/bus.svg',
    iconSize: [15, 15]
});


// Marcador inicial
var marker;
var control;
var routeCoordinates;
var isRouting = false;


// FABIANSKI
// Función para simular el movimiento a lo largo de la ruta
function simulateMovement() {


    isRouting = true;

    var interval = 1000; // Intervalo en milisegundos entre la aparición de puntos
    var index = 0;

    function moveMarker() {
        if (index < routeCoordinates.length) {
            var point = routeCoordinates[index];
            marker.setLatLng(point); // Actualizar la posición del marcador
            marker.bindPopup(`Latitud: ${point.lat}, Longitud: ${point.lng}`).openPopup();
            index++;
            setTimeout(moveMarker, interval);
        } else {
            isRouting = false;
        }
    }

    moveMarker();
}
$('#form_hora').val("08:00")
$('#form_tarifa').val(50)
L.easyButton('<i class="bi bi-card-list"></i> style="font-size: 4rem;" ', function (btn, map) {


    map.once("locationfound", function (e) {

        // Limpiar marcadores anteriores
        if (marker) {
            map.removeLayer(marker);
        }

        // Crear un segundo marcador
        var segundoMarcador = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
        // Crear un marcador inicial en la posición encontrada
        marker = L.marker(e.latlng, { icon: busIcon }).addTo(map);

        // Si ya existe un control de enrutamiento, elimínalo
        if (control) {
            map.removeControl(control);
        }


        // Crear el control de enrutamiento con el punto de inicio
        control = L.Routing.control({
            waypoints: [e.latlng], // El punto de inicio es la ubicación actual
            routeWhileDragging: true,
        }).addTo(map);

        control.on("routesfound", function (e) {
            // Obtener las coordenadas de la ruta
            routeCoordinates = e.routes[0].coordinates;

            // Generar puntos a lo largo de la ruta
            simulateMovement();
        });
        // Evento de clic en el mapa
        map.once("click", function (e) {


            // Actualizar el punto final del enrutamiento
            var finalWaypoint = L.latLng(e.latlng.lat, e.latlng.lng);
            control.spliceWaypoints(
                control.getWaypoints().length - 1,
                1,
                finalWaypoint
            );

            // Recalcular la ruta
            control.route();
        });

        // Evento 'routeselected' para obtener la ruta y geometría
        control.on('routeselected', function (e) {

            var ruta = e.route; // Objeto con la información de la ruta seleccionada
            var geometry = ruta.geometry; // La geometría de la ruta como que es asincrona porque al llamarla en console log indica que es undefined

            const polyline = L.polyline(ruta.coordinates, { color: 'blue' })//elemento de leaflet que usa la geometria previa para generar la polilinea


            // Objeto de JavaScript para convertir lo de leafle a geojson.
            var geoline = polyline.toGeoJSON();
            //console.log(geoline.geometry)

            // Convertir el objeto en una cadena JSON pero de texto leible para el SQL.
            var dataS = JSON.stringify(geoline.geometry)
            // Crear un objeto FormData y asignarle la clave "dataS" y el valor dataS esto es para que php pueda manejar bien el dato, se puede hacer de otra manera
            //pero no me quise complicar
            var formData = new FormData();
            formData.append("dataS", dataS);
            // console.log(formData)//esto al mostrarlo no da mucha informacion del elemento la verdad

            var estilo_parada =
            {
                radius: 5,
                fillColor: "#0000ff",
                color: "#000000",
                weight: 1,
                opacity: 1,
                fillOpacity: 1,
            };


            //uso fetch para enviar el geojson al agregar.php
            fetch("./php/agregar.php", {
                method: "POST",
                body: formData //los datos enviados.
            })
                .then(function (respuesta) {
                    if (respuesta.ok) {

                        return respuesta.text();
                    } else {
                        throw new Error('Error en la solicitud');
                    }
                })
                .then(function (data) {
                    // Imprimir la respuesta del servidor en la consola
                    console.log(data);

                    // Almacenar el ID recibido en una variable
                    let idRecibido = data; // Almacenar la cadena recibida como ID

                    //boton de editar tarifa y horario.
                    let edit_ruta_form = L.easyButton('<i class="bi bi-pencil-square"></i> style="font-size: 4rem;" ', function (btn, map) {
                        //muestra el modal del formulario
                        $('#editeModal').modal('show');



                        var label = document.getElementById("label_id");

                        // Cambiar el contenido del label con el valor de 'dato'
                        label.textContent = "ID: " + idRecibido;

                    }).addTo(map)
                    //-----------------------------------------------------------------------
                    //boton de enviar formulario dentro del modal
                    $('#enviarEditForm').on("click", function () {

                        
                        let hora = $('#form_hora').val()
                        let tarifa = $('#form_tarifa').val()

                        console.log(hora)

                        let formData = new FormData();
                        formData.append('id', idRecibido);
                        formData.append('tarifa', tarifa);
                        formData.append('hora', hora);

                        fetch("ingreso_tar_hor.php", {
                            method: "POST",
                            body: formData
                        })
                            .then(function (respuesta) {
                                if (respuesta.ok) {
    
                                    return respuesta.text();
                                } else {
                                    throw new Error('Error en la solicitud');
                                }
                            })
                            .then((data) => {
                                console.log(data);
                                $('#editeModal').modal('hide');
                            })
                            .catch(function (error) {
                                // Capturar y manejar error en fetch envio de id.
                                console.error('Error en envio de de datos: ', error);
                            });
                    })
                    //------------------------------------

                    let formData = new FormData();
                    formData.append('id', idRecibido);

                    fetch("data_ruta.php", {
                        method: "POST",
                        body: formData
                    })
                        .then(function (respuesta) {
                            if (respuesta.ok) {

                                return respuesta.text();
                            } else {
                                throw new Error('Error en la solicitud');
                            }
                        })
                        .then((puntos) => {

                            console.log(puntos)
                            var parada = L.geoJson();
                            map.removeLayer(parada);


                            // Parsear los datos JSON de geometría de hurto usando JSON.parse en lugar de eval
                            var paradaData = JSON.parse(puntos);

                            console.log(paradaData)

                            hurto = L.geoJson(paradaData.features, {
                                onEachFeature: function (feature, layer) {
                                    // Acciones específicas para cada feature si es necesario
                                },
                                pointToLayer: function (feature, latlng) {
                                    return L.circleMarker(latlng, estilo_parada);
                                }
                            }).addTo(map);

                            map.addLayer(parada);
                            parada.addTo(map);
                        })
                        .catch(function (error) {
                            // Capturar y manejar error en fetch envio de id.
                            console.error('Error en envio de id: ', error);
                        });


                })
                .catch(function (error) {
                    // Capturar y manejar errores
                    console.error('Error:', error);
                });
        });

    });
}).addTo(map);

    // Función para inicializar el contador en 0 cuando se carga la página
    function inicializarContador() {
        $("#contador-result").text("Número de pasajeros: 0");
      }
  
      function incrementarContador() {
        actualizarContador('incrementar');
      }
  
      function decrementarContador() {
        actualizarContador('decrementar');
      }
  
      function resetContador() {
        actualizarContador('restablecer');
      }
  
      function toggleForm() {
        $("#contador-form").toggle();
      }
  
      function actualizarContador(operacion) {
        // Obtener el ID del bus desde la URL
        var busId = getParameterByName('bus_id');
  
        // Realizar la solicitud AJAX para actualizar el contador en el servidor
        $.ajax({
          type: "GET",
          url: "actualizar_contador.php",
          data: { bus_id: busId, operacion: operacion },
          success: function (response) {
            ress = JSON.parse(response)
            console.log(response)
            // Actualizar el valor del contador en el formulario con la respuesta del servidor
            $("#contador-result").text("Número de pasajeros: " + ress.nuevo_valor_contador);
          },
          error: function (error) {
            console.error("Error en la solicitud AJAX:", error);
          }
        });
      }
  
      // Función para obtener el valor de un parámetro desde la URL
      function getParameterByName(name) {
        var url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
          results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
      }
  
      // Crear el EasyButton para abrir el formulario de contador
      // Ruta de la imagen que quieres usar como ícono
      var imagenUrl = 'img/icon.png'; // Reemplaza con la URL de tu imagen
  
      // Crear el botón fácil con la imagen como ícono
      var contadorButton = L.easyButton({
        states: [{
          stateName: 'open-form',
          icon: '<img src="' + imagenUrl + '" style="width: 20px; height: 20px;">',
          title: 'Abrir formulario',
          onClick: function (control) {
            toggleForm();
            control.state('close-form');
          }
        }, {
          stateName: 'close-form',
          icon: '<img src="' + imagenUrl + '" style="width: 20px; height: 20px;">',
          title: 'Cerrar formulario',
          onClick: function (control) {
            toggleForm();
            control.state('open-form');
          }
        }]
      }).addTo(map);
  
      // Obtener el ID del bus desde los parámetros de la URL
      var urlParams = new URLSearchParams(window.location.search);
      var busId = urlParams.get('bus_id');
  
      // Verificar si hay un ID de bus
      if (busId !== null) {
        // Realizar una solicitud AJAX para obtener el valor del contador
        fetch('contador.php?bus_id=' + busId)
          .then(response => response.json())
          .then(data => {
            // Manejar la respuesta exitosa
            console.log(data);
            console.log('Nuevo valor del contador:', data.nuevo_valor_contador);
          })
          .catch(error => {
            // Manejar errores en la solicitud fetch
            console.log(error);
          });
      } else {
        console.log('No se proporcionó un ID de bus en la URL.');
      }


