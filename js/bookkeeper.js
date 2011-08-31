var canvas;
var context;

var Bookkeeper = function () {
	this.months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Juy', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

	this.makeTwoDigits = function (number) {
		number = String(number);
		newNumber = number;
		if (number.length == 1) {
			newNumber = '0' + number;
		}
		return newNumber;
	};

	this.calcDaysBetween = function (start, end, readingDays) {
		date1 = new Date(start);
		date2 = new Date(end);
		daysLeft = 0;
		for (loopTime = date1; loopTime < date2; loopTime.setTime(loopTime.valueOf() + 86400000)) {
			if (readingDays[loopTime.getDay()] == 1) {
				daysLeft++;
			}
		}
		return daysLeft + 1;
	};

	this.compareDates = function (date1, date2) {
		var rtnInt = 0;
		if (date1.valueOf() < date2.valueOf()) {
			rtnInt = -1;
		} else if (date1.valueOf() > date2.valueOf()) {
			rtnInt = 1;
		}
		return rtnInt;
	};

	this.chartEntries = function (book) {
		chartpoints = [];
		previousPage = 0;
		currentEntry = 0;
		tempday = new Date();
		today = new Date(tempday.getFullYear() + '-' + (tempday.getMonth() + 1) + '-' + tempday.getDate());
		for (loopTime = new Date(book.startDate); loopTime <= today; loopTime.setTime(loopTime.valueOf() + 86400000)) {
			if (book.readingDays[loopTime.getDay()] == 1) {
				date = loopTime.getFullYear() + '-' + (loopTime.getMonth() + 1) + '-' + loopTime.getDate();
				for (currentEntry; currentEntry < book.entries.length; currentEntry++) {
					compared = this.compareDates(new Date(book.entries[currentEntry].entryDate), new Date(date));
					if (compared == 0) {
						previousPage = book.entries[currentEntry].pageNumber;
						break;
					} else if (compared == 1) {
						break;
					} else {
						previousPage = book.entries[currentEntry].pageNumber;
					}
				}
				chartpoints.push(previousPage);
			}
		}
		return chartpoints;
	};
};

$.extend(DateInput.DEFAULT_OPTS, {
	stringToDate: function(string) {
		var matches;
		if (matches = string.match(/^(\d{4,4})-(\d{2,2})-(\d{2,2})$/)) {
			return new Date(matches[1], matches[2] - 1, matches[3]);
		} else {
			return null;
		};
	}, 
	dateToString: function(date) {
		var month = (date.getMonth() + 1).toString();
		var dom = date.getDate().toString();
		if (month.length == 1) month = "0" + month;
		if (dom.length == 1) dom = "0" + dom;
		return date.getFullYear() + "-" + month + "-" + dom;
	}
});

$(document).ready(function () {
	bk = new Bookkeeper();
	canvas = document.getElementById("reading_chart");
	if (canvas != null) {
		canvas.width = $("#reading_chart").width();
		canvas.height = $("#reading_chart").height();

		if (canvas.getContext) {
			context = canvas.getContext('2d');
			chartEntries = bk.chartEntries(current_book);
			var chart = new Chart(current_book.totalPages, bk.calcDaysBetween(current_book.startDate, current_book.endDate, current_book.readingDays), chartEntries, canvas, context);
		}
	}


	$(".date_input").date_input();
	$("#currententry").focus();

	$("#deletebooklink").click(function () {
		if (confirm("Are you sure you want to delete this book? This action can not be undone.")) {
			return true;
		}
		return false;
	});

	$("#currententry").change(function () {
		bookid = Number($("#currentbookid").val());
		page = Number($(this).val());
		$.getJSON(app_url + '/' + currentuser + '/action/saveentry?bookid=' + bookid + '&page=' + page, function (data) {
			percent = Math.round(data.percentage);
			$("#booklist li a#book" + data.bookId + " .percent").css('width', percent + 'px');
			$("#booklist li a#book" + data.bookId + " .percentage span").html('<b>' + percent + '%</b> (' + data.pagesLeft + ' pages left)');
			$("#pagesleft").text(data.pagesLeft);
			$("#actionhtml").html(data.actionHtml);
			$("#entries").html('');
			content = '';
			for (i in data.entries) {
				thedate = new Date(data.entries[i].entryDate);
				content += '<li>' + bk.months[thedate.getMonth()] + ' ' + thedate.getDate() + ' (page ' + data.entries[i].pageNumber + ')</li>';
			}
			$("#entries").html(content);
			chartEntries = bk.chartEntries(data);
			var chart = new Chart(data.totalPages, bk.calcDaysBetween(data.startDate, data.endDate, data.readingDays), chartEntries, canvas, context);
		});
	});
});
