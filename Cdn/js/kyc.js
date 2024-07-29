$(document).on('submit', '#imageUploadForm', (function (e) {
	
	e.preventDefault();
	var formData = new FormData(this);
	$('.btn-photo-browse').hide();
	
	$.ajax({
		type:'POST',
		url: $(this).attr('action'),
		data:formData,
		cache:false,
		contentType: false,
		processData: false,
		beforeSubmit:function(e){},
		error: function (data) {
			console.log(data);
		}
	}).done(function(data) {
		console.log(data);

		var response = JSON.parse(data);
		upload_container = $('#photo_container').val();
		
		if (response.status == 1) {
			
			$('.' + upload_container).css("background-image", "url('" + response.temp_url + "')");

			if (upload_container == "selfie-container") {
				$('.photo-selfie').val(response.filename);
			} else {
				$('.photo-id').val(response.filename);
			}
			
			$('.response').html('');
		} else {
			$('.response').append("<div class=' alert  alert-danger  alert-dismissible' id=''><div class='d-flex'><div class=''><i class='ti ti-alert-triangle me-2' aria-hidden='true'><\/i><\/div><div class=''><p class='p-0 m-0'>There was a problem uploading your photo please contact the System Administrator.<\/p><\/div><\/div><button type='button' class='btn-close' data-bs-dismiss='alert'><\/button> <\/div>");alert('');
		}
		
		$('#ImageBrowse').val('');
	});
}));

$(document).on("change", "#ImageBrowse", function () {

	$('.undefined').remove();
	$('.response').html('');
	
	$('.response').html("<div class='bg-white p-3 mt-3 rounded'><div class='d-flex gap-3 align-items-center'><div class='loader'></div><p class='mb-0'>Processing, Please wait...</p></div></div>");
	$("#imageUploadForm").submit();
});

$(document).on('click', '.photo-preview, .btn-photo-browse', function () {
	
	let upload_container;
	upload_container = $(this).data('photo-container');
	$('#photo_container').val(upload_container);
	$('#ImageBrowse').click();

});

function setImageThumb(container,filename) {
	$('#thumb_img').val(filename);
	$('.btn-set-thumb-image i').remove();
	$('.btn-set-thumb-image').removeClass('btn-success');
	$('.btn-set-thumb-image').addClass('btn-outline-primary');

	$(container + ' .btn-set-thumb-image').prepend("<i class='ti ti-check me-2'></i>");
	$(container + ' .btn-set-thumb-image').removeClass('btn-outline-primary');
	$(container + ' .btn-set-thumb-image').addClass('btn-success');
}