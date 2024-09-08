

$(document).ready(function(){
    window.groupCount = 0;
  
    String.prototype.replaceAll = function(search, replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };
  
    var rowTemplate = '<tr>'+
				 '<input type="hidden" size="3" name="id[]" data-group="%group%" value="new" data-id="id" required><input type="hidden" size="3" name="cost[]" data-group="%group%" value="0" data-id="cost" required>'+
				 '<td><input type="text" size="3" class="form-control" name="quantity[]" data-group="%group%" value="" data-id="quantity" required></td>'+
				'<td><input type="text" size="25" class="form-control" name="name[]" data-group="%group%" data-id="name" value="" required></td>'+
				'<input type="hidden" size="6" class="form-control" name="hsn[]" data-group="%group%" data-id="name" value="12" >'+
				'<td><input type="text" size="10" class="form-control" maxlength="13" name="tax[]" data-group="%group%" value="" data-id="tax" required>'+
				'<input type="hidden" size="6"maxlength="13" name="taxrate[]" data-group="%group%" placeholder="" data-id="taxrate"></td>'+
				 '<td><input type="text" size="3" class="form-control" name="price[]" data-group="%group%" data-id="price" value="" required></td>'+
				 '<td><input type="text" size="3" class="form-control" name="subtotal[]" data-group="%group%" value="" id="subtotal" data-id="subtotal" readonly required></td>'+
				 '<td width="20px;"><a class="remove" style="width:20px"><i class="fa fa-trash"></i></a></td>'+
				 '</tr>';
   
    
  
    $(document).on("change paste keyup", "input[data-id='id']", function(){
        var id = $(this).val();
        var group = $(this).data('group');
   		console.log('id:', id, group);
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
	 $(document).on("change paste keyup", "input[data-id='price']", function(){
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
	
    $("#btnadd").on('click', function() {
        groupCount++;
        $('#mytable tr:last').after(rowTemplate.replaceAll('%group%', groupCount));      
    });
});




function generatetotal(){
	test_qty = 0;
	tax = 0;
	$sumDisplay = $('#subtotallast');
	$tax = $('#taxtotal');
	$total = $('#totaltotal');
	$discount = $("input[name='discount']").val();
	$cartage = parseInt($("input[name='cartage']").val());
	$("input[name^='subtotal']").each(function() { 
		test_qty +=parseInt($(this).val());  
	});
	$("input[name^='taxrate']").each(function() { 
		if($(this).val()==0){
			tax +=0;
		}else{
			tax +=parseInt($(this).val());
		}
		 
	});
	console.log(tax);
	$tot = test_qty - $discount + $cartage;
	$sumDisplay.text("Rs. "+test_qty);
	$tax.text("Rs. "+tax);
	$total.text("Rs. "+$tot);
}




 $(document).on('click', '.remove', function(){
  $(this).closest('tr').remove();
  generatetotal();
 });





/* $(window).ready(function(){

//$("#bCode").scannerDetection();

$(window).scannerDetection();
$(window).bind('scannerDetectionComplete',function(e,data){
		console.log(data.string);
		var dataa = 'gbpr=' + data.string;
		$.ajax({
			url:'api/api.php',
			cache: false,<!---
			data: dataa,
			type: 'POST',
			dataType:"json",
			success: function (response) {
				if ($.trim(response)){  				
					var tax = parseInt(response[0]['tax']);
					var subtotal = response[0]['dqty']*response[0]['price'];
					var taxrate = subtotal/100*tax;
					var rowTemplate = '<tr>'+
						 '<input type="hidden" size="3" name="id[]" data-group="%group%" value="'+response[0]['id']+'" data-id="id" required><input type="hidden" size="3" name="cost[]" data-group="%group%" value="'+response[0]['cost']+'" data-id="cost" required>'+
						 '<td><input type="text" size="3" class="form-control" name="quantity[]" data-group="%group%" value="'+response[0]['dqty']+'" data-id="quantity" required></td>'+
						'<td><input type="text" size="25" name="name[]" data-group="%group%" data-id="name" value="'+response[0]['name']+'" readonly required></td>'+
						'<td><input type="text" size="6" name="hsn[]" data-group="%group%" data-id="name" value="'+response[0]['hsn_sac']+'" readonly required></td>'+
						'<td><input type="text" size="10" maxlength="13" name="tax[]" data-group="%group%" value="'+response[0]['tax']+'" data-id="tax" required><br>'+
						'GST '+tax+'% - <input type="text" size="6" maxlength="13" name="taxrate[]" data-group="%group%" placeholder="'+taxrate+'" data-id="taxrate" readonly></td>'+
						 '<td><input type="text" size="3" name="price[]" data-group="%group%" data-id="price" value="'+response[0]['price']+'" readonly required></td>'+
						 '<td><input type="text" size="3" name="subtotal[]" data-group="%group%" value="'+subtotal+'" id="subtotal" data-id="subtotal" readonly required></td>'+
						 '<td width="20px;"><a class="remove" style="width:20px"><i class="fa fa-trash"></i></a></td>'+
						 '</tr>';
					groupCount++;
					$('#mytable tr:last').after(rowTemplate.replaceAll('%group%', groupCount));
					generatetotal();
					$('#search').val("");
				}else{
					alert("Item Does Not Exist In Database");
				}
			}
		});
		})

	//$(window).scannerDetection('success');	
}); */