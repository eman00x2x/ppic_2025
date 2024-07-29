
const userClient = {
	"userAgent": null,
	"geo": null,
	"browser": null
};

const convertDate = (dateData) => {
	let date = new Date(0);
	date.setUTCSeconds(dateData);
	return date.toLocaleDateString("en-US", {
		year: "numeric",
		month: "long",
		day: "numeric",
	});
};

const niceTrim = (data, length) => {
	if (data.length < length) {
		return data;
	} else {
		return data.substring(0, length - 3) + "...";
	}
};

const formatFileSize = (bytes, decimalPoint = 2) => {
	if (bytes == 0) return "0 Bytes";
	var k = 1000,
		dm = decimalPoint || 2,
		sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
		i = Math.floor(Math.log(bytes) / Math.log(k));
	return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
};

const uuidv4 = () => {
	return "0000000000000000-8000000000000000".replace(/[018]/g, c =>
		(c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
	);
};

const randomCharacterGenerator = (text = "000000") => {
	return text.replace(/[018]/g, c =>
		(c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
	);
};

const random = (start, end) => {
	return Math.floor(Math.random() * (end - start + 1)) + start;
};

const getGeo = async () => {
	$.get('https://ipinfo.io/json', function (data) {
		userClient.geo = data;
		localStorage.setItem('client', JSON.stringify(userClient));
	});
};

const getBrowser = () => {

	const isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
	const isFirefox = typeof InstallTrigger !== 'undefined';
	const isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === '[object SafariRemoteNotification]'; })(!window['safari'] || (typeof safari !== 'undefined' && window['safari'].pushNotification));
	const isIE = /*@cc_on!@*/false || !!document.documentMode;
	const isEdge = !isIE && !!window.StyleMedia;
	const isChrome = !!window.chrome && (!!window.chrome.webstore || !!window.chrome.runtime);
	const isEdgeChromium = isChrome && (navigator.userAgent.indexOf('Edg') != -1);
	const isBlink = (isChrome || isOpera) && !!window.CSS;

	if (isOpera) { userClient.browser = 'Opera'; }
	else if (isFirefox) { userClient.browser = 'Firefox'; }
	else if (isSafari) { userClient.browser = 'Safari'; }
	else if (isIE) { userClient.browser = 'IE'; }
	else if (isEdge) { userClient.browser = 'Edge'; }
	else if (isChrome) { userClient.browser = 'Chrome'; }
	else if (isEdgeChromium) { userClient.browser = 'Edge'; }
	else if (isBlink) { userClient.browser = 'Blink'; }
	else { userClient.browser = 'Unknown Browser'; }

	localStorage.setItem('client', JSON.stringify(userClient));

};

const initUserClient = () => {

	const obj = JSON.parse(localStorage.getItem('client'));

	if (localStorage.getItem('client') === null) {
		userClient.userAgent = navigator.userAgent;
		getBrowser();
		getGeo();
	}

	userClient.userAgent = obj.userAgent;
	userClient.geo = obj.geo;
	userClient.browser = obj.browser;

};

const recordTraffic = (post_url, page_url, page_title, post_token) => {
	$.post(post_url, {
		'name': page_title,
		'url': page_url,
		'client_info': {
			'userAgent': userClient.userAgent,
			'geo': userClient.geo,
			'browser': userClient.browser
		},
		'csrf_token': post_token
	});
};

$(document).on('click','.btn-save', function(e) {
	
	e.preventDefault();

	const form = $("#form");
	
	if ($('#snow-container').length) {
		$('#snow-container').val(tinymce.get('snow-container').getContent());
	}

	$('.btn-save').css({
		'cursor': 'wait',
		'pointer-events': 'none'
	});

	$("#form :input").attr('readonly', true);
	$('.btn-save').addClass("d-none");

	$('.response').html("<div class='bg-white p-3 mt-3 rounded'><div class='d-flex gap-3 align-items-center'><div class='loader'></div><p class='mb-0'>Processing, Please wait...</p></div></div>");
	$('html, body').animate({ scrollTop: 0 }, 'slow');

	if ((message = validateInput(form.serializeArray()))) {
		const errorAlert = "<div class='response alert alert-danger alert-dismissible fade show mt-3' role='alert'><span>" + message + "</span><button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
		$(".response").html(errorAlert);
	}else {
	
		$.post(form.attr('action'), form.serialize(), function (data, status) {
			
			let response;

			if (typeof data == 'object') {
				response = data;
			} else { response = JSON.parse(data); }

			$('.response').html(response.message);

			if (response.status == 1) {
				if ($('#reference_url').val() !== undefined) {

					let message = " <div class='bg-white p-3 mt-3 rounded'><div class='d-flex gap-3 align-items-center'><div class='loader'></div><p class='mb-0'>Please wait while you are redirecting...</p></div></div>";

					$('.response').html(message);

					setTimeout(function () {
						window.location = $('#reference_url').val();
					}, 10);
				}
			}

		});

	}

	$('.btn-save').css({
		'cursor': 'pointer',
		'pointer-events': 'auto'
	});

	$('.btn-save').removeClass("d-none");
	$("#form :input").removeAttr('readonly');
	
	return false;
});

$(document).on('click','.btn-show-offconvas', function(e) {
	url = $(this).data('url');
	$.get(url, function (data, status) { 
		$('.offcanvas').html(data);
	});
});

$(document).on('click','.btn-view', function(e) {
	url = $(this).data('url');
	id = $(this).data('id');
	
	$('#viewModal .entries-content').html("<div class='bg-white p-3 mt-3 rounded'><div class='d-flex gap-3 align-items-center'><div class='loader'></div><p class='mb-0'>Retrieving data please wait...</p></div></div>");
	
	$.get(url,function(data,status) {
		$('#viewModal .entries-content').html(data);
	});
});

$(document).on('click','.btn-continue-delete', function(e) {
	url = $(this).data('url');
	row = $(this).data('row');
	proceed_url = $(this).data('url-proceed');
	
	$('.response').html("<div class='bg-white p-3 mt-3 rounded'><div class='d-flex gap-3 align-items-center'><div class='loader'></div><p class='mb-0'>Processing, Please wait...</p></div></div>");
	$('.response-body').hide();
	
	$.get(url,function(data,status) {
		response = JSON.parse(data);
		if (response.status == 1) {
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

		$('.response').html(response.message);

	});
	
});

$(document).on('submit','#form', function(e) {
	e.preventDefault();
});

$(document).on('keypress','#search', function(e) {
	if(e.which == 13) {
	
		val = $(this).val();
		url = $(this).data('url')+'?search='+val;
		title = 'Search: '+val;
		
		$('.request-container').css('opacity',.3);
		
		window.location = url;
		
	}
});

$(document).on('focusout', '#search', function (e) {
	
	val = $(this).val();

	if (val != '') {
		url = $(this).data('url') + '?search=' + val;
		title = 'Search: ' + val;

		$('.request-container').css('opacity', .3);

		window.location = url;
	}

});

$(document).on('change', '#select_option', function () { 
	if ($(this).prop('checked') == true) {
		$('.selection').prop('checked', true);
	} else { 
		$('.selection').prop('checked', false);
	}
});

$(document).on('click', '.avatar', function () { 
	id = $(this).data('id');
	if ($('.' + id).prop('checked') == true) {
		$('.' + id).prop('checked', false);
	} else { 
		$('.' + id).prop('checked', true);
	}
});


function getAmortization() {

	let selling_price = parseInt($('#selling_price').val());
	let dp_percent = parseInt($('#mortgage-downpayment-selection').val());
	let dp = selling_price * (dp_percent / 100);

	let loan_amount = selling_price - dp;
	let interest_rate = parseFloat($('#mortgage-interest-selection').val());
	let years = parseInt($('#mortgage-years-selection').val()) + 1;
	let payments_per_year = 12;

	let monthly_payment = pmt((interest_rate / 100) / payments_per_year, payments_per_year * years, -loan_amount);
	let monthly_payment_formated = parseFloat(monthly_payment.toFixed(2)).toLocaleString();

	let schedule = computeSchedule(loan_amount, interest_rate, payments_per_year, years, monthly_payment);

	return {
		'monthly_payment': monthly_payment,
		'monthly_payment_formated': monthly_payment_formated,
		'schedule': schedule
	};

}