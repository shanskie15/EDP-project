<table class="table table-striped" id="cartTable">
  <thead>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Quantity</th>
      <td>Subtotal</td>
      <th style="width:10%">Remove</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $total = 0;  
    ?>
    @foreach ($cart as $item)
      <?php
        $total += $item->subtotal;
      ?>
      <tr>
        <td>{{$item->name}}</td>
        <td>{{number_format($item->price,2)}}</td>
        <td>
          <span class="quantity" id="quantity-{{$item->id}}">{{$item->quantity}}</span>
          <div class="input-group input-group mb-3 float-right col-md-6">
            <input type="number" min="0" class="form-control" name="quantity">
            <div class="input-group-prepend">
              <button onclick="editQuantity({{$item->id}},this,'minus')" type="button" class="btn btn-sm btn-outline-dark"><i class="fas fa-minus"></i></button>
              <button onclick="editQuantity({{$item->id}},this,'add')" type="button" class="btn btn-sm btn-outline-dark"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <div class="btn-group btn-group-sm float-right" role="group">
            
          </div>
        </td>
        <td><span class="subtotal" id="subtotal-{{$item->id}}">{{number_format($item->subtotal,2)}}</span></td>
        <td><button onclick="confirmDelete({{$item->id}},this)" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-trash"></i></button></td>
      </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3" class="text-right"><strong>Total :</strong></td>
      <td colspan="2"><strong><span id="cart_total" class="text-danger">{{number_format($total,2)}}</span></strong></td>
    </tr>
  </tfoot>
</table>
<script>
$('#cartTable').DataTable();
</script>