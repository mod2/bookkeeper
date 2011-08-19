var goalData = undefined;

var Bookkeeper = function () {
	this.months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	this.days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

	this.makeTwoDigits = function (number) {
		number = String(number);
		newNumber = number;
		if (number.length == 1) {
			newNumber = '0' + number;
		}
		return newNumber;
	};

	this.calcDaysLeft = function (goal) {
		today = new Date();
		endDate = new Date(goal.endDate);
		daysLeft = 0;
		for (loopTime = today; loopTime < endDate; loopTime.setTime(loopTime.valueOf() + 86400000)) {
			if (goal.readingDays[loopTime.getDay()] == 1) {
				daysLeft++;
			}
		}
		return daysLeft + 1;
	};

	this.calcPagesPerDay = function (entries, daysLeft, goal, fromToday) {
		var entry = 0;
		var today = new Date();
		if (entries.length == 1 && today != new Date(entries[0].date)) {
			entry = entries[0].page;
		} else if (entries.length > 1) {
			if (fromToday && new Date(entries[entries.length - 1]) == today) {
				entry = entries[entries.length - 1].page;
			} else if (new Date(entries[entries.length - 1].date) == today) {
				entry = entries[entries.length - 2].page;
			} else {
				entry = entries[entries.length - 1].page;
			}
		}
		var pages = goal.totalPages - entry;
		return Math.ceil(pages / (daysLeft));
	};

	this.compareDateToToday = function (date) {
		var today = new Date();
		today = new Date(today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate());
		var theDate = new Date(date);
		var rtnBool = false;
		if (today.valueOf() == theDate.valueOf()) {
			rtnBool = true;
		}
		return rtnBool;
	};

	this.calcPagesToday = function (entries, pagesperday) {
		var today = new Date();
		previousentry = 0;
		currententry = 0;
		if (entries.length == 1 && today == new Date(entries[0].date)) {
			currententry = entries[0].page;
		} else if (entries.length > 1) {
			if (this.compareDateToToday(entries[entries.length - 1].date)) {
				previousentry = entries[entries.length - 2].page;
				currententry = entries[entries.length - 1].page;
			} else {
				previousentry = entries[entries.length - 1].page;
				currententry = previousentry;
			}
		}
		pages = pagesperday - (currententry - previousentry);
		return Math.ceil(pages);
	};

	this.calcToPage = function (pagestoday, currentpage) {
		return currentpage + pagestoday;
	};

	this.updatePage = function (goal) {
		entries = bi.getEntries(goal.id);
		daysLeft = this.calcDaysLeft(goal);
		currentEntryPage = 0;
		if (entries.length > 0) {
			currentEntryPage = entries[entries.length - 1].page;
		}
		pagesLeft = goal.totalPages - currentEntryPage;
		pagesperday = this.calcPagesPerDay(entries, daysLeft, goal, false);
		pagestoday = this.calcPagesToday(entries, pagesperday);
		if (pagestoday < 0) {
			pagesperday = this.calcPagesPerDay(entries, daysLeft, goal, true);
			pagestoday = -1 * pagestoday;
		}

		$("#entrylist").html('');
		for (var i in entries) {
			date = new Date(entries[i].date);
			dateStr = this.days[date.getDay()] + ' ' + this.months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
			$("#entrylist").append("<li>Page " + entries[i].page + " on " + dateStr + "</li>");
		}
		$("#goaldetail h1").text(goal.name);
		$("#currentEntryDiv").attr('name', goal.id);
		$("#currentEntry").val(currentEntryPage);
		$("#daysleft").text(daysLeft);
		$("#pagesleft").text(pagesLeft);
		$("#pagestoday").text(pagestoday);
		$("#pagesperday").text(pagesperday);
		$("#topage").text(this.calcToPage(pagestoday, currentEntryPage));
		
	};
};

function saveGoal() {
	var newGoal = new Goal();
	newGoal.name = $("#editgoalname").val();
	newGoal.totalPages = Number($("#editgoaltotalpages").val());
	newGoal.startDate = $("#editgoalstartdate").val();
	newGoal.endDate = $("#editgoalenddate").val();
	
	var sun = $("#readingdaysunday").prop('checked');
	var mon = $("#readingdaymonday").prop('checked');
	var tue = $("#readingdaytuesday").prop('checked');
	var wed = $("#readingdaywednesday").prop('checked');
	var thu = $("#readingdaythursday").prop('checked');
	var fri = $("#readingdayfriday").prop('checked');
	var sat = $("#readingdaysaturday").prop('checked');
	newGoal.readingDays = [Number(sun), Number(mon), Number(tue), Number(wed), Number(thu), Number(fri), Number(sat)];

	bi.saveGoal(newGoal);
	$("#editgoalname").val('');
	$("#editgoaltotalpages").val('');
	$("#editgoalstartdate").val('');
	$("#editgoalenddate").val('');
	$("#goaledit").hide();
	$("#goalinfo").show();
	loadPage();
	return false;
}

function loadPage() {
	$("#booklist").html('');
	bi = new ConfigInterface();
	goalData = bi.getGoals();
	bookkeeper = new Bookkeeper();

	first = false;
	$.each(goalData, function(index, goal) {
		if (!first) {
			bookkeeper.updatePage(goal);
			$("#currentEntry").focus();
			first = true;
		}
		$('#booklist').append('<li><a id="book' + index + '" name="goal' + goal.id + '" class="booklink" href="#">' + goal.name + '</a></li>');
	});

	$(".booklink").click(function () {
		index = $(this)[0].id.substring(4);
		bookkeeper.updatePage(goalData[index]);
		$("#goaledit").hide();
		$("#goalinfo").show();
		$("#currentEntry").focus();
		return false;
	});

	$("#addBook").click(function () {
		$("#goaldetail h1").text('New Goal');
		$("input:checkbox").prop('checked', true);
		$("#goalinfo").hide();
		$("#goaledit").show();
		return false;
	});

	$("#currentEntry").change(function () {
		entry = new Entry();
		entry.goalId = Number($(this).parent().attr('name'));
		entry.page = Number($(this).val());
		today = new Date();
		entry.date = today.getFullYear() + '-' + bookkeeper.makeTwoDigits(today.getMonth() + 1) + '-' + bookkeeper.makeTwoDigits(today.getDate());
		oldEntry = bi.getEntryByDate(entry.goalId, entry.date);
		console.log(oldEntry);
		if (oldEntry.id != 0) {
			entry.id = Number(oldEntry.id);
		}
		bi.saveEntry(entry);
		console.log(entry.goalId);
		console.log(bi.getGoal(entry.goalId));
		bookkeeper.updatePage(bi.getGoal(entry.goalId));
	});
}

$(document).ready(function () {
	loadPage();
});
