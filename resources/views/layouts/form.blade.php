@extends('layouts.app')
@section('css')


@endsection
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      @yield('inside')
    </div>
  </div>
</div>
@endsection