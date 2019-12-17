<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $data = [
            'name'              =>$request->get('name'),
            'address_address'   => $request->get('address_address'),
            'lat'               => $request->get('lat'),
            'long'              =>  $request->get('long'),
            'type'              =>  $request->get('type')
        ];  
        $location = Location::create($data);
        // return redirect()->route('map.index');
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
        // dd($locations);
        return view('location', compact('locations'));
    }
}
