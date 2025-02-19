
document.addEventListener('DOMContentLoaded', function () {
	$(document).on('click', '.btn-save', function (e) {
		eo.submitForm("#form", {
			onBeforeSend: (formData) => {
				if (typeof tinymce === 'object' && tinymce.get('text-container')) {
					if (formData.has('about')) {
						formData.append('about', tinymce.get('text-container').getContent());
					}

					if (formData.has('data_privacy')) {
						formData.append('data_privacy', tinymce.get('text-container').getContent());
					}

					if (formData.has('terms')) {
						formData.append('terms', tinymce.get('text-container').getContent());
					}
				}
			},
		});
	});
});


window.addEventListener('load', function () {
	eo.component.tinymce.init("#text-container");
});
