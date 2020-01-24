<table class="table table-hover" id="paymentsTable">
  <thead>
    <tr>
      <th>Date</th>
      <th>Due</th>
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
        <td>{{number_format($payment->due,2)}}</td>
        <td>{{number_format($payment->amount,2)}}</td>
      </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2" class="text-right">Total paid:</td>
      <td><strong class="text-success">{{number_format($paid,2)}}</strong></td>
    </tr>
  </tfoot>
</table>
<script>
  $(function () {
    $('[data-toggle="popover"]').popover()
  });
  $('.popover-dismiss').popover({
    trigger: 'focus'
  });
</script>