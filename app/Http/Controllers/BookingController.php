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

    public function getConfig(Request $request){
        return response()->json($this->config['types']);
    }

    // public function getAvailableSlots(Request $request)
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
        $validated = $request->validate([
            'firstname' => 'required',
            'secondname' => 'required',
            'email' => 'required|email',
            'location' => 'required',
            'datetime'=>'required',
            'vaccine' => 'required',
         ]);

        // Check if patient with same email already exists otherwise create one
        $patient = Patient::where('email', '=', Input::get('email'))->first();
        if ($patient === null) {
            $patient = Patient::create($request->all());
        }
        else {
            // Check whether the patient already had the vaccine
            // If not check if the time span between the vaccines is correct
            // return descriptive error message
        }

        // Add patient id to request object
        $request->request->add(['patient_id' => $patient->getId()]);
        
        //  Store data in database
        Booking::create($request->all());

        // Return success message
        return back()->with('success', 'We have received your message and would like to thank you for writing to us.');
    }

}