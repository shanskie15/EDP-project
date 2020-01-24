@extends('layouts.form')

@section('css')
    
@endsection


@section('inside')
<div class="card">
  <div class="card-header">
    Orders
    <div class="btn-group float-right" role="group" >
      <button href="#" class="btn btn-sm btn-success" onclick="addOrder('new')" data-toggle="modal" data-target="#bigModal"><i class="fas fa-plus"></i> New</button>
    </div>
    
  </div>
  <div class="card-body">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="pills-pending-tab" data-toggle="pill" href="#pills-pending" role="tab" aria-controls="pills-pending" aria-selected="true">Pending</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="pills-shipped-tab" data-toggle="pill" href="#pills-shipped" role="tab" aria-controls="pills-shipped" aria-selected="false">Shipped</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="pills-received-tab" data-toggle="pill" href="#pills-received" role="tab" aria-controls="pills-received" aria-selected="false">Received</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="pills-history-tab" data-toggle="pill" href="#pills-history" role="tab" aria-controls="pills-history" aria-selected="false">History</a>
      </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab">
        <table class="display row-border table" id="ordersTable">
          <thead>
            <tr>
              <th style="width:20%">Shipping date</th>
              <th style="width:30%">Client</th>
              <th>Location</th>
              <th style="width:10%">Ship</th>
              <th style="width:10%">Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
              @if ('pending' == $order->status)
                <tr id="{{$order->id}}">
                  <td>
                    {{$order->delivery_date}}
                    <a href='#' data-target='#bigModal' data-toggle='modal' onclick='editOrder({{$order->id}},this)' class='float-right'><i class='fas fa-edit'></i></a>
                  </td>
                  <td>
                    <a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder({{$order->id}})'>{{$order->store}}</a>
                    <div class='float-right'>
                      <a href='#' class='btn btn-sm btn-outline-success' onclick='viewProducts({{$order->id}})' data-target='#bigModal' data-toggle='modal'><i class='fas fa-cart-plus'></i></a>
                      <a href='#' class='btn btn-sm btn-outline-info' onclick='viewCart({{$order->id}})' data-target='#bigModal' data-toggle='modal'><i class='fas fa-shopping-cart'></i></a>
                    </div>
                    
                  </td>
                  <td>{{$order->city}}, {{$order->province}} {{$order->zipcode}}</td>
                  <td>
                    <button onclick='shipOrder({{$order->id}},this,"shipped")' class='btn btn-sm btn-block btn-warning'><i class='fas fa-shipping-fast'></i></button>
                  </td>
                  <td>
                    <button class='btn btn-sm btn-outline-danger btn-block' onclick='deleteOrder({{$order->id}},this)'><i class='fas fa-trash'></i></button>
                  </td>
                </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="pills-shipped" role="tabpanel" aria-labelledby="pills-shipped-tab">
        <table class="table table-hover" id="shippedTable">
          <thead>
            <tr>
              <th style="width:20%">Shipping date</th>
              <th style="width:30%">Client</th>
              <th>Location</th>
              <th>Receive</th>
              <th style="width:10%">Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
              @if ('shipped' == $order->status)
                <tr id="{{$order->id}}">
                  <td>{{$order->delivery_date}}</td>
                  <td>
                    <a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder({{$order->id}})'>{{$order->store}}</a>
                  </td>
                  <td>{{$order->city}}, {{$order->province}} {{$order->zipcode}}</td>
                  <td>
                    <button onclick='shipOrder({{$order->id}},this,"received")' class='btn btn-sm btn-block btn-success'><i class='fas fa-hands'></i></button>
                  </td>
                  <td>
                    <button class='btn btn-sm btn-danger' onclick='deleteOrder({{$order->id}},this)'><i class='fas fa-trash'></i></button>
                  </td>
                </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="pills-received" role="tabpanel" aria-labelledby="pills-received-tab">
        <table class="table table-hover" id="receivedTable">
          <thead>
            <tr>
              <th>Shipping date</th>
              <th>Client</th>
              <th>Total</th>
              <th>Balance</th>
              <th>Payment</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
              @if ('received' == $order->status && 'unpaid' == $order->paid)
                <tr id="{{$order->id}}">
                  <td>{{$order->delivery_date}}</td>
                  <td>
                    <a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder({{$order->id}})'>{{$order->store}}, {{$order->city}}, {{$order->province}} {{$order->zipcode}}</a>
                  </td>
                  <td>{{number_format($order->total_amount,2)}}</td>
                  <td><span class='balance'>{{number_format($order->balance,2)}}</span></td>
                  <td>
                    <button onclick='paymentOrder({{$order->id}},this)' class='btn btn-sm btn-block btn-warning' data-target='#bigModal' data-toggle='modal'><i class='fas fa-plus'></i></button>
                  </td>
                </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
        <table class="table table-hover" id="historyTable">
          <thead>
            <tr>
              <th>Shipping</th>
              <th>Client</th>
              <th>Total</th>
              <th>Payment</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($orders as $order)
              @if ('unpaid' != $order->paid && 'received' == $order->status)
                <tr>
                  <td>{{$order->delivery_date}}</td>
                  <td>
                    <a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder({{$order->id}})'>{{$order->store}}, {{$order->city}}, {{$order->province}} {{$order->zipcode}}</a>
                  </td>
                  <td>{{number_format($order->total_amount,2)}}</td>
                  <td>
                    {{ucfirst($order->paid)}}
                    <button class='btn btn-sm btn-info float-right' onclick='viewPayments({{$order->id}})' data-toggle='modal' data-target='#bigModal'><i class='fas fa-list'></i></button>
                  </td>
                  <td>
                    <button class='btn btn-sm btn-danger btn-block' onclick='deleteOrder({{$order->id}},this)'><i class='fas fa-trash'></i></button>
                  </td>
                </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    
  </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header card-header" style="background-color:#108790;color:white">
        <h5 class="modal-title" id="modalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body card-body" id="modalBody"></div>
      <div class="modal-footer card-footer" style="background-color:#108790;color:white">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelNormal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveNormal">Save changes</button>
        
      </div>
    </div>
  </div>
</div>

{{-- Big modal --}}
<div class="modal fade" id="bigModal" tabindex="-1" role="dialog" aria-labelledby="bigModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bigModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="bigModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelBig">Cancel</button>
        <button type="button" id="loanBig" class="btn btn-dark">Loan</button>
        <button type="button" id="saveBig" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection


@section('js')
@include('inc.orders')
@endsection