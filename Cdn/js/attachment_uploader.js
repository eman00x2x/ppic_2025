	
	storageData = JSON.parse(localStorage.getItem('files'));
	
	if(storageData != undefined) {
	
		html = "";
		for(i=0; i<storageData.length; i++) {
			html += createAttachmentElements(storageData);
		}
		
		$('.attachedFiles ul').append(html);
		
	}
	

	$(document).on('submit','#attachmentUploadForm',(function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		
		$('.btn-file-browse').hide();

		$.ajax({
			type:'POST',
			url: $(this).attr('action'),
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
			beforeSubmit:function(e){
				
			},
			error: function(data){
				console.log("error");
				console.log(data);
			}
		}).done(function(data) {
		
			if(data != 0) {
			
				let filesArray = localStorage.getItem('files') ? JSON.parse(localStorage.getItem('files')) : [];
				localStorage.setItem('files', JSON.stringify(filesArray));
				
				response = JSON.parse(data);
				
				for(i = 0; i < response.length; i++) {
					if(response[i].status == 2) {
						html += "<div class='' style=\"background-image:url('images/warning_48.png'); background-repeat: no-repeat; background-size: cover; width:150px;height:150px; \"> Error Uploading</div>";
					}else {
						
						items = {id:response[i].id, url:response[i].url, filename:response[i].filename};
						
						filesArray.push(items);
						localStorage.setItem('files', JSON.stringify(filesArray));
						
						$('.attachedFiles ul').append(createAttachmentElements(response));
						
						$('.btn-file-browse').show();
						
					}
				}
				
				$('.fileUploader').html('');
			}else {
				alert('There was a problem uploading your file please contact the System Administrator.');
			}
			
		});
	}));

	$(document).on("change", "#FileBrowse",function() {
		$('.fileUploader').html('<img src="images/loader.gif" /> Please wait file is attaching...');
		$("#attachmentUploadForm").submit();
	});
	
	$(document).on('click','.btn-file-browse',function() {
		$('#FileBrowse').click();
	});
	
	function removeAttachment(container,attchment_id,filename) {
		
		if(localStorage.getItem('files') !== null) {
			storageData = JSON.parse(localStorage.getItem('files'));
			index = storageData.map(function(e) { return e.storageData; }).indexOf(attchment_id);
			storageData.splice(index, 1);
			localStorage.setItem('files',JSON.stringify(storageData));
		}
		
		$(container+" * ").css("color","#eee");
		$(container+" * ").css("border-color","#eee");
		
		$.get("?request=mails&task=removeAttachment&filename="+filename,function(data,status) {
			$(container).remove();
		});
		
	}
	
	function createAttachmentElements(response) {
		html = "<li class='"+response[i].id+" mb-2'>";
			html += "<input type='hidden' class='attachment_filename' name='attachment_filename[]' value='"+response[i].filename+"' />";
			html += "<div class='row'>";
				html += "<div class='col-md-1 text-center'>";
					html += "<span class='btn btn-sm btn-outline-danger btn-remove-file' title='Remove Attachment' onclick=\"removeAttachment('."+response[i].id+"','"+response[i].id+"','"+response[i].filename+"')\"><i class='ti ti-x'></i></span>";
				html += "</div>";
				
				html += "<div class='col-md-11'>";
					html += ""+response[i].filename+"";
				html += "</div>";
			html += "</div>";
		html += "</li>";
		return html;
	}