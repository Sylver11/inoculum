<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;


class BookingController extends Controller {

    public function __construct(
        // Config $config
        // BookingRepository $bookings
        ) {
            $this->config = config('constant.booking');
        // $this->config = $config::get('constant.booking');
        // $this->bookings = $bookings;
    }

    // public function getAvailableDates(Request $request)
    // {
    //     $all = $this->orders->all();

    //     return View::make('orders', compact('all'));
    // }

    // public function getAvailableTimes(Request $request)
    // {
    //     $all = $this->orders->all();

    //     return View::make('orders', compact('all'));
    // }


    // Return booking form view with configs
    public function create(Request $request) {
      return view('booking/create', [
          'locations'=> $this->config['types']['locations'],
          'interval'=> $this->config['types']['interval'],
          'vaccines'=> $this->config['types']['vaccines'] 
        ]);
    }

    // Store  Form data
    public function store(Request $request) {

        // Form validation
        $this->validate($request, [
            'firstname' => 'required',
            'secondname' => 'required',
            'email' => 'required|email',
            'location' => 'required',
            'time'=>'required',
            'vaccine' => 'required',
         ]);

        //  Store data in database
        Booking::create($request->all());

        // 
        return back()->with('success', 'We have received your message and would like to thank you for writing to us.');
    }

}