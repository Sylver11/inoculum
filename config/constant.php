<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Booking config types and their default values
    |--------------------------------------------------------------------------
    |
    | These values must be set in the .env file as string as following:
    | BOOKING_LOCATION; array; vaccination site names 
    | BOOKING_INTERVAL; int; specify session interval in minutes
    | BOOKING_VACCINE; multidimensional array; with each nested array representing
    |   a vaccine with the first entry being the vaccine name and second entry 
    |   specifying availability denoted in boolean of True or False. The remaining entries
    |   represent the availabilty of doses. For example, if there is only the second dose
    |   available for AstraZeneca then the only remaining entry would be an int of number 2.
    |   However, if there there are three doses but and all of them would be availabe the 
    |   remaining ints after the second entry would be 1, 2, 3
    | 
    | All of the above can be can be ignored if the below defaults suffice
    */

    'booking' => [

        'types' => [
            'locations' => (array) env('BOOKING_LOCATIONS', ['Uhingen']),
            'interval' => env('BOOKING_INTERVAL', 15),
            'vaccines' => (array) env('BOOKING_VACCINES', [
                ['AstraZeneca', True, 2],
                ['Sinopharm', True, 1, 2],
                ['Moderna', False, 1, 2]
            ]),
        ],
    ],
];