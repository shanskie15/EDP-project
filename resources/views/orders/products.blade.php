<table class="table table-striped" id="productsTable">
  <thead>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Quantity</th>
      <th style="width:30%">Add to cart</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($products as $product)
      <tr>
        <td>{{$product->name}}</td>
        <td>{{number_format($product->price,2)}}</td>
        <td><span id="quantity-{{$product->id}}">{{$product->quantity}}</span></td>
        <td>
          <div class="input-group mb-3">
            <input type="number" min="1" class="form-control" name="quantity">
            <div class="input-group-append">
              <button onclick="addToCart({{$product->id}},{{$order->id}},this)" class="btn btn-outline-success"><i class="fas fa-plus"></i></button>
            </div>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
<script>
$('#productsTable').DataTable();
</script>