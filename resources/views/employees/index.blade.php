@extends('layouts.form')

@section('css')
    
@endsection


@section('inside')
<div class="card">
  <div class="card-header">
    Employees
    <a href="#" class="btn btn-sm btn-success float-right" id="add" data-toggle="modal" data-target="#bigModal">Add employee</a>
  </div>
  <div class="card-body">
    <table class="display row-border" id="employeesTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          
          <th style="width:10%">Delete</th>
          <th>Gender</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($employees as $employee)
          <tr id="{{$employee->id}}">
            <td>
              <a href="#" onclick="viewEmployee({{$employee->id}})" data-target="#bigModal" data-toggle="modal">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</a>
              <a href="#" onclick="editEmployee({{$employee->id}},this)" class="fas fa-edit float-right" data-target="#bigModal" data-toggle="modal"></a>
            </td>
            <td>{{$employee->email}}</td>
            <td>{{$employee->contact}}</td>
            <td>
              <button onclick="deleteEmployee({{$employee->id}},this)" class="btn btn-sm btn-danger btn-block"><i class="fas fa-trash-alt"></i></button>
           
            </td>
            <td>{{$employee->gender}}</td>
          </tr>
        @endforeach
      </tbody>
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
@include('inc.employees')
@endsection