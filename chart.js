function Chart() {
	this.canvas;
	this.context;

	this.startDate = '16 Jun 2011';
	this.endDate = '25 Jun 2011';

	this.numPages = 855;
	this.entries = [5, 32, 68, 71, 90, 111, 147, 162, 180, 195, 250, 300, 490, 700, 800];
	this.numDays = 25;

	this.draw = function() {
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

		// draw the hatches
		var x = this.minX;
		var step_size = this.displayWidth / this.numDays;
		var y_size = this.displayHeight / this.numPages;

		c.strokeStyle = "#ccc";
		c.beginPath();
		for (x=step_size+this.minX; x<=this.maxX; x+=step_size) {
			c.moveTo(x, this.maxY + 8);
			c.lineTo(x, this.maxY + 15);
		}
		x = this.minX - 8;
		var pages_step = (this.numPages + (100 - this.numPages % 100)) / this.displayHeight * 5;
		console.log(this.displayHeight, pages_step);
		for (dy = this.minY; dy <= this.maxY; dy += pages_step) {
			c.moveTo(x, dy);
			c.lineTo(x - 7, dy);
		}
		c.stroke();
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
