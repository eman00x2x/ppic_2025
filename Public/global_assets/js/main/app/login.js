
const login = function () {
	
	"use strict";

	const initFormSubmit = () => {
		$(document).on('keypress', '#username, #password', function (e) {
			if (e.which == 13 || e.keyCode == 13) {
				$('.btn-login').trigger('click');
			}
		});

		$(document).on('keypress', '#cpassword', function (e) {
			if (e.which == 13 || e.keyCode == 13) {
				$('.btn-save').trigger('click');
			}
		});

		$(document).on('keypress', '#email', function (e) {
			if (e.which == 13 || e.keyCode == 13) {
				$('.btn-verify-email').trigger('click');
			}
		});

		$(document).on('click', '.btn-login', function (e) {
			let redirect = $(this).data('redirect');
			eo.submitForm("#form", {
				onBeforeSend: (formData) => {
					formData.append('user_agent', btoa(JSON.stringify(eo.userClient)));
				},
				validation: {
					username: { presence: { allowEmpty: false } },
					password: { presence: { allowEmpty: false } }
				},
				redirectUrl: redirect
			});
		});

		$(document).on('click', '.btn-save', function (e) {
			eo.submitForm("#form", {
				Validation: {
					password: { length: { minimum: 6 } },
					confirmPassword: { equality: 'password' }
				}
			});
		});

		$(document).on('click', '.btn-verify-email', function (e) {
			eo.submitForm("#form", {
				validation: {
					email: { presence: { allowEmpty: false } }
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
	};

	return {
		initBeforeLoad: () => {
			initFormSubmit();
		},

		initAfterLoad: () => {
		}
	};

}();

document.addEventListener('DOMContentLoaded', function () {
	login.initBeforeLoad();
});

window.addEventListener('load', function () {
	login.initAfterLoad();
});