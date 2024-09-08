$(document).ready(function () {
//change selectboxes to selectize mode to be searchable
    $("#addpr").select2();
});
$(function() {
    console.log("submit handler has fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/getinvoice/',
        data: { invid: invoice },
        success: function(data){ 
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.serial + "</td><td>" + value.dated + "</td><td>" + value.qty + "</td><td>"+ value.name + "</td><td>" + value.hsn + "</td><td>" + value.tax + "</td><td>" + value.rate + "</td><td>"+value.discount +"</td><td>"+value.subtotal +"</td><td><a onclick='deleterec("+value.id +");'><i class='fa fa-trash'></i></a></td></tr>");
                $("#intable").append(row);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
});

$(function() {
    console.log("submit handler has fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/invoicescore/',
        data: { invid: invoice },
        success: function(response){ 
            console.log(response);
            $("#subtotal").text("Rs. "+response.subtotal);
            $("#discount").text("Rs. "+response.discount);
            $("#ttax").text("Rs. "+response.tax);
            $("#cartage").text("Rs. "+response.cartage);
            $("#total").text("Rs. "+response.total);
            $("#paid").text("Rs. "+response.paid);
            $("#due").text("Rs. "+response.due);
            $("#inv").text("#00"+response.id);
            $("#oid").text(response.invid);
            $("#dat").text(response.dated);
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
});
$(function() {
    console.log("submit handler has fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/inpay/',
        data: { invid: invoice },
        success: function(data){ 
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.type + "</td><td>Rs. " + value.amount + "</td><td>" + value.rmks + "</td><td>"+ value.dated + "</td><td><a onclick='deletePay("+value.id +");'><i class='fa fa-trash'></i></a></td></tr>");
                $("#paymenttable").append(row);
            });
            $('#ctable').DataTable();
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
});

function deleterec(val){
    $.confirm({
        title: '<b class="text-danger">ATTENTION !!</b>',
        content: '<b class="text-info">Select Appropriate Action!</b>',
        buttons: {
            somethingElse: {
                text: 'Defect',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function(){
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: 'api/insert.php?defectin',
                        data: { id: val },
                        success: function(response){ 
                            if(response.status=='1'){
                                toastr.success(response.message);
                            }else{
                                toastr.warning(response.message);
                            }
                            reloadInvoice();
                            reloadScore();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert('error: ' + textStatus + ': ' + errorThrown);
                        }
                    });
                }
            },
            alsoSomething: {
                text: 'Return',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function(){
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: '/inbill/return/',
                        data: { pid: val },
                        success: function(response){ 
                            if(response.status=='1'){
                                toastr.success(response.message);
                            }else{
                                toastr.warning(response.message);
                            }
                            reloadInvoice();
                            reloadScore();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert('error: ' + textStatus + ': ' + errorThrown);
                        }
                    });
                }
            },
            cancel: function () {
                $.alert('<b class="text-info">Canceled!</b>');
            }
        }
    });
}

function deleteInvoice(val){
    $.confirm({
        title: '<b class="text-danger">ATTENTION !!</b>',
        content: '<b class="text-info">Select Appropriate Action!</b>',
        buttons: {
            somethingElse: {
                text: 'Delete Invoice',
                btnClass: 'btn-blue',
                keys: ['enter', 'shift'],
                action: function(){
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: '/delete/invoice/',
                        data: { id: val },
                        success: function(response){ 
                            if(response.status=='1'){
                                alert(response.message);
                                window.location.replace('/invoice/');
                            }else{
                                alert(response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            alert('error: ' + textStatus + ': ' + errorThrown);
                        }
                    });
                }
            },
            cancel: function () {
                $.alert('<b class="text-info">Canceled!</b>');
            }
        }
    });
}

$('#addpr').on('change', function(){      
    $('#billingmodal').modal('show');
    jQuery.ajax({
        url:'/api/inpr/',
        cache: false,
        data: {
            gpr: $(this).val(),
        },
        type: 'POST',
        dataType:"json",
        success: function(response){
        var tax = parseInt(response[0]['tax']);
        var subtotal = response[0]['dqty']*response[0]['price'];
        var taxrate = subtotal/100*tax;
        var rowTemplate = '<tr>'+
                '<input type="hidden" size="3" name="id[]" data-group="%group%" value="'+response[0]['id']+'" data-id="id" required><input type="hidden" size="3" name="cost[]" data-group="%group%" value="'+response[0]['cost']+'" data-id="cost" required>'+
                '<td><input type="text" size="3" name="quantity[]" data-group="%group%" value="200" data-id="quantity" required></td>'+
                '<td><input type="text" size="10" name="name[]" data-group="%group%" data-id="name" value="'+response[0]['name']+'" readonly required></td>'+
                '<input type="hidden" size="6" name="hsn[]" data-group="%group%" data-id="name" value="'+response[0]['hsn_sac']+'" readonly required>'+
                '<input type="hidden" size="10" maxlength="13" name="tax[]" data-group="%group%" value="'+response[0]['tax']+'" data-id="tax" required><br>'+
                '<input type="hidden" size="3" maxlength="13" name="taxrate[]" data-group="%group%" value="'+taxrate+'" data-id="taxrate" required></td>'+
                '<td><input type="text" size="3" name="price[]" data-group="%group%" data-id="price" value="'+response[0]['price']+'" readonly required></td>'+
                '<td><input type="digit" size="3" name="dis[]" data-group="%group%" id="dis" data-id="dis" value="0" required></td>'+
                '<td><input type="text" size="3" name="subtotal[]" data-group="%group%" value="'+subtotal+'" id="subtotal" data-id="subtotal" readonly required></td>'+
                '<td width="20px;"><a class="remove" style="width:20px">Remove</a></td>'+
                '</tr>';
        groupCount++;
        $('#addtable tr:last').after(rowTemplate.replaceAll('%group%', groupCount));
        generatetotal();
        }
    });

});
$(document).on('click', '.remove', function(){
    $(this).closest('tr').remove();
    generatetotal();
});
$(document).on("change paste keyup", "input[data-id='quantity']", function(){
    var qty = $(this).val();
    var group = $(this).data('group');
    var tax = $("input[data-id='tax'][data-group='"+group+"']").val();
    var price = $("input[data-id='price'][data-group='"+group+"']").val();
    var subtotal = qty * price;
    var taxrate = subtotal/100*tax;
    $("input[data-id='taxrate'][data-group='"+group+"']").val(taxrate);
    $("input[data-id='subtotal'][data-group='"+group+"']").val(subtotal);
    generatetotal();
});
$(document).on("change paste keyup", "input[data-id='dis']", function(){
    var dis = $(this).val();
    var group = $(this).data('group');
    var qty = $("input[data-id='quantity'][data-group='"+group+"']").val();
    var price = $("input[data-id='price'][data-group='"+group+"']").val();
    var tot = qty * price;
    var subtotal =  parseInt(tot) - parseInt(dis);
    $("input[data-id='subtotal'][data-group='"+group+"']").val(subtotal);
    generatetotal();
});
function generatetotal(){
    test_qty = 0;
    tax = 0;
    discount = 0;
    $sumDisplay = $('#subtotallast');
    $tax = $('#taxtotal');
    $total = $('#totaltotal');
    $("input[name^='subtotal']").each(function() { 
        test_qty +=parseInt($(this).val());  
    });
    $("input[name^='dis']").each(function() { 
        discount +=parseInt($(this).val());  
    });
    $("input[name^='taxrate']").each(function() {
        tax +=parseInt($(this).val());  
    });
    //console.log(tax);
    $tot = test_qty - discount;
    $sumDisplay.text("Rs. "+test_qty);
    $tax.text("Rs. "+tax);
    $total.text("Rs. "+$tot);
}

$(document).ready(function(e){
    // Submit form data via Ajax
    $("#invadd").on('submit', function(e){
        $('#tbody').empty();
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/insert/inbill/',
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
                if(response.status == "1"){
                    $('#invadd')[0].reset();
                    toastr.success(response.message);
                }else{
                    toastr.warning(response.message);
                    
                }
                $('#billingmodal').modal('hide');
                reloadInvoice();
                reloadScore();
            }
        });
    });
});

function reloadInvoice(){
    console.log("submit handler has fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/getinvoice/',
        data: { invid: invoice },
        success: function(data){ 
            $.each(data, function( index, value ) {
                var uid = value.uid;
                var row = $("<tr id='tt'><td>" + value.serial + "</td><td>" + value.dated + "</td><td>" + value.qty + "</td><td>"+ value.name + "</td><td>" + value.hsn + "</td><td>" + value.tax + "</td><td>" + value.rate + "</td><td>"+value.discount +"</td><td>"+value.subtotal +"</td><td><a onclick='deleterec("+value.id +");'><i class='fa fa-trash'></i></a></td></tr>");
                $("#intable").append(row);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
}

function reloadScore(){
    console.log("submit handler has fired");
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: '/api/invoicescore/',
        data: { invid: invoice },
        success: function(response){ 
            console.log(response);
            $("#subtotal").text("Rs. "+response.subtotal);
            $("#discount").text("Rs. "+response.discount);
            $("#ttax").text("Rs. "+response.tax);
            $("#cartage").text("Rs. "+response.cartage);
            $("#total").text("Rs. "+response.total);
            $("#paid").text("Rs. "+response.paid);
            $("#due").text("Rs. "+response.due);
            $("#inv").text("#00"+response.id);
            $("#oid").text(response.invid);
            $("#dat").text(response.dated);
            
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('error: ' + textStatus + ': ' + errorThrown);
        }
    });
    return false;//suppress natural form submission
}
