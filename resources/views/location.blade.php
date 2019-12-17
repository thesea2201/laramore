@extends('layouts.app')

@section('content')
    
    <div class="form-group">
        <label for="address_address">Address</label>
        <input type="text" id="address-input" name="address_address" class="form-control map-input">
        <input type="hidden" name="address_latitude" id="address-latitude" value="0" />
        <input type="hidden" name="address_longitude" id="address-longitude" value="0" />
    </div>
    {{-- <div class="form-group">
        {{ csrf_field() }}
        <label for="address_address">Link</label>
        <input type="text" id="maps-link-input" name="name" class="form-control" required>
        <select name="type">
            <option value="pin" selected>Pin</option>
            <option value="info">Info</option>
            <option value="parking">Parking</option>
            <option value="library">Library</option>
        </select>
        <input type="hidden" id="lat-input" name="lat" class="form-control">
        <input type="hidden" id="long-input" name="long" class="form-control">
        <input id="create-location" type="submit" value="Save">
        <div class="form-group">

    </div> --}}
        <div id="location-data" hidden data-locations="{{ $locations }}"></div>
    <div id="address-map-container" style="width:100%;height:700px; ">
        <div style="width: 100%; height: 100%" id="address-map"></div>
    </div>
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize" async defer></script>
    <script src="{{ asset('js/mapInput.js') }}"></script>
    <script>
        $(function(){
            $('#create-location').off('click');
            $('#create-location').on('click', function(e){
                e.preventDefault();
                var address = $('#maps-link-input').val();
                if(address.trim() == ''){
                    return false;
                }
                // var address = 'www.google.com/maps/place/UBND+Phường+Trung+Mỹ+Tây/@10.8444213,106.6182869,15.21z/data=!4m6!3m5!1s0x31752a30054cfa5b:0x4a3ef2e4744b3c16!4b1!8m2!3d10.847239!4d106.6169487';
                var splitArr = address.split('/');
                var name = splitArr[3].split('+').join(' ');
                var latLong = splitArr[4].split(',');
                var lat = latLong[0].replace('@', '');
                var long = latLong[1];
                var type = $('[name="type"').val();
                $.ajax({
                    type: 'POST',
                    url: '/save-maps',
                    data: { 
                        name: name, 
                        lat: lat, 
                        long: long,
                        type: type,
                        _token: $('[name="_token"]').val()
                    },
                    success: function(response){
                        $('#maps-link-input').val('');console.log(lat);
                    }
                });
            });
        })
        
    </script>
@endsection