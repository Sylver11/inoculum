<x-layout>
@push('scripts')
  <script src="{{ asset('js/my_bookings.js') }}"></script>
@endpush
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<h2>{{ $patient->firstname }} {{ $patient->secondname }}</h2>
<ul class="list-group">
    @foreach ($patient->bookings as $booking)
        <div class="card mb-2">
            <div class="card-header">
                <span class="badge bg-primary rounded-pill">{{ $booking->status }}</span>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $booking->datetime }}</h5>
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <p class="card-text d-inline-block">At {{ $booking->location }} for {{ $booking->vaccine }} ({{ $booking->number }})</p>
                    </div>
                    @if ($booking->status == 'scheduled')
                        <form method="POST" action="/booking/cancel-booking" class="mb-0">
                            @csrf
                            <input name="number" type="hidden" value="{{ $booking->number }}">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Cancel Booking</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</ul>
</x-layout>