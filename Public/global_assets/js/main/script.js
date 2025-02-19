const app = function () {

	"use strict";

	const recordTraffic = () => {
		const url = `https://www.philproperties.ph/saveTraffic`;
		const pageTitle = document.title;
		const pageUrl = window.location.href;
		const {
			content: sessionId,
		} = document.querySelector('meta[name="session-id"]');

		const accountId = $('#account_id').val();

		const formData = new FormData();

		formData.append('session_id', sessionId);
		formData.append('account_id', accountId);

		formData.append('traffic[name]', pageTitle); // Nested object using bracket notation
		formData.append('traffic[url]', pageUrl);

		formData.append('user_agent[userAgent]', eo.userClient.userAgent); // Nested object
		formData.append('user_agent[geo]', eo.userClient.geo);
		formData.append('user_agent[browser]', eo.userClient.browser);

		formData.append('csrf_token', eo._CSRFToken);

		eo.post(url, formData);
	};

	const deleteTransition = () => {
		$(document).on('click', '.btn-continue-delete', function (e) {
			const url = $(this).data('url');
			const row = $(this).data('row');
			const proceed_url = $(this).data('url-proceed');

			eo.component.alert.loader();
			$('.response-body').hide();

			$.get(url, function (data, status) {
				if (data.status == 1) {
					if (proceed_url != undefined) {
						window.location = proceed_url;
					} else {
						$('.' + row).remove();
						bootstrap.Offcanvas.getInstance($('.offcanvas')).hide();
					}

					$('.btn-delete-controls').hide();
				} else {
					$('.response-body').show();
				}

				eo.component.alert.message(data.message);
			});
		});
	};

	const search = () => {
		$(document).on('keypress', '.search-wrapper #search', function (e) {
			if (e.which == 13 || e.keyCode == 13) { 
				const val = $(this).val();
				const url = $(this).data('url') + '?search=' + val;

				$('.request-container').css('opacity', .3);
				eo.redirect(url);
			}
		});

		$(document).on('focusout', '.search-wrapper #search', function (e) {
			const val = $(this).val();
			if (val != '') {
				const url = $(this).data('url') + '?search=' + val;
				$('.request-container').css('opacity', .3);

				eo.redirect(url);
			}
		});
	};

	const selectionTransitions = () => {
		$(document).on('change', '#select_option', function () {
			if ($(this).prop('checked') == true) {
				$('.selection').prop('checked', true);
			} else {
				$('.selection').prop('checked', false);
			}
		});

		$(document).on('change', '.check-input-selector', function () {
			if ($(this).prop('checked') == true) {
				$('.form-check-input-selection').prop('checked', true);
			} else {
				$('.form-check-input-selection').prop('checked', false);
			}

			verifySelectedCheckboxs();
		});

		$(document).on('change', '.form-check-input-selection', function () {
			verifySelectedCheckboxs();
		});

		$(document).on('click', '.table-list .data-container td', function () {
			const parentClassName = $(this).parent().get(0).className;
			const element = $('.' + parentClassName + ' .form-check-input');

			if (element.prop('checked') !== false) {
				element.prop('checked', false).trigger('change');
			} else {
				element.prop('checked', true).trigger('change');
			}
		});

		/* $(document).on('click', '.avatar', function () {
			id = $(this).data('id');
			if ($('.' + id).prop('checked') == true) {
				$('.' + id).prop('checked', false);
			} else {
				$('.' + id).prop('checked', true);
			}
		}); */

		const verifySelectedCheckboxs = () => {
			const selection = $('.form-check-input-selection:checked').length;
			if (selection) {
				$('.actions-wrapper').removeClass('d-none');
				$('.search-wrapper').addClass('d-none');
				$('.btn-list-wrapper').addClass('d-none');
			} else {
				$('.actions-wrapper').addClass('d-none');
				$('.search-wrapper').removeClass('d-none');
				$('.btn-list-wrapper').removeClass('d-none');
			}
		};
	};

	const getAllCheckboxesValue = () => {
		const checkboxValue = [];
		$(".form-check-input-selection:checked").each(function () {
			checkboxValue.push($(this).val());
		});

		return checkboxValue;
	};

	const doActionTransition = () => {
		$(document).on("click", ".do-action", function () {
			eo.component.button.disable();
			eo.component.alert.loader();

			const ids = getAllCheckboxesValue();
			let action = $(this).data('action');
			let actionValue = $(this).data('action-value');
			let url = $(this).data('url');

			if (ids.length == 0) {
				eo.component.alert.error("Select atleast one checkbox");
				return false;
			}

			eo.post(url, {
				"ids": ids,
				"action_value": actionValue,
				"action": action,
				"csrf_token": eo._CSRFToken
			}, {
				onSuccess: function (data) {
					eo.component.alert.message("");
					eo.component.button.enable();

					const statusWindow = {
						"delete": "danger"
					};

					eo.component.modal.create({
						id: "modalDoAction",
						size: "fullscreen",
						status: (statusWindow[action] != undefined ? statusWindow[action] : "info"),
						callback: function () {
							return data;
						}
					});
				}
			});
		});
	};

	const _filterAction = () => {
		const form = $('#filterForm');
		const data = eo.serializeFormData(form.serializeArray());

		const uri = [];

		for (let [key, value] of Object.entries(data)) {
			if (value != '') {
				uri.push(key + '=' + encodeURIComponent(value))
			}
		}
		
		let params = uri.join('&');
		eo.redirect(form.attr('action') + '?' + params);
	};

	const modalFilter = () => {
		$(document).on('click', '#modalFilterForm .btn-filter', function () {
			_filterAction();
		});
	}

	const filterFormBody = () => {
		$(document).on('click', '#filterFormBody .btn-filter', function () {
			_filterAction();
		});
	}
	
	return {
		initBeforeLoad: () => {
			modalFilter();
			filterFormBody();
			doActionTransition();
			selectionTransitions();
			search();
			deleteTransition();
		},

		initAfterLoad: () => {
		},
		recordTraffic
	};

}();

document.addEventListener('DOMContentLoaded', function () {
	app.initBeforeLoad();
});

window.addEventListener('load', function () {
	app.initAfterLoad();
});
