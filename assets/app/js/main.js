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
         * Date Select
         * @author Jahid
         */
        $(document).on('click', '.tfhb-calendar-dates li', function (e) {
			$('.tfhb-calendar-dates li').removeClass('active');
            var $this = $(this);
            $this.addClass('active');
			$('.tfhb-meeting-times .tfhb-select-date').html($this.attr('data-date'));
			$('.tfhb-meeting-times').show();
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
			console.log(calenderData);

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


			
		});

		// Function to generate the tfhb-calendar
		function tfhb_date_manipulate($this, calenderData, year, month, date, months) {

			const day = $this.find(".tfhb-calendar-dates");
			const currdate = $this.find(".tfhb-calendar-current-date");
 

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
				lit += `<li data-date="${i} ${months[month]}, ${year}" class="${isToday} current">${i}</li>`;
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
