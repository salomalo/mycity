
var map;
var markers = [];
var geocoder;
var model_name = '';
var data_center = [];
var data = [];
var infoBoxes = [];
var selectCity = $('#map-canvas').data('city');
window.onload = function() {
    var select2Name = $("#business-idcity").select2('data')[0].text;
    if (select2Name != 'Выберите город') {
        selectCity = select2Name;
    }
};
function change_data_center() {
    var cityName = $("#business-idcity").select2('data')[0].text;
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': cityName }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
        } else {
            alert("Could not find location: " + location);
        }
    });

    set_select_city(cityName);
}

function change_hidden_input() {
    var value = $("#business-idcity").select2('data')[0].text;
    $('.address_block.activemap input.city-name').val(value);
}

function set_data_ajax() {
    var lang = document.documentElement.lang;
    lang = lang.substring(0, 2);

    //посылаем аякс запрос для списка предприятий
    $.ajax({
        url: '/' + lang + '/business/list-address',
        type: 'post',
        data: {
            id_category : idCategory,
            _csrf: csrfVar
        },
        success: function (resultData) {
            //console.log(resultData.result);
            data = JSON.parse(resultData.listAddress);
            set_data_center(data[0]['lat'], data[0]['lon']);
            initialize();
        }
    });
}

function set_data(c1) {
    data = JSON.parse(c1);
}

function set_model_name(cl) {
    model_name = cl;
}

function set_data_center(lat, lon) {
    data_center = [lat, lon];
}

function set_select_city(city) {
    selectCity = city;
}

function initialize() {
    var isScrollwheel;
    if(device.desktop()) {
        isScrollwheel = true;
    } else {
        isScrollwheel = false;
    }

    geocoder = new google.maps.Geocoder();
    var mapOptions = {
        zoom: 12,
        scrollwheel: isScrollwheel,
        disableDefaultUI: true
    };
    
    map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions); 
    
    google.maps.event.addListener(map, 'click', function(event) {
        addMarker(event.latLng);
    }); 
    
    if(data_center.length > 0){ // Указан адрес центровки
        pos = new google.maps.LatLng(data_center[0],data_center[1]);
        map.setCenter(pos);  
    } else {
            if (selectCity) {
                if (selectCity === 'dnepr') {
                    selectCity = 'dnepropetrovsk';
                }
                // Указан город, центруем по городу
                //        var address = document.getElementById('address').value;
                geocoder.geocode({'address': selectCity}, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                    } else {
                      alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
            else{ // // Не казан город, центруем по позиции пользователя
                var nav =navigator.geolocation.getCurrentPosition(function(position) {
                pos = new google.maps.LatLng(position.coords.latitude,
                                                 position.coords.longitude);
                var infowindow = new google.maps.InfoWindow({
                  map: map,
                  position: pos,
                  content: 'Ваше местоположение'
                }); 

                map.setCenter(pos);  

                });      
            }
   
    }

//    map.setCenter(pos);
    ViewMarkers();
}

function getgeocode(latlng){
    var res;
    geocoder.geocode({'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        res =  (results[0].formatted_address);
      } else {
        alert ('No results found');
      }
    } else {
      alert ('Geocoder failed due to: ' + status);
    }
  });    
    
  return res;   
}

// Sets the map on all markers in the array.
function setAllMap(map) {
    if(map){    //    вывод маркеров группированных по кластерам
        var mcOptions = {gridSize: 50, maxZoom: 15};
        var mc = new MarkerClusterer(map, markers, mcOptions);
    }
    else{   //    простой вывод маркеров   
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}

// Add news marker to the map and push to the array.
function addMarker(location) {
    var count = $('.activemap').parents('.address_list').data('count'); // при нажатии "добавить адрес"

    if (model_name != '') { // вставляем данные в поля формы (1 запись: post)

        clearMarkers();
        var marker = new google.maps.Marker({
            position: location,
            icon: markerUrl,
            map: map
        });
        $('#' + model_name + '-lat').val(location.lat());
        $('#' + model_name + '-lon').val(location.lng());
        geocoder.geocode({'latLng': location}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#' + model_name + '-address').val(results[0].formatted_address);
                }
            }
        });
        markers[0] = marker;
        showMarkers();
        map.panTo(location);    // центрация по маркеру
    }
    else {
//            backend busines create
        if (count) {
            clearMarkers();
            var marker = new google.maps.Marker({
                position: location,
                icon: markerUrl,
                map: map
            });
            $('.activemap .business_lat').val(location.lat());
            $('.activemap .business_lon').val(location.lng());

            geocoder.geocode({'latLng': location}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('.activemap .business_address').val(results[0].address_components[1].short_name + ',' + results[0].address_components[0].short_name);
                    }
                }
            });
            markers[count - 1] = marker;
            showMarkers();
            map.panTo(location);    // центрация по маркеру
        }

    }
}
// Shows any markers currently in the array.
function showMarkers() {
  setAllMap(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}

$(document).on("click", 'a.view', function(){
    if($(this).parent().children('.address_block').css('display') === 'none')
    {
        nom = $(this).parent().data('count');
        $(this).parent().parent().data('nom', nom); // пишем в ul номер li(адресса) который редактируем
        
        $('.address_block').slideUp().removeClass('activemap');
        $(this).parent().children('.address_block').slideDown().addClass('activemap');
    }
    else{
       
    }
    return false;   
    
});

$(document).on("click", 'li.address_list a.delete', function(){
    $( "#main-address-add").show();
   clearMarkers(); 
   var count = $(this).parent('li').data(count);
   markers.splice (count,1);
   showMarkers();
     
   $(this).parent('li').remove();
   return false;
});


function AddAddressInputs(){
    $('.address_block').slideUp().removeClass('activemap');
    $( "#main-address-add").hide();
    var kol = $('div.address_block').size()+1;
    console.log(kol);
    var add = $('li.address_add');
        add.before('<li data-count="'+kol+'" class="address_list"><a href="#" class="view">Новый адрес</a> <a href="#" class="delete"><small style="color:red;">X</small></a> \n\
        <div class="address_block">Адрес:<a href="#0" class="btn-success btn-xs address_add" style="padding: 5px 10px;margin: 10px;">Добавить адрес</a>(Введите адрес в таком виде: улица Соборная 25. Или кликните по объекту на карте) \n\
        <input type="text" class="form-control business_address" name="business_address['+kol+'][address]" value=""/>\n\
        Телефон:<input type="text" class="form-control business_phone" name="business_address['+kol+'][phone]" value=""/>\n\
        График работы:<input type="text" class="form-control business_working_time" name="business_address['+kol+'][working_time]" value=""/>\n\
        <div style="display: none;"> \n\
        Широта:<input type="text" class="form-control business_lat" name="business_address['+kol+'][lat]" value=""/>\n\
        Долгота:<input type="text" class="form-control business_lon" name="business_address['+kol+'][lon]" value=""/>\n\
        </div> \n\
        <input type="hidden" class="city-name" name="business_address['+kol+'][city]" value="' + selectCity + '"/>\n\
    </div>\n\
    </li>');     
        $('li.address_list:last .address_block').slideDown().addClass('activemap');
}

$(document).on("click", '.address_add', function(){

    if ($('select[name="Business[idCity]"]').val() == ''){
        alert('Выберите город')
    } else {
        AddAddressInputs();
    }
});



function ViewMarkers(){
   var address_block = $('li.address_list');
   var address;
   var count;
   var lat;
   var lon;
   var localepos;
//   var marker;
  
   if(address_block.length > 0){
            address_block.each(function (i) {
                address = address_block[i];
                count = $(address).attr('data-count');
                lat = $(address).children().children().children(".business_lat").val();
                lon = $(address).children().children().children(".business_lon").val();
                localepos = new google.maps.LatLng(lat,lon)  
                marker = new google.maps.Marker({
                    position: localepos,
                    icon: markerUrl,
                    map: map
                });
                markers[count-1] = marker;  

          });  
    }else{
        // вывод на frontend
        if(data.length > 0){
            for(var i=0; i< data.length; i++ ){
               localepos = new google.maps.LatLng(data[i]['lat'],data[i]['lon']);
               
                var title = data[i]['title'].split('\\"').join('"');
                var dataImage = data[i]['image'];
                var dataLink = data[i]['hrefLink'];

                contentString = '<div class="infobox"><div class="infobox-inner">' +
                    '<a href="' + dataLink + '" class="infobox-image" style="background-image: url(' + dataImage + ');"></a>' +
                    '<div class="infobox-title"><h2><a href="' + dataLink + '" style="text-decoration: none;">' + title + '</a></h2></div>' +
                    '<a class="close" onclick="closeAllInfoWindows();">x</a>' +
                    '</div></div>';

                marker = new google.maps.Marker({
                    position: localepos,
                    title: title,
                    map: map,
                    optimized: false,
                });

                markers[i] = marker; 

                attachSecretMessage(marker, contentString);
            }
       
        }  
        
    }

    var myoverlay = new google.maps.OverlayView();
    myoverlay.draw = function () {
        this.getPanes().markerLayer.id='markerLayer';
    };
    myoverlay.setMap(map);
    
     showMarkers(); 
   /*  if (localepos){
       map.setCenter(localepos);
     }*/
}

google.maps.Map.prototype.setCenterWithOffset= function(map, latlng, offsetX, offsetY) {
    var ov = new google.maps.OverlayView();
    ov.onAdd = function() {
        var proj = this.getProjection();
        var aPoint = proj.fromLatLngToContainerPixel(latlng);
        aPoint.x = aPoint.x+offsetX;
        aPoint.y = aPoint.y+offsetY;
        map.setCenter(proj.fromContainerPixelToLatLng(aPoint));
    };
    ov.draw = function() {};
    ov.setMap(this);
};

function attachSecretMessage(marker, contentString) {
    var ibOptions = {
        content: contentString,
        pixelOffset: new google.maps.Size(-130, -260),
        closeBoxURL: ""
    };

  google.maps.event.addListener(marker, 'click', function() {
      //закрываем все предыдущие откритые инфобоксы
      closeAllInfoWindows();
      var ib = new InfoBox(ibOptions);
      infoBoxes.push(ib);
      ib.open(map, marker);
      map.setCenterWithOffset(map, marker.getPosition(),0, -150);
  });
}

//закрываем все инфобоксы
function closeAllInfoWindows() {
    for (var i = 0; i < infoBoxes.length; i++) {
        infoBoxes[i].close();
    }
}

//проверка адреса при потере фокуса
$(document).on("focusout", '.form-control.business_address', function(e){
    e.preventDefault();

    var nom = $(this).parent().parent().parent().data('nom'); // номер адресса в массиве

    // вулиця Світлицького, 11/23, Київ, Украина
    var address = $(this).parent().find('input:first').val();

    var count = $('.activemap').parents('.address_list').data('count');

    var inp_lat = $(this).parent().find('input.business_lat');
    var inp_lon = $(this).parent().find('input.business_lon');

    if (selectCity){
        address = address + ', ' + selectCity;
    }

    geocoder.geocode({'address': address}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);

            var lat = results[0].geometry.location.lat();
            var lon = results[0].geometry.location.lng();
            var localepos = new google.maps.LatLng(lat,lon);
//            alert(results[0].geometry.location.lat());
            clearMarkers();
            var marker = new google.maps.Marker({
                position: localepos,
                icon: markerUrl,
                map: map
            });

            markers[nom-1] = marker;
            showMarkers();

            inp_lat.val(lat);
            inp_lon.val(lon);

        } else {
            alert("Адрес не найден.");
        }
    });
});

//Для Business
// $(document).on("click", 'button.check-address', function(e){
//
//     e.preventDefault();
//
//     var nom = $(this).parent().parent().parent().data('nom'); // номер адресса в массиве
//
//     // вулиця Світлицького, 11/23, Київ, Украина
//     var address = $(this).parent().find('input:first').val();
//
//     var count = $('.activemap').parents('.address_list').data('count');
//
//     var inp_lat = $(this).parent().find('input.business_lat');
//     var inp_lon = $(this).parent().find('input.business_lon');
//
//     geocoder.geocode({'address': address}, function(results, status) {
//         if (status === google.maps.GeocoderStatus.OK) {
//             map.setCenter(results[0].geometry.location);
//
//             var lat = results[0].geometry.location.lat();
//             var lon = results[0].geometry.location.lng();
//             var localepos = new google.maps.LatLng(lat,lon);
// //            alert(results[0].geometry.location.lat());
//             clearMarkers();
//             var marker = new google.maps.Marker({
//                  position: localepos,
//                  icon: markerUrl,
//                  map: map
//              });
//
// //            for (var i = 0; i < markers.length; i++) {
// //                pos = markers[i].getPosition().toLocaleString(); // получаем координаты из маркера (lat,lon)
// //                console.log(pos);
// //            }
//
//             markers[nom-1] = marker;
//             showMarkers();
//
//             inp_lat.val(lat);
//             inp_lon.val(lon);
//
//         } else {
//           alert('Поле "Адрес" не заполненно. Невозможно установить маркер. Код ошибки: ' + status);
//         }
//     });
//
// });

//Для Post
$(document).on("click", 'button.check-address-post', function(e){

    e.preventDefault();

    address = $(document).find('#post-address').val();

    var inp_lat = $(document).find('#post-lat');
    var inp_lon = $(document).find('#post-lon');

    geocoder.geocode({'address': address}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK)
        {
            map.setCenter(results[0].geometry.location);
            lat = results[0].geometry.location.lat();
            lon = results[0].geometry.location.lng();
            localepos = new google.maps.LatLng(lat,lon)
            clearMarkers();
            var marker = new google.maps.Marker({
                position: localepos,
                icon: markerUrl,
                map: map
            });

            markers[0] = marker;
            showMarkers();

            inp_lat.val(lat);
            inp_lon.val(lon);

        } else {
            alert('Поле "Адрес" не заполненно. Невозможно установить маркер. Код ошибки: ' + status);
        }
    });

});

$('button.get-address').click(function (e) {
    e.preventDefault();

    var form = $(this).parent();
    var inp_lat = parseFloat(form.find('input.business_lat').val());
    var inp_lon = parseFloat(form.find('input.business_lon').val());

    console.log('');
    geocoder.geocode({'latLng': {lat: inp_lat, lng: inp_lon}}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK && results[0]) {
            var address = results[0].formatted_address;
            form.find('input.business_address').val(address);
        }
    });
});