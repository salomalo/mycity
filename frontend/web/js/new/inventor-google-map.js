jQuery(document).ready(function($) {
    'use strict';

    /**
     * Switch
     */
    $('.map-switch').on('click', function() {
        $(this).closest('.map-wrapper').find('.map-inner').toggleClass('closed');
        var id = $(this).closest('.widget').attr('id');

        if ($(this).closest('.map-wrapper').find('.map-inner').hasClass('closed')) {
            Cookies.set('map-toggle-' + id, 'closed', { expires: 7 });
        } else {
            Cookies.set('map-toggle-' + id, 'open', { expires: 7 });
        }
    });

});


$('#map-control-zoom-in').on('click', function (e) {
    e.preventDefault();
    var zoom = map.getZoom();
    map.setZoom(zoom + 1);
});

$('#map-control-zoom-out').on('click', function (e) {
    e.preventDefault();
    var zoom = map.getZoom();
    map.setZoom(zoom - 1);
});

$('#map-control-type-roadmap').on('click', function (e) {
    e.preventDefault();
    map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
});

$('#map-control-type-terrain').on('click', function (e) {
    e.preventDefault();
    map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
});

$('#map-control-type-satellite').on('click', function (e) {
    e.preventDefault();
    map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
});

$('#map-control-current-position').on('click', function (e) {
    e.preventDefault();

    $('input[name=geolocation]').attr('value', 'Loading address...');

    navigator.geolocation.getCurrentPosition(function (position) {
        initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        map.setCenter(initialLocation);

        $('input[name=distance-latitude]').attr('value', position.coords.latitude);
        $('input[name=distance-longitude]').attr('value', position.coords.longitude);
        addressFromPosition(position);
    }, function (error) {
        map.center = new google.maps.LatLng(settings.center.latitude, settings.center.longitude);
        $('input[name=geolocation]').attr('value', 'Failed to load address');
    });
});