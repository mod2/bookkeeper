function Chart() {
	this.canvas;
	this.context;

	this.startDate = '16 Jun 2011';
	this.endDate = '25 Jun 2011';

	this.numPages = 855;
	this.entries = [5, 32, 68, 71, 90, 111, 147, 162, 180, 195, 250, 300, 490, 700, 800];
	this.numDays = 25;

	this.draw = function() {
		var BORDERSTYLE = "#999";
		var TICKSTYLE = "#bbb";
		var TICKLENGTH = 6;
		var LABELMARGIN = 5;

		cv = this.canvas;
		c = this.context;

		this.margin = 35;
		this.minX = this.margin;
		this.minY = this.margin;
		this.maxX = cv.width - this.margin;
		this.maxY = cv.height - this.margin;
		this.displayWidth = this.maxX - this.minX;
		this.displayHeight = this.maxY - this.minY;

		// clear the canvas
		c.fillStyle = "#fff";
		c.fillRect(0, 0, cv.width, cv.height);

		// draw the lines
		c.strokeStyle = BORDERSTYLE;
		c.beginPath();
		c.moveTo(this.minX, this.minY);
		c.lineTo(this.minX, this.maxY);
		c.lineTo(this.maxX, this.maxY);
		c.stroke();
		c.closePath();

		// draw the ticks
		var x = this.minX;
		var step_size = this.displayWidth / this.numDays;
		var y_size = this.displayHeight / this.numPages;

		c.strokeStyle = TICKSTYLE;
		c.beginPath();
		for (x=step_size+this.minX; x<=this.maxX; x+=step_size) {
			c.moveTo(x, this.maxY);
			c.lineTo(x, this.maxY - TICKLENGTH);
		}
		x = this.minX;
		var pages_step = (this.numPages + (100 - this.numPages % 100)) / this.displayHeight * 5;
		for (dy = this.minY; dy <= this.maxY; dy += pages_step) {
			c.moveTo(x, dy);
			c.lineTo(x + TICKLENGTH, dy);
		}
		c.stroke();
		c.closePath();

		// draw labels
		c.font = "9x helvetica";
		c.textAlign = "right";
		c.textBaseline = "middle";
		c.fillStyle = BORDERSTYLE;
		c.fillText("1", this.minX - LABELMARGIN, this.maxY - 5);
		c.fillText("100", this.minX - LABELMARGIN, this.maxY - 5 - pages_step);
		c.fillText("200", this.minX - LABELMARGIN, this.maxY - 5 - (pages_step * 2));
		c.fillText("300", this.minX - LABELMARGIN, this.maxY - 5 - (pages_step * 3));
		c.fillText("400", this.minX - LABELMARGIN, this.maxY - 5 - (pages_step * 4));
		c.closePath();

		// first draw the goal
		c.beginPath();
		c.strokeStyle = "rgba(0, 0, 255, 0.3)";
		c.lineWidth = 1;
		c.moveTo(this.minX, this.maxY);
		c.lineTo(this.maxX, this.minY);
		c.stroke();
		c.closePath();

		// now the lines
		c.beginPath();
		c.strokeStyle = "#000";
		c.fillStyle = "#000";

		x = this.minX;
		y = this.maxY;
		c.moveTo(x, y);

		for (i in this.entries) {
			x += step_size;
			y = this.maxY - (this.entries[i] * y_size);
			c.lineTo(x, y);
		}
		c.stroke();
		c.closePath();

		x = this.minX;
		for (i in this.entries) {
			x += step_size;
			y = this.maxY - (this.entries[i] * y_size);
			c.beginPath();
			c.arc(x, y, 3, 0, Math.PI * 2, false);
			c.fill();
			c.closePath();
		}
	}
}

$(document).ready(function() {
	var canvas = document.getElementById("reading_chart");
	canvas.width = $("#reading_chart").width();
	canvas.height = $("#reading_chart").height();

	if (canvas.getContext) {
		var context = canvas.getContext('2d');
	}

	var chart = new Chart();
	chart.canvas = canvas;
	chart.context = context;
	chart.draw();
});
