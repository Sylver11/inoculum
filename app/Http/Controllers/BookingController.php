<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Patient;
use App\Repository\PatientRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Validator;
use DateTime;

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
        // TODO
        // groups by date but need to handle current date special because
        // could be less appointments than specified in the config but already past 
        // that time

        $today = date("Y-m-d");
        $bookedDates = DB::table('bookings')
            ->select(DB::raw('DATE(datetime) as date'), DB::raw('count(*) as count'))
            ->where('datetime', '>=', $today)
            ->where('status', 'scheduled')
            ->groupBy('date', 'status')
            ->get();        
        $fullyBookedDates = array();
        foreach($bookedDates as $date){
            $bookingCount = $date->count;
            $dayOfWeek = date('w', strtotime($date->date));
            $allowTimesCount = count($this->config['types']['allowTimes'][$dayOfWeek]);
            if($bookingCount != 0 && $bookingCount >= $allowTimesCount || $allowTimesCount == 0){
                array_push($fullyBookedDates, $date->date);
            }
        }
        return response()->json($fullyBookedDates);
    }

    // This method return available slots based on the date.
    // Preferably this should be stored away in a class to 
    // be reusable. 
    public function getBookedSlotsByDate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
        ]);
        $bookedSlots = DB::table('bookings')
            ->select(DB::raw('TIME(datetime) as time'))
            ->where(DB::raw('DATE(datetime)'), '=', $request->get('date'))
            ->where('status', 'scheduled')
            ->get()
            ->pluck('time')
            ->toArray();
        $dayOfWeek = date('w', strtotime($request->get('date')));
        $times = $this->config['types']['allowTimes'][$dayOfWeek];

        // This is another example of code that should be part of a utility class
        // In case the requested date is today we need to run some additional checks
        $today = new DateTime('today');
        $requestedDate = new DateTime($request->get('date'));
        $diffDays = $today->diff($requestedDate)->days;
        if($diffDays == 0){
            $currentTime = date('H:i:s');
            foreach($times as $timeString){
                $time = date('H:i:s', strtotime($timeString));
                if ($currentTime > $time){
                    if (($key = array_search($timeString, $times)) !== false) {
                        unset($times[$key]);
                    }
                }
            }  
        }
        return array_diff($times, $bookedSlots);
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
        // TODO
        // Need to check if the time is valid eg. minute interval; from to; seconds = 00 
        $validator = Validator::make($request->all(), [ 
            'firstname' => 'required',
            'secondname' => 'required',
            'email' => 'required|email',
            'location' => 'required',
            'datetime'=>'required|after:yesterday',
            'vaccine' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // check if time is within accepted range
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $request->get('datetime'));
        $time = $datetime->format('H:i:s');

        // Check if time is within accepted range
        $dayOfWeek = date('w', strtotime( $request->get('datetime')));
        $times = $this->config['types']['allowTimes'][$dayOfWeek];
        if( !in_array($time, $times)){
            $validator
            ->getMessageBag()
            ->add('datetime', 'The booking time is not within the allowed times.');
        return redirect()->back()->withErrors($validator)->withInput();
        }

        // check if exact booking already exists
        if (Booking::where( 'datetime', $datetime )->where('status','!=', 'cancelled')->exists()){
            $validator
                ->getMessageBag()
                ->add('datetime', 'A booking for the same date and time already exists.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if patient with same email already exists otherwise create one
        $patient = Patient::where('email', $request->get('email'))->first();
        if ($patient === NULL) {        
            $patient = Patient::create($request->all());
        }
        else {
            // Preferably the code snippet below should be part of a utility class
            // or it could be added as a custom validation checker in the above validation
            // checking method as described here (this would be the cleanest solution):
            // https://laravel.com/docs/8.x/validation#using-rule-objects
            // And based on the answer by JustAMartin on this thread:
            // https://stackoverflow.com/questions/22158580/adding-custom-validation-errors-to-laravel-form
            // the better way of doing things is to manually alter the validater as suggested 
            // being the cleanest solution. Anything, else becomes hacky.
            
            $previousBooking = $patient->bookings
                ->where('status','!=', 'cancelled')
                ->last();
            
            // Only continue checking if patient already has/had a scheduled booking
            if ($previousBooking != NULL) {

                // Check that user has not booked same vaccination dose before
                if($request->get('vaccine') == $previousBooking->vaccine){
                    $validator
                        ->getMessageBag()
                        ->add('vaccine', 'You already have a booking for the same vaccine and dose.
                            In case you missed your previous booking you may cancel your booking under 
                            Cancel My Booking in the top right corner.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                // Removing the dose count from end of string
                $vaccine = substr_replace($request->get('vaccine') ,"", -1);
                $previousVaccine = substr_replace($previousBooking->vaccine,"", -1);

                // Check that user does not attempt cross vaccination
                if($vaccine != $previousVaccine){
                    $validator
                        ->getMessageBag()
                        ->add('vaccine', 'Your previous vaccination included a different
                            vaccine. Currently, cross-vaccination is not an option.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
    
                // Get the day difference between the previous booking and the current
                $dateDifference = ($previousBooking->datetime)
                    ->diff(date($request->get('datetime')))
                    ->days;
                $doseInterval = 0;
                foreach($this->config['types']['vaccines'] as $key => $val){
                    if ($val[0] === $vaccine) {
                        $doseInterval = $val[2];
                    }
                }

                // check if the day difference is lower than specified in the configs
                if($dateDifference < $doseInterval){
                    // Calculating recommanded date. This could also be part of a 
                    // utility class as it could be reused
                    $recommendedDate = date("Y-m-d",
                        strtotime(date($previousBooking->datetime).' + '.$doseInterval.' days')); 
                    $validator
                        ->errors()
                        ->add('vaccine', 'The minimum interval between your previous
                            vaccination booking and your current is too low. Consider
                            a date on or beyond the '.$recommendedDate);
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }
        }
        //  Generating random booking number and checking that its unique
        do {
            $bookingNumber = mt_rand( 10000000, 99999999 );
        } while ( Booking::where( 'number', $bookingNumber )->exists() );

        // Merging missing parameters and saving booking to db
        Booking::create(array_merge(
            $request->all(),
            ['patient_id' => $patient->id, 'number' => $bookingNumber]
        ));

        // Return redirect to MyBookings site with success message
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