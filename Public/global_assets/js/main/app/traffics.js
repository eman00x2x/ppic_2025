const traffics = function () { 
	const init = () => {
		
		$(document).on('click', '.btn-confirm-selection', function () {
			eo.submitForm("#form", {
				callback: function (formData) {
					const { ids, action, action_value } = formData;
					ids.split(",").forEach(function (id) {
						switch (action) {
							case "set_source":
								$('.row_' + id + " .source-text").html(action_value);
								break;
							case "delete":
								$('.row_' + id).remove();
								break;
						}
					});
					bootstrap.Modal.getInstance($('#modalDoAction')).hide();
					$('.form-check-input-selection, .check-input-selector').prop('checked', false).trigger('change');
				}
			});
		});	
	};

	return {
		initBeforeLoad: () => {
			init();
		}
	};
}();

document.addEventListener('DOMContentLoaded', function () {
	traffics.initBeforeLoad();
});

window.addEventListener('load', function () {
	charts.getTotalTrafficsPerDay();
});