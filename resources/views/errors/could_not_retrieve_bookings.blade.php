<x-layout>
    @if (session('message'))
        <div class="alert alert-danger">
            {{ session('message') }}
        </div>
    @endif
    <div class="d-grid col-6 mx-auto pt-4">
    <a class="btn btn-success" href="/booking/patient-login" role="button">Try again</a>
    </div>
</x-layout>