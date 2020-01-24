<script>
  $('.table').DataTable(
    {
      "columns": [
            { "data": "store" },
            { "data": "owner" },
            { "data": "email" },
            { "data": "location" },
            { "data": "delete" },
        ]
    }
  );
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  // edit client
  function editClient(id,elem)
  {
    $.get("{{url('clients')}}/"+id+"/edit",function(data,status){
      $('#bigModalLabel').html('Edit Client');
      $('#bigModalBody').html(data);
      $('#saveBig').show();
      $('#cancelBig').show();
      $('#saveBig').unbind();
      $('#saveBig').on('click',function(){
        updateClient(id,elem);
      });
    });
  }
  function updateClient(id,elem)
  {
    var client = getData();
    // alert(JSON.stringify(client));
    resetErrors();
    $.ajax({
      url: "{{url('clients')}}/"+id,
      type: 'PUT',
      data: client,
      success:function(result){
        // alert(result);
        if('invalid' == result.status){
          showErrors(result.errors);
        }else if('success' == result.status){
          updateRow();
          swal('Success',result.message,'success');
          $('#cancelBig').click();
        }
      }
    });
    // update row in table
    function updateRow()
    {
      var table = $('#clientsTable'+client.division_id).DataTable();  
      table.row($(elem).parents('tr')).data(fillRowData(client,id)).invalidate().draw();
    }
  }
  function confirmDelete(id,elem)
  {
    swal("Do you want to remove client from database?", {
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
          removeClient("{{url('clients/soft')}}/"+id,elem);
          
          break;
    
        case "hard":
          removeClient("{{url('clients')}}/"+id,elem);
          break;
        default:;
      }
    });
  }
  function removeClient(url,elem)
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



  // add btn
  $('#add').on('click',function(){
    $.get("{{route('clients.create')}}",function(data,status){
      $('#bigModalLabel').html('Add Client');
      $('#saveBig').html('Save');
      $('#bigModalBody').html(data);
      $('#saveBig').show();
      $('#cancelBig').show();
      $('#saveBig').unbind();
      $('#saveBig').on('click',function(){
        saveClient();
      });
    });
  });

  // save client to database
  function saveClient()
  {
    var client = getData();
    // alert(JSON.stringify(client));
    $.post("{{route('clients.store')}}",client,function(result){
      // alert(result);
      if('invalid' == result.status){
        showErrors(result.errors);
      }else if('success' == result.status){
        appendTo(result.id,client.division_id);
        swal('Success',result.message,'success');
        $('#cancelBig').click();
      }
    });
    // Add to table
    function appendTo(id,division_id)
    {
      var table = $('#clientsTable'+division_id).DataTable();
      table.row.add(fillRowData(client,id)).draw();
    }
  }

  // get client data from input
  function getData()
  {
    var client = {
      store : toTitleCase($('#store').val()),
      owner : toTitleCase($('#owner').val()),
      contact : $('#contact').val(),
      email : $('#email').val(),
      city : toTitleCase($('#city').val()),
      province : toTitleCase($('#province').val()),
      zipcode : $('#zipcode').val(),
      contact_person : toTitleCase($('#contact_person').val()),
      division_id : $('#division_id ').val(),
    };
    return client;
  }
  // capitalize first letters
  function toTitleCase(str) 
  {
    return str.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
  }
  // Show errors
  function showErrors(errors)
  {
    $.each(errors,function(key, value){
      if(true == value.includes('store')){
        alertError('#store',value);
      }
      if(true == value.includes('owner')){
        alertError('#owner',value);
      }
      if(true == value.includes('contact') && false == value.includes('person')){
        alertError('#contact',value);
      }
      if(true == value.includes('email')){
        alertError('#email',value);
      }
      if(true == value.includes('city')){
        alertError('#city',value);
      }
      if(true == value.includes('province')){
        alertError('#province',value);
      }
      if(true == value.includes('zipcode')){
        alertError('#zipcode',value);
      }
      if(true == value.includes('contact person')){
        alertError('#contact_person',value);
      }
      if(true == value.includes('division id')){
        alertError('#division_id',value);
      }
    });
    // alert error
    function alertError(elem,value)
    {
      $(elem).addClass('is-invalid');
      $(elem).next('span').children('strong').html(value);
    }
  }
  // Reset errors
  function resetErrors()
  {
    $('.input').each(function(){
      $(this).removeClass('is-invalid');
    });
  }
  // fill data of a row
  function fillRowData(client,id)
  {
    var row = {
        "DT_RowId": id,
        "store":"<a href='#' onclick='viewClient("+id+")' data-target='#bigModal' data-toggle='modal'>"+client.store+"</a><a href='#' onclick='editClient("+id+",this)' class='fas fa-edit float-right' data-target='#bigModal' data-toggle='modal'></a>",
        "owner":client.owner,
        "email":client.email,
        "location":client.city+', '+client.province+' '+client.zipcode,
        "delete":"<button onclick='deleteClient("+id+",this)' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i></button>"
    };
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
</script>