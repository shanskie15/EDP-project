<form>
  {{ csrf_field() }}
  <div class="form-group row">
    <label for="firstname" class="col-md-4 col-form-label text-md-right">First name</label>
    <div class="col-md-6">
        <input id="firstname" type="text" class="form-control" name="firstname" required autofocus>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="middlename" class="col-md-4 col-form-label text-md-right">Middle name</label>
    <div class="col-md-6">
        <input id="middlename" type="text" class="form-control" name="middlename" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="lastname" class="col-md-4 col-form-label text-md-right">Last name</label>
    <div class="col-md-6">
        <input id="lastname" type="text" class="form-control" name="lastname" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="email" class="col-md-4 col-form-label text-md-right">E-mail Address</label>
    <div class="col-md-6">
        <input id="email" type="email" class="form-control" name="email" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="gender" class="col-md-4 col-form-label text-md-right">Gender</label>
    <div class="col-md-6">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="gender" id="female" value="female" checked>
        <label class="form-check-label" for="female">Female</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="gender" id="female" value="male">
        <label class="form-check-label" for="female">Male</label>
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="birth_date" class="col-md-4 col-form-label text-md-right">Date of birth</label>
    <div class="col-md-6">
        <input id="birth_date" type="date" max="{{date('Y-m-d', strtotime('-21 years'))}}" class="form-control" name="birth_date"  required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="contact" class="col-md-4 col-form-label text-md-right">Contact</label>
    <div class="col-md-6">
        <input id="contact" type="text" class="form-control" name="contact" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>
    <div class="col-md-6">
        <input id="address" type="text" class="form-control" name="address" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  
  <div class="form-group row">
    <label for="salary" class="col-md-4 col-form-label text-md-right">Salary</label>
    <div class="col-md-6">
        <input id="salary" type="number" class="form-control" name="salary" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
</form>