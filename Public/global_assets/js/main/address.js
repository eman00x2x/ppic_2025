
const Address = function () {

	let currentAddress = { "region": "", "province": "", "municipality": "", "barangay": "", "street": "", "village": "" };

	const setRegionValue = () => {
		const regionValue = $('.region-select').data('region');
		currentAddress.region = regionValue;
	};

	const setProvinceValue = () => {
		const provinceValue = $('.province-select').data('province');
		currentAddress.province = provinceValue;
	};

	const setMunicipalityValue = () => {
		const municipalityValue = $('.municipality-select').data('municipality');
		currentAddress.municipality = municipalityValue;
	};

	const setBarangayValue = () => {
		const barangayValue = $('.barangay-select').data('barangay');
		currentAddress.barangay = barangayValue;
	};

	const loadAddress = () => {
		if (currentAddress.region) {
			$('#region').find(`option:contains("${currentAddress.region}")`).prop('selected', true).end().trigger('change');

			if (currentAddress.province) {
				$('#province').find(`option:contains("${currentAddress.province}")`).prop('selected', true).end().trigger('change');

				if (currentAddress.municipality) {
					$('#municipality').find(`option:contains("${currentAddress.municipality}")`).prop('selected', true).end().trigger('change');

					if (currentAddress.barangay) {
						$('#barangay').find(`option:contains("${currentAddress.barangay}")`).prop('selected', true).end().trigger('change');
					}
				}
			}
		}

		$('#address_barangay').val(currentAddress.barangay);
		$('#address_municipality').val(currentAddress.municipality);
		$('#address_province').val(currentAddress.province);
		$('#address_region').val(currentAddress.region);
		
	};

	const handleRegionChange = () => {
		$(document).on('change', '#region', function (event) {
			const selectedRegionId = event.currentTarget.value;
			$('input[name="address[region]"]').val(
				$(`#region option:selected`).text()
			);

			let provinceOptionsHtml = `<option value=""></option>`;
			
			province.forEach((provinceObj) => {
				
				if (provinceObj.region_id == selectedRegionId) {
					const provinceName = provinceObj.province_name.replace('ñ', 'n');
					provinceOptionsHtml += `<option value="${provinceObj.province_id}">${provinceName}</option>`;
				}
			});
			
			$('#province').html(provinceOptionsHtml);

			$('#municipality').empty();
			$('#barangay').empty();
		});
	};

	const handleProvinceChange = () => {
		$(document).on('change', '#province', function (event) {
			const selectedProvinceId = event.currentTarget.value;;
			$('input[name="address[province]"]').val(
				$(`#province option:selected`).text()
			);

			let municipalityOptionsHtml = `<option value=""></option>`;

			municipality.forEach((municipalityObj) => {
				if (municipalityObj.province_id == selectedProvinceId) {
					const municipalityName = municipalityObj.municipality_name.replace('ñ', 'n');
					municipalityOptionsHtml += `<option value="${municipalityObj.municipality_id}">${municipalityName}</option>`;
				}
			});

			$('#municipality').html(municipalityOptionsHtml);

			$('#barangay').html('');
		});
	};

	const handleMunicipalityChange = () => {
		$(document).on('change', '#municipality', function (event) {
			const selectedMunicipalityId = event.currentTarget.value;
			$(`input[name="address[municipality]"]`).val(
				$(`#municipality option:selected`).text()
			);

			let barangayOptionsHtml = `<option value=""></option>`;
			barangay.forEach(barangayObj => {
				if (barangayObj.municipality_id == selectedMunicipalityId) {
					const barangayName = barangayObj.barangay_name.replace('ñ', 'n');
					barangayOptionsHtml += `<option value="${barangayObj.barangay_id}">${barangayName}</option>`;
				}
			});

			$('#barangay').html(barangayOptionsHtml);
		});
	};

	const handleBarangayChange = () => {
		$(document).on('change', '#barangay', function () {
			const selectedBarangayName = $('#barangay option:selected').text();
			$('input[name="address[barangay]"]').val(selectedBarangayName);
		});
	};

	const createHiddenInputs = () => {
		const hiddenInputs = `
			<input type="hidden" id="addressBarangay" name="address[barangay]" value="" />
			<input type="hidden" id="addressMunicipality" name="address[municipality]" value="" />
			<input type="hidden" id="addressProvince" name="address[province]" value="" />
			<input type="hidden" id="addressRegion" name="address[region]" value="" />
		`;

		$('.address-hidden-inputs').html(hiddenInputs);
	};

	const createRegionSelectElement = () => {
		const $regionSelectHtml = $('<select>', {
			id: 'region',
			class: 'form-select'
		});
		let regionOptionsHtml = `<option value=""></option>`;
		region.forEach(regionData => {
			const regionName = regionData.region_name.replace('ñ', 'n');
			regionOptionsHtml += `<option value="${regionData.region_id}">${regionName}</option>`;
		});
		$regionSelectHtml.append(regionOptionsHtml);
		$('.region-select').prepend($regionSelectHtml);
	};

	const createProvinceSelectElement = () => {
		const $provinceSelect = $('<select>', {
			id: 'province',
			class: 'form-select'
		});
		$provinceSelect.append('<option value=""></option>');
		$('.province-select').prepend($provinceSelect);
	};

	const createMunicipalitySelectElement = () => {
		const $municipalitySelect = $('<select>', {
			id: 'municipality',
			class: 'form-select'
		});
		$municipalitySelect.append('<option value=""></option>');
		$('.municipality-select').prepend($municipalitySelect);
	};

	const createBarangaySelectElement = () => {
		const $barangaySelect = $('<select>', {
			id: 'barangay',
			class: 'form-select'
		});
		$barangaySelect.append('<option value=""></option>');
		$('.barangay-select').prepend($barangaySelect);
	};


	return {

		initBeforeLoad: () => {
			createRegionSelectElement();
			createProvinceSelectElement();
			createMunicipalitySelectElement();
			createBarangaySelectElement();
			createHiddenInputs();
		},

		initAfterLoad: () => {
			setRegionValue();
			setProvinceValue();
			setMunicipalityValue();
			setBarangayValue();
			handleRegionChange();
			handleProvinceChange();
			handleMunicipalityChange();
			handleBarangayChange();
			loadAddress();
		}

	};

}();

document.addEventListener('DOMContentLoaded', function () {
	Address.initBeforeLoad();
});

window.addEventListener('load', function () {
	Address.initAfterLoad();
});



