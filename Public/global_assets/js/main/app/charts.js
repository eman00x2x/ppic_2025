const charts = function () {

	let totalDailyLoginChart;
	let totalPropertiesPerListingTypeChart;
	let totalPropertiesPerCategoryChart;
	let monthlyPostingsChart;
	let totalPropertiesPerStatusChart;
	let trafficsPerDayChart;
	let totalLeadsPerDayChart;

	const reDraw = () => {
		totalDailyLoginChart ? totalDailyLoginChart() : null;
		totalPropertiesPerListingTypeChart ? totalPropertiesPerListingTypeChart() : null;
		totalPropertiesPerCategoryChart ? totalPropertiesPerCategoryChart() : null;
		monthlyPostingsChart ? monthlyPostingsChart() : null;
		totalPropertiesPerStatusChart ? totalPropertiesPerStatusChart() : null;
		trafficsPerDayChart ? trafficsPerDayChart() : null;
		totalLeadsPerDayChart ? totalLeadsPerDayChart() : null;
	};
	const getTotalLoginPerDay = (filterParam = null) => {
		const elementContainer = $("#totalDailyLogin");
		if (!elementContainer.length) { return; }

		const url = `${elementContainer.data('url')}${filterParam ? `?filter=${filterParam}` : ''}`;
		eo.get(url, {
			beforeRequest: () => eo.component.alert.loader("Loading chart...", ".totalDailyLoginLoader"),
			onSuccess: function (response) {
				eo.component.alert.message("", ".totalDailyLoginLoader");
				totalDailyLoginChart = eo.component.googleChart.line({
					containerId: 'totalDailyLogin',
					data: (table) => {
						table.addColumn('date', 'Dates');
						table.addColumn('number', 'Total');

						const rows = Object.entries(response).map(([key, value]) => [new Date(value.date), parseInt(value.total)]);

						table.addRows(rows);
						return table;
					},

					options: {
						height: (elementContainer.attr('height') ?? 300),
						vAxis: {
							title: 'Total',
							format: 'decimal'
						},
						legend: { position: 'none' }
					}
				});
			}
		});
	};

	const getTotalPropertiesPerListingType = (filterParam = null) => {
		const elementContainer = $("#totalPropertiesPerListingType");
		if (!elementContainer.length) { return; }

		const url = `${elementContainer.data('url')}${filterParam ? `?filter=${filterParam}` : ''}`;
		eo.get(url, {
			beforeRequest: () => eo.component.alert.loader("Loading chart...", ".totalPropertiesPerListingTypeLoader"),
			onSuccess: function (response) {
				eo.component.alert.message("", ".totalPropertiesPerListingTypeLoader");
				totalPropertiesPerListingTypeChart = eo.component.googleChart.pie({
					containerId: 'totalPropertiesPerListingType',
					data: (table) => {
						table.addColumn('string', 'Listing Type');
						table.addColumn('number', 'Total');

						const rows = Object.entries(response).map(([key, value]) => [value.listing_type, parseInt(value.total)]);

						table.addRows(rows);
						return table;
					},
					options: {
						is3D: true,
						height: (elementContainer.attr('height') ?? 300),
						vAxis: {
							title: 'Total',
							format: 'decimal'
						},
						legend: { position: 'none' }
					}
				});
			}
		});
	};

	const getTotalTrafficsPerDay = (filterParam = null) => {
		const elementContainer = $("#totalTrafficsPerDay");
		if (!elementContainer.length) { return; }

		const url = `${elementContainer.data('url')}${filterParam ? `?filter=${filterParam}` : ''}`;
		eo.get(url, {
			beforeRequest: () => eo.component.alert.loader("Loading chart...", ".totalTrafficsPerDayLoader"),
			onSuccess: function (response) {
				eo.component.alert.message("", ".totalTrafficsPerDayLoader");
				trafficsPerDayChart = eo.component.googleChart.line({
					containerId: 'totalTrafficsPerDay',
					data: (table) => {
						table.addColumn('date', 'Dates');
						table.addColumn('number', 'Total');

						const rows = Object.entries(response).map(([key, value]) => [new Date(value.date), parseInt(value.total)]);

						table.addRows(rows);
						return table;
					},
					options: {
						height: (elementContainer.attr('height') ?? 300),
						vAxis: {
							title: 'Total',
							format: 'decimal'
						},
						legend: { position: 'none' }
					}
				});
			}
		});
	};

	const getTotalPropertiesPerCategory = (filterParam = null) => {
		const elementContainer = $('#totalPropertiesPerCategory');
		if (!elementContainer.length) { return; }
		
		const url = `${elementContainer.data('url')}${filterParam ? `?filter=${filterParam}` : ''}`;
		eo.get(url, {
			beforeRequest: () => eo.component.alert.loader("Loading chart...", ".totalPropertiesPerCategoryLoader"),
			onSuccess: function (response) {
				eo.component.alert.message("", ".totalPropertiesPerCategoryLoader");
				totalPropertiesPerCategoryChart = eo.component.googleChart.pie({
					containerId: 'totalPropertiesPerCategory',
					data: (table) => {
						table.addColumn('string', 'Category');
						table.addColumn('number', 'Count');

						const rows = Object.entries(response).map(([key, value]) => [value.category, parseInt(value.total)]);

						table.addRows(rows);
						return table;
					},
					options: {
						is3D: true,
						height: (elementContainer.attr('height') ?? 280),
						vAxis: {
							title: 'Total',
							format: 'decimal'
						}
					}
				});
			}
		});
	};

	const getMonthlyPostings = (filterParam = null) => {
		const elementContainer = $("#getMonthlyPostings");
		if (!elementContainer.length) { return; }

		const url = `${elementContainer.data('url')}${filterParam ? `?filter=${filterParam}` : ''}`;
		eo.get(`${url}`, {
			beforeRequest: () => eo.component.alert.loader("Loading chart...", ".getMonthlyPostingsLoader"),
			onSuccess: function (response) {
				eo.component.alert.message("", ".getMonthlyPostingsLoader");
				monthlyPostingsChart = eo.component.googleChart.line({
					containerId: 'getMonthlyPostings',
					data: (table) => {
						table.addColumn('date', 'Dates');
						table.addColumn('number', 'Created');
						table.addColumn('number', 'Modified');

						const rows = Object.entries(response).map(([key, value]) => [new Date(key), parseInt(value.created), parseInt(value.modified)]);

						table.addRows(rows);
						return table;
					},
					options: {
						height: (elementContainer.attr('height') ?? 300),
						vAxis: {
							title: 'Total',
							format: 'decimal'
						}
					}
				});
			}
		});
	};

	const getTotalPropertiesPerStatus = (filterParam = null) => {
		const elementContainer = $("#totalPropertiesPerStatus");
		if (!elementContainer.length) { return; }

		const url = `${elementContainer.data('url')}${filterParam ? `?filter=${filterParam}` : ''}`;
		eo.get(url, {
			beforeRequest: () => eo.component.alert.loader("Loading chart...", ".totalPropertiesPerStatusLoader"),
			onSuccess: function (response) {
				eo.component.alert.message("", ".totalPropertiesPerStatusLoader");
				totalPropertiesPerStatusChart = eo.component.googleChart.bar({
					containerId: 'totalPropertiesPerStatus',
					data: (table) => {
						table.addColumn('string', 'Status');
						table.addColumn('number', 'Count');

						const rows = Object.entries(response).map(([key, value]) => [key.charAt(0).toUpperCase() + key.slice(1), parseInt(value)]);

						table.addRows(rows);
						return table;
					},
					options: {
						height: (elementContainer.attr('height') ?? 300),
						vAxis: {
							title: 'Total',
							format: 'decimal'
						},
						legend: { position: 'none' }
					}
				});
			}
		});
	};

	const getTotalLeadsPerDay = (filterParam = null) => {
		const elementContainer = $("#getTotalLeadsPerDay");
		if (!elementContainer.length) { return; }

		const url = `${elementContainer.data('url')}${filterParam ? `?filter=${filterParam}` : ''}`;
		eo.get(`${url}`, {
			beforeRequest: () => eo.component.alert.loader("Loading chart...", ".getTotalLeadsPerDayLoader"),
			onSuccess: function (response) {
				eo.component.alert.message("", ".getTotalLeadsPerDayLoader");
				totalLeadsPerDayChart = eo.component.googleChart.line({
					containerId: 'getTotalLeadsPerDay',
					data: (table) => {
						table.addColumn('date', 'Dates');
						table.addColumn('number', 'Total');

						const rows = Object.entries(response).map(([key, value]) => [new Date(value.date), parseInt(value.total)]);

						table.addRows(rows);
						return table;
					},
					options: {
						height: (elementContainer.attr('height') ?? 300),
						vAxis: {
							title: 'Total',
							format: 'decimal'
						},
						legend: { position: 'none' }
					}
				});
			}
		});
	};

	const filterReportResults = () => {
		const groups = {
			"totalPropertiesPerListingType": getTotalPropertiesPerListingType,
			"totalDailyLogin": getTotalLoginPerDay,
			"totalPropertiesPerCategory": getTotalPropertiesPerCategory,
			"getMonthlyPostings": getMonthlyPostings,
			"totalPropertiesPerStatus": getTotalPropertiesPerStatus,
			"totalTrafficsPerDay": getTotalTrafficsPerDay,
			"totalLeadsPerDay": getTotalLeadsPerDay
		};

		$(document).on('change', '.select-filter', function () {
			target = $(this).data('target');
			val = $(this).find(':selected').val();
			groups[target](val);
		});
	}

	return {
		getTotalPropertiesPerListingType,
		getTotalLoginPerDay,
		getTotalPropertiesPerCategory,
		getMonthlyPostings,
		getTotalPropertiesPerStatus,
		filterReportResults,
		getTotalTrafficsPerDay,
		getTotalLeadsPerDay,
		reDraw
	};

}();

$(window).on('resizeEnd', function () {
	charts.reDraw();
});