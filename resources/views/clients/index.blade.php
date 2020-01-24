@extends('layouts.form')

@section('css')
    
@endsection


@section('inside')
<div class="card">
  <div class="card-header">
    Clients
    <a href="#" class="btn btn-sm btn-success float-right" id="add" data-toggle="modal" data-target="#bigModal">Add client</a>
  </div>
  <div class="card-body">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      @foreach ($divisions as $division)
        <li class="nav-item">
          <a class="nav-link" id="tab-{{$division->id}}" data-toggle="tab" href="#pane-{{$division->id}}" role="tab" aria-controls="pane-{{$division->id}}" aria-selected="true">{{$division->name}}</a>
        </li>
      @endforeach
    </ul>
    <div class="tab-content" id="myTabContent">
      @foreach ($divisions as $division)
        <div class="tab-pane fade" id="pane-{{$division->id}}" role="tabpanel" aria-labelledby="tab-{{$division->id}}">
          <hr>
          <table class="table table-striped" id="clientsTable{{$division->id}}">
            <thead class="thead-dark">
              <tr>
                <th>Store</th>
                <th>Owner</th>
                <th>Email Address</th>
                <th>Location</th>
                <th style="width:10%">Delete</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($clients as $client)
                @if ($client->division_id == $division->id)
                  <tr id="{{$client->id}}">
                    <td>
                      <a href='#' onclick='viewClient({{$client->id}})' data-target='#bigModal' data-toggle='modal'>{{$client->store}}</a>
                      <a href='#' onclick='editClient({{$client->id}},this)' class='fas fa-edit float-right' data-target='#bigModal' data-toggle='modal'></a>
                    </td>
                    <td>{{$client->owner}}</td>
                    <td>{{$client->email}}</td>
                    <td>{{$client->city}}, {{$client->province}} {{$client->zipcode}}</td>
                  

                    <td>
                      <button onclick='confirmDelete({{$client->id}},this)' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i></button>
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      @endforeach
    </div>
    <table class="display row-border" id="clientsTable">
      
      
    </table>
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
        <button type="button" id="saveBig" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection


@section('js')
@include('inc.clients')
@endsection