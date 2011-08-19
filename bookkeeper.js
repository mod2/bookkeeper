var goalData = undefined;

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

		$("#entries").html('');
		for (var i in entries) {
			date = new Date(entries[i].date);
			dateStr = date.getDate() + ' ' + this.months[date.getMonth()] + ' ' + date.getFullYear();
			$("#entries").append("<li>Page " + entries[i].page + " <span class='date'>(" + dateStr + ")</span></li>");
		}
		$("#view h1").text(goal.name);
		$("#goals").attr('name', goal.id);
		$("#currentEntry").val(currentEntryPage);
		$("#daysleft").text(daysLeft);
		$("#pagesleft").text(pagesLeft);
		$("#pagestoday").text(pagestoday);
		$("#pagesperday").text(pagesperday);
		$("#totalpages").text(goal.totalPages);
		$("#goaldate").text(goal.endDate);
		$("#topage").text(this.calcToPage(pagestoday, currentEntryPage));
	};
};

function saveGoal() {
	var newGoal = new Goal();
	newGoal.name = $("#editgoalname").val();
	newGoal.totalPages = Number($("#editgoaltotalpages").val());
	newGoal.startDate = $("#editgoalstartdate").val();
	newGoal.endDate = $("#editgoalenddate").val();
	
	var sun = $("#readingdaysun").prop('checked');
	var mon = $("#readingdaymon").prop('checked');
	var tue = $("#readingdaytue").prop('checked');
	var wed = $("#readingdaywed").prop('checked');
	var thu = $("#readingdaythu").prop('checked');
	var fri = $("#readingdayfri").prop('checked');
	var sat = $("#readingdaysat").prop('checked');
	newGoal.readingDays = [Number(sun), Number(mon), Number(tue), Number(wed), Number(thu), Number(fri), Number(sat)];

	bi.saveGoal(newGoal);
	$("#editgoalname").val('');
	$("#editgoaltotalpages").val('');
	$("#editgoalstartdate").val('');
	$("#editgoalenddate").val('');
	$("#edit").hide();
	$("#view").show();
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

		/* TODO: make this live */
		entries = bi.getEntries(goal.id);
		currentEntryPage = 0;
		if (entries.length > 0) {
			currentEntryPage = entries[entries.length - 1].page;
		}
		goal.pagesleft = goal.totalPages - currentEntryPage;
		goal.percent = Math.round((currentEntryPage / goal.totalPages) * 100);

		$('#booklist').append('<li><a id="book' + index + '" name="goal' + goal.id + '" class="booklink" href="#">' + goal.name + '<div class="percentage"><div class="percentage_container"><div class="percent" style="width: ' + goal.percent + 'px;"></div></div><span><b>' + goal.percent + '%</b> (' + goal.pagesleft + ' pages left)</span></div></a></li>');
	});
	$("#booklist li:first-child").addClass("selected");

	$(".booklink").click(function () {
		$("#booklist li.selected").removeClass("selected");
		$(this).parent().addClass("selected");

		index = $(this)[0].id.substring(4);
		bookkeeper.updatePage(goalData[index]);
		$("#edit").hide();
		$("#view").show();
		$("#currentEntry").focus();
		return false;
	});

	$("#addBook").click(function () {
		$("input:checkbox").prop('checked', true);
		$("#view").hide();
		$("#edit").show();
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
