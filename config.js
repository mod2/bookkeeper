var ConfigInterface = function() {
	this.host = 'http://bc.local/sandbox/bookkeeper/example-backend/';

	this.getGoals = function () {
		var goals = [];
		$.ajax({
			url: this.host + 'books.php',
			async: false,
			dataType: 'json',
			success: function (data) {
				$.each(data, function(index, goal) {
					newGoal = new Goal();
					newGoal.id = goal.id;
					newGoal.name = goal.name;
					newGoal.totalPages = goal.totalPages;
					newGoal.startDate = goal.startDate;
					newGoal.endDate = goal.endDate;
					newGoal.readingDays = goal.readingDays;
					goals.push(newGoal);
				});
			}
		});
		return goals;
	};

	this.getGoal = function (goalId) {
		var goal = new Goal();
		$.ajax({
			url: this.host + 'goal.php',
			data: {id: goalId},
			async: false,
			dataType: 'json',
			success: function (data) {
				goal.id = data.id;
				goal.name = data.name;
				goal.totalPages = data.totalPages;
				goal.startDate = data.startDate;
				goal.endDate = data.endDate;
				goal.readingDays = data.readingDays;
			}
		});
		return goal;
	};
	
	this.getEntries = function (goalId) {
		var entries = [];
		$.ajax({
			url: this.host + 'entries.php',
			data: {id: goalId},
			async: false,
			dataType: 'json',
			success: function (data) {
				$.each(data, function(index, entry) {
					newEntry = new Entry();
					newEntry.id = entry.id;
					newEntry.goalId = entry.goalId;
					newEntry.page = entry.page;
					newEntry.date = entry.date;
					entries.push(newEntry);
				});
			}
		});
		return entries;
	};

	this.getEntry = function (entryId) {
	};

	this.getEntryByDate = function (goalId, date) {
		var newEntry = new Entry();
		$.ajax({
			url: this.host + 'entry.php',
			data: {goalid: goalId, date: date},
			async: false,
			dataType: 'json',
			success: function (data) {
				newEntry.id = data.id;
				newEntry.goalId = data.goalId;
				newEntry.page = data.page;
				newEntry.date = data.date;
			}
		});
		return newEntry;
	};

	this.saveGoal = function (goal) {
		$.ajax({
			url: this.host + 'savegoal.php',
			data: {id: goal.id, name: goal.name, totalpages: goal.totalPages, startdate: goal.startDate, enddate: goal.endDate, readingdays: String(goal.readingDays).replace(/,/g,'')},
			async: false,
			dataType: 'json'
		});
	};
	
	this.saveEntry = function (entry) {
		$.ajax({
			url: this.host + 'saveentry.php',
			data: {id: entry.id, goalid: entry.goalId, page: entry.page, date: entry.date},
			async: false,
			dataType: 'json'
		});
	};
};

var Goal = function() {
	this.id = 0;
	this.name = '';
	this.totalPages = 0;
	this.startDate = '';
	this.endDate = '';
	this.readingDays = [1,1,1,1,1,1,1]
};

var Entry = function() {
	this.id = 0;
	this.goalId = 0;
	this.page = 0;
	this.date = '';
};

