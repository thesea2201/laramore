function initMap() {
    const DFM = { lat: 10.8542955, long: 106.6261099 };
    const type = { pin: 'pin', info: 'info', parking: 'parking', library: 'library' };
    map = new google.maps.Map(document.getElementById('address-map-container'), {
        center: { lat: DFM.lat, lng: DFM.long },
        // center: { lat: 10, lng: 106 },
        zoom: 15
    });
    const icons = {
        pin: {
            icon: 'image/icons/maps-icon.png'
        },
        parking: {
            icon: 'image/icons/parking_lot_maps.png'
        },
        library: {
            icon: 'image/icons/library_maps.png'
        },
        info: {
            icon: 'image/icons/info-i_maps.png'
        }
    };

    var features = [];
    const size = 0.01;
    features.push({
        position: new google.maps.LatLng(DFM.lat, DFM.long),
        type: type.pin
    });
    
    // markerRandomPosition(DFM, size);

    data = $('#location-data').data('locations');
    $.each(data, function(i, item) {
        features.push({
            position: new google.maps.LatLng(item.lat, item.long),
            type: item.type
        });
    });

    markerLocationOnMap(features, map, icons);
}

function getRandomPositionAround(area, size) {
    let x = area.lat + Math.random() * 2 * size - size;
    let y = area.long + Math.random() * 2 * size - size;
    return { lat: x, long: y };
};

function getRandomPositionAround(area, size) {
    let x = area.lat + Math.random() * 2 * size - size;
    let y = area.long + Math.random() * 2 * size - size;
    return { lat: x, long: y };
};

function markerRandomPosition(location, size){
    for (let i = 0; i < 4; i++) {
        let point = getRandomPositionAround(location, size);
        features.push({
            position: new google.maps.LatLng(point.lat, point.long),
            type: type.info
        });
    }

    for (let i = 0; i < 4; i++) {
        let point = getRandomPositionAround(location, size);
        features.push({
            position: new google.maps.LatLng(point.lat, point.long),
            type: type.parking
        });
    }

    for (let i = 0; i < 4; i++) {
        let point = getRandomPositionAround(location, size);
        features.push({
            position: new google.maps.LatLng(point.lat, point.long),
            type: type.library
        });
    }
}

function markerLocationOnMap(location, map, icons) {
    for (var i = 0; i < location.length; i++) {
        var marker = new google.maps.Marker({
            position: location[i].position,
            icon: icons[location[i].type].icon,
            map: map
        });
    };
}

function initialize() {

    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    const locationInputs = document.getElementsByClassName("map-input");

    const autocompletes = [];
    const geocoder = new google.maps.Geocoder;
    console.log(locationInputs);
    for (let i = 0; i < locationInputs.length; i++) {

        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");
        const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';

        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || 10.8542955;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 106.6261099;

        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: { lat: latitude, lng: longitude },
            zoom: 15
        });
        const marker = new google.maps.Marker({
            map: map,
            position: { lat: latitude, lng: longitude },
        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({ input: input, map: map, marker: marker, autocomplete: autocomplete });
    }

    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            marker.setVisible(false);
            const place = autocomplete.getPlace();

            geocoder.geocode({ 'placeId': place.place_id }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    setLocationCoordinates(autocomplete.key, lat, lng);
                }
            });

            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

        });
    }
}

function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}