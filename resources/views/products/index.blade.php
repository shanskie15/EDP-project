@extends('layouts.form')


@section('inside')
<div class="card">
  <div class="card-header">
  Products
  <a href="#" class="btn btn-sm btn-success float-right" id="add" data-toggle="modal" data-target="#bigModal">Add product</a>
  </div>
  <div class="card-body">
    <table class="table table-striped" id="productsTable">
      <thead class="thead-dark">
        <tr>
          <th>Barcode</th>
          <th>Product name</th>
          <th>Brand</th>
          <th>Quantity</th>
          <th>Price</th>
          <th style="width:10%">Delete</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
          <tr>
            <td>{{$product->barcode}}</td>
            <td><a href="#" onclick="viewProduct({{$product->id}})" data-toggle="modal" data-target="#smallModal">{{$product->name}}</a> 
              <button type="button" onclick="addItems({{$product->id}},this)" class="btn btn-sm btn-outline-success float-right" title="Add items">
                <i class="fas fa-plus"></i>
              </button>
            </td>
            <td>{{$product->brand}}</td>
            <td><span class="quantity">{{$product->quantity}}</span></td>
          <td><span class="price">{{$product->price}}</span></td>
            <td>
              <button type="button" onclick="deleteProduct({{$product->id}},this)" class="btn btn-sm btn-danger btn-block"><i class="fas fa-trash"></i></button>
              
            </td>
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

@include('inc.products')
@endsection