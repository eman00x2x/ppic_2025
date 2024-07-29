$(document).on('submit', '#uploadForm', (function (e) {
	
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
			$('.response').html("<div class=' alert  alert-danger  alert-dismissible' id=''><div class='d-flex'><div class=''><i class='ti ti-alert-triangle me-2' aria-hidden='true'><\/i><\/div><div class=''><p class='p-0 m-0'>Error! The file cannot be uploaded. Please select another file; it is either too large or not permitted for upload.<\/p><\/div><\/div><button type='button' class='btn-close' data-bs-dismiss='alert'><\/button> <\/div>");
		}
	}).done(function(data) {
		response = JSON.parse(data);

		if (response.status == 1) {
			$('.photo-preview').css("background-image", "url(" + response.temp_url + ")");
			$('#photo').val(response.final_url);
			$('.response').html("");
		} else {
			$('.response').html("<div class=' alert  alert-danger  alert-dismissible' id=''><div class='d-flex'><div class=''><i class='ti ti-alert-triangle me-2' aria-hidden='true'><\/i><\/div><div class=''><p class='p-0 m-0'>There was a problem uploading your photo please contact the System Administrator.<\/p><\/div><\/div><button type='button' class='btn-close' data-bs-dismiss='alert'><\/button> <\/div>");
		}

		$('.photo-upload-loader').html('');
		$('#ImageBrowse').val('');


	});
}));

$(document).on("change", "#browseFile", function () {

	$('.undefined').remove();
	$('.response').html('');
	
	/* var $fileUpload = $("input[type='file']");
	if (parseInt($fileUpload.get(0).files.length) >= 5) {
		$('.response').append("<div class=' alert  alert-danger  alert-dismissible' id=''><div class='d-flex'><div class=''><i class='ti ti-alert-triangle me-2' aria-hidden='true'><\/i><\/div><div class=''><p class='p-0 m-0'>Error! Select 5 or less images per upload!<\/p><\/div><\/div><button type='button' class='btn-close' data-bs-dismiss='alert'><\/button> <\/div>");
		$('#browseFile').val('');
		return false;
	} */

	$('.response').html('<img src="' + CDN + 'images/loader.gif" /> Please wait photo is uploading...');
	$("#uploadForm").submit();
});

$(document).on('click','.browseFile',function() {
	$('#browseFile').click();
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

function removeImage(container,image_id,filename,application) {
	
	console.log(localStorage.getItem('items'));
	if(localStorage.getItem('items') !== null) {
		storageData = JSON.parse(localStorage.getItem('items'));
		index = storageData.map(function (e) { return e.storageData; }).indexOf(image_id);
		storageData.splice(index, 1);
		localStorage.setItem('items', JSON.stringify(storageData));
	}

	$(container).remove();

	switch(application) {
		case 'listings': req = MANAGE + "listingImages/"; break;
	}

	$.get(req + image_id + "/delete?filename=" + filename, function (data, status) {
		response = JSON.parse(data);
		console.log(data);
		$('.upload-response').html(response.message);
		
	});

}

function createElements(response,application = "listings", counter = 0) {
	html = "<input type='hidden' name='listing_image_filename[" + ( i + 300) + "][height]' value='" + response[i].height + "' />";
	html += "<input type='hidden' name='listing_image_filename[" + ( i + 300) + "][width]' value='" + response[i].width + "' />";
	html += "<input type='hidden' name='listing_image_filename[" + ( i + 300) + "][name]' value='" + response[i].filename + "' />";
	html += "<div class='' style=\"background-image:url('" + CDN + "images/temporary/" + response[i].filename + "'); background-repeat: no-repeat; background-size: cover; width:180px;height:180px; \"></div>";
	html += "<div class='mt-2'>";
		html += "<div class='btn-group'>";
	html += "<span class='btn btn-md btn-outline-secondary btn-remove-image' title='Remove image' onclick=\"removeImage('." + response[i].id + "','image_" + response[i].id + "','" + response[i].filename + "','" + application + "')\"><i class='ti ti-trash'></i></span>";
	html += "<span class='btn btn-md btn-outline-primary btn-set-thumb-image' title='Set image as thumbnail' onclick=\"setImageThumb('." + response[i].id + "','" + response[i].filename + "')\"> <i class='ti ti-click me-2'></i> Thumbnail</span>";
		html += "</div>";
	html += "</div>";
	return html;
}