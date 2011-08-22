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
		// settings
		BORDERSTYLE = "#999";
		BGLINESTYLE = "#ddd";
		NUMLINES = 10;
		TICKSTYLE = "#ccc";
		TICKLENGTH = 4;
		DAYLABELS = 5;
		LABELMARGIN = 5;
		XMARGIN = 35;
		YMARGIN = 15;

		cv = this.canvas;
		c = this.context;

		// set up the display boundaries
		this.minX = XMARGIN;
		this.minY = YMARGIN;
		this.maxX = cv.width - XMARGIN;
		this.maxY = cv.height - YMARGIN;
		this.displayWidth = this.maxX - this.minX;
		this.displayHeight = this.maxY - this.minY;

		// set up intervals
		bgline_step = this.displayHeight / NUMLINES;
		day_step = this.displayWidth / this.numDays;
		tick_step = this.displayWidth / this.numDays;
		page_step = this.displayHeight / this.numPages;

		// clear the canvas
		c.fillStyle = "#fff";
		c.fillRect(0, 0, cv.width, cv.height);

		// draw the border
		c.strokeStyle = BORDERSTYLE;
		c.beginPath();
		c.strokeRect(this.minX, this.minY, this.displayWidth, this.displayHeight);
		c.closePath();

		// draw the background lines
		c.strokeStyle = BGLINESTYLE;
		c.beginPath();
		for (dy = this.minY + bgline_step; dy <= this.maxY; dy += bgline_step) {
			c.moveTo(this.minX, dy);
			c.lineTo(this.maxX, dy);
		}
		c.stroke();
		c.closePath();

		// draw the reading day ticks
 /*
		c.strokeStyle = TICKSTYLE;
		c.beginPath();
		for (x = this.minX + tick_step; x <= this.maxX; x += tick_step) {
			c.moveTo(x, this.maxY);
			c.lineTo(x, this.maxY + TICKLENGTH);
		}
		c.stroke();
		c.closePath();
		*/

		// set up some font stuff
		c.font = "9x helvetica";
		c.textBaseline = "middle";
		c.fillStyle = BORDERSTYLE;
		midY = this.minY + this.displayHeight / 2;

		// draw left side labels (pages)
		c.textAlign = "right";
		c.fillText(this.numPages, this.minX - LABELMARGIN, this.minY);
		c.fillText(Math.round(this.numPages / 2), this.minX - LABELMARGIN, midY);
		c.fillText("0", this.minX - LABELMARGIN, this.maxY);
		c.closePath();

		// draw right side labels (percentage)
		c.textAlign = "left";
		c.fillText("0%", this.maxX + LABELMARGIN, this.maxY);
		c.fillText("50%", this.maxX + LABELMARGIN, midY);
		c.fillText("100%", this.maxX + LABELMARGIN, this.minY);

		// and the bottom labels (days)
		c.textAlign = "center";
		c.textBaseline = "top";
		daylabel_step = this.displayWidth / DAYLABELS;
		numdays_step = this.numDays / DAYLABELS;
		for (i=0; i<=DAYLABELS; i++) {
			c.fillText(Math.round(i * numdays_step), this.minX + (i * daylabel_step), this.maxY + LABELMARGIN);
		}
		c.closePath();

		// draw the goal line
		c.beginPath();
		c.strokeStyle = "rgba(0, 0, 0, 0.1)";
		c.lineWidth = 1;
		c.dashedLine(this.minX, this.maxY, this.maxX, this.minY, 5);
		c.stroke();
		c.closePath();

		// now the lines for the entries
		x = this.minX;
		y = this.maxY;
		c.beginPath();
		c.moveTo(x, y);
		c.strokeStyle = "#000";
		c.fillStyle = "rgba(0, 0, 0, 0.2)";
		for (i in this.entries) {
			y = this.maxY - (this.entries[i] * page_step);
			c.lineTo(x, y);
			x += tick_step;
		}

		// and the fill line (with the shading)
		c.stroke();
		c.lineTo(this.maxX, y);
		c.lineTo(this.maxX, this.maxY);
		c.fill();
		c.closePath();
	
		// draw entry circles	
		c.fillStyle = "#000";
		x = this.minX;
		for (i in this.entries) {
			y = this.maxY - (this.entries[i] * page_step);
			c.beginPath();
			c.arc(x, y, 2, 0, Math.PI * 2, false);
			c.fill();
			c.closePath();
			x += tick_step;
		}

		// and the current goal
		c.beginPath();
		c.strokeStyle = "rgba(255, 0, 0, 0.3)";
		c.dashedLine(this.minX + (i * tick_step), y, this.maxX, this.minY, 5);
		c.stroke();
		c.closePath();
	};

	this.draw();
}