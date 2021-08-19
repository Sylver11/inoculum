<x-layout>
@push('scripts')
  <script src="{{ asset('js/patient_login.js') }}"></script>
@endpush
<form>
  <div class="row mb-3">
    <label for="firstname" class="col-sm-2 col-form-label">First Name</label>
    <div class="col-sm-10">
      <input name="firstname" type="text" class="form-control" id="firstname" value="{{ old('firstname') }}" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="secondname" class="col-sm-2 col-form-label">Second Name</label>
    <div class="col-sm-10">
      <input name="secondname" type="text" class="form-control" id="secondname" value="{{ old('secondname') }}" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="email" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
      <input name="email" type="email" class="form-control" id="email" value="{{ old('email') }}" required> 
    </div>
  </div>
  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button type="submit" class="btn btn-success">Login</button>
  </div>
</form>
</x-layout>