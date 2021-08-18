var g_isSetAllowedTimes = false;
var g_Settings;
var g_fullyBookedDates;

function showAllowedDates() {
  g_fullyBookedDates.forEach(function(item, index) {
    item = new Date(item);
    $('.xdsoft_date[data-date=' + item.getDate()
    + '][data-month=' + item.getMonth()
    + '][data-year=' + item.getFullYear() + ']')
    .addClass('xdsoft_disabled');
  })
  if(!g_isSetAllowedTimes){
    $('.xdsoft_time').addClass('xdsoft_disabled');
  }
}

var showAllowedTimes = function( currentDateTime, $i ){
  var t = this;
  $.get('/booking/get-booked-slots-by-date', function(bookedSlots) {
    bookedSlots = ['14:15','15:45']
    allowedTimes = g_Settings['allowTimes'];
    allowedTimes = allowedTimes.filter( ( el ) => !bookedSlots.includes( el ) );
    // console.log(allowedTimes);
    $('.xdsoft_time').removeClass('xdsoft_disabled');
    g_isSetAllowedTimes = true;
    t.setOptions({allowTimes:allowedTimes});
    t.setOptions({timepicker:true});
    $i.datetimepicker('show');
  })
};

$.when(
    $.get('/booking/get-config', function(settings) {
      g_Settings = settings;
    }),
    $.get('/booking/get-fully-booked-dates', function(dates) {
      g_fullyBookedDates = ['2021-08-18','2021-08-29'];
    }),
  ).then(function() {
      $('#datetime').datetimepicker({
        onGenerate:showAllowedDates,
        disabledWeekDays:[0, 3, 4],
        minDate:0,
        defaultTime:'00:00',
        format:'Y-m-d H:i',
        timepicker:false,
        onSelectDate:showAllowedTimes,
  })
})