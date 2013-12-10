/* Chart-drawing code */

/* From http://vetruvet.blogspot.com/2010/10/drawing-dashed-lines-on-html5-canvas.html */
CanvasRenderingContext2D.prototype.dashedLine=function(d,e,g,h,a){if(a==undefined)a=2;this.beginPath();this.moveTo(d,e);var b=g-d,c=h-e;a=Math.floor(Math.sqrt(b*b+c*c)/a);b=b/a;c=c/a;for(var f=0;f++<a;){d+=b;e+=c;this[f%2==0?"moveTo":"lineTo"](d,e)}this[f%2==0?"moveTo":"lineTo"](g,h);this.stroke();this.closePath()};

function Chart(numPages, numDays, entries, drawGoalLines, canvas, context) {
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
		DAYLABELS = 5;
		LABELMARGIN = 5;
		XMARGIN = 45;
		YMARGIN = 15;
		FONTSIZE = 10;
		MINIFONTSIZE = 9;
		NODESIZE = 2;
		BGLINEWIDTH = 1;
		LINEWIDTH = 1;

		if (window.devicePixelRatio == 2) {
			XMARGIN = 60;
			YMARGIN = 35;
			LABELMARGIN *= 2;
			FONTSIZE = 18;
			MINIFONTSIZE = 14;
			NODESIZE = 4;
			BGLINEWIDTH = 1;
			LINEWIDTH = 2;
		}

		// if we've gone over the goal date, just display however many entries we have
		if (this.entries.length > this.numDays) {
			this.numDays = this.entries.length;
		}
		this.numDays += 1; // TODO: make sure this works

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
		tick_step = this.displayWidth / (this.numDays - 2);
		page_step = this.displayHeight / this.numPages;

		// clear the canvas
		c.fillStyle = "#fff";
		c.fillRect(0, 0, cv.width, cv.height);

		// draw the border
		c.strokeStyle = BORDERSTYLE;
		c.lineWidth = BGLINEWIDTH;
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

		// set up some font stuff
		c.font = FONTSIZE + "px helvetica";
		c.textBaseline = "middle";
		c.fillStyle = BORDERSTYLE;
		midY = this.minY + this.displayHeight / 2;

		// draw left side labels (pages)
		c.textAlign = "right";
		c.fillText("p. " + this.numPages, this.minX - LABELMARGIN, this.minY);
		c.fillText("p. " + Math.round(this.numPages / 2), this.minX - LABELMARGIN, midY);
		c.fillText("p. 1", this.minX - LABELMARGIN, this.maxY);
		c.closePath();

		// draw right side labels (percentage)
		c.textAlign = "left";
		c.fillText("0%", this.maxX + LABELMARGIN, this.maxY);
		c.fillText("50%", this.maxX + LABELMARGIN, midY);
		c.fillText("100%", this.maxX + LABELMARGIN, this.minY);

		/*
		// and the bottom labels (days)
		c.textAlign = "center";
		c.textBaseline = "top";
		if (this.numDays < DAYLABELS) {
			DAYLABELS = this.numDays;
		}
		daylabel_step = this.displayWidth / (DAYLABELS - 1);
		numdays_step = this.numDays / DAYLABELS;
		c.closePath();
		for (i=0; i<DAYLABELS; i++) {
			c.fillText(Math.round(i * numdays_step), this.minX + (i * daylabel_step), this.maxY + LABELMARGIN);
		}
		c.closePath();
		*/

		// draw the goal line
		if (drawGoalLines) {
			c.beginPath();
			c.strokeStyle = "rgba(0, 0, 0, 0.1)";
			c.lineWidth = BGLINEWIDTH;
			c.dashedLine(this.minX, this.maxY, this.maxX, this.minY, 5);
			c.stroke();
			c.closePath();
		}

		// Situations where they finished on the same day they started
		if (this.entries.length == 1) {
			pageY = this.maxY - this.entries[0].page * page_step;
			c.beginPath();
			c.moveTo(this.minX, this.maxY);
			c.lineTo(this.maxX, pageY);
			c.lineTo(this.maxX, this.maxY);
			c.fillStyle = "rgba(0, 0, 0, 0.2)";
			c.fill();
			c.closePath();

			// Line
			c.beginPath();
			c.moveTo(this.minX, this.maxY);
			c.lineTo(this.maxX, pageY);
			c.strokeStyle = "#000";
			c.stroke();
			c.closePath();

			// Dot
			c.fillStyle = "#000";
			c.beginPath();
			c.arc(this.maxX, pageY, NODESIZE, 0, Math.PI * 2, false);
			c.fill();
			c.closePath();
		} else if (this.entries.length > 1) {
			// now the lines for the entries
			x = this.minX;
			y = this.maxY;
			c.beginPath();
			c.moveTo(x, y);
			c.strokeStyle = "#000";
			c.lineWidth = LINEWIDTH;
			c.fillStyle = "rgba(0, 0, 0, 0.2)";
			for (i in this.entries) {
				y = this.maxY - (this.entries[i].page * page_step);
				x = this.minX + ((this.entries[i].day - 1) * tick_step);
				c.lineTo(x, y);
			}
			c.stroke();

			// and the fill line (with the shading)
			c.lineTo(this.maxX, y);
			c.lineTo(this.maxX, this.maxY);
			c.fill();
			c.closePath();
		
			// draw entry circles	
			c.fillStyle = "#000";
			c.textAlign = "center";
			c.textBaseline = "top";
			c.font = MINIFONTSIZE + "px Helvetica";
			for (i in this.entries) {
				y = this.maxY - (this.entries[i].page * page_step);
				x = this.minX + ((this.entries[i].day - 1) * tick_step);
				c.beginPath();
				c.arc(x, y, NODESIZE, 0, Math.PI * 2, false);
				/*
				if (i > 0 && i < this.entries.length - 1 && i % ENTRYSKIP == 0) {
					c.fillText(this.entries[i].page, x, y - (NODESIZE * 8));
				}
				*/
				c.fill();
				c.closePath();
			}

			// and the current goal
			if (drawGoalLines) {
				c.beginPath();
				c.strokeStyle = "rgba(255, 0, 0, 0.3)";
				c.dashedLine(this.minX + ((this.entries[i].day - 1) * tick_step), y, this.maxX, this.minY, 5);
				c.stroke();
				c.closePath();
			}
		}
	};

	this.draw();
}
