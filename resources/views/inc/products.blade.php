<script>
  $('#productsTable').DataTable();
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  $('#add').on('click',
    function(){
      
      $.get("{{url('products/create')}}", function(data, status){
        $('#bigModalLabel').html('Add product');
        
        $('#saveBig').html('Save');
        $('#bigModalBody').html(data);
        $('#saveBig').on('click',function(){
          saveProduct();
        });
        
      });
      
    }
  );

  // Delete Product 
  function deleteProduct(id,elem)
  {
    var table = $('#productsTable').DataTable();
    swal("Do you want to remove product from database?", {
      buttons: {
        cancel: "Cancel",
        catch: {
          text: "No",
          value: "soft",
        },
        defeat: {
          text: "Yes",
          value: "hard"
        }
      },
    })
    .then((value) => {
      switch (value) {
    
        case "soft":
          removeProduct("{{url('products/soft')}}/"+id,elem);
          
          break;
    
        case "hard":
          removeProduct("{{url('products')}}/"+id,elem);
          break;
        default:;
      }
    });
    function removeProduct(url,elem)
    {
      $.ajax({
        url: url,
        type: 'DELETE',
        success:function(result){
          if('success' == result.status){
            swal(result.message, {
              icon: "success",
            });
            table.row($(elem).parents('tr')).remove().draw();
          }else{
            swal('Error',result.message,'error');
          }
        }
      });

    }
  }
  

  // Add items to product
  function addItems(id,elem)
  {
    swal("Quantity:", {
      content: "input"
    })
    .then((value) => {
      
      if(!$.isNumeric(value)){
        swal('Invalid input',{
          icon: 'error'
        });
        return;
      }
      var quantity = parseInt(value);
      if(isEmpty(value)){
        swal('Empty input',{
          icon: 'error'
        });
        return;
      }
      $.post("{{route('products.add_items')}}",{id:id,quantity:quantity},function(result){
        if( 'success' == result.status){
          swal(result.message, {
            icon: "success",
          });
          $(elem).parents('tr').children('td').children('span.quantity').html(result.quantity);
        }
      });
    });
    function isEmpty(word){
      return $.trim(word).length == 0;
    }
  }


  // View product
  function viewProduct(id)
  {
    $.get("{{url('products')}}/"+id,function(data,status){
      $('#smallModalLabel').html('Product Information');
      $('#smallModalBody').html(data);
      $('#saveSmall').hide();
      $('#cancelSmall').hide();
    });
  }

  // Store product
  function saveProduct()
  {
    var product = {
        barcode : $('#barcode').val(),
        name : $('#name').val(),
        price : $('#price').val(),
        quantity : $('#quantity').val(),
        category : $('#category').val(),
        brand : $('#brand').val(),
      }
    
    resetErrors();
    
    $.post("{{route('products.store')}}",product,function(result){
      
      if('invalid' == result.status){
        showErrors(result.errors);
      }else if('success' == result.status){
        appendTo(result.id);
        $('#cancelBig').click();
        swal('Success',result.message,'success');
      }
    });
    
    // Add to table
    function appendTo(id)
    {
      var table = $('#productsTable').DataTable();
      table.row.add([
        product.barcode,
        "<a href='#' onclick='viewProduct("+id+")' data-toggle='modal' data-target='#smallModal'>"+product.name+"</a><button type='button' onclick='addItems("+id+")' class='btn btn-sm btn-outline-success float-right' title='Add items'><i class='fas fa-plus'></i></button>",
        product.brand,
        "<span class='quantity'>"+product.quantity+"</span>",
        "<button type='button' onclick='deleteProduct("+id+",this)' class='btn btn-sm btn-danger btn-block'><i class='fas fa-trash'></i></button>"
      ]).draw();
      
    }

    // Show errors
    function showErrors(errors)
    {
      $.each(errors,function(key, value){
        if(true == value.includes('barcode')){
          alertError('#barcode',value);
        }
        if(true == value.includes('name')){
          alertError('#name',value);
        }
        if(true == value.includes('price')){
          alertError('#price',value);
        }
        if(true == value.includes('quantity')){
          alertError('#quantity',value);
        }
        if(true == value.includes('category')){
          alertError('#category',value);
        }
        if(true == value.includes('brand')){
          alertError('#brand',value);
        }
      });
       
      function alertError(elem,value)
      {
        $(elem).addClass('is-invalid');
        $(elem).next('span').children('strong').html(value);
      }

      
    }
    function resetErrors()
    {
      $('input').each(function(){
        $(this).removeClass('is-invalid');
      });
    }
    
  }


</script>