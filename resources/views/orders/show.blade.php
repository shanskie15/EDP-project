<div class="container">
  <div class="row">
    <div class="col-md-6">
      <p><strong>Shipping:</strong> {{date('l, F j, Y',strtotime($order->delivery_date))}}</p>
      <p><strong>Number of products:</strong> {{$order->num_products}}</p>
      <p><strong>Total Amount:</strong> <strong class="text-muted"> ₱ {{number_format($order->total_amount,2)}}</strong></p>
      <p><strong>Status: <i id="status">{{$order->status}}</i>, <i id="paid">{{$order->paid}}</i></strong></p>
    </div>
    <div class="col-md-6">
      <p><strong>Store:</strong> {{$order->store}}, {{$order->city}}, {{$order->province}} {{$order->zipcode}}</p>
      <p><strong>Contact Person:</strong> {{$order->owner}} / {{$order->contact_person}}</p>
      <p><strong>Contact:</strong> {{$order->contact}} / {{$order->email}}</p>
      <p>
        <strong>Balance:</strong> <strong class="text-muted"> ₱ {{number_format($order->balance,2)}}</strong>
        <span class="float-right">
          <strong>Paid: <span class="text-muted">₱ {{number_format($paid,2)}}</span></strong>
        </span>
        
      </p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <table class="table table-hover" id="cart">
        <thead>
          <tr>
            <th style="width:55%">Product</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($cart as $item)
            <tr>
              <td>{{$item->name}}</td>
              <td>{{number_format($item->price,2)}}</td>
              <td>{{$item->quantity}}</td>
              <td>{{number_format($item->subtotal,2)}}</td>
            </tr>  
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td class="text-right" colspan="3"><strong>Total Amount:</strong></td>
            <td class="text-danger"><strong>₱ {{number_format($order->total_amount,2)}}</strong></td>
          </tr>
        </tfoot>
      </table>
    </div>
    
  </div>
</div>

<script>
  var status = "{{$order->status}}",
      paid = "{{$order->paid}}",
      stat = $('#status'),
      pd = $('#paid');
  switch(status) {
    case 'pending':
      stat.addClass('text-danger');
      break;
    case 'shipped':
      stat.addClass('text-warning');
      break;
    default:
      stat.addClass('text-success');
  }
  switch(paid) {
    case 'unpaid':
      pd.addClass('text-danger');
      break;
    case 'partial':
      pd.addClass('text-warning');
      break;
    default:
      pd.addClass('text-success');
  }
</script>