const article = function () { 
	const init = () => {
		$(document).on('click', '.btn-save', function (e) {
			eo.submitForm("#form", {
				onBeforeSend: (formData) => {
					if (typeof tinymce === 'object' && tinymce.get('textContainer')) {
						formData.append('content', tinymce.get('textContainer').getContent());
					}
				},
				validation: {
					title: { presence: { allowEmpty: false } },
					created_by: { presence: { allowEmpty: false } },
					content: { presence: { allowEmpty: false } }
				}
			});
		});

		$(document).on('click', '.btn-confirm-selection', function () {
			const group = {
				set_category: (id, category) => {
					$(`.row_${id} .category-text`).text(category);
				},
				set_status: (id, statusValue) => {
					const status = statusValue == 1 ? 'success' : 'danger';
					const value = statusValue == 1 ? 'Yes' : 'No';
					$(`.row_${id} .status-text`).html(`<span class="badge bg-${status} me-1"></span> ${value}`);
				},
				delete: (id) => {
					$( `.row_${id}` ).remove();
				}
			};

			eo.submitForm("#form", {
				callback: (formData) => {
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

	const imageUploader = () => {
		const imageUplaoderContainer = $('.banner-preview');
		if (!imageUplaoderContainer[0]) {
			return false;
		}

		eo.component.uploader.create({
			url: $('.banner-container').data("url"),
			uploadContainerSelector: '.banner-container',
			multiple: false,
			success: function (data) {
				eo.component.uploader.setSingleUploadContainer(data[0], '.banner-preview');
			}
		});
	};

	const initTinyMCE = () => {
		$(document).ready(function () {
			tinymce.remove();

			tinymce.init({
				selector: 'textarea#textContainer',
				height: 500,
				menubar: false,
				relative_urls: false,
				remove_script_host: true,
				document_base_url: eo.DOMAIN,
				image_advtab: true,
				plugins: [
					'advlist autolink lists link charmap print preview anchor',
					'searchreplace visualblocks code fullscreen',
					'insertdatetime media table paste code wordcount image responsivefilemanager'
				],
				toolbar: 'responsivefilemanager image media link | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat code',
				
				external_filemanager_path: "/global_assets/js/vendor/responsive_filemanager/filemanager/",
				filemanager_title: "Responsive Filemanager",
				external_plugins: { "filemanager": "/global_assets/js/vendor/responsive_filemanager/filemanager/plugin.min.js" },

				content_css: [
					'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
					eo.CDN + '/css/global.style.css'
				]
			});
		});
	}

	return {
		initBeforeLoad: () => {
			init();
		},

		initAfterLoad: () => {
			/* imageUploader(); */
			initTinyMCE();
		}
	};
}();

document.addEventListener('DOMContentLoaded', function () {
	article.initBeforeLoad();
});

window.addEventListener('load', function () {
	article.initAfterLoad();
});

