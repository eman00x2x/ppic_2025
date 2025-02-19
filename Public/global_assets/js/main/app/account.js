const account = function () { 

	const init = () => {
		$(document).on('click', '.btn-save', function () {
			eo.submitForm("#form", {
				validation: {
					email: {
						presence: { allowEmpty: false },
						type: "string",
						format: {
							pattern: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
							message: "must be a valid email address."
						}
					},
					username: {
						presence: { allowEmpty: false },
						type: "string",
						length: { minimum: 2 },
					}
				}
			});
		});

		$(document).on('click', '.btn-confirm-selection', function () {
			eo.submitForm("#form", {
				callback: (formData, response) => {
					const { ids, action, action_value } = formData;
					ids.split(",").forEach(function (id) {
						let status = action_value == 'active' ? "success" : "danger";
						$('.row_' + id + " .status-text").html("<span class='badge bg-" + status + " me-1'></span> " + action_value + "");
					});
					bootstrap.Modal.getInstance($('#modalDoAction')).hide();
					$('.form-check-input-selection, .check-input-selector').prop('checked', false).trigger('change');
				}
			});
		});

		$(document).on('change', '.check-input-selector, .form-check-input-selection', function () {
			let selection = $('.form-check-input-selection:checked').length;
			if (selection > 1) {
				$('.delete-wrapper').addClass('d-none');
			} else {
				$('.delete-wrapper').removeClass('d-none');
			}
		});

		$(document).on('click', '.btn-single-delete', function (e) {
			const id = [];
			$(".form-check-input-selection:checked").each(function () {
				id.push($(this).val());
			});

			const element = $('#offcanvasEnd');
			const bsOffcanvas = new bootstrap.Offcanvas(element);

			bsOffcanvas.show();

			$.get(eo.DOMAIN + "/accounts/" + id[0] + "/delete", function (data, status) {
				element.html(data);
			});
		});
	}

	const imageUploader = () => {
		const imageUplaoderContainer = $('.photo-preview');
		if (!imageUplaoderContainer[0]) {
			return false;
		}

		eo.component.uploader.create({
			url: $('.photo-container').data("url"),
			uploadContainerSelector: '.photo-container',
			multiple: false,
			success: function (data) {
				eo.component.uploader.setSingleUploadContainer(data[0], '.photo-preview');
			}
		});

	};

	return {
		initBeforeLoad: () => {
			init();
		},

		initAfterLoad: () => {
			imageUploader();
		}
	};
}();

document.addEventListener('DOMContentLoaded', function () {
	account.initBeforeLoad();
});

window.addEventListener('load', function () {
	account.initAfterLoad();
	charts.getTotalPropertiesPerCategory();
	charts.getMonthlyPostings();
	charts.getTotalPropertiesPerStatus();
	charts.filterReportResults();
});

