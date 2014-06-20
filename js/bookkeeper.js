var canvas;
var context;

var Bookkeeper = function() {
	this.months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

	this.makeTwoDigits = function(number) {
		number = String(number);
		newNumber = number;
		if (number.length == 1) {
			newNumber = '0' + number;
		}
		return newNumber;
	};

	this.calcDaysBetween = function(start, end, readingDays) {
		date1 = parseDate(start);
		date2 = parseDate(end);
		daysLeft = 0;
		for (loopTime = date1; loopTime < date2; loopTime.setTime(loopTime.valueOf() + 86400000)) {
			if (readingDays.length != 0 && readingDays[loopTime.getDay()] == 1) {
				daysLeft++;
			} else if (readingDays.length == 0) {
				daysLeft++;
			}
		}
		return daysLeft + 1;
	};

	this.compareDates = function(date1, date2) {
		var rtnInt = 0;
		if (date1.valueOf() < date2.valueOf()) {
			rtnInt = -1;
		} else if (date1.valueOf() > date2.valueOf()) {
			rtnInt = 1;
		}
		return rtnInt;
	};

	this.chartEntries = function(book) {
		chartpoints = [];
		if (book.endDate != '0000-00-00') {
			previousPage = 0;
			currentEntry = 0;
			count = 0;
			if (book.pagesLeft > 0) {
				tempday = new Date();
				endDateStr = tempday.getFullYear() + '-' + (tempday.getMonth() + 1) + '-' + tempday.getDate();
			} else {
				lastEntry = book.entries[book.entries.length - 1].entryDate;
				if (this.compareDates(parseDate(lastEntry), parseDate(book.endDate)) > 0) {
					endDateStr = lastEntry;
				} else {
					endDateStr = book.endDate;
				}
			}
			endDate = parseDate(endDateStr);
			for (loopTime = parseDate(book.startDate); loopTime <= endDate; loopTime.setTime(loopTime.valueOf() + 86400000)) {
				if (loopTime.getHours() != 0) {
					if (loopTime.getHours() == 23) {
						loopTime.setTime(loopTime.valueOf() + (60 * 60 * 1000));
					} else {
						loopTime.setTime(loopTime.valueOf() + (-1 * 60 * 60 * 1000));
					}
				}
				if (book.readingDays[loopTime.getDay()] == 1) {
					count++;
					date = loopTime.getFullYear() + '-' + (loopTime.getMonth() + 1) + '-' + loopTime.getDate();
					for (currentEntry; currentEntry < book.entries.length; currentEntry++) {
						compared = this.compareDates(parseDate(book.entries[currentEntry].entryDate), parseDate(date));
						if (compared == 0) {
							previousPage = book.entries[currentEntry].pageNumber;
							break;
						} else if (compared == 1) {
							break;
						} else {
							previousPage = book.entries[currentEntry].pageNumber;
						}
					}
					chartpoints.push({page: previousPage, day: count});
				}
			}
		} else {
			for (i in book.entries) {
				chartpoints.push({page: book.entries[i].pageNumber, day: this.calcDaysBetween(book.startDate, book.entries[i].entryDate, [])});
			}
		}
		return chartpoints;
	};
};

function parseDate(input) {
	format = 'yyyy-mm-dd';

	var parts = input.match(/(\d+)/g), 
		i = 0, fmt = {};

	// extract date-part indexes from the format
	format.replace(/(yyyy|dd|mm)/g, function(part) { fmt[part] = i++; });

	return new Date(parts[fmt['yyyy']], parts[fmt['mm']]-1, parts[fmt['dd']]);
}

$(document).ready(function() {
	bk = new Bookkeeper();
	canvas = document.getElementById("reading_chart");
	if (canvas != null) {
		canvas.width = $("#reading_chart").width();
		canvas.height = $("#reading_chart").height();

		if (canvas.getContext) {
			context = canvas.getContext('2d');
			chartEntries = bk.chartEntries(current_book);

			// Take into account retina displays
			if (window.devicePixelRatio) {
				var cvWidth = canvas.width;
				var cvHeight = canvas.height;

				canvas.width = cvWidth * window.devicePixelRatio;
				canvas.height = cvHeight * window.devicePixelRatio;

				canvas.style.width = cvWidth + "px";
				canvas.style.height = cvHeight + "px";
			}

			lastEntry = current_book.entries[current_book.entries.length - 1];
			lastEntryDate = (lastEntry) ? lastEntry.entryDate : '';

			if (current_book.endDate == '0000-00-00') {
				endDate = lastEntryDate;
				readingDays = [];
				drawGoalLines = false;
			} else {
				if (current_book.entries.length > 0 && bk.compareDates(parseDate(lastEntryDate), parseDate(current_book.endDate)) >= 0) {
					endDate = lastEntryDate;
					readingDays = current_book.readingDays;
					drawGoalLines = true;
				} else {
					endDate = current_book.endDate;
					readingDays = current_book.readingDays;
					drawGoalLines = true;
				}
			}

			if (endDate != '') {
				numDays = bk.calcDaysBetween(current_book.startDate, endDate, readingDays);
			} else {
				numDays = 1;
			}

			chart = new Chart(current_book.totalPages, numDays, chartEntries, drawGoalLines, canvas, context);
		}
	}

	// make sure the entry field exists (if it doesn't, we're on a finished book page)
	var currententry = $(".currententry");
	if (currententry.length == 1) {
		// focus on the entry
		currententry.focus();
		// and move the cursor to the end
		//var entrylength = currententry.val().length;
		//currententry[0].setSelectionRange(entrylength, entrylength);
	}

	var editTitle = $("#editbooktitle");
	if (editTitle && editTitle.length > 0 && $("#edit h1").text() == 'Add Book') {
		editTitle.focus();
	}

	$("#deletebooklink").click(function() {
		if (confirm("Are you sure you want to delete this book? This action can not be undone.")) {
			return true;
		}
		return false;
	});

	$("#editbookstartdate").simpleDatepicker();
	$("#editbookenddate").simpleDatepicker();

	$("#account").submit(function() {
		var rtn = false;
		var username = $("#username").val();
		var google = $("#google").val();
		var email = $("#email").val();
		if (username != '' && email != '' && google != '') {
			$.ajax({
				url: app_url + '/usernamecheck/' + username + '/' + google,
				async: false,
				dataType: 'json',
				success: function(data) {
					if (data.unique) { rtn = true; }
				}
			});
		}
		return rtn;
	});

	$(".currententry").keydown(function(e) {
		var charCode = (e.which) ? e.which : e.keyCode;

		// blur on enter/esc
		if (charCode == 13 || charCode == 27) {
			$(this).blur();
		}

		// allow backspace, tab, home, end, arrows, insert, delete, 0-9, numpad 0-9, enter
		if ((charCode == 8) || (charCode == 9) || (charCode == 13) || (charCode >= 35 && charCode <= 57) || (charCode >= 96 && charCode <= 105)) {
			return true;
		}

		return false;
	});

	$(".currententry").on("input", function(e) {
		bookid = Number($(this).attr("data-book-id"));
		page = Number($(this).val());

		$.getJSON(app_url + '/' + currentuser + '/action/saveentry?bookid=' + bookid + '&page=' + page, function(data) {
			if ($("#booklist").length > 0) {
				// Home page
				percent = Math.round(data.percentage);
				$("#booklist li a#book" + data.bookId + " .percent").css('width', percent + 'px');
				$("#booklist li a#book" + data.bookId + " .percentage > span b").html(percent + '&hairsp;<span>%</span>');
				$("#booklist li a#book" + data.bookId + " .percentage > span .pagesleft").html(data.pagesLeft);

				// If we've finished the book
				if (percent == 100) {
					// Hide the li since we're done
					parent_li = $("#booklist li a#book" + data.bookId).parent("li");
					parent_li.css("z-index", "-50");
					parent_li.css("position", "relative");
					width = parent_li.width() + 10;
					parent_li.animate({ left: '+=' + width }, 500, function() { $(this).hide(); } );
				} else {
					// Show that we've updated it
					percentage = $("#booklist li a#book" + data.bookId + " .percentage");
					if (percentage.hasClass("changed")) {
						percentage.removeClass("changed");
					} else {
						percentage.addClass("changed");
					}
				}
			}

			if ($("#pagesleft").length > 0) {
				// Detail page
				$("#pagesleft").text(data.pagesLeft);
				$("#actionhtml").html(data.actionHtml);
				$("#entries").html('');

				content = '';
				for (i in data.entries) {
					thedate = parseDate(data.entries[i].entryDate);
					content += '<li>Page ' + data.entries[i].pageNumber + ' <span class="date">(' + thedate.getDate() + ' ' + bk.months[thedate.getMonth()] + ')</span></li>';
				}
				$("#entries").html(content);
				chartEntries = bk.chartEntries(data);
				if (data.endDate == '0000-00-00') {
					var chart = new Chart(data.totalPages, bk.calcDaysBetween(data.startDate, data.entries[data.entries.length - 1].entryDate, []), chartEntries, false, canvas, context);
				} else {
					if (bk.compareDates(parseDate(data.entries[data.entries.length - 1].entryDate), parseDate(data.endDate)) >= 0) {
						var chart = new Chart(data.totalPages, bk.calcDaysBetween(data.startDate, data.entries[data.entries.length - 1].entryDate, data.readingDays), chartEntries, true, canvas, context);
					} else {
						var chart = new Chart(data.totalPages, bk.calcDaysBetween(data.startDate, data.endDate, data.readingDays), chartEntries, true, canvas, context);
					}
				}
			}
		});

		//$(this).blur();
	});
});
