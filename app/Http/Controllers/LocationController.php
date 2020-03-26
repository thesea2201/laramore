<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $data = [
            'name'              =>$request->get('name'),
            'address_address'   => $request->get('name'),
            'lat'               => $request->get('lat'),
            'long'              =>  $request->get('long'),
            'type'              =>  $request->get('type')
        ];  
        $location = Location::create($data);
        return redirect()->route('map.index');
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(){
        $locations = Location::get();
        if($locations){
            $locations = $locations->toArray();
            $locations = json_encode($locations);
        }
        $urlRegex = Config::get('constants.urlRegex');
        // dd($locations);
        return view('location', compact('locations', 'urlRegex'));
    }
}
