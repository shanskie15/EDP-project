<div class="container">
  <div class="row">
    <div class="col-md-3">
      <p><strong class="text-muted">Balance:</strong> <strong class="text-danger" id="paybal">{{number_format($order->balance,2)}}</strong></p>
    </div>
    <div class="col-md-7">
      <div class="form-group row">
        <label for="amount" class="col-md-4 col-form-label text-md-right">Amount:</label>
        <div class="col-md-6">
          <input id="amount" type="number" step="0.01" min="0" class="form-control input" name="amount" required autofocus>
            <span class="invalid-feedback" role="alert">
              <strong></strong>
            </span>
        </div>
      </div>
    </div>
    <div class="col-md-2">
      <select name="method" id="method" class="custom-select">
        <option value="cash">Cash</option>
        <option value="cheque">Cheque</option>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group row">
        <label for="comments" class="col-md-2 col-form-label text-md-right">Date:</label>
        <div class="col-md-10">
          <input type="date" name="date" id="date"
          @if ('1' == $order->new)
            value="{{date('Y-m-d')}}"

          @else
            value="{{date('Y-m-d',strtotime($order->delivery_date))}}" 
            
          @endif
            min="{{date('Y-m-d',strtotime($order->delivery_date))}}"
            max="{{date('Y-m-d')}}" required class="form-control">
        </div>
      </div>
      
    </div>
    <div class="col-md-8">
      <div class="form-group row">
        <label for="comments" class="col-md-2 col-form-label text-md-right">Comments:</label>
        <div class="col-md-10">
          <textarea name="comments" id="comments" placeholder="Optional" class="form-control" cols="80" rows="2"></textarea>
        </div>
      </div>
    </div>
  </div>
  
    

@if(count($payments) > 0)
  <div class="row">
    <table class="table table-hover" id="paymentsTable">
      <thead>
        <tr>
          <th>Date</th>
          <th>Method</th>
          <th class="text-right">Due</th>
          <th>Paid</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $paid = 0;  
        ?>
        @foreach ($payments as $payment)
        <?php $paid += $payment->amount;?>
          <tr>
            <td>
              {{date('D, M j, Y',strtotime($payment->date))}}
              @if(!empty($payment->comments))
                <button type="button" class="btn btn-info btn-sm float-right" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="top" data-content="{{$payment->comments}}">
                  Comments
                </button>
              @endif
            </td>
            <td>{{ucfirst($payment->method)}}</td>
            <td class="text-right">{{number_format($payment->due,2)}}</td>
            <td>{{number_format($payment->amount,2)}}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-right">Total paid:</td>
          <td><strong class="text-success">{{number_format($paid,2)}}</strong></td>
        </tr>
      </tfoot>
    </table>
  </div>
  <script>
    $(function () {
      $('[data-toggle="popover"]').popover()
    });
    $('.popover-dismiss').popover({
      trigger: 'focus'
    });
  </script>
@endif
  
</div>