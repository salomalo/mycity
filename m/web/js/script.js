
var geocoder;
var model_name = 'business';

google.maps.event.addDomListener(window, 'load', initialize); 

function initialize() {
    geocoder = new google.maps.Geocoder();
    
    navigator.geolocation.getCurrentPosition(function(position) {

        pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);


        $('.'+model_name+'_lat').val(position.coords.latitude);   
        $('.'+model_name+'_lon').val(position.coords.longitude);  

        geocoder.geocode({'latLng': pos}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('.'+model_name+'_address').val(results[0].formatted_address);  
                 }
            }
        });   

    });      
        
}

