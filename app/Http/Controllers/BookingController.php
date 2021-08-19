<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Patient;
use App\Repository\PatientRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
* This would be a perfect case to implement an Archtictual Decision Record (ADR)
* As one can see below I integrated a dependency injection with the 
* PatientRepositoryInterface (not fully working yet) but this would replace the 
* DB calls currently executed using the DB instane of the models.
*/

class BookingController extends Controller {

    public function __construct(
        // PatientRepositoryInterface $patientRepository
        ) {
            $this->config = config('constant.booking');
            // $this->patientRepository = $patientRepository;
    }

    public function getConfig(Request $request){
        return response()->json($this->config['types']);
    }

    public function getFullyBookedDates(Request $request)
    {
        // Retrieves all booked dates (only date) from the current date onwards
        // groups by date but need to handle current date special because
        // could be less appointments than specified in the config but already past 
        // that time

        $today = date("Y-m-d");
        $bookedDates = DB::table('bookings')
            ->select(DB::raw('DATE(datetime) as date'), DB::raw('count(*) as count'))
            ->where('datetime', '>', $today)
            ->where('status', 'scheduled')
            ->groupBy('date', 'status')
            ->get();
        // foreach($bookedDates as $Dates){
            // here compare them with the configs and if config length for that date is the
            // same as the the count of the date the add the fully booked dates
        // }
        return response()->json($bookedDates);
    }

    public function getBookedSlotsByDate(Request $request)
    {
        // Retrieves all booked slots per date specified
        // $bookedSlots = $this->bookings->where();
        $bookedSlots = 'dummyString';
        return response()->json($this->config['types']);
    }


    // Return booking form view with configs
    public function create(Request $request) {
      return view('booking/create', [
          'locations'=> $this->config['types']['locations'],
          'vaccines'=> $this->config['types']['vaccines'] 
        ]);
    }

    public function patientLogin(Request $request) {
        return view('booking/patient_login');
    }

    // Return bookings per patient by firstname, secondname and email
    public function myBookings($firstname, $secondname, $email) {

        // TODO 
        // Eventually one would create a proper authentication class
        // but this will do in the meantime
        $matchThese = [
            'firstname' => $firstname,
            'secondname' => $secondname,
            'email' => $email
        ];
        
        // TODO
        // The error message does not pull through    
        $patient = Patient::where($matchThese)->first();
        if ($patient === null){
            return view('errors.could_not_retrieve_bookings')->with('message', 'Could not retrieve booking details');
        }
        return view('booking/my_bookings', ['patient'=>$patient->load('bookings')]);
      }

    // Store form data
    // This receives a normal (non-asynchronise) form submission
    public function store(Request $request) {

        // No input data trimming or normalisation neccessary according to the following resource:
        // https://laravel.com/docs/8.x/requests#input-trimming-and-normalization

        // Form validation partially on the frontend but mostly on the backend
        $validated = $request->validate([
            'firstname' => 'required',
            'secondname' => 'required',
            'email' => 'required|email',
            'location' => 'required',
            'datetime'=>'required|after:yesterday',
            'vaccine' => 'required',
         ]);

        // Check if patient with same email already exists otherwise create one
        $patient = Patient::where('email', '=', $request->get('email'))->first();
        if ($patient === NULL) {        
            $patient = Patient::create($request->all());
        }
        else {
            // Check whether the patient already had the vaccine
            // If not check if the time span between the vaccines is correct
            // return descriptive error message
        }

        // Store data in database while adding patient id and random booking number 
        do {
            $bookingNumber = mt_rand( 10000000, 99999999 );
         } while ( Booking::where( 'number', '=', $bookingNumber )->exists() );
        Booking::create(array_merge(
            $request->all(),
            ['patient_id' => $patient->id, 'number' => $bookingNumber]
        ));

        // Return redirect to my bookings site with success message
        return redirect()->route('myBookings', [
            'firstname' => $patient->firstname,
            'secondname' => $patient->secondname,
            'email' => $patient->email
        ])->with('message', 'Successfully added booking');
    }

    public function cancelBooking(Request $request) {
        $validated = $request->validate([
            'number' => 'required',
         ]);
        $booking = Booking::where('number', $request->get('number'))->first()->load('patient');
        $booking->update(['status' => 'cancelled']);
        return redirect()->route('myBookings', [
            'firstname' => $booking->patient->firstname,
            'secondname' => $booking->patient->secondname,
            'email' => $booking->patient->email
        ])->with('message', 'Successfully cancelled booking');
    }
}