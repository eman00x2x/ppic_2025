const leads = function () { 
	const init = () => {
		$(document).on('click', '.btn-save', function (e) {
			eo.submitForm("#form", {
				validation: {
					name: {
						presence: { allowEmpty: false },
						type: "string",
						length: { minimum: 4 }
					},
					email: {
						presence: { allowEmpty: false },
						type: "string",
						format: {
							pattern: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
							message: "must be a valid email address."
						}
					},
					contact_number: {
						presence: { allowEmpty: false }
					}
				}
			});
		});

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
	leads.initBeforeLoad();
});
