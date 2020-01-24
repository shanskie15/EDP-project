@extends('layouts.form')


@section('inside')
<div class="card">
  <div class="card-header"><strong>LOANS</strong></div>
  <div class="card-body">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="pills-pending-tab" data-toggle="pill" href="#pills-pending" role="tab" aria-controls="pills-pending" aria-selected="true">Pending</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="pills-paid-tab" data-toggle="pill" href="#pills-paid" role="tab" aria-controls="pills-paid" aria-selected="false">Paid</a>
      </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab">
        <table class="table table-hover" id="pendingTable">
          <thead>
            <tr>
              <td>Date Filed</td>
              <td>Client</td>
              <td>Total</td>
              <td>Balance</td>
              <td style="width:10%">Payment</td>
            </tr>
          </thead>
          <tbody>
            @foreach ($loans as $loan)
              @if ($loan->balance > 0)
              <tr>
                <td>
                  {{$loan->loan_date}}
                  <button class="btn btn-sm btn-info float-right" data-toggle='modal' data-target='#bigModal' onclick='viewOrder({{$loan->order_id}})'><i class="fas fa-cube"></i></button>
                </td>
                <td>
                  <a href='#' onclick='viewClient({{$loan->client_id}})' data-target='#bigModal' data-toggle='modal'>{{$loan->store}}, {{$loan->location}}</a>
                  @if(!empty($loan->comments))
                    <button type="button" class="btn btn-info btn-sm float-right" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="top" data-content="{{$loan->comments}}">
                      Comments
                    </button>
                  @endif
                </td>
                <td>{{number_format($loan->total,2)}}</td>
                <td><span class="balance">{{number_format($loan->balance,2)}}</span></td>
                <td>
                  <button data-toggle="modal" data-target="#bigModal" class='btn btn-sm btn-block btn-warning' onclick="paymentLoan({{$loan->id}},this,{{$loan->client_id}})"><i class='fas fa-plus'></i></button>
                </td>
              </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="tab-pane fade" id="pills-paid" role="tabpanel" aria-labelledby="pills-paid-tab">
        <table class="table table-hover" id="paidTable">
          <thead>
            <tr>
              <th>Date Filed</th>
              <th>Client</th>
              <th>Total</th>
              <th>Paid</th>
              <th style="width:15%">Delete</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($loans as $loan)
              @if ( 0 == $loan->balance)
                <tr>
                  <td>
                    {{$loan->loan_date}}
                    <button class="btn btn-sm btn-info float-right" data-toggle='modal' data-target='#bigModal' onclick='viewOrder({{$loan->order_id}})'><i class="fas fa-cube"></i></button>
                  </td>
                  <td>
                    <a href='#' onclick='viewClient({{$loan->client_id}})' data-target='#bigModal' data-toggle='modal'>{{$loan->store}}, {{$loan->location}}</a>
                    @if(!empty($loan->comments))
                    <button type='button' class='btn btn-info btn-sm float-right'  onclick='popoverFn("{{$loan->comments}}")' class='btn btn-info btn-sm float-right' data-toggle='modal' data-target='#smallModal'>
                        Comments
                      </button>
                    @endif
                  </td>
                  <td>{{number_format($loan->total,2)}}</td>
                  <td>
                    @foreach ($payments as $payment)
                      @if ($loan->id == $payment->loan_id)
                        {{number_format($payment->total,2)}}
                      @endif
                    @endforeach
                  </td>
                  <td>
                    <button class='btn btn-danger btn-sm btn-block' onclick='confirmDelete({{$loan->id}},this)'><i class='fas fa-trash'></i></button>
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

{{-- Small Modal --}}
<div class="modal fade bd-example-modal-sm" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header card-header" style="background-color:#108790;color:white">
        <h5 class="modal-title" id="smallModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body card-body" id="smallModalBody"></div>
      <div class="modal-footer card-footer" style="background-color:#108790;color:white">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelSmall">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveSmall">Save changes</button>
        
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
        <button type="button" id="saveBig" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection



@section('js')

@include('inc.loans')
@endsection