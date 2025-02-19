const relatedProperties = function () {
	return {
		initBeforeLoad: () => {
			let url = $('.related-properties-wrapper').data("uri");
			eo.get(url, {
				onSuccess: function (response) {
					$('.related-properties-container').html(response);
				}
			});
		}
	};
}();

document.addEventListener('DOMContentLoaded', function () {
	relatedProperties.initBeforeLoad();
});

window.addEventListener('load', function () {
	
});