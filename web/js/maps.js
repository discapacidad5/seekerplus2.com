// VARIABLES GLOBALES JAVASCRIPT
var geocoder;
var marker;
var latLng;
var latLng2;
var map;
var circle;
var latitude;
var longitude;
var panorama;


function setLatitude(latitude){
	this.latitude=latitude;
}
function setLongitude(longitude){
	this.longitude=longitude;
}
// INICiALIZACION DE MAPA
function initialize() {
  geocoder = new google.maps.Geocoder();  
  latLng = new google.maps.LatLng(parseFloat(latitude),parseFloat(longitude));
  map = new google.maps.Map(document.getElementById('mapCanvas'), {
    zoom:14,
    center: latLng,
	mapTypeControl: false,
	streetViewControl : false,
    disableDefaultUI: true,
    zoomControl: false,
    mapTypeControl: false,
    zoomControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE,
        position: google.maps.ControlPosition.TOP_LEFT
    },
    mapTypeId: google.maps.MapTypeId.ROADMAP
    
  });


}

function setDefaultMarkerPosition(){
	 geocoder = new google.maps.Geocoder();  
     var latLng = new google.maps.LatLng(parseFloat(latitude),parseFloat(longitude));
	//CREACION DEL MARCADOR  
	  marker = new google.maps.Marker({
	  position: latLng,
	  title: 'Arrastra el marcador si quieres moverlo',
	  map: map,
	  draggable: true
	});

	  marker.setIcon("http://seekerplus.com/images/map/here.png");
	  
	//CREACION DEL CIRCULO
	  circle = new google.maps.Circle({
	      map: map,
	      radius: 100,
	      strokeWeight: 0,
	      strokePosition: google.maps.StrokePosition.CENTER,
	      fillColor: '#1BA1E2'
	    });

	 circle.bindTo('center', marker, 'position');
     updateMarkerPosition (latLng);
     
  // Escucho el CLICK sobre el mama y si se produce actualizo la posicion del marcador
     google.maps.event.addListener(map, 'click', function(event) {
     updateMarker(event.latLng);
   });
  // Inicializo los datos del marcador
  //    updateMarkerPosition(latLng);
     
      geocodePosition(latLng);
 
  // Permito los eventos drag/drop sobre el marcador
  google.maps.event.addListener(marker, 'dragstart', function() {
    updateMarkerAddress('Buscando...');
  });
 
  google.maps.event.addListener(marker, 'drag', function() {
    updateMarkerStatus('Buscando...');
    updateMarkerPosition(marker.getPosition());
  });
 
  google.maps.event.addListener(marker, 'dragend', function() {
    updateMarkerStatus('Arrastre finalizado');
    geocodePosition(marker.getPosition());
  });
}
// Permito la gesti¢n de los eventos DOM
//google.maps.event.addDomListener(window, 'load', initialize);

// ESTA FUNCION OBTIENE LA DIRECCION A PARTIR DE LAS COORDENADAS POS
function geocodePosition(pos) {    
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(getCityAddress(responses[0].formatted_address));
    } else {
      updateMarkerAddress('No puedo encontrar esta direccion.');
    }
  });
}

function getCityAddress(results)
{
    var citystateArray = results.split(",",2);
    var city = citystateArray[0];
    return (city)
}
// OBTIENE LA DIRECCION A PARTIR DEL LAT y LON DEL FORMULARIO
function codeLatLon() {
   /*   latLng2 = new google.maps.LatLng(
    		  $("#seekerplus_adsmanagerbundle_adsmanagerads_adLatitude").val() ,
    		  $("#seekerplus_adsmanagerbundle_adsmanagerads_adLongitude").val()
      );
      marker.setPosition(latLng2);
      map.setCenter(latLng2);
      geocodePosition (latLng2);*/
}

// OBTIENE LAS COORDENADAS DESDE lA DIRECCION EN LA CAJA DEL FORMULARIO
function codeAddress() {
   /*     var address = $("#address").html();
          geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
             updateMarkerPosition(results[0].geometry.location);
             marker.setPosition(results[0].geometry.location);
             map.setCenter(results[0].geometry.location);
           } else {
            alert('ERROR : ' + status);
          }
        });*/
      }

// OBTIENE LAS COORDENADAS DESDE lA DIRECCION EN LA CAJA DEL FORMULARIO
function codeAddress2 (address) {
 /*         
          geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
             updateMarkerPosition(results[0].geometry.location);
             marker.setPosition(results[0].geometry.location);
             map.setCenter(results[0].geometry.location);
             $("#address").html(address);
           } else {
            alert('ERROR : ' + status);
          }
        });*/
      }

function updateMarkerStatus(str) {
  //$("#address").html(str)
}

function updateMarkerPosition (latLng) {
 $("#seekerplus_adsmanagerbundle_adsmanagerads_adLatitude").val(latLng.lat());
 $("#seekerplus_adsmanagerbundle_adsmanagerads_adLongitude").val(latLng.lng());
}

function updateMarkerAddress(str) {
  $("#address").html(str);
}

// ACTUALIZO LA POSICION DEL MARCADOR
function updateMarker(location) {
        marker.setPosition(location);
        updateMarkerPosition(location);
        geocodePosition(location);
      }
function getMyLocation(){

			//CREACION DEL MARCADOR  
			  marker = new google.maps.Marker({
			  title: 'Arrastra el marcador si quieres moverlo',
			  map: map,
			  draggable: true
			});
		
			  marker.setIcon("http://seekerplus.com/images/map/here.png");
			  
			//CREACION DEL CIRCULO
			  circle = new google.maps.Circle({
			      map: map,
			      radius: 100,
			      strokeWeight: 0,
			      strokePosition: google.maps.StrokePosition.CENTER,
			      fillColor: '#1BA1E2'
			    });
		
			 circle.bindTo('center', marker, 'position');
		   updateMarkerPosition (latLng);
		   
		// Escucho el CLICK sobre el mama y si se produce actualizo la posicion del marcador
		   google.maps.event.addListener(map, 'click', function(event) {
		   updateMarker(event.latLng);
		 });
   	
               dragActive = false;
               watcher = navigator.geolocation.watchPosition(function(position){

                currentPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                marker.setPosition(currentPosition);
                updateMarkerPosition (currentPosition);
                geocodePosition(currentPosition);
                // Desactivar el seguimiento cuando se activa el drag
                if (!dragActive) {
                  map.setZoom(16);
                  circle.setVisible(true);
                  map.setCenter(currentPosition);
                }
              }, function(error){
                circle.setVisible(false);
              }, {
                enableHighAccuracy:true,
                maximumAge: 0,
                timeout: 1000
              });
        }
function getMyCurrentLocation(){

	//CREACION DEL MARCADOR  
	  marker = new google.maps.Marker({
	  title: 'Arrastra el marcador si quieres moverlo',
	  map: map,
	  draggable: true
	});

	  marker.setIcon("http://seekerplus.com/images/map/person.png");
	  
	//CREACION DEL CIRCULO
	  circle = new google.maps.Circle({
	      map: map,
	      radius: 30,
	      strokeWeight: 0,
	      strokePosition: google.maps.StrokePosition.CENTER,
	      fillColor: '#38A934'
	    });

	 circle.bindTo('center', marker, 'position');
   updateMarkerPosition (latLng);
   
// Escucho el CLICK sobre el mama y si se produce actualizo la posicion del marcador
   google.maps.event.addListener(map, 'click', function(event) {
   updateMarker(event.latLng);
 });

       dragActive = false;
       watcher = navigator.geolocation.watchPosition(function(position){

        currentPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

        marker.setPosition(currentPosition);
        updateMarkerPosition (currentPosition);
        geocodePosition(currentPosition);
        // Desactivar el seguimiento cuando se activa el drag
        if (!dragActive) {
          map.setZoom(18);
          circle.setVisible(true);
          map.setCenter(currentPosition);
        }
      }, function(error){
        circle.setVisible(false);
      }, {
        enableHighAccuracy:true,
        maximumAge: 0,
        timeout: 1000
      });
}
function stopLocation(){
    if ((navigator.geolocation) && (watcher !== null)) {
        navigator.geolocation.clearWatch(watcher);
        circle.setVisible(false);
        dragActive = true;
      }
}

function setLocationPlaces(id,catId,latitud,longitud,titulo,imagen,telefono,direccion,rated,url){
	geocoder = new google.maps.Geocoder();  
    var latlng = new google.maps.LatLng(latitud,longitud);
    var places= new google.maps.Marker({
    position: latlng,
    map: map,
    title:titulo
     });
    places.setIcon("http://seekerplus.com/images/com_adsmanager/categories/"+catId+"cat_t.png");
    
    var infowindow = new google.maps.InfoWindow({
        content: "<div style='width: 10rem ! important;'>" +
        			"<address> "+
        			"<a style='color: #000' href="+url+"><strong style='font-size: 1rem;'>"+titulo+"</strong></a><br>" +
        			""+direccion+"<br>" +
        			"<div class='rating small'data-score-title='Valoracion : ' data-role='rating' data-static='true' " +
        			"data-size='small' data-value='"+rated+"' data-on-rate='doNothing'>" +
        			"</div>" +
        			"<br><a onclick='toggleStreetView("+latitud+","+longitud+");'>Vista de la calle</a>" +
        			"</address> "+ 
        			"<div style='width: 10rem ! important;'>" +
        			"<img style='width: 100%;' " +
        			"src='"+imagen+"'</img>" +
        			""+
        		"</div>"
    });

    // We get the map's default panorama and set up some defaults.
    // Note that we don't yet set it visible.

	google.maps.event.addListener(places, 'click', function() {

		infowindow.open(map,places);
		setTimeout(function () { infowindow.close(); }, 50000);
	});
}
function toggleStreetView(latitud,longitud) {
		var latlng = new google.maps.LatLng(latitud,longitud);
	    panorama = map.getStreetView();
	    panorama.setPosition(latlng);
	    panorama.setPov(/** @type {google.maps.StreetViewPov} */({
	      heading: 265,
	      pitch: 0
	    }));
	    
	  var toggle = panorama.getVisible();
	  if (toggle == false) {
	    panorama.setVisible(true);
	  } else {
	    panorama.setVisible(false);
	  }
	}


function closeInfoWindow() {
	if (infowindow !== null) {
	    google.maps.event.clearInstanceListeners(infowindow);
	    infowindow.close();
	}
}
function doNothing(value, star, widget){

    return false;
}
