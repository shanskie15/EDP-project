@extends('layouts.form')


@section('inside')
<div class="card">
  <div class="card-header">
  Divisions
  <a href="#" class="btn btn-sm btn-success float-right" id="add" data-toggle="modal" data-target="#bigModal">Create Division</a>
  </div>
  <div class="card-body">
    <table class="table table-striped" id="divisionsTable">
      <thead class="thead-dark">
        <tr>
          <th>Division</th>
          <th>Area</th>
          <th>Agent</th>
          <th>No. of Stores</th>
          <th style="width:10%">Delete</th>
        </tr>
      </thead>
      <tbody>
        @foreach($divisions as $division)
          <tr>
            <td><a href="#" onclick="viewDivision({{$division->id}})" data-toggle="modal" data-target="#smallModal">{{$division->name}}</a><a href="#" onclick="editDivision({{$division->id}},this)" class="fas fa-edit float-right" data-target="#bigModal" data-toggle="modal"></a></td>
            <td>{{$division->area}}</td>
            <td><a href="#" data-target='#bigModal' data-toggle='modal' onclick="viewEmployee({{$division->agent_id}})">{{$division->firstname}} {{$division->lastname}}</a></td>
            <td>{{$division->population}}</td>
            <td><button onclick='confirmDelete("{{$division->id}}",this)' class='btn btn-sm btn-danger btn-block'><i class='fas fa-trash-alt'></i></button></td>
          </tr>
        @endforeach
      </tbody>
    </table>
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

@include('inc.divisions')
@endsection