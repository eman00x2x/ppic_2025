const administration = function () {

	const init = function () {

		$(document).on('click', '.btn-send-code', function () {
			let url = $(this).data('url');
			eo.get(url, {
				beforeRequest: function () {
					eo.component.alert.loader("Sending code...")
					eo.component.button.disable();
				},
				onSuccess: (response) => {
					eo.component.alert.message(response.message);
				}
			});
		})

		let inputs = document.querySelectorAll('[data-code-input]');

		$(document).on('click', '.btn-verify-code', function () {
			
			eo.submitForm("#form", {
				onBeforeSend: (formData) => {
					let code = "";
					inputs.forEach((input, index) => {
						code += input.value;
					});
					formData.push({ name: 'authorization_code', value: code });
				},
				redirectUrl: "/admin/super/cron"
			});
		});

		
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
		
	};

	return {
		initBeforeLoad: () => {
			init();
		},

		initAfterLoad: () => {
		}
	};

}();

document.addEventListener('DOMContentLoaded', function () {
	administration.initBeforeLoad();
});