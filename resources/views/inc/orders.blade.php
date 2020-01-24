<script>
$('.table').DataTable(
  {
    "columns": [
          { "data": "date" },
          { "data": "client" },
          { "data": "location" },
          { "data": "action" },
          { "data": "delete" },
      ]
  }
);
var pending = $('#ordersTable').DataTable();
var shipped = $('#shippedTable').DataTable();
var received = $('#receivedTable').DataTable();
var his = $('#historyTable').DataTable();
his.order([0,'desc']).draw();
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// view payments
function viewPayments(id)
{
  $.get("{{url('payments')}}/"+id+"/order",function(data,status){
    $('#bigModalLabel').html('Payments');
    $('#bigModalBody').html(data);
    $('#saveBig').hide();
    $('#cancelBig').hide();
    $('#loanBig').hide();
    $('#saveBig').unbind();
  });
}


// payment
function paymentOrder(id,elem)
{
  $.get("{{url('payments/order')}}/"+id,function(data,status){
    $('#bigModalLabel').html('Pay Order');
    $('#bigModalBody').html(data);
    $('#saveBig').show();
    $('#cancelBig').show();
    $('#loanBig').show();
    $('#saveBig').unbind();
    $('#saveBig').html('Submit Payment');
    $('#saveBig').on('click',function(){
      submitPayment(id,elem);
    });

    $('#loanBig').on('click',function(){
      loanOrder(id,elem);
    });
  });
}
// loan order
function loanOrder(id,elem)
{
  var data ={
    id: id,
    comments: $("#comments").val(),
    date: $('#date').val()
  }
  // alert(JSON.stringify(data));
  $.post("{{route('loan')}}",data,function(result){
    // alert(result);
    if('success' == result.status){
      swal(result.message,{
        icon: 'success'
      });
      received.row($(elem).parents('tr')).remove().draw();
      his.row.add(historyRowData(result.order,result.client)).draw();
      $('#cancelBig').click();
    } 
  });
}

// submit payment
function submitPayment(id,elem)
{
  var data = {
    id : id,
    amount : $('#amount').val(),
    method: $('#method').val(),
    date: $('#date').val(),
    comments: $('#comments').val()
  }
  // alert(JSON.stringify(data));  
  if(isEmpty(data.amount)){
    swal('Empty input',{
      icon: 'error'
    });
  }else{
    $.post("{{route('pay.order')}}",data,function(result){
      // alert(result);
      if('success' == result.status){
        swal(result.message,{
          icon: 'success'
        });
        if(0 == result.order['balance']){
          received.row($(elem).parents('tr')).remove().draw();
          his.row.add(historyRowData(result.order,result.client)).draw();
        }else{
          $('#paybal').html(numberWithCommas(result.order['balance'].toFixed(2)));
          $(elem).closest('tr').find('span.balance').html(numberWithCommas(result.order['balance'].toFixed(2)));
        }
        $('#cancelBig').click();
      }
    });
  }
}

function shipOrder(id,elem,type)
{
  var data = {
    type: type,
    id: id
  }
  $.post("{{route('order.ship')}}",data,function(result){
    if( 'success' == result.status ){
      swal(result.message,{
        icon: 'success'
      });
      switch(type){
        case 'shipped':
          pending.row($(elem).parents('tr')).remove().draw();
          shipped.row.add(shipRowData(result.order,result.client,"received")).draw();
          break;
        case 'received':
          shipped.row($(elem).parents('tr')).remove().draw();
          received.row.add(receivedRowData(result.order,result.client,"received")).draw();
          break;
        default:
      }
      
    }else{
      swal(result.message,{
        icon: 'error'
      });
    }
  });
}


// delete order
function deleteOrder(id,elem)
{
  swal("Do you want to delete order from database?", {
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
        removeOrder("{{url('orders/soft')}}/"+id,elem);
        break;
  
      case "hard":
        removeOrder("{{url('orders')}}/"+id,elem);
        
        break;
      default:;
    }
  });
}
// delete order
function removeOrder(url,elem)
{
  var table_id = $(elem).closest("table").attr('id'),
  table = $('#'+table_id).DataTable();
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

// remove item
function confirmDelete(id,elem)
{
  swal({
    title: "Are you sure?",
    text: "This item will be removed from your cart.",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      removeItem(id,elem);
    } 
  });
}

function removeItem(id,elem)
{
  var table = $('#cartTable').DataTable();
  $.ajax({
    url: "{{url('orders/item')}}/"+id,
    type: 'DELETE',
    success:function(result){
      if('success' == result.status){
        swal(result.message, {
          icon: "success",
        });
        table.row($(elem).parents('tr')).remove().draw();
        $('#cart_total').html(numberWithCommas(result.order['total_amount'].toFixed(2)));
      }else{
        swal('Error',result.message,'error');
      }
    }
  });
}

// edit quantity
function editQuantity(id,elem,type)
{
  removeErrors();
  var quantity = getQuantity(elem),
      data = {
        item_id: id,
        quantity: quantity,
        type: type
      };
  // alert(quantity);
  if(quantity){
    $.post("{{route('cart.quantity')}}",data,function(result){
      // alert(result);
      if('success' == result.status){
        
        $(elem).closest("td").find("span.quantity").html(result.item_qty);
        $(elem).closest("tr").find("span.subtotal").html(numberWithCommas(result.item_total.toFixed(2)));
        $('#cart_total').html(numberWithCommas(result.order_total.toFixed(2)));
        swal(result.message,{
          icon: 'success'
        });
        removeInputs();
        if( 0 == result.item_qty){
          swal({
            title: "Quantity is zero",
            text: "Remove this item from the cart?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              confirmDelete(id,elem);
            } 
          });
        }
      }else{
        swal(result.message,{
          icon: 'error'
        });
        $(elem).closest("div.input-group").find("input[name='quantity']").addClass('is-invalid');
      }
    });
  }else{
    swal('Empty input',{
      icon: 'error'
    });
    $(elem).closest("div.input-group").find("input[name='quantity']").addClass('is-invalid');
    removeInputs();
  }
  
}

function getQuantity(elem)
{
  var quantity = $(elem).closest("div.input-group").find("input[name='quantity']").val();
  if(null == isEmpty(quantity)){
    
    quantity = null;
  }
  return quantity;
}
// remove inputs
function removeInputs()
{
  $("input[name='quantity']").each(function(){
    $(this).val('');
  });
}
// remove errors
function removeErrors()
{
  $("input[name='quantity']").each(function(){
    $(this).removeClass('is-invalid');
  });
}
function addToCart(product_id,order_id,elem)
{
  removeErrors();
  var quantity = $(elem).closest("div.input-group").find("input[name='quantity']").val();
  if(isEmpty(quantity)){
    swal('Empty input',{
      icon: 'error'
    });
    $(elem).closest("div.input-group").find("input[name='quantity']").addClass('is-invalid');
    removeInputs();
  }else{

    $.post("{{route('order.addcart')}}",{product_id:product_id,order_id:order_id,quantity:quantity},function(result){
      if('success' == result.status){
        $('#quantity-'+product_id).html(result.product_qty);
        swal(result.message,{
          icon: 'success'
        });
        removeInputs();
      }else{
        swal(result.message,{
          icon: 'error'
        });
        $(elem).closest("div.input-group").find("input[name='quantity']").addClass('is-invalid');
      }
    });
  }
  
}

function viewCart(id)
{
  $.get("{{url('orders/cart')}}/"+id,function(data,status){
    $('#bigModalLabel').html('Cart');
    $('#bigModalBody').html(data);
    $('#saveBig').hide();
    $('#cancelBig').hide();
    $('#loanBig').hide();
    $('#saveBig').unbind();
  });
}

function viewProducts(id)
{
  $.get("{{url('orders/products')}}/"+id,function(data,status){
    $('#bigModalLabel').html('Products');
    $('#bigModalBody').html(data);
    $('#saveBig').hide();
    $('#cancelBig').hide();
    $('#loanBig').hide();
    $('#saveBig').unbind();
  });
}

function viewOrder(id)
{
  $.get("{{url('orders')}}/"+id,function(data,status){
    $('#bigModalLabel').html('Order Details');
    $('#bigModalBody').html(data);
    $('#saveBig').hide();
    $('#cancelBig').hide();
    $('#loanBig').hide();
    $('#saveBig').unbind();
  });
}

function editOrder(id,elem)
{
  $.get("{{url('orders')}}/"+id+"/edit",function(data,status){
    $('#bigModalLabel').html('Edit Order');
    $('#bigModalBody').html(data);
    $('#loanBig').hide();
    $('#saveBig').show();
    $('#cancelBig').show();
    $('#saveBig').unbind();
    $('#saveBig').on('click',function(){
      updateOrder(id,elem);
    });
  });
}
function updateOrder(id,elem)
{
  var order = getData();
  resetErrors();
  $.ajax({
    url: "{{url('orders')}}/"+id,
    type: 'PUT',
    data: order,
    success:function(result){
      if('invalid' == result.status){
        showErrors(result.errors);
      }else if('success' == result.status){
        updateRow(result.client);
        swal('Success',result.message,'success');
        $('#cancelBig').click();
      }
    }
  });
  // update row in table
  function updateRow(client)
  {
    var table = $('#ordersTable').DataTable();
    table.row($(elem).parents('tr')).data(fillRowData(order,id,client)).invalidate().draw();
  }
}
function addOrder(type)
{
  $.get("{{route('orders.create')}}",function(data,status){
    $('#bigModalLabel').html(toTitleCase(type) +' Order');
    $('#saveBig').html('Save');
    $('#bigModalBody').html(data);
    $('#saveBig').show();
    $('#loanBig').hide();
    $('#cancelBig').show();
    $('#saveBig').unbind();
    $('#saveBig').on('click',function(){
      saveOrder(type);
    });
  });
}
  

// Save Order
function saveOrder(type)
{
  var order = getData();
  order.type = type;
  resetErrors();
  alert(JSON.stringify(order));
  $.post("{{route('orders.store')}}",order,function(result){
    // alert(result.status);
    if('invalid' == result.status){
      showErrors(result.errors);
    }else if('success' == result.status){
      appendTo(result.id,result.client);
      swal('Success',result.message,'success');
      $('#cancelBig').click();
    }
  });
  // Add to table
  function appendTo(id,client)
  {
    var table = $('#ordersTable').DataTable();
    table.row.add(fillRowData(order,id,client,'shipped')).draw();
  }
}
// get division data
function getData()
{
  var order = {
    delivery_date : $('#delivery_date').val(),
    client_id : $('#client_id').val(),
    remarks: $('#remarks').val()
  };
  return order;
}
function historyRowData(order,client)
{
  var row = {
    "DT_RowId": order.id,
    "date" : order.delivery_date,
    "client": "<a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder("+order.id+")'>"+client.store+", "+client.city +', '+client.province+' '+client.zipcode+"</a>",
    "location": numberWithCommas(order.total_amount.toFixed(2)),
    "action": toTitleCase(order.paid)+ "<button class='btn btn-sm btn-info float-right' onclick='viewPayments("+order.id+")' data-toggle='modal' data-target='#bigModal'><i class='fas fa-list'></i></button>",
    "delete": "<button class='btn btn-sm btn-danger btn-block' onclick='deleteOrder("+order.id+",this)'><i class='fas fa-trash'></i></button>"
  };
  return row;
}

// fill data of received
function receivedRowData(order,client)
{
  var row = {
    "DT_RowId": order.id,
    "date" : order.delivery_date,
    "client": "<a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder("+order.id+")'>"+client.store+", "+client.city +', '+client.province+' '+client.zipcode+"</a>",
    "location": numberWithCommas(order.total_amount.toFixed(2)),
    "action": "<span class='balance'>"+numberWithCommas(order.balance.toFixed(2))+"</span>",
    "delete": "<button onclick='paymentOrder("+order.id+",this)' class='btn btn-sm btn-block btn-warning' data-target='#bigModal' data-toggle='modal'><i class='fas fa-plus'></i></button>"
  };
  return row;
}


// fill data of shipped
function shipRowData(order,client,type)
{
  var row = {
    "DT_RowId": order.id,
    "date" : order.delivery_date,
    "client": "<a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder("+order.id+")'>"+client.store+"</a>",
    "location": client.city +', '+client.province+' '+client.zipcode,
    "action": "<button onclick='shipOrder("+order.id+",this,"+'"'+type+'"'+")' class='btn btn-block btn-sm btn-success'><i class='fas fa-hands'></i></button>",
    "delete": "<button class='btn btn-sm btn-danger' onclick='deleteOrder("+order.id+",this)'><i class='fas fa-trash'></i></button>"
  };
  return row;
}


// fill data of a row
function fillRowData(order,id,client,type)
{
  var row = {
      "DT_RowId": id,
      "date": order.delivery_date+"<a href='#' data-target='#bigModal' data-toggle='modal' onclick='editOrder("+id+",this)' class='float-right'><i class='fas fa-edit'></i></a>",
      "client":"<a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewOrder("+id+")'>"+client.store+"</a><div class='float-right'><a href='#' class='btn btn-sm btn-outline-success' data-target='#bigModal' data-toggle='modal' onclick='viewProducts("+id+")'><i class='fas fa-cart-plus'></i></a>\n<a href='#' class='btn btn-sm btn-outline-info' data-target='#bigModal' data-toggle='modal' onclick='viewCart("+id+")'><i class='fas fa-shopping-cart'></i></a></div>",
      "location":client.city +', '+client.province+' '+client.zipcode,
      "action":"<button onclick='shipOrder("+id+",this,"+'"'+type+'"'+")' class='btn btn-sm btn-block btn-warning'><i class='fas fa-shipping-fast'></i></button>",
      "delete":"<button class='btn btn-sm btn-outline-danger btn-block' onclick='deleteOrder("+id+",this)'><i class='fas fa-trash'></i></button>"
  };
  return row;
}
// if string is empty
function isEmpty(word){
  return $.trim(word).length == 0;
}
// capitalize first letters
function toTitleCase(str) 
{
  return str.replace(/\w\S*/g, function(txt){
      return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
  });
}
// Reset errors
function resetErrors()
{
  $('.input').each(function(){
    $(this).removeClass('is-invalid');
  });
}
// Show errors
function showErrors(errors)
{
  $.each(errors,function(key, value){
    if(true == value.includes('name')){
      alertError('#name',value);
    }
    if(true == value.includes('area')){
      alertError('#area',value);
    }
    if(true == value.includes('agent_id')){
      alertError('#agent_id',value);
    }
    if(true == value.includes('population')){
      alertError('#population',value);
    }
    
  });
  // alert error
  function alertError(elem,value)
  {
    $(elem).addClass('is-invalid');
    $(elem).next('span').children('strong').html(value);
  }
}
// number with commas
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

</script>