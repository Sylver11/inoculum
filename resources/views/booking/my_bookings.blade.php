<x-layout>
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
@push('styles')
  <link href="{{ asset('css/vendor/jquery.datetimepicker.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
  <script src="{{ asset('js/vendor/jquery.datetimepicker.full.min.js') }}"></script>
  <script src="{{ asset('js/create.js') }}"></script>
@endpush
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
{{ echo $patient; }}
@endphp
</x-layout>