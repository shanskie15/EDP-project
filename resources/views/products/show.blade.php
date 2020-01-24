<h1 class="card-title text-center">{{$product->name}}</h1>
<ul class="list-unstyled text-center">
  <li><strong>{{$product->barcode}}</strong></li>
  <li class="text-primary"><strong>{{$product->brand}}</strong></li>
  <li class="text-muted">{{$product->category}}</li>
  <li class="text-danger">₱ {{number_format($product->price,2)}}</li>
  <li>Quantity: {{$product->quantity}}</li>
</ul>
