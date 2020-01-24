<script>
  $('#employeesTable').DataTable(
    {
      "columns": [
            { "data": "name" },
            { "data": "email" },
            { "data": "contact" },
            { "data": "delete" },
        ]
    }
  );
  var table = $('#employeesTable').DataTable();
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  $('#add').on('click',
    function(){
      $.get("{{route('employees.create')}}",function(data,status){
        $('#bigModalLabel').html('Add Employee');
        $('#saveBig').html('Save');
        $('#bigModalBody').html(data);
        $('#saveBig').show();
        $('#cancelBig').show();
        $('#saveBig').unbind();
        $('#saveBig').on('click',function(){
          
          saveEmployee();
        });
      });
    }
  );

  // Save Employee
  function saveEmployee()
  {
    var employee = getData();
    // alert(JSON.stringify(employee));
    resetErrors();
    $.post("{{route('employees.store')}}",employee,function(result){
      // alert(result);
      if('invalid' == result.status){
        showErrors(result.errors);
      }else if('success' == result.status){
        appendTo(result.id);
        swal('Success',result.message,'success');
        $('#cancelBig').click();
      }
    });
    
    // Add to table
    function appendTo(id)
    {
      table.row.add(fillRowData(employee,id)).draw();
    }
  }
  // Show errors
  function showErrors(errors)
  {
    $.each(errors,function(key, value){
      if(true == value.includes('firstname')){
        alertError('#firstname',value);
      }
      if(true == value.includes('middlename')){
        alertError('#middlename',value);
      }
      if(true == value.includes('lastname')){
        alertError('#lastname',value);
      }
      if(true == value.includes('email')){
        alertError('#email',value);
      }
      if(true == value.includes('birth date')){
        alertError('#birth_date',value);
      }
      if(true == value.includes('contact')){
        alertError('#contact',value);
      }
      if(true == value.includes('address') && false == value.includes('email')){
        alertError('#address',value);
      }
      if(true == value.includes('position')){
        alertError('#position',value);
      }
      if(true == value.includes('salary')){
        alertError('#salary',value);
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
    $('input').each(function(){
      $(this).removeClass('is-invalid');
    });
  }
  // capitalize first letters
  function toTitleCase(str) {
    return str.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
  }
  // edit employee
  function editEmployee(id,elem)
  {
    $.get("{{url('employees')}}/"+id+"/edit",function(data,status){
      $('#bigModalLabel').html('Edit Employee');
      $('#bigModalBody').html(data);
      $('#saveBig').show();
      $('#cancelBig').show();
      $('#saveBig').unbind();
      $('#saveBig').on('click',function(){
        if(null == elem){
          elem = findElem(id);
        }
        updateEmployee(id,elem);
      });
    });
  }
  // find elem of employee record
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
  // update employee in the database
  function updateEmployee(id,elem)
  {
    var employee = getData();
    alert(JSON.stringify(employee));
    resetErrors();
    $.ajax({
      url: "{{url('employees')}}/"+id,
      type: 'PUT',
      data: employee,
      success:function(result){
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
      table.row($(elem).parents('tr')).data(fillRowData(employee,id)).invalidate().draw();
    }

  }
  // fill data of a row
  function fillRowData(employee,id)
  {
    var row = {
        "DT_RowId": id,
        "name":"<a href='#' onclick='viewEmployee("+id+")' data-target='#bigModal' data-toggle='modal'>"+ employee.lastname +", " + employee.firstname + " " + employee.middlename+"</a><a href='#' onclick='editEmployee("+id+",this)' class='fas fa-edit float-right' data-target='#bigModal' data-toggle='modal'></a>",
        "email":employee.email,
        "contact":employee.contact,
        "delete":"<button onclick='deleteEmployee("+id+",this)' class='btn btn-sm btn-danger btn-block'><i class='fas fa-trash-alt'></i></button>"
    };
    return row;
  }

  // delete employee
  function deleteEmployee(id,elem)
  {
    swal("Do you want to remove employee from database?", {
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
          removeEmployee("{{url('employees/soft')}}/"+id,elem);
          
          break;
    
        case "hard":
          removeEmployee("{{url('employees')}}/"+id,elem);
          break;
        default:;
      }
    });
    function removeEmployee(url)
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

  // get employee data
  function getData()
  {
    var employee = {
      firstname : toTitleCase($('#firstname').val()),
      middlename : toTitleCase($('#middlename').val()),
      lastname : toTitleCase($('#lastname').val()),
      email : $('#email').val(),
      gender : $('input[name="gender"]:checked').val(),
      birth_date : $('#birth_date').val(),
      contact : $('#contact').val(),
      address : toTitleCase($('#address').val()),
      salary : $('#salary').val()
    };
    return employee;
  }

  // View employee
  function viewEmployee(id)
  {
    $.get("{{url('employees')}}/"+id,function(data,status){
      $('#bigModalLabel').html('Employee Information');
      $('#bigModalBody').html(data);
      $('#saveBig').hide();
      $('#cancelBig').hide();

    });
  }

</script>