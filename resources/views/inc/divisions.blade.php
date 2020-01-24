<script>
  
  $('#divisionsTable').DataTable(
    {
      "columns": [
            { "data": "name" },
            { "data": "area" },
            { "data": "agent" },
            { "data": "population" },
            { "data": "delete" },
        ]
    }
  );
  var table = $('#divisionsTable').DataTable();
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  // add btn for creating division
  $('#add').on('click',function(){
    $.get("{{route('divisions.create')}}",function(data,status){
      $('#bigModalLabel').html('Add Division');
      $('#saveBig').html('Save');
      $('#bigModalBody').html(data);
      $('#saveBig').show();
      $('#cancelBig').show();
      $('#saveBig').unbind();
      $('#saveBig').on('click',function(){
        saveDivision();
      });
    });
  });
  function saveDivision()
  {
    var division = getData();
    resetErrors();
    // alert(JSON.stringify(division));
    $.post("{{route('divisions.store')}}",division,function(result){
      // alert(result.status);
      if('invalid' == result.status){
        showErrors(result.errors);
      }else if('success' == result.status){
        appendTo(result.id,result.agent);
        swal('Success',result.message,'success');
        $('#cancelBig').click();
      }
    });
    // Add to table
    function appendTo(id,agent)
    {
      table.row.add(fillRowData(division,id,agent)).draw();
    }
  }
  // get division data
  function getData()
  {
    var division = {
      name : toTitleCase($('#name').val()),
      area : toTitleCase($('#area').val()),
      agent_id : $('#agent_id').val(),
    };
    return division;
  }

  // fill data of a row
  function fillRowData(division,id,agent)
  {
    var row = {
        "DT_RowId": id,
        "name":"<a href='#' onclick='viewDivision("+id+")' data-target='#smallModal' data-toggle='modal'>"+ division.name+"</a><a href='#' onclick='editDivision("+id+",this)' class='fas fa-edit float-right' data-target='#bigModal' data-toggle='modal'></a>",
        "area":division.area,
        "agent":"<a href='#' data-target='#bigModal' data-toggle='modal' onclick='viewEmployee("+division.agent_id+")'>"+agent+"</a>",
        "population":0,
        "delete":"<button onclick='deleteDivision("+id+",this)' class='btn btn-sm btn-danger btn-block'><i class='fas fa-trash-alt'></i></button>"
    };
    return row;
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
  // Reset errors
  function resetErrors()
  {
    $('.input').each(function(){
      $(this).removeClass('is-invalid');
    });
  }
  // capitalize first letters
  function toTitleCase(str) 
  {
    return str.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
  }
  
  // edit division
  function editDivision(id,elem)
  {
    $.get("{{url('divisions')}}/"+id+"/edit",function(data,status){
      $('#bigModalLabel').html('Edit Division');
      $('#bigModalBody').html(data);
      $('#saveBig').show();
      $('#cancelBig').show();
      $('#saveBig').unbind();
      $('#saveBig').on('click',function(){
        updateDivision(id,elem);
      });
    });
  }
  // update division in database
  function updateDivision(id,elem)
  {
    var division = getData();
    resetErrors();
    $.ajax({
      url: "{{url('divisions')}}/"+id,
      type: 'PUT',
      data: division,
      success:function(result){
        if('invalid' == result.status){
          showErrors(result.errors);
        }else if('success' == result.status){
          updateRow(result.agent);
          swal('Success',result.message,'success');
          $('#cancelBig').click();
        }
      }
    });
    // update row in table
    function updateRow(agent)
    {
      table.row($(elem).parents('tr')).data(fillRowData(division,id,agent)).invalidate().draw();
    }

  }
  // find elem of record
  function findElem(id)
  {
    var row;
    table.rows().eq(0).each( function (index) {
      if($(table.row(index).node()).attr('id') == id){
        row = table.row(index).node();
      }
    });
    return $(row).children('td').get(0);
  }

  function confirmDelete(id,elem)
  {
    swal("Do you want to remove division from database?", {
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
          removeDivision("{{url('divisions/soft')}}/"+id,elem);
          break;
    
        case "hard":
        swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this data!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            removeDivision("{{url('divisions')}}/"+id,elem);
          }
        });
          
          
          break;
        default:;
      }
    });
    function removeDivision(url,elem)
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


  // view division
  function viewDivision(id)
  {
    $.get("{{url('divisions')}}/"+id,function(data,status){
      $('#smallModalLabel').html('Division Information');
      $('#smallModalBody').html(data);
      $('#saveSmall').hide();
      $('#cancelSmall').hide();
    });
  }
   // View employee
  function viewEmployee(id)
  {
    $.get("{{url('employees')}}/"+id,function(data,status){
      $('#bigModalLabel').html('Employee Information');
      $('#bigModalBody').html(data);
      $('#saveBig').hide();
      $('#cancelBig').hide();
      $('.profile-edit-btn').hide();
    });
  }

</script>