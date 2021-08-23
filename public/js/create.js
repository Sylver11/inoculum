var g_isSetAllowedTimes = false;
var g_Settings;
var g_fullyBookedDates;

function showAllowedDates() {
  if(g_Settings['disabledDates']){
    g_fullyBookedDates = g_fullyBookedDates.concat(g_Settings['disabledDates']);
  }
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
  const offset = currentDateTime.getTimezoneOffset()
  currentDateTime = new Date(currentDateTime.getTime() - (offset*60*1000))
  currentDateTime = currentDateTime.toISOString().split('T')[0]
  t.setOptions({timepicker:false});
  $.get('/booking/get-booked-slots-by-date', { date: currentDateTime},function(availableSlots) {
    if(availableSlots.length <= 0){
      g_isSetAllowedTimes = false;
    } else{
      g_isSetAllowedTimes = true;
      t.setOptions({allowTimes:Object.values(availableSlots)});
      t.setOptions({timepicker:true});
    }
    $i.datetimepicker('show');
  })
  .fail(function(xhr, status, error) {
    alert( error );
  })
};

$.when(
    $.get('/booking/get-config', function(settings) {
      g_Settings = settings;
    }),
    $.get('/booking/get-fully-booked-dates', function(fullyBookedDates) {
      g_fullyBookedDates = Object.values(fullyBookedDates);
    }),
  ).then(function() {
      $('#datetime').datetimepicker({
        onGenerate:showAllowedDates,
        disabledWeekDays:Object.values(g_Settings['disabledWeekDays']),
        minDate:0,
        defaultTime:'00:00',
        format:'Y-m-d H:i:s',
        timepicker:false,
        onSelectDate:showAllowedTimes,
  })
})