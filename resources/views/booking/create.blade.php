
<x-layout>
@push('scripts')
    <script src="/js/create.js"></script>
@endpush

    <div class="alert alert-danger">
        <ul>

            @php
            var_dump($vaccines);
        @endphp

        </ul>
    </div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="/booking">
  @csrf
  <div class="row mb-3">
    <label for="firstname" class="col-sm-2 col-form-label">First Name</label>
    <div class="col-sm-10">
      <input name="firstname" type="text" class="form-control" id="firstname" value="{{ old('firstname') }}">
    </div>
  </div>
  <div class="row mb-3">
    <label for="secondname" class="col-sm-2 col-form-label">Second Name</label>
    <div class="col-sm-10">
      <input name="secondname" type="text" class="form-control" id="secondname" value="{{ old('secondname') }}">
    </div>
  </div>
  <div class="row mb-3">
    <label for="email" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
      <input name="email" type="email" class="form-control" id="email" value="{{ old('email') }}"> 
    </div>
  </div>
  <div class="row mb-3">
    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
    <div class="col-sm-10">
      <input name="phone" type="text" class="form-control" id="phone" value="{{ old('phone') }}"> 
    </div>
  </div>
  <div class="row mb-3">
    <label for="location" class="col-sm-2 col-form-label">Location</label>
    <div class="col-sm-10">
      <select class="form-select" aria-label="Default select example" name="location">
        @foreach ($locations as $location)
          @if (old('location') == $location)
            <option value="{{ $location }}" selected>{{ $location }}</option>
          @else
            <option value="{{ $location }}">{{ $location }}</option>
          @endif
        @endforeach
      </select>
    </div>
  </div>
  <div class="row mb-3">
    <label for="date" class="col-sm-2 col-form-label">Date</label>
    <div class="col-sm-10">
      <input name="date" type="text" class="form-control datepicker" id="date" value="{{ old('date') }}"> 
    </div>
  </div>
  <div class="row mb-3">
    <label for="time" class="col-sm-2 col-form-label">Time</label>
    <div class="col-sm-10">
      <input name="time" type="text" class="form-control timepicker" id="time" value="{{ old('time') }}"> 
    </div>
  </div>
  <fieldset class="row mb-3">
    <legend class="col-form-label col-sm-2 pt-0">Vaccine</legend>
    <div class="col-sm-10">
      @foreach ($vaccines as $vaccine)
        @if ($vaccine[1] == True)
          <div>
            <label class="form-check-label pe-2" for="gridRadios1">
                {{ $vaccine[0]}}
            </label>
            <div class="col-sm-10">
            @foreach (array_slice($vaccine,2) as $entry)
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="vaccine" id="gridRadios1" value="{{ $vaccine[0].(strval($entry)) }}">           
              <label class="form-check-label" for="gridRadios1">
                {{ $entry }}
              </label>
            </div>
            @endforeach
            </div>
          </div>
        @else
          <div class="form-check disabled">
            <input class="form-check-input" type="radio" name="vaccine" id="gridRadios3" value="option3" disabled>
            <label class="form-check-label" for="gridRadios3">
              {{ $vaccine[0] }}
            </label>
          </div>
        @endif
      @endforeach
  </fieldset>
  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button type="submit" class="btn btn-success">Submit Booking</button>
  </div>
</form>
</x-layout>