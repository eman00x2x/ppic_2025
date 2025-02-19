const logs = function () { 
	const init = () => {
		$(document).on('click', '.btn-confirm-selection', function () {
			const group = {
				delete: (id) => {
					$(`.row_${id}`).remove();
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

	return {
		initBeforeLoad: () => {
			init();
		},

		initAfterLoad: () => {
			
		}
	};
}();

document.addEventListener('DOMContentLoaded', function () {
	logs.initBeforeLoad();
});

window.addEventListener('load', function () {
	hljs.highlightAll();
	logs.initAfterLoad();
});