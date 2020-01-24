@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header"><strong>SALES</strong></div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            
            <table class="table table-hover" class="ordersTable">
              <thead>
                <tr>
                  <td>Date</td>
                  <td style="width:20%">Amount</td>
                </tr>
              </thead>
              <tbody>
                @foreach ($payments as $order)
                  <tr>
                    <td>{{date('D, M d, Y',strtotime($order->date))}}</td>
                    <td>{{number_format($order->total,2)}}</td>
                  </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-right">Total:</td>
                  <td>{{number_format($total,2)}}</td>
                </tr>
              </tfoot>
            </table>
            <div class="float-right">{{$payments->links()}}</div>
            
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
