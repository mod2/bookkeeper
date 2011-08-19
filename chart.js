/* Chart-drawing code */

/* From http://vetruvet.blogspot.com/2010/10/drawing-dashed-lines-on-html5-canvas.html */
CanvasRenderingContext2D.prototype.dashedLine=function(d,e,g,h,a){if(a==undefined)a=2;this.beginPath();this.moveTo(d,e);var b=g-d,c=h-e;a=Math.floor(Math.sqrt(b*b+c*c)/a);b=b/a;c=c/a;for(var f=0;f++<a;){d+=b;e+=c;this[f%2==0?"moveTo":"lineTo"](d,e)}this[f%2==0?"moveTo":"lineTo"](g,h);this.stroke();this.closePath()};

function Chart(numPages, numDays, entries, canvas, context) {
	this.canvas = canvas;
	this.context = context;
	this.numPages = numPages;
	this.numDays = numDays;
	this.entries = entries;

	this.draw = function() {
		var BORDERSTYLE = "#999";
		var TICKSTYLE = "#ddd";
		var TICKLENGTH = 4;
		var LABELMARGIN = 5;

		cv = this.canvas;
		c = this.context;

		this.xmargin = 35;
		this.ymargin = 15;
		this.minX = this.xmargin;
		this.minY = this.ymargin;
		this.maxX = cv.width - this.xmargin;
		this.maxY = cv.height - this.ymargin;
		this.displayWidth = this.maxX - this.minX;
		this.displayHeight = this.maxY - this.minY;

		// clear the canvas
		c.fillStyle = "#fff";
		c.fillRect(0, 0, cv.width, cv.height);

		// draw the lines
		c.strokeStyle = BORDERSTYLE;
		c.beginPath();
		c.strokeRect(this.minX, this.minY, this.displayWidth, this.displayHeight);
		c.closePath();

		// draw the ticks
		var x = this.minX;
		var step_size = this.displayWidth / this.numDays;
		var y_size = this.displayHeight / this.numPages;

		c.strokeStyle = TICKSTYLE;
		c.beginPath();
		for (x=step_size+this.minX; x<=this.maxX; x+=step_size) {
			c.moveTo(x, this.maxY);
			c.lineTo(x, this.maxY + TICKLENGTH);
		}
		var pages_step = (this.numPages + (100 - this.numPages % 100)) / this.displayHeight * 5;
		for (dy = this.minY; dy <= this.maxY; dy += pages_step) {
			c.moveTo(this.minX, dy);
			c.lineTo(this.maxX, dy);
		}
		c.stroke();
		c.closePath();

		// draw labels
		c.font = "9x helvetica";
		c.textAlign = "right";
		c.textBaseline = "middle";
		c.fillStyle = BORDERSTYLE;
		c.fillText("0", this.minX - LABELMARGIN, this.maxY);
		c.fillText("100", this.minX - LABELMARGIN, this.maxY - pages_step);
		c.fillText("200", this.minX - LABELMARGIN, this.maxY - (pages_step * 2));
		c.fillText("300", this.minX - LABELMARGIN, this.maxY - (pages_step * 3));
		c.fillText("400", this.minX - LABELMARGIN, this.maxY - (pages_step * 4));
		c.fillText("500", this.minX - LABELMARGIN, this.maxY - (pages_step * 5));
		c.fillText("600", this.minX - LABELMARGIN, this.maxY - (pages_step * 6));
		c.fillText("700", this.minX - LABELMARGIN, this.maxY - (pages_step * 7));
		c.fillText("800", this.minX - LABELMARGIN, this.maxY - (pages_step * 8));
		c.fillText("900", this.minX - LABELMARGIN, this.maxY - (pages_step * 9));
		c.closePath();

		c.textAlign = "left";
		c.fillText("0%", this.maxX + LABELMARGIN, this.maxY);
		c.fillText("50%", this.maxX + LABELMARGIN, this.maxY - (this.displayHeight / 2));
		c.fillText("100%", this.maxX + LABELMARGIN, this.minY);

		c.textBaseline = "top";
		c.textAlign = "center";
		x = this.minX;
		for (i=0; i<this.numDays; i++) {
			y = this.maxY + LABELMARGIN;
			c.fillText(i, x, y);
			c.closePath();
			x += step_size;
		}

		// first draw the goal
		c.beginPath();
		c.strokeStyle = "rgba(0, 0, 255, 0.2)";
		c.lineWidth = 1;
		c.dashedLine(this.minX, this.maxY, this.maxX, this.minY, 5);
		c.stroke();
		c.closePath();

		// now the lines
		c.beginPath();
		c.strokeStyle = "#000";
		c.fillStyle = "#000";

		x = this.minX;
		y = this.maxY;
		c.moveTo(x, y);

		c.fillStyle = "rgba(0, 0, 0, 0.2)";
		for (i in this.entries) {
			y = this.maxY - (this.entries[i] * y_size);
			c.lineTo(x, y);
			x += step_size;
		}
		console.log(this.displayWidth, this.displayHeight);
		c.stroke();
		c.lineTo(this.maxX, y);
		c.lineTo(this.maxX, this.maxY);
		c.fill();
		c.closePath();
		
		c.fillStyle = "#000";
		x = this.minX;
		for (i in this.entries) {
			y = this.maxY - (this.entries[i] * y_size);
			c.beginPath();
			c.arc(x, y, 2, 0, Math.PI * 2, false);
			c.fill();
			c.closePath();
			x += step_size;
		}
	};

	this.draw();
}
