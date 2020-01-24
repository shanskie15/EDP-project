
 
<form>
  {{ csrf_field() }}
  <div class="form-group row">
    <label for="delivery_date" class="col-md-4 col-form-label text-md-right">Delivery date</label>
    <div class="col-md-6">
      <input id="delivery_date" type="date" class="form-control input" name="delivery_date" required autofocus
        @if(isset($order))
          value="{{$order->delivery_date}}"
        @endif
      >
        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="client_id" class="col-md-4 col-form-label text-md-right">Client</label>
    <div class="col-md-6">
        <select name="client_id" id="client_id" class="custom-select input">
          <option value="">--</option>
          @foreach ($clients as $client)
            <option value="{{$client->id}}"
              @if(isset($order) && $order->client_id == $client->id)
                selected
              @endif
            >{{$client->store}}</option>
          @endforeach
        </select>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="remarks" class="col-md-4 col-form-label text-md-right">Remarks</label>
    <div class="col-md-6">
      <textarea id="remarks" placeholder="Optional" class="form-control input" name="remarks" required autofocus
        @if(isset($order))
          value="{{$order->remarks}}"
        @endif
      ></textarea>
        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  
</form>
