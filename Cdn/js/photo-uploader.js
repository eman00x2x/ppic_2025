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

		if (data.status == 1) {
			$('.photo-preview').css("background-image", "url('" + data.temp_url + "')");
			$('#photo').val(data.filename);
		} else {
			$('.response').html("<div class=' alert  alert-danger  alert-dismissible' id=''><div class='d-flex'><div class=''><i class='ti ti-alert-triangle me-2' aria-hidden='true'><\/i><\/div><div class=''><p class='p-0 m-0'>There was a problem uploading your photo please contact the System Administrator.<\/p><\/div><\/div><button type='button' class='btn-close' data-bs-dismiss='alert'><\/button> <\/div>");
		}
		
		$('.photo-upload-loader').html('');
		$('#ImageBrowse').val('');
	});
}));

$(document).on("change", "#ImageBrowse", function () {
	$('.upload-response').html('');
	$('.photo-upload-loader').html("<div class='bg-white p-3 mt-3 rounded'><div class='d-flex gap-3 align-items-center'><div class='loader'></div><p class='mb-0'>Processing, Please wait...</p></div></div>");
	$("#imageUploadForm").submit();
});

$(document).on('click','.photo-preview',function() {
	$('#ImageBrowse').click();
});
