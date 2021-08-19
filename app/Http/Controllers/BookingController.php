<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Patient;
use App\Repository\PatientRepositoryInterface;

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
        // $bookedDates = $this->bookings->where();
        $bookedDates = ['dummyString' => 'something','dummyString' => 'something', ];
        // return response()->json($this->bookingRepository->all(), 200);
        return response()->json($this->config['types']);
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

    // Return bookings per patient by firstname, secondname and email
    public function myBookings($firstname, $secondname, $email) {

        // TODO 
        // Eventually one would create a proper authentication class
        // but this will do in the meantime
        $matchThese = [
            'firstname' => $firstname,
            'secondname' => $secondname,
            'email' => $email];
        $patient = Patient::where($matchThese)->first()->load('bookings');
        if ($patient === null){
            return view('errors.could_not_retrieve_bookings', [], 500);
        }
        return view('booking/my_bookings', ['patient'=>$patient]);
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
            'datetime'=>'required',
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

        //  Store data in database while adding patient id and random booking number 
        Booking::create(array_merge(
            $request->all(),
            ['patient_id' => $patient->id, 'number' => rand(10, 10)]
        ));

        // Return redirect to my bookings site with success message
        return redirect()->route('myBookings', [
            'firstname' => $patient->firstname,
            'secondname' => $patient->secondname,
            'email' => $patient->email
        ])->with('message', 'Successfully added booking');
    }
}