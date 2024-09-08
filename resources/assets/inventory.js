

$(function() {
    console.log("Product Fetch Api has been fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/products/',
        success: function(data){
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.name + "</td><td class='zoom'><img src='/assets/products/" + value.image + "\'height='50px;' width='50px;'></td><td>" + value.hsn_sac + "</td><td>" + value.stockcode + "</td><td>"+ value.cost + "</td><td>"+ value.price + "</td><td>" + value.stock + "</td><td>" + value.tax + "%</td><td><a style='margin:0rem 1rem;' href='/editproduct/"+value.id+"'> <i class='fa fa-edit'></i></a><a style='margin:0rem 1rem;' onclick='actiontransfer(\"" + value.id + "\",\"" + value.stid + "\");'> <i class='fa fa-truck'></i></a><a style='margin:0rem 1rem;' onclick='show_stock_history(\"" + value.id + "\");'> <i class='fa fa-clipboard'></i></a></td></tr>");
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


// Add Category ////////////////////////////////////
// Add Category ////////////////////////////////////// Add Category ////////////////////////////////////
// Add Category ////////////////////////////////////
$(function() {
    console.log("category Api Request has been fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/category/',

        success: function(data){
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.name + "</td><td>" + value.description + "</td><td>" + value.dated + "</td><td><a style='margin:0rem 1rem;' onclick='catdelete(\"" + value.id + "\");'> <i class='fa fa-trash'></i></a></td></tr>");
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
    $("#addcategory").on('submit', function(e){
        $('#tbody').empty();
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
                    $('#categorymodal').modal('hide');
                    reload_category_table();
                }else{
                    alert("There was an error");
                }
                
            }
        });
    });
});


function catdelete(val){
    var jsonString = JSON.stringify(val);
    $.ajax({
        type: 'POST',
        url: '/insert/delcat/'+val,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData:false,
        success: function(response){
            if(response.status == "success"){
                alert("Successful");
            }else{
                alert("Error");
        
            }
            reload_category_table();
        }
    });
}

function reload_category_table() {
    console.log("Api Request has been fired");
    $("#catbody").empty();
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/category/',

        success: function(data){
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.name + "</td><td>" + value.description + "</td><td>" + value.dated + "</td><td><a style='margin:0rem 1rem;' onclick='catdelete(\"" + value.id + "\");'> <i class='fa fa-trash'></i></a></td></tr>");
                $("#catbody").append(row);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
};


// Add Category ////////////////////////////////////// Add Category ////////////////////////////////////

$(document).ready(function(e){
// Submit form data via Ajax
$("#transferstock").on('submit', function(e){
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
                $('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
            }else{
                $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');

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



//Edit Product
function actionedit(val){
    $link = "/editproduct/".val;
    window.location.replace("/editproduct/");
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
console.log("Table Reload Has Fired");
$.ajax({
type: 'POST',
dataType: "json",
url: 'api/api.php?products',
success: function(data){
    $("#tbody").empty();
    $.each(data, function( index, value ) {
        var row = $("<tr id='tt'><td>" + value.name + "</td><td class='zoom'><img src=\'res/products/" + value.image + "\'height='50px;' width='50px;'></td><td>" + value.hsn_sac + "</td><td>" + value.stockcode + "</td><td>" + value.cost + "</td><td>"+ value.price + "</td><td>" + value.stock + "</td><td>" + value.tax + "%</td><td><a style='margin:0rem 1rem;' onclick='actionedit(\"" + value.id + "\");'> <i class='fa fa-edit'></i></a></td></tr>");
        $("#ctable").append(row);
    });
    $('#ctable').DataTable();
},
error: function(jqXHR, textStatus, errorThrown){
    alert('error: ' + textStatus + ': ' + errorThrown);
}
});
return false;//suppress natural form submission
}

function reload_supplier_table() {
console.log("Api Request has been fired");
$.ajax({
type: 'POST',
dataType: "json",
url: 'api/api.php?supplier',

success: function(data){
    $.each(data, function( index, value ) {
        var uid = value.uid;
        var row = $("<tr id='tt'><td>" + value.name + "</td><td>" + value.phone + "</td><td>" + value.address + "</td><td>" + value.gstin + "</td><td><a style='margin:0rem 1rem;' onclick='actionedit(\"" + value.id + "\");'> <i class='fa fa-edit'></i></a></td></tr>");
        $("#suptable").append(row);
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