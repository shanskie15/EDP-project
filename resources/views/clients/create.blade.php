<form>
    {{ csrf_field() }}
    <div class="form-group row">
      <label for="store" class="col-md-4 col-form-label text-md-right">Store</label>
      <div class="col-md-6">
          <input id="store" type="text" class="form-control input" name="store" required autofocus>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="owner" class="col-md-4 col-form-label text-md-right">Owner</label>
      <div class="col-md-6">
          <input id="owner" type="text" class="form-control input" name="owner" required>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="contact" class="col-md-4 col-form-label text-md-right">Contact Number</label>
      <div class="col-md-6">
          <input id="contact" type="text" class="form-control input" name="contact" required>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="email" class="col-md-4 col-form-label text-md-right">E-mail Address</label>
      <div class="col-md-6">
          <input id="email" type="email" class="form-control input" name="email" required>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="city" class="col-md-4 col-form-label text-md-right">City</label>
      <div class="col-md-6">
          <input id="city" type="text" class="form-control input" name="city"  required>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="province" class="col-md-4 col-form-label text-md-right">Province</label>
      <div class="col-md-6">
          <input id="province" type="text" class="form-control input" name="province"  required>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="zipcode" class="col-md-4 col-form-label text-md-right">Zip Code</label>
      <div class="col-md-6">
          <input id="zipcode" type="text" class="form-control input" name="zipcode"  required>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="contact_person" class="col-md-4 col-form-label text-md-right">Contact Person</label>
      <div class="col-md-6">
          <input id="contact_person" type="text" class="form-control input" name="contact_person" required>
  
          <span class="invalid-feedback" role="alert">
            <strong></strong>
          </span>
      </div>
    </div>
    <div class="form-group row">
      <label for="address" class="col-md-4 col-form-label text-md-right">Division</label>
      <div class="col-md-6">
        <select name="division_id" id="division_id" class="custom-select input">
          <option value=""> -- </option>
          @foreach ($divisions as $division)
            <option value="{{$division->id}}">{{$division->name}}</option>
          @endforeach
        </select>
        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
      </div>
    </div>
  </form>