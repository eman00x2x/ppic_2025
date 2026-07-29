const administration = function () {

	const init = function () {
		$(document).on('click', '.btn-submit-admin-form', function () {
			if ($('#query').val() == '') {
				eo.component.alert.error('Please enter your Sql Query');
			} else {
				
				eo.submitForm("#form", {
					callback: (formData, response) => {
						$('.query_result').html(response);
					}
				});
			}
		});

		$(document).on('click', '.btn-backup', function () {
			const ele = $(this);
			const url = ele.data('url');
			ele.addClass('d-none');

			eo.component.alert.loader("Dumping database data to a file...");
			eo.get(url, {
				onSuccess: function (response, status) {
					eo.component.alert.message(response.message);

					setTimeout(() => {
						eo.component.alert.loader("Please wait while the page is reloading...");
					}, 500);
					setTimeout(() => {
						location.reload();
					}, 1000);
				}
			});
		});

		$(document).on('click', '.btn-restore-backup', function () {
			const ele = $(this);
			const url = ele.data('url');
			const filename = ele.data('file');
			let html = "";

			eo.component.modal.create({
				id: 'confirm-restore-backup',
				size: 'md',
				status: 'danger',
				callback: function () {
					html += "<div class='text-left'>";
					html += "<i class='ti ti-exclamation-circle display-1 text-danger'></i>";
					html += "<p>Are you sure do you want to restore this backup?";
					html += "<p><span class='fw-bold my-1'>Backup:</span> " + filename + "</p>";
					html += "<p>This action will process as follows:";
					html += "<ol>";
						html += "<li>Backup the current database.</li>";
						html += "<li>Drop all existing tables.</li>";
						html += "<li>Attempt to restore <span class='fw-bold'>" + filename + "</span> file.</li>";
						html += "<li>If the restore fails, the previous backup will be restored.</li>";
					html += "</ol>";
					html += "<p>This process ensures that your system remains in a good state. If all steps fail, you will need to restore the database manually.</p>";
					html += "<div class='mt-5'>";
					html += "<div class=''>";
					html += "<span class='btn btn-secondary me-2' aria-label='Close' data-bs-dismiss='modal'>Cancel</span> <span class='btn btn-danger btn-continue-restore-backup' data-url='" + url + "' data-bs-dismiss='modal'><i class='ti ti-trash fs-18 me-1'></i> Restore Backup</span>";
					html += "</div> ";
					html += "</div> ";
					html += "</div>";

					return html;
				}
			});
		});

		$(document).on('click', '.btn-continue-restore-backup', function () {
			const url = $(this).data('url');
			eo.component.button.disable();
			eo.component.alert.loader("Initializing the restoration process...");
			$('.navbar, .page-body, .btn-backup, .page-pretitle, .page-title, .header').remove();
			$('.page-wrapper').addClass('d-flex min-vh-100 justify-content-center align-items-center');
			$('.container-xl').css("padding", 0);
			$('.container-xl .row').remove();
			$('.response').addClass('w-100');
			$('.page-header').css({
				"margin": "-200px 0 0 0",
				"padding": 0
			});

			const loadingMessages = [
				"Starting the restoration process...",
				"Backing up the current database...",
				"Dropping existing tables...",
				"Finalizing the restoration process...",
				"Checking for any errors..."
			];

			let index = 0;
			let intervalId;

			const _updateStatus = () => {
				eo.component.alert.loader(loadingMessages[index]);
				index++;

				if (index >= loadingMessages.length) {
					clearInterval(intervalId);
				}
			};

			const _checkProcessCompletion = () => {
				return eo.get(url, {
					onSuccess: function (data) {
						eo.component.alert.message(data.message);
						eo.component.button.enable();
					}
				});
			};

			intervalId = setInterval(_updateStatus, 2000);

			setTimeout(() => {
				_checkProcessCompletion().then((completed) => {
					if (completed) {
						clearInterval(intervalId);
					}
				});
			}, 12000);

		});

		$(document).on('click', '.btn-delete-backup', function () {
			const ele = $(this);
			const url = ele.data('url');
			const filename = ele.data('file');
			let html = "";

			eo.component.modal.create({
				id: 'confirm-delete-backup',
				size: 'md',
				status: 'danger',
				callback: function () {
					html += "<div class='text-center'>";
						html += "<i class='ti ti-exclamation-circle display-1 text-danger'></i>";
						html += "<p>Are you sure do you want to delete this backup? <span class='d-block fw-bold my-1'>" + filename + "</span> This action cannot be undone.</p>";
						html += "<div class='text-center mt-5'>"; 
							html += "<div class=''>"; 
								html += "<span class='btn btn-secondary me-2' aria-label='Close' data-bs-dismiss='modal'>Cancel</span> <span class='btn btn-danger btn-continue-delete-backup' data-url='" + url + "' data-bs-dismiss='modal'><i class='ti ti-trash fs-18 me-1'></i> Delete Backup</span>";
							html += "</div> ";
						html += "</div> ";
					html += "</div>";

					return html;
				}
			});
		});

		$(document).on('click', '.btn-continue-delete-backup', function () {
			const url = $(this).data('url');
			eo.component.alert.loader("Deleting file...");
			eo.get(url, {
				onSuccess: function (data) {
					eo.component.alert.message(data.message);

					setTimeout(() => {
						eo.component.alert.loader("Please wait while the page is reloading...");
					}, 500);
					setTimeout(() => {
						location.reload();
					}, 1000);
				}
			});
		});

		$(document).on('click', '.show_table', function () {
			query = $(this).data('query');
			$('#query').val(query);
		});

		$(document).on('click', '.btn-run-task', function () { 
			const url = $(this).data('url');
			eo.get(url, {
				beforeRequest: function () {
					eo.component.alert.loader("Running task...");
					eo.component.button.disable();
				},
				onSuccess: function (data) {
					eo.component.alert.message(data.message);
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
	administration.initBeforeLoad();
});