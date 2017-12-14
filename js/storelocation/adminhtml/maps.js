//     function initMap() {
//         var input = document.getElementById('pac-input');
//
//         var autocomplete = new google.maps.places.Autocomplete(input);
//         autocomplete.addListener('place_changed', function() {
//             console.log(autocomplete.getPlace());
//
//         })
//     }
//     initMap();
//
// alert("ok");

function initMap() {
    var input = document.getElementById('address');
    var latitude = document.getElementById('latitude');
    var longitude = document.getElementById('longitude');
    var administrative_area_level_1 = document.getElementById('city');
    var postal_code = document.getElementById('postcode');
    var autocomplete = new google.maps.places.Autocomplete(input,{
        types: ['geocode']
    });
    autocomplete.addListener('place_changed', function () {
        longitude.value='';
        latitude.value='';
        administrative_area_level_1.value='';
        postal_code.value='';
        var place = autocomplete.getPlace();
        longitude.value= place.geometry.location.lng();
        latitude.value= place.geometry.location.lat();
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if(addressType=="administrative_area_level_1"){
                administrative_area_level_1.value = place.address_components[i].long_name;
            }
            if(addressType=="postal_code"){
                postal_code.value = place.address_components[i].long_name;
            }

        }
    })

}