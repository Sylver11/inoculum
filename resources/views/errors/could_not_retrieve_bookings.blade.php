<x-layout>
    @if (session('status'))
        <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif
    <div>Could not retrieve bookings</div>
    <div class="d-grid col-6 mx-auto pt-4">
    <a class="btn btn-success" href="/booking/get-my-bookings" role="button">Try again</a>
    </div>
</x-layout>