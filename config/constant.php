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
            'months_open' => (array) env('BOOKING_MONTHS_OPEN', 
                ['January','February','March','April','May',
                'June','July','August','September','October',
                'November','December']),
            'allowTimes' => (array) env('BOOKING_HOURS_OPEN',
                [
                    '07:00','07:15','07:30','07:45',
                    '08:00','08:15','08:30','08:45',
                    '09:00','09:15','09:30','09:45',
                    '10:00','10:15','10:30','10:45',
                    '11:00','11:15','11:30','11:45',
                    '12:00','12:15','12:30','12:45',
                    '14:00','14:15','14:30','14:45',
                    '15:00','15:15','15:30','15:45',
                    '16:00','16:15','16:30','16:45']),
            'days_open' => (array) env('BOOKING_DAYS_OPEN'),
                ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
            'dates_closed' => (array) env('BOOKING_DATES_CLOSED',
                ['01/01/2021','21/03/2021','22/03/2021','02/04/2021',
                    '04/04/2021','10/12/2021','25/12/2021','26/12/2021',
                    '27/12/2021']),
        ],
    ],
];
