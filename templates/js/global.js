$(document).ready(function () {
	if ($('.require-delete-confirm').size() > 0)
	{
		$('.require-delete-confirm').click(function () {
			var item = $(this);
			messenger.confirm("Vuoi cancellare l'elemento selezionato?", null, 'Annulla', 'Conferma', function() {

				$.ajax({
					type: 'POST',
					url: item.attr('data-href'),
					dataType: 'json',
					error: function(jqXHR, textStatus, errorThrown) {
						messenger.error("Error", "There was an error while sending data");
					},
					success: function(json, textStatus, jqXHR) {
						if (json.success)
						{
							messenger.success(json.message);
							item.parentsUntil('tr').parent().fadeOut();
						}
						else
						{
							messenger.error(json.message);
						}
					},
					
				});

			});
		});

		var dialog = $('<div>')
			.attr('title', 'Conferma richiesta')
			.attr('id', 'dialog_confirm')
			;
		$('body').append(dialog);

		$('.require-confirm').click(function (e) {
			var href = $(this).attr('href');
			e.preventDefault();

			dialog.dialog({
				modal: true,
				buttons: {
					"Continua": function() {
						$(this).dialog('close').dialog('destroy');
						location.href = href;
					},
					"Annulla": function() {
						$(this).dialog('close').dialog('destroy');
					}
				}
			});
		});
	}
});