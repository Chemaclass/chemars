/**
 * Author: José María Valera Reales
 * 
 */
$(document).ready(function(){
	
}); 

$(document).on('click', '.voucher-delete', function(e) {
    $this = $(this)
    
    voucherId = $this.attr('voucher_id')

    data = {voucherId : voucherId}

	$.ajax({
		url : "remove-voucher",
		type : "POST",
		data : data,
		dataType : "json",
		beforeSend: function() {
			
		},
		success : function(json) {
			// rest 1 to total
			vouchersTotal = $('.vouchers-total').text()
			$('.vouchers-total').text(vouchersTotal-1)
			// remove tr row
			parent = $this.parent().parent()
			parent.remove()
		}, error: function (xhr, ajaxOptions, thrownError) {
		 console.log("status: "+xhr.status + ",\n responseText: "+xhr.responseText 
				 + ",\n thrownError "+thrownError);
		}
	})
});