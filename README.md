# Inoculum - Covid-19 Vaccination Booking System
## About

This application serves as a booking automation system for a single vaccination site in order to combat long queues and thus relieve pressure of both the patients and on-site workers. The application name (Inoculum) is derived from Latin and means as much as adding a foreign substance to one's body that immunizes against a certain threat (virus).
The current functionality includes 

- Creating booking based on location, vaccine, dose, date and time
- Cancelling booking 
- Configuration includes:
- - disabled week days
- - disabled dates (public holidays)
- - allowed times to book
- - available vaccines and their available doses
- - minimum interval (in days) between vaccinations
- - locations (vaccination sites)
- Validation checks inlude:
- - required fields
- - email format
- - datetime format 
- - cross vaccination bookings
- - interval between bookings


## TODOS
- Further validation as specified in code
- Disable today date when past time and no slots open

## Development Setup
- At root of application execute the following commands:
- - ./vendor/bin/sail up
- In a new window run the migration command:
- - ./vendor/bin/sail artisan migrate

Visit app at http://localhost

Keep in mind that you mind need to stop local instances of services running on port 80 and 3306

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
