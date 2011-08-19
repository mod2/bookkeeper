// implement the interface between Bookkeeper and your backend or datastore
var ConfigInterface = function() {
	this.getGoals = function () {
		var goals = [];
		// interact with the datastore/backend
		return goals;
	};

	this.getGoal = function (goalId) {
		var goal = new Goal();
		// interact with the datastore/backend
		return goal;
	};
	
	this.getEntries = function (goalId) {
		var entries = [];
		// interact with the datastore/backend
		return entries;
	};

	this.getEntry = function (entryId) {
		var entry = new Entry();
		// interact with the datastore/backend
		return entry;
	};

	this.getEntryByDate = function (goalId, date) {
		var newEntry = new Entry();
		// interact with the datastore/backend
		return newEntry;
	};

	this.saveGoal = function (goal) {
		// interact with the datastore/backend to save or insert a goal/book
	};
	
	this.saveEntry = function (entry) {
		// interact with the datastore/backend to save or insert an entry
	};
};

//****************************************************************************
//                  DON'T EDIT PAST THIS LINE
//****************************************************************************
// classes to represent the correct representation of a Goal and an Entry
// Don't edit these.
var Goal = function() {
	this.id = 0;
	this.name = '';
	this.totalPages = 0;
	this.startDate = '';
	this.endDate = '';
	this.readingDays = [1,1,1,1,1,1,1];
	this.hidden = 0;
};

var Entry = function() {
	this.id = 0;
	this.goalId = 0;
	this.page = 0;
	this.date = '';
};

