$(document).ready(function () {
	$('form[data-default-submit="yes"]').submit(function (e) {
		e.preventDefault();

		$(this).find('label').removeClass('has-error');
		var form = $(this);

		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			dataType: 'json',
			data: $(this).serialize(),
			error: function(jqXHR, textStatus, errorThrown) {
				messenger.error("Error", "There was an error while sending data");
			},
			success: function(json, textStatus, jqXHR) {
				if (json.success)
				{
					messenger.success(json.message);
					form.find('#id').val(json.id);
				}
				else
				{
					//evidenzia la label con l'attributo for corrispondente
					form.find('label[for="'+json.wrong_label+'"]').addClass('has-error');
					messenger.error(json.message);
				}
			},
			
		});

	});
});