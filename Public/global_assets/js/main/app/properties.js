const Properties = function () {
	const initTinyMCEeditor = () => {
		eo.component.tinymce.init("#textContainer");
	};

	const contentNavTransition = () => {
		$(document).on('click', '.content-nav', function () {
			let target = $(this).data('target');

			$('.content-nav').removeClass('active');
			$(this).addClass('active');

			$('.content-container').addClass('d-none');
			$(`#${target}`).removeClass('d-none');
		});
	};

	const handleListingTypeChange = () => {
		$(document).on('change', '#listing_type', (event) => {
			const selectedListingType = $(event.target).val();

			switch (selectedListingType) {
				case 'for sale':
					$('.hide_looking_for').show();
					$('.hide_rental').show();
					break;

				case 'looking for':
					$('#service_type').val('general brokerage');
					$('#authority_type').val('N/A').trigger('change');
					$('#is_website').prop('checked', false);
					$('.hide_looking_for').hide();
					$('.hide_foreclosure').hide();
					$('#service_type').trigger('change');
					$('.hide_rental').show();
					break;

				case 'for rent':
					$('#service_type').val('general brokerage').trigger('change');
					$('.hide_foreclosure').hide();
					$('.hide_rental').hide();
					break;
			}
		});
	};

	const handleAuthorityChange = () => {
		$(document).on('change', '#authority_type', function () {
			selected = $('#authority_type option:selected').val();

			if (selected == 'N/A') {
				$('#authority_to_sell_expiration').addClass('d-none');
				$('#authority_expiration_label').removeClass('d-none');
				$('#authority_to_sell_expiration').val('2038-01-01');
			} else {
				$('#authority_to_sell_expiration').removeClass('d-none');
				$('#authority_expiration_label').addClass('d-none');
				$('#authority_to_sell_expiration').val('');
			}
		});	
	};

	const handleServiceTypeChange = () => {
		$(document).on('change', '#service_type', function () {
			const selectedServiceType = $(this).val();

			if (selectedServiceType === 'project selling') {
				$('.mls-options, .brokerage-options').hide();
				$('#listing_type').val('for sale').trigger('change');
				$('.hide_foreclosure').hide();
				$('#listing_type').prop('disabled', true);
			} else {
				$('.mls-options, .brokerage-options').show();
				$('#listing_type').prop('disabled', false);
			}
		});
	};

	const handlePriceInputChange = () => {
		$(document).on('input', '#price', function () {
			const $this = $(this);
			$(this).val(parseInt($this.val().replace(/\,/g, '')));
		});	
	};

	const confirmSelection = () => {
		$(document).on('click', '.btn-confirm-selection', function () {
			const group = {
				set_category: (id, action_value) => {
					$(`.row_${id} .category-text`).text(action_value);
				},
				set_status: (id, status) => {
					const set_status = status == 1 ? "badge-outline text-green" : "bg-red text-red-fg";
					const value = status == 1 ? "Available" : "Sold";
					$(`.row_${id} .status-text`).html(`<span class='badge ${set_status}'><i class='ti ti-home-dollar'></i> ${value}</span>`);

					if (status == 2) {
						$(`.row_${id} .btn-options, .row_${id} .check-box-wrapper`).empty();
					}
				},
				delete: (id, action_value) => {
					$(`.row_${id}`).remove();
				}
			};
			
			eo.submitForm("#form", {
				callback: (formData, response) => {
					const { ids, action, action_value } = formData;
					ids.split(",").forEach(function (id) {
						group[action](id, action_value);
					});
					bootstrap.Modal.getInstance($('#modalDoAction')).hide();
					$('.form-check-input-selection, .check-input-selector').prop('checked', false).trigger('change');
				}
			});
		});
	};

	const saveForm = () => {
		$(document).on('click', '.btn-save', function (e) {
			eo.submitForm("#form", {
				onBeforeSend: (formData) => {
					if (typeof tinymce === 'object' && tinymce.get('textContainer')) {
						formData.append('long_desc', tinymce.get('textContainer').getContent());
					}
				},
				validation: {
					title: {
						length: { minimum: 4 },
						presence: { allowEmpty: false }
					},
					lot_area: { presence: { allowEmpty: false } },
					category: { presence: { allowEmpty: false } },
					thumb_img: { presence: { allowEmpty: false } },
					listing_type: { presence: { allowEmpty: false } },
					price: { presence: { allowEmpty: false } }
				}
			});
		});
	};

	const imageUploader = () => {
		const imageUplaoderContainer = $('.image-uploader');
		if (!imageUplaoderContainer[0]) {
			return false;
		}

		eo.component.uploader.create({
			uploadContainerSelector: '.image-uploader',
			inputId: 'browseImage',
			url: $('.image-uploader').data("url"),
			success: function (data) {
				if (Array.isArray(data)) {
					data.forEach(image => {
						eo.component.uploader.setMultipleImageUploadContainer(image, '.images-container');
					});
				} else if (data && typeof data === 'object' && data.status === 1) {
					eo.component.uploader.setMultipleImageUploadContainer(data, '.images-container');
				} else if (data && typeof data === 'object' && data.status !== 1) {
					console.error(data.message);
				}
				else {
					console.error("Unexpected data format:", data); // Handle unexpected data
				}
			}
		});

	};

	const fileUploader = () => {
		const fileUplaoderContainer = $('.file-uploader');
		if (!fileUplaoderContainer[0]) {
			return false;
		}

		eo.component.uploader.create({
			uploadType: "document",
			uploadContainerSelector: '.file-uploader',
			inputId: 'browseFile',
			url: $('.file-uploader').data("url"),
			success: function (data) {

				if (Array.isArray(data)) {
					data.forEach(file => {
						eo.component.uploader.setMultipleFileUploadContainer(file, '.files-container');
					});
				} else if (data && typeof data === 'object' && data.status === 1) {
					eo.component.uploader.setMultipleFileUploadContainer(data[key], '.files-container');
				} else if (data && typeof data === 'object' && data.status !== 1) {
					console.log(data.message);
				}
				else {
					console.log("Unexpected data format:", data); // Handle unexpected data
				}

			}
		});
		
	};

	const initSelectTag = () => {

		const ele = document.getElementById('tags');

		if (ele) {
			eo.component.tomSelect.init('#tags');
		}

		
	};

	const initServiceType = () => {
		$('#service_type').trigger('change');
	};

	return {
		initBeforeLoad: () => {
			contentNavTransition();
			handleListingTypeChange();
			handleServiceTypeChange();
			handleAuthorityChange();
			handlePriceInputChange();
			confirmSelection();
			saveForm();
			initSelectTag();			
		},

		initAfterLoad: () => {
			initTinyMCEeditor();
			initServiceType();
			imageUploader();
			fileUploader();
		}
	}
}();

document.addEventListener('DOMContentLoaded', function () {
	Properties.initBeforeLoad();
});

window.addEventListener('load', function () {
	Properties.initAfterLoad();
});