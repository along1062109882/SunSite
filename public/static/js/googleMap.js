$(function() {
  $.getScript( "http://ditu.google.cn/maps/api/js?v=3&key=AIzaSyCUBW5-qZMjWP8ITe0ldu-VnBxBXkIOxkY&sensor=false&callback=initialize" )
  .done(function( script, textStatus ) { initialize() })
  .fail(function( jqxhr, settings, exception ) {
    console.log( "Google Map Can Not Loaded." );
  });
})
  function initialize() {
    var center = new google.maps.LatLng(22.188807,113.550114);
    var mapProp = {
        center,
        zoom: 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        styles: [
          {
            featureType: 'all',
            stylers: [
              {
                saturation: 0,
              },
              { hue: '#a1925c' },
            ],
          },
          { featureType: 'road', stylers: [{ saturation: -70 }] },
          { featureType: 'transit', stylers: [{ visibility: 'off' }] },
          {
            featureType: 'water',
            stylers: [{ visibility: 'simplified' }, { saturation: -60 }],
          },
        ]
    };
    var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
    var marker = new google.maps.Marker({
      position: center,
    });

    marker.setMap(map);
  }
  
