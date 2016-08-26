<form class="form-horizontal">
	<fieldset id="payment">
		<div class="buttons">
	        <div class="pull-right">
	        	<input type="button" value="<?php echo $button_pay; ?>" id="button-confirm" class="btn btn-primary" />
	        </div>
	    </div>
	</fieldset>
</form>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/payu_easyplus/send',
		type: 'post',
        dataType: 'json',	
        cache: false,	
		beforeSend: function() {
			$('#button-confirm').hide();
			$('#button-confirm').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> Contacting PayU secure payments gateway... </div>');
		},
		complete: function() {

		},				
		success: function(json) {
			if (json['success']) {
				console.log('Success');
				location.replace(json['success']);
			}
            if (json['error']) {
				alert(json['error']);
                $('#button-confirm').show();
                $('.attention').remove();
			}
		}
	});
});
//--></script>

	
	