
const registration = function () {
	
	"use strict";

	const initFormSubmit = () => {
		$(document).on('click', '.btn-save', function (e) {
			eo.submitForm("#form", {
				validation: {
					email: { presence: { allowEmpty: false }, email: true },
					username: { presence: { allowEmpty: false }, length: { minimum: 6 } },
					password: { length: { minimum: 6 } },
					confirmPassword: { equality: 'password' }
				},
				onBeforeSend: (formData) => {
					const data = eo.serializeFormData(formData);
					if (data.agree_terms === undefined) { 
						eo.component.alert.error('Please agree to the terms and conditions');
						throw Error('Please agree to the terms and conditions by checking the checkbox');
					}

					console.clear();
				},
				redirectUrl: "/registration/success"
			});
		});

	};

	const initVerificationInput = () => {
		let inputs = document.querySelectorAll('[data-code-input]');
		// Attach an event listener to each input element
		for (let i = 0; i < inputs.length; i++) {
			inputs[i].addEventListener('input', function (e) {
				// If the input field has a character, and there is a next input field, focus it
				if (e.target.value.length === e.target.maxLength && i + 1 < inputs.length) {
					inputs[i + 1].focus();
				}
			});
			inputs[i].addEventListener('keydown', function (e) {
				// If the input field is empty and the keyCode for Backspace (8) is detected, and there is a previous input field, focus it
				if (e.target.value.length === 0 && e.keyCode === 8 && i > 0) {
					inputs[i - 1].focus();
				}
			});
		}
	}

	const initRegistrationForm = () => {
		$('#user_agent').val(btoa(JSON.stringify(eo.userClient)));
	}

	return {
		initBeforeLoad: () => {
			initFormSubmit();
			initVerificationInput();
		},

		initAfterLoad: () => {
			initRegistrationForm();
		}
	};

}();

document.addEventListener('DOMContentLoaded', function () {
	registration.initBeforeLoad();
});

window.addEventListener('load', function () {
	registration.initAfterLoad();
});