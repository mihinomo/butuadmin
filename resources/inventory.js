
$(function() {
    console.log("Product Fetch Api has been fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/products/',
        success: function(data){
            
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.name + "</td><td class='zoom'><img src=\'/resources/products/" + value.image + "\'height='50px;' width='50px;'></td><td>" + value.hsn_sac + "</td><td>" + value.stockcode + "</td><td>"+ value.cost + "</td><td>"+ value.price + "</td><td>" + value.stock + "</td><td>" + value.tax + "%</td><td><a style='margin:0rem 1rem;' onclick='actionedit(\"" + value.id + "\");'> <i class='fa fa-edit'></i></a></td></tr>");
                $("#ctable").append(row);
            });
            $('#ctable').DataTable({
                "dom": '<"pull-left"f><"pull-right"l>tip'
            });
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
});
  $(function() {
      console.log("Api Request has been fired");
      $.ajax({
          type: 'POST',
          dataType: "json",
          url: '/api/category/',

          success: function(data){
              $.each(data, function( index, value ) {
                 var uid = value.uid;
                 var row = $("<tr id='tt'><td>" + value.name + "</td><td>" + value.description + "</td><td>" + value.dated + "</td><td><a style='margin:0rem 1rem;' onclick='deleteCat(\"" + value.id + "\");'> <i class='fa fa-trash'></i></a></td></tr>");
                 $("#catbody").append(row);
              });
          },
          error: function(jqXHR, textStatus, errorThrown){
              alert('error: ' + textStatus + ': ' + errorThrown);
          }
      });
      return false;//suppress natural form submission
  });

$(document).ready(function(e){
  // Submit form data via Ajax
  $("#addcustomer").on('submit', function(e){
      $('#tbody').empty();
      e.preventDefault();
      $.ajax({
          type: 'POST',
          url: 'api/insert.php?addproduct',
          data: new FormData(this),
          dataType: 'json',
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function(){
              //
          },
          success: function(response){ console.log(response);
              if(response.status == 1){
                  $('#addcustomer')[0].reset();
                  $('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
              }else{
                  $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');

              }
              $('#exampleModal').modal('hide');
              reload_table();
          }
      });
  });
});

$(document).ready(function(e){
    // Submit form data via Ajax
    $("#addcategory").on('submit', function(e){
        $('#catbody').empty();
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/insert/addcategory/',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                //
            },
            success: function(response){ console.log(response);
                if(response.status == "success"){
                  toastr.success('Successfully Added To Category List');
                }else{
                  toastr.warning('There was an error. Please Try again')
  
                }
                $('#categorymodal').modal('hide');
                reload_category_table();
            }
        });
    });
  });
  $(document).ready(function(e){
    // Submit form data via Ajax
    $("#transferstock").on('submit', function(e){
        $('#tbody').empty();
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'api/insert.php?transferstock',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                //
            },
            success: function(response){ console.log(response);
                $('.statusMsg').html('');
                if(response.status == 1){
                  toastr.success('Successfully Added To Category List');
                }else{
                  toastr.warning('There Was a erro. Please Try Again');
  
                }
                $('#transfermodal').modal('hide');
                reload_table();
            }
        });
    });
  });
  
  function actionde(val){
  
     $('#tbody').empty();
        var jsonString = JSON.stringify(val);
        $.ajax({
        type: 'POST',
        url: 'api/insert.php?del='+val,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,

        success: function(response){
            $('.statusMsg').html('');
            if(response.status == 1){
                $('#addcustomer')[0].reset();
                $('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
            }else{
                $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');

            }
            reload_table();
        }
    });
}
  
  
  
  function deleteCat(val){
      $.ajax({
          type: 'POST',
          url: '/insert/delcat/'+val,
          dataType: 'json',
          contentType: false,
          cache: false,
          processData:false,
  
          success: function(response){
              if(response.status == "success"){
                  toastr.success('Successfully Deleted From Category List');
              }else{
                  toastr.warning('There Was an error. Please Try Again');
  
              }
              reload_category_table()
          }
      });
  }
  
  
  
  function actionedit(val){
  $('#editmodal').modal('show');
  $.ajax({
    type: 'GET',
    url: 'api/api.php?getproduct='+val,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData:false,
    success: function(response){
        $('input[name="titlee"]').val(response[0]['name']);
        $('input[name="hsne"]').val(response[0]['hsn_sac']);
        $('input[name="coste"]').val(response[0]['cost']);
        $('input[name="pricee"]').val(response[0]['price']);
        $('input[name="taxe"]').val(response[0]['tax']);
        $('input[name="stocke"]').val(response[0]['stock']);
        $('input[name="descriptione"]').val(response[0]['description']);
        $('input[name="stockcodee"]').val(response[0]['stockcode']);
        $('input[name="dqtye"]').val(response[0]['dqty']);
        $("select[name='categorye']").find("option[value='"+response[0]['category']+"']"). attr("selected",true);
        $("select[name='suppliere']").find("option[value='"+response[0]['supplier']+"']"). attr("selected",true);
        $("select[name='storee']").find("option[value='"+response[0]['store']+"']"). attr("selected",true);
        $("select[name='unite']").find("option[value='"+response[0]['unit']+"']"). attr("selected",true);
        $("textarea#descripti").val(response[0]['description'])
        $('#defsup').text(response[0]['supplier']);
        $('input[name="ppid"]').val(response[0]['id']);
    }
  });
  }
  
  function actiontransfer(val,val2){
  $('#transfermodal').modal('show');
  $.ajax({
    type: 'GET',
    url: 'api/api.php?transfer='+val+'&tfid='+val2,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData:false,
    success: function(response){
        $('input[name="prid"]').val(response['id']);
        $('input[name="prname"]').val(response['name']);
        $('input[name="prunit"]').val(response['stock']);
        $("select[name='trstore']").find("option[value='"+response['store']+"']"). attr("selected",true);
  
    }
  });
  }
  function reload_table() {
    console.log("Product Fetch Api has been fired");
    $("#ctable").empty();
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/products/',
        success: function(data){
            
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.name + "</td><td class='zoom'><img src=\'/resources/products/" + value.image + "\'height='50px;' width='50px;'></td><td>" + value.hsn_sac + "</td><td>" + value.stockcode + "</td><td>"+ value.cost + "</td><td>"+ value.price + "</td><td>" + value.stock + "</td><td>" + value.tax + "%</td><td><a style='margin:0rem 1rem;' onclick='actionedit(\"" + value.id + "\");'> <i class='fa fa-edit'></i></a></td></tr>");
                $("#ctable").append(row);
            });
            $('#ctable').DataTable({
                "dom": '<"pull-left"f><"pull-right"l>tip'
            });
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
  }
  function reload_category_table() {
  console.log("Api Request has been fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/category/',
  
        success: function(data){
            $("#catbody").empty();
            $.each(data, function( index, value ) {
                  var uid = value.uid;
                  var row = $("<tr id='tt'><td>" + value.name + "</td><td>" + value.description + "</td><td>" + value.dated + "</td><td><a style='margin:0rem 1rem;' onclick='deleteCat(\"" + value.id + "\");'> <i class='fa fa-trash'></i></a></td></tr>");
                   $("#catbody").append(row);
              });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
  }
  
  
  function show_stock_history(val){
  $('#stockhistory').modal('show');
  console.log("Stock History Request has been fired");
  $.ajax({
    type: 'POST',
    dataType: "json",
    url: 'api/api.php?stockhistory='+val,
  
    success: function(data){
        $.each(data, function( index, value ) {
           var uid = value.uid;
           var row = $("<tr id='tt'><td>" + value.id + "</td><td>" + value.name + "</td><td class='text-danger'><i class='fa fa-arrow-up text-danger'></i>" + value.from + "</td><td class='text-success'><i class='fa fa-arrow-down text-success'></i>" + value.to + "</td><td>" + value.qty + "</td><td>" + value.stamp + "</td></tr>");
           $("#stockhistorytable").append(row);
        });
    },
    error: function(jqXHR, textStatus, errorThrown){
        alert('error: ' + textStatus + ': ' + errorThrown);
    }
  });
  return false;//suppress natural form submission
  }