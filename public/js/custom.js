/****
CSRF 
*****/
$.ajaxSetup({
   headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
});

/****
TODO ADD 
*****/
$('#todoForm').on('submit', function(e){
    var todo_form_data = $(this).serialize();
    $('#ajaxResponsename').html(''); 
    e.preventDefault();
    $.ajax({
        type : 'POST',
        data : todo_form_data ,
        success : function(data){
          console.log(data);
            if(data.status=='success') {
              $('#name').val('');
              dataTableInstance.draw();
              swal("Success!", "Your todo has been added successfully.", "success");
            }else{
              $.each(data, function (index, value) {
                  $("#ajaxResponse"+index).html(value);
                  $('#todobtn').attr('disabled',false);
              });
            }
        }
    });
});

/****
TODO DELETE
*****/
function delete_account(id, type, extra_param)
{
  var action = extra_param;
  var params = {}

  params['token'] = $('meta[name="csrf-token"]').attr('content')
  params['id'] = id
  params['type'] = type
  params['extra_param'] = extra_param

  $.ajax({
       url: base_url + '/todos',
       async:false,
       method:'POST',
       data:params,
       success:function(msg){
        if(msg=='true')
        {
          dataTableInstance.draw();
          swal("Deleted!", "Your selected todo has been deleted.", "success");
        }
        else
        {
          return;
        }
       }
   });
}


