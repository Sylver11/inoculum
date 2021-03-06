<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">Covid-19 Vaccination Booking System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
      @if(!Request::is('booking/my-bookings/*') && !Request::is('booking/patient-login*'))
        <a href="/booking/patient-login" class="btn btn-outline-danger" type="button">Cancel My Booking</a>
      @endif
    </div>
  </div>
</nav>