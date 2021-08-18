var g_settings;
// $.datetimepicker({
// 	format:'d/m/Y H:i', minDate:'0',
// })
// var setDefaults = function( currentDateTime ){
//   this.setOptions({
//       minDate:'0',
//       format:'d/m/Y H:i'
//   });
// };

Object.size = function(obj) {
  var size = 0,
    key;
  for (key in obj) {
    if (obj.hasOwnProperty(key)) size++;
  }
  return size;
};

function reconsileSettings(){
    // var settings = {format:'d/m/Y H:i', minDate:'0',};
    // settings['disabledDays'] = g_settings['dates_closed'];
    // console.log(g_settings)
    // console.log(settings)
    return function( currentDateTime ){
      var size = Object.size(g_settings);
      for ( var i = 0, l =  size; i < l; i++ ) {
        
        console.log(g_settings[ i ])
      }
      this.setOptions({
          minDate:'0',
          format:'d/m/Y H:i'
      });
      
    }
    //return Settings;

//
}

$.when(
    $.get('/booking/get-config', function(settings) {
      g_settings = settings;
    }),
    // $.get('/booking/get-available-slots', function(slots) {
    //   globalStore.slots = slots;
    // }),
  ).then(function() {
      console.log(g_settings)
      var Settings = reconsileSettings();
      $('#datetime').datetimepicker({onShow:Settings});
    // $('#datetime').datetimepicker({
    //     // i18n:{
    //     //  en:{
    //       months:[
    //        'Januar','Februar','März','April',
    //        'Mai','Juni','Juli','August',
    //        'September','Oktober','November','Dezember',
    //       ],
    //       dayOfWeek:["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    //     //  },
    //     // },
    //     format:'d/m/Y H:i',
    //     minDate:'0',
    //     allowTimes:[
    //         '12:00', '13:00', '15:00',
    //         '17:00', '17:05', '17:20', '19:00', '20:00'
    //        ],
    //     disabledDates: ['30/08/2021'],
    // });

  });






