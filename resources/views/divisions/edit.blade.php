@if(count($employees) > 0)
<form>
  {{ csrf_field() }}
  <div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">Division Name</label>
    <div class="col-md-6">
      <input id="name" type="text" value="{{$division->name}}" class="form-control input" name="name" required autofocus>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="area" class="col-md-4 col-form-label text-md-right">Area</label>
    <div class="col-md-6">
        <input id="area" type="text" value="{{$division->area}}" class="form-control input" name="area" required>

				<span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="agent_id" class="col-md-4 col-form-label text-md-right">Agent</label>
    <div class="col-md-6">
      <select name="agent_id" id="agent_id" class="custom-select input" required>
        <option value=""> -- </option>
        @foreach ($employees as $employee)
          <option value="{{$employee->id}}"
            @if ($employee->id == $division->agent_id)
              selected
            @endif
          >{{$employee->firstname}} {{$employee->lastname}}</option>
        @endforeach
      </select>

      <span class="invalid-feedback" role="alert">
        <strong></strong>
      </span>
    </div>
  </div>
  

</form>

@else
  <p class="alert alert-danger">No employee found. Cannot create division.</p>
@endif