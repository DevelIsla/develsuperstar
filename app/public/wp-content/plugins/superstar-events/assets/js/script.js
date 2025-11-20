jQuery(document).ready(function ($) {

	/**
	 * Initialize Map on Single Event Page
	 */
	if ($('#superstar-map').length > 0) {
		var lat = $('#superstar-map').data('lat');
		var lng = $('#superstar-map').data('lng');

		if (lat && lng) {
			var map = L.map('superstar-map').setView([lat, lng], 13);

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

			L.marker([lat, lng]).addTo(map)
				.bindPopup('Event Location')
				.openPopup();
		}
	}

	/**
	 * AJAX Filtering
	 */
	$('#superstar-filter-btn').on('click', function (e) {
		e.preventDefault();

		var category = $('#superstar-category').val();
		var dateStart = $('#superstar-start-date').val();
		var dateEnd = $('#superstar-end-date').val();
		var search = $('#superstar-search').val();

		$.ajax({
			url: superstar_ajax.ajax_url,
			type: 'POST',
			data: {
				action: 'superstar_filter_events',
				nonce: superstar_ajax.nonce,
				category: category,
				date_start: dateStart,
				date_end: dateEnd,
				search: search
			},
			beforeSend: function () {
				$('#superstar-events-list').html('<p>Loading...</p>');
			},
			success: function (response) {
				$('#superstar-events-list').html(response);
			},
			error: function () {
				$('#superstar-events-list').html('<p>Error loading events.</p>');
			}
		});
	});

	/**
	 * Simple Calendar Logic
	 */
	if ($('#calendar-grid').length > 0) {
		var currentDate = new Date();

		function renderCalendar(date) {
			var year = date.getFullYear();
			var month = date.getMonth(); // 0-indexed

			$('#current-month-label').text(date.toLocaleString('default', { month: 'long', year: 'numeric' }));

			var firstDay = new Date(year, month, 1);
			var lastDay = new Date(year, month + 1, 0);

			var startDayIndex = firstDay.getDay(); // 0 = Sunday
			var totalDays = lastDay.getDate();

			var html = '';

			// Day Headers
			var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
			days.forEach(function (day) {
				html += '<div class="calendar-day-header">' + day + '</div>';
			});

			// Empty cells before first day
			for (var i = 0; i < startDayIndex; i++) {
				html += '<div class="calendar-day other-month"></div>';
			}

			// Days
			for (var day = 1; day <= totalDays; day++) {
				var todayClass = (day === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) ? ' today' : '';
				html += '<div class="calendar-day' + todayClass + '" data-day="' + day + '" data-date="' + year + '-' + (month + 1).toString().padStart(2, '0') + '-' + day.toString().padStart(2, '0') + '">';
				html += '<span class="day-number">' + day + '</span>';
				html += '<div class="day-events"></div>'; // Container for events
				html += '</div>';
			}

			$('#calendar-grid').html(html);

			// Fetch Events
			fetchEvents(month + 1, year);
		}

		function fetchEvents(month, year) {
			$.ajax({
				url: superstar_ajax.ajax_url,
				type: 'POST',

				renderCalendar(currentDate);

		$('#prev-month').click(function () {
					currentDate.setMonth(currentDate.getMonth() - 1);
					renderCalendar(currentDate);
				});

				$('#next-month').click(function () {
					currentDate.setMonth(currentDate.getMonth() + 1);
					renderCalendar(currentDate);
				});
			}

});
