const website = function () {
	
	const init = () => {
		$(document).on('change', '#scheduleSwitch', function () {
			if (this.checked) {
				$('.viewing-schedule-input').removeClass('d-none');
			} else { 
				$('.viewing-schedule-input').addClass('d-none');
			}
		});
	};

	const sendInquiry = () => {
		$(document).on('click', '.btn-send-message', function () {
			eo.submitForm("#inquiry-form", {
				validation: {
					name: {
						type: "string",
						length: { minimum: 4 }
					},
					contact_number: {
						presence: { allowEmpty: false }
					},
					email: {
						type: "string",
						format: {
							pattern: /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
							message: "address must be valid ."
						}
					},
					security_code: {
						presence: { allowEmpty: false },
						equality: 'generated_security_code'
					}
				}
			});
		});
	};

	const showHideInquiryFormInput = () => {
		$(document).on('input', '#name, #contact_number', function () {
			const element = $('.show-hide-input');

			if ($('#name').val() != "" || $('#contact_number').val() != "") {
				element.removeClass('d-none');
			} else {
				element.addClass('d-none');
			}
		});
	};

	const moveInquiryForm = () => {
		$(document).on('hide.bs.modal', '#modalSendMessageForm', function (e) {
			let fromElement = '.send-message-modal-container';
			let toElement = '.inquiry-form .inquiry-form-wrapper';
			eo.moveHtmlElement(fromElement, toElement);
		});

		$(document).on('show.bs.modal', '#modalSendMessageForm', function () {
			let fromElement = '.inquiry-form .inquiry-form-wrapper';
			let toElement = '.send-message-modal-container';
			eo.moveHtmlElement(fromElement, toElement);
		});
	};

	const moveProperyFilterForm = () => {
		$(document).on('hide.bs.modal', '#modalFilterForm', function (e) {
			let fromElement = '.filter-modal-container';
			let toElement = '.filter-container #filterFormBody';
			eo.moveHtmlElement(fromElement, toElement);
		});

		$(document).on('show.bs.modal', '#modalFilterForm', function () {
			let fromElement = '.filter-container #filterFormBody';
			let toElement = '.filter-modal-container';
			eo.moveHtmlElement(fromElement, toElement);
		});
	};

	const homeSearchForm = () => {
		const form = $('#homeSearch');
		const data = eo.serializeFormData(form.serializeArray());

		const uri = [];

		let path = 'buy';

		for (let [key, value] of Object.entries(data)) {

			switch (key) {
				case 'listing_type':
					if (value == 'For Sale') {
						path = 'buy';
					} else { 
						path = 'rent';
					}
					break;
			}

			if (key != 'listing_type') {
				if (value != '') {
					uri.push(key + '=' + encodeURIComponent(value))
				}
			}
			
		}

		let params = uri.join('&');
		eo.redirect(form.attr('action') + path + '/?' + params);
	};

	const homeSearch = () => {
		$(document).on('click', '#homeSearch .btn-homeSearch', function () {
			homeSearchForm();
		});

		$(document).on('keypress', '#homeSearch .homeSearchInput', function (e) {
			if (e.which == 13 || e.keyCode == 13) {
				homeSearchForm();
			}
		});
	};

	const generateSecurityCode = () => {
		const scode = eo.getRandomChar(3);
		$('#securityCodeText').text(scode);
		$('#generated_security_code').val(scode);
	};

	return {
		initBeforeLoad: () => {
			homeSearch();
			moveProperyFilterForm();
			sendInquiry();
			showHideInquiryFormInput();
			moveInquiryForm();
			init();
		},
		generateSecurityCode
	};
}();

document.addEventListener('DOMContentLoaded', function () {
	website.initBeforeLoad();
});

window.addEventListener('load', function () {
	website.generateSecurityCode();
});