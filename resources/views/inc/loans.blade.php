<script>
$('#pendingTable').DataTable();
var pending = $('#pendingTable').DataTable();
var paid = $('#paidTable').DataTable();
pending.order([0,'desc']).draw();
paid.order([0,'desc']).draw();
$(function () {
  $('[data-toggle="popover"]').popover()
});
$('.popover-dismiss').popover({
  trigger: 'focus'
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//confirm delete
function confirmDelete(id,elem)
{
  swal({
    title: "Are you sure?",
    text: "This loan will be deleted!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      removeLoan("{{url('loans/delete')}}/"+id,elem);
    } 
  });
}

function removeLoan(url,elem)
{
  $.ajax({
    url: url,
    type: 'DELETE',
    success:function(result){
      if('success' == result.status){
        swal(result.message, {
          icon: "success",
        });
        paid.row($(elem).parents('tr')).remove().draw();
      }else{
        swal('Error',result.message,'error');
      }
    }
  });
}

function popoverFn(comments)
{
  $("#smallModalLabel").html('Comments');
  $("#smallModalBody").html(comments);
  $('#saveSmall').hide();
  $('#cancelSmall').hide();
}
// payment
function paymentLoan(id,elem,client_id)
{
  $.get("{{url('loans/payment')}}/"+id,function(data,status){
    $('#bigModalLabel').html('Pay Loan');
    $('#bigModalBody').html(data);
    $('#saveBig').show();
    $('#cancelBig').show();
    $('#saveBig').unbind();
    $('#saveBig').html('Submit Payment');
    $('#saveBig').on('click',function(){
      submitPayment(id,elem,client_id);
    });
  });
}

// submit payment
function submitPayment(id,elem,client_id)
{
  var data = {
    id : id,
    amount : $('#amount').val(),
    method: $('#method').val(),
    date: $('#date').val(),
    comments: $('#comments').val(),
    client_id: client_id
  }
  // alert(JSON.stringify(data));  
  if(isEmpty(data.amount)){
    swal('Empty input',{
      icon: 'error'
    });
  }else{
    $.post("{{route('pay.loan')}}",data,function(result){
      // alert(JSON.stringify(result.loan));
      if('success' == result.status){
        swal(result.message,{
          icon: 'success'
        });
        if(0 == result.loan['balance']){
          pending.row($(elem).parents('tr')).remove().draw();
          paid.row.add(paidRowData(result.loan,result.client,result.paid)).draw();
        }else{
          $(elem).closest('tr').find('span.balance').html(numberWithCommas(result.loan['balance'].toFixed(2)));
        }
        $('#cancelBig').click();
      }
    });
  }
}

// fill data of paid
function paidRowData(loan,client,paid)
{
  var button = "";
  if(!isEmpty(loan.comments)){
    button = "<button type='button' onclick='popoverFn("+'"'+loan.comments+'"'+")' class='btn btn-info btn-sm float-right' data-toggle='modal' data-target='#smallModal'>Comments</button>"
  }
  var row = [
    loan.loan_date + "<button class='btn btn-sm btn-info float-right' data-toggle='modal' data-target='#bigModal' onclick='viewOrder("+loan.id+")'><i class='fas fa-cube'></i></button>",
    "<a href='#' onclick='viewClient("+client.id+")' data-target='#bigModal' data-toggle='modal'>"+client.store+", "+client.location+"</a>"+button,
    numberWithCommas(loan.total.toFixed(2)),
    numberWithCommas(paid.toFixed(2)),
    "<button class='btn btn-danger btn-sm btn-block' onclick='confirmDelete("+loan.id+",this)'><i class='fas fa-trash'></i></button>"
  ];
  return row;
}

// view client
function viewClient(id)
{
  $.get("{{url('clients')}}/"+id,function(data,status){
    $('#bigModalLabel').html('Client Information');
    $('#bigModalBody').html(data);
    $('.profile-edit-btn').hide();
    $('#saveBig').hide();
    $('#cancelBig').hide();
  });
}


// view order
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
// number with commas
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
</script>