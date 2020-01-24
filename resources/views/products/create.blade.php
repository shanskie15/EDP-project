


<form>
  {{ csrf_field() }}
  <div class="form-group row">
    <label for="barcode" class="col-md-4 col-form-label text-md-right">Barcode</label>
    <div class="col-md-6">
        <input id="barcode" type="text" class="form-control" name="barcode" required autofocus>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">Product Name</label>
    <div class="col-md-6">
        <input id="name" type="text" class="form-control" name="name" required>

				<span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="price" class="col-md-4 col-form-label text-md-right">Price</label>
    <div class="col-md-6">
        <input id="price" type="number" min="0" class="form-control" name="price" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="quantity" class="col-md-4 col-form-label text-md-right">Quantity</label>
    <div class="col-md-6">
        <input id="quantity" type="number" min="0" class="form-control" name="quantity" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="category" class="col-md-4 col-form-label text-md-right">Category</label>
    <div class="col-md-6">
        <input id="category" type="text" class="form-control" name="category"  required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>
  <div class="form-group row">
    <label for="brand" class="col-md-4 col-form-label text-md-right">Brand</label>
    <div class="col-md-6">
        <input id="brand" type="text" class="form-control" name="brand" required>

        <span class="invalid-feedback" role="alert">
          <strong></strong>
        </span>
    </div>
  </div>

</form>