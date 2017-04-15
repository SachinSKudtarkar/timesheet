    var map;
    var geocoder;
    var centerChangedLast;
    var reverseGeocodedLast;
    var currentReverseGeocodeResponse;
    var bounds;
    var phone;
    var infowindow;
    var bname;
    var only;
    
 (function () {

  google.maps.Map.prototype.markers = new Array();
    
  google.maps.Map.prototype.addMarker = function(marker) {
    this.markers[this.markers.length] = marker;
  };
    
  google.maps.Map.prototype.getMarkers = function() {
    return this.markers
  };
    
  google.maps.Map.prototype.clearMarkers = function() {
    if(infowindow) {
      infowindow.close();
    }
    
    for(var i=0; i<this.markers.length; i++){
      this.markers[i].set_map(null);
    }
  };
})();


  function initialize(id, num) {
    var latlng = new google.maps.LatLng(32.5468,-23.2031);
    var myOptions = {
      zoom: 1,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById(id), myOptions);
    geocoder = new google.maps.Geocoder(); 
    bounds = new google.maps.LatLngBounds(); 
  }

  function reverseGeocode() {
    reverseGeocodedLast = new Date();
    geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
  }

  function reverseGeocodeResult(results, status) {
    currentReverseGeocodeResponse = results;
    if(status == 'OK') {
      if(results.length == 0) {
        document.getElementById('formatedAddress').innerHTML = 'None';
      } else {
        document.getElementById('formatedAddress').innerHTML = results[0].formatted_address;
      }
    } else {
      document.getElementById('formatedAddress').innerHTML = 'Error';
    }
  }


  function geocode(address, phonenum, businessname, onlylo) {
    phone = phonenum;
    bname = businessname;
    only = onlylo;
    
    geocoder.geocode({
      'address': address,
      'partialmatch': true}, geocodeResult);
  }

  function geocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
      plotMatchesOnMap(results);
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  }
  
  function createMarker(latlng, address) {
    var marker = new google.maps.Marker({position: latlng, map: map});
    google.maps.event.addListener(marker, "click", function() {
      if (infowindow) infowindow.close();
      var text = getWindowContent(address); 
      infowindow = new google.maps.InfoWindow({content: text});
      infowindow.open(map, marker);
    });
    return marker;
  }


function plotMatchesOnMap(results) {
  markers = new Array(results.length);
   
  for(var i = 0; i < results.length; i++) {
      var fullAddress = results[i].formatted_address;  
      map.addMarker(createMarker(results[i].geometry.location, fullAddress));
             
      //alert(only);//console.log('myVar:'+i, results[i]); 
      latlng = new google.maps.LatLng(results[i].geometry.location.b,results[i].geometry.location.c);
      //console.log({'myvar':latlng});
      if(only != 1)
      {
          bounds.extend(latlng);
          map.fitBounds(bounds);                               
      }else {
          
          map.setCenter(latlng);
          map.setZoom(12);

      }
          
  }  
}

function getWindowContent(address)
{
   var html = '<table style="font-size: 11px;">';
   html += '<tr><td style="font-size: 14px;" colspan="2"><b>'+bname+'</b></td></tr>'; 
   html += '<tr><td><b>Address: </b></td>';
   html += '<td>'+address+'</td></tr>';
   html += '<tr><td><b>Phone: </b></td>';
   html += '<td>'+phone+'</td></tr>';
   html += '</table>';
   
   return html; 
}
