@extends('layouts.app')

@section('content')
    
<div class="form">
    <div class="form-group">
        <label for="address_address">{{ __('location.address') }} :</label>
        <input type="text" id="address-input" name="address_address" class="form-control map-input">
        <input type="hidden" name="address_latitude" id="address-latitude" value="0" />
        <input type="hidden" name="address_longitude" id="address-longitude" value="0" />
    </div>
    <div class="form-group">
        {{ csrf_field() }}
        <div>
            <label for="address_address">{{ __('location.addressType') }} :</label>
            <select class="location_type_select" name="type">
                <option value="pin" selected>Pin</option>
                <option value="info">Info</option>
                <option value="parking">Parking</option>
                <option value="library">Library</option>
            </select>
            <input type="hidden" id="lat-input" name="lat" class="form-control">
            <input type="hidden" id="long-input" name="long" class="form-control">
        </div>
        <button id="create-location" type="submit" value="Save">{{ __('common.save') }}</button>
        
        <div id="location-data" hidden data-locations="{{ $locations }}"></div>
        <div id="address-map-container" style="width:100%;height:700px; ">
            <div style="width: 100%; height: 100%" id="address-map"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
    <script src="{{ asset('js/mapInput.js') }}"></script>
    <script>
        $(function(){
            $('#create-location').off('click');
            $('#create-location').on('click', function(e){
                e.preventDefault();
                var name = $('#address-input').val();
                var lat = $('#address-latitude').val();
                var long = $('#address-longitude').val();
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
                        $('#address-input').val('');console.log(name, lat, long);
                    }
                });
            });
        })
        
    </script>
@endsection