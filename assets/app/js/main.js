(function ($) {

    $(document).ready(function () {
        /**
         * Time Zone Change
         * @author Jahid
         */
        $(document).on('click', '.tfhb-timezone-tabs ul li', function (e) {
            $('.tfhb-timezone-tabs ul li').removeClass('active');
            var $this = $(this);
            $this.addClass('active');
        });


        /**
         * Time Select
         * @author Jahid
         */
        $(document).on('click', '.tfhb-available-times li .time', function (e) {
            $('.tfhb-available-times li .next').remove();
            var $this = $(this);
            $this.parent().append('<span class="next tfhb-flexbox tfhb-gap-8"> Next<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 10L14 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 4.16666L14.8333 9.99999L9 15.8333" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>');
        });

	 
 

		/**
		 * Time Select
		 * @author Sydur
		 * */ 
		$('.tfhb-meeting-box').each(function(){
			// Get Calender Id
			let $this = $(this),
			 	calenderId = $this.attr('data-calendar'),
				calenderData =  eval("tfhb_app_booking_" + calenderId);
			// Select 2 Time Zone 
			$this.find('.tfhb-time-zone-select').select2({
				dropdownCssClass: 'tfhb-select2-dropdown',
			});

			let date = new Date();
			let year = date.getFullYear();
			let month = date.getMonth();

		
			const tfhb_calendar_navs = $this.find(".tfhb-calendar-navigation span");


			// Array of month names
			const months = [
				"January",
				"February",
				"March",
				"April",
				"May",
				"June",
				"July",
				"August",
				"September",
				"October",
				"November",
				"December"
			]; 


			tfhb_date_manipulate( $this, calenderData, year, month, date, months );


			// Attach a click event listener to each icon
			tfhb_calendar_navs.each(function() {
				// When an icon is clicked
				$(this).on("click", function() {
					// Check if the icon is "tfhb-calendar-prev"
					// or "tfhb-calendar-next"
					month = $(this).attr("id") === "tfhb-calendar-prev" ? month - 1 : month + 1;
		
					// Check if the month is out of range
					if (month < 0 || month > 11) {
		
						// Set the date to the first day of the 
						// month with the new year
						date = new Date(year, month, new Date().getDate());
		
						// Set the year to the new year
						year = date.getFullYear();
		
						// Set the month to the new month
						month = date.getMonth();
					} else {
		
						// Set the date to the current date
						date = new Date();
					}
		
					// Call the tfhb_date_manipulate function to 
					// update the tfhb-calendar display
					tfhb_date_manipulate( $this, calenderData, year, month, date, months );
				});
			});

			// Select Date
			$(document).on('click', '.tfhb-calendar-dates li', function (e) {
				var $this_li = $(this);
				$this.find('.tfhb-calendar-dates li').removeClass('active');  
				$this_li.addClass('active');	

				// Get the first day of the month
				tfhb_times_manipulate( $this, calenderData, $this_li );

				
			});
			$(document).on('change', $this.find('input[name="tfhb_time_format"]'), function (e) { 
				var $this_li = $this.find('.tfhb-calendar-dates li.active');  
				// Get the first day of the month
				tfhb_times_manipulate( $this, calenderData, $this_li );

				
			});


			
		});

		// Function to generate the tfhb-calendar
		function tfhb_date_manipulate($this, calenderData, year, month, date, months) {

			const day = $this.find(".tfhb-calendar-dates");
			const currdate = $this.find(".tfhb-calendar-current-date");
  
			let calender_data = calenderData;
			let availability = calender_data.availability;
			let date_slots = availability.date_slots;  
			 

			// Get the first day of the month
			let dayone = new Date(year, month, 1).getDay();

			// Get the last date of the month
			let lastdate = new Date(year, month + 1, 0).getDate();
	
			// Get the day of the last date of the month
			let dayend = new Date(year, month, lastdate).getDay();
	
			// Get the last date of the previous month
			let monthlastdate = new Date(year, month, 0).getDate();
	
			// Variable to store the generated tfhb-calendar HTML
			let lit = "";
	
			// Loop to add the last dates of the previous month
			for (let i = dayone; i > 0; i--) {
				lit += `<li class="inactive">${monthlastdate - i + 1}</li>`;
			}
	
			// Loop to add the dates of the current month
			for (let i = 1; i <= lastdate; i++) {
	
				// Check if the current date is today
				let isToday = i === date.getDate() && month === new Date().getMonth() && year === new Date().getFullYear() ? "active" : "";

				// Check if the current date has availability slots
				let dateKey = year + "-" + (month + 1).toString().padStart(2, '0') + "-" + i.toString().padStart(2, '0'); 
				let dateSlot = date_slots.find(slot => slot.date.match(dateKey) );
				let availabilityClass = typeof dateSlot !== 'undefined' && dateSlot.available   ? "inactive " : " ";
				let dataAvailable = typeof dateSlot !== 'undefined' && dateSlot.available != 1   ? "available" : "";
		
				lit += `<li data-date="${dateKey}" data-available="${dataAvailable}" class="${isToday} current ${availabilityClass}">${i}</li>`;
		   }
	
			// Loop to add the first dates of the next month
			for (let i = dayend; i < 6; i++) {
				lit += `<li class="inactive">${i - dayend + 1}</li>`;
			}
	
			// Update the text of the current date element 
			// with the formatted current month and year
			currdate.text(`${months[month]} ${year}`);
	
			// update the HTML of the dates element 
			// with the generated tfhb-calendar
			day.html(lit);
		}

		// Function to generate the tfhb-calendar
		function tfhb_times_manipulate($this, calenderData, $this_li) {
 
			var selected_date = $this_li.attr('data-date'); 
			var data_available = $this_li.attr('data-available'); 
			//  input radio data name tfhb_time_format
			var time_format = $this.find('input[name="tfhb_time_format"]:checked').val(); 
			$this.find('.tfhb-meeting-times .tfhb-select-date').html(selected_date);
			
			// Get Selected Date day
			let selected_date_day = new Date(selected_date).getDay(),
			 	calender_data = calenderData,
			 	duration = calender_data.duration,
			 	meeting_interval = calender_data.meeting_interval,
			 	buffer_time_before = calender_data.buffer_time_before,
			 	buffer_time_after = calender_data.buffer_time_after,
			 	availability = calender_data.availability,
				date_slots = availability.date_slots,
				time_slots = availability.time_slots, 
				selected_date_slots =time_slots[selected_date_day],
				times = selected_date_slots.times, //array  
				timesData = []; //array 
 
			
			if(data_available == 'available'){
				// Generate time slots  form date_slots
				for (var i = 0; i < date_slots.length; i++) {
					var date_slot = date_slots[i]; 
					for (var i = 0; i < date_slot.times.length; i++) {
						var startTime = date_slot.times[i].start;
						var endTime = date_slot.times[i].end;
						var generatedSlots = generateTimeSlots(startTime, endTime, duration, meeting_interval, buffer_time_before, buffer_time_after, selected_date, time_format);
						// merge with timesData 
						timesData = timesData.concat(generatedSlots);
					} 
				}

			}else{
				// Generate time slots
				for (var i = 0; i < times.length; i++) {
					var startTime = times[i].start;
					var endTime = times[i].end;
					var generatedSlots = generateTimeSlots(startTime, endTime, duration, meeting_interval, buffer_time_before, buffer_time_after, selected_date, time_format);
					// merge with timesData 
					timesData = timesData.concat(generatedSlots);
				} 
			}
			

			$this.find('.tfhb-available-times ul').html('');

			for (var i = 0; i < timesData.length; i++) {
				// loop times and add to html li
				// Remove 
				$this.find('.tfhb-available-times ul').append('<li class="tfhb-flexbox"> <span class="time">' + timesData[i].start + '</span> </li>');
			}

			// tfhb-meeting-times show
			$this.find('.tfhb-meeting-times').show(); 


			// loop times and add to html li
			 

		}

		// Generate Time Slots
		function generateTimeSlots(startTime, endTime, duration, meeting_interval, buffer_time_before, buffer_time_after, selected_date, time_format) {
			var timeSlots = [];
			// start date data format =   2024-05-04 
			var start = new Date(selected_date + " " + startTime);
			var end = new Date(selected_date + " " + endTime);
			var current = new Date(start);
			var before = new Date(start);
			var after = new Date(start);
			var diff = duration * 60000;
			var before_diff = buffer_time_before * 60000;
			var after_diff = buffer_time_after * 60000;
			var meeting_interval = meeting_interval * 60000;
			var total_diff = diff +before_diff + after_diff;
			while (current < end) {
				// new Date(current.getTime() + total_diff).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
				var start_time = time_format == 12 ? current.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true  }) : current.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false  });
				var end_time = time_format == 12 ? new Date(current.getTime() + total_diff).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true  }) : new Date(current.getTime() + total_diff).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false  }); 
				timeSlots.push({

					start: start_time, 
					// before_diff and after_diff need to use 
					end: end_time,

				});
				current = new Date(current.getTime() + total_diff + meeting_interval);
			} 
			return timeSlots;
		}


		


    });

})(jQuery);

// let date = new Date();
// let year = date.getFullYear();
// let month = date.getMonth();

// const day = document.querySelector(".tfhb-calendar-dates");
// const currdate = document.querySelector(".tfhb-calendar-current-date");
// const tfhb_calendar_navs = document.querySelectorAll(".tfhb-calendar-navigation span");

// // Array of month names
// const months = [
// 	"January",
// 	"February",
// 	"March",
// 	"April",
// 	"May",
// 	"June",
// 	"July",
// 	"August",
// 	"September",
// 	"October",
// 	"November",
// 	"December"
// ];

// // Function to generate the tfhb-calendar
// const tfhb_date_manipulate = () => {

// 	// Get the first day of the month
// 	let dayone = new Date(year, month, 1).getDay();

// 	// Get the last date of the month
// 	let lastdate = new Date(year, month + 1, 0).getDate();

// 	// Get the day of the last date of the month
// 	let dayend = new Date(year, month, lastdate).getDay();

// 	// Get the last date of the previous month
// 	let monthlastdate = new Date(year, month, 0).getDate();

// 	// Variable to store the generated tfhb-calendar HTML
// 	let lit = "";

// 	// Loop to add the last dates of the previous month
// 	for (let i = dayone; i > 0; i--) {
// 		lit +=
// 			`<li class="inactive">${monthlastdate - i + 1}</li>`;
// 	}

// 	// Loop to add the dates of the current month
// 	for (let i = 1; i <= lastdate; i++) {

// 		// Check if the current date is today
// 		let isToday = i === date.getDate()
// 			&& month === new Date().getMonth()
// 			&& year === new Date().getFullYear()
// 			? "active"
// 			: "";
// 		lit += `<li data-date="${i} ${months[month]}, ${year}" class="${isToday} current">${i}</li>`;
// 	}

// 	// Loop to add the first dates of the next month
// 	for (let i = dayend; i < 6; i++) {
// 		lit += `<li class="inactive">${i - dayend + 1}</li>`
// 	}

// 	// Update the text of the current date element 
// 	// with the formatted current month and year
// 	currdate.innerText = `${months[month]} ${year}`;

// 	// update the HTML of the dates element 
// 	// with the generated tfhb-calendar
// 	day.innerHTML = lit;
// }

// tfhb_date_manipulate();

// // Attach a click event listener to each icon
// tfhb_calendar_navs.forEach(icon => {

// 	// When an icon is clicked
// 	icon.addEventListener("click", () => {
		
// 		// Check if the icon is "tfhb-calendar-prev"
// 		// or "tfhb-calendar-next"
// 		month = icon.id === "tfhb-calendar-prev" ? month - 1 : month + 1;

// 		// Check if the month is out of range
// 		if (month < 0 || month > 11) {

// 			// Set the date to the first day of the 
// 			// month with the new year
// 			date = new Date(year, month, new Date().getDate());

// 			// Set the year to the new year
// 			year = date.getFullYear();

// 			// Set the month to the new month
// 			month = date.getMonth();
// 		}

// 		else {

// 			// Set the date to the current date
// 			date = new Date();
// 		}

// 		// Call the tfhb_date_manipulate function to 
// 		// update the tfhb-calendar display
// 		tfhb_date_manipulate();
// 	});
// });
