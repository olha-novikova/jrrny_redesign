var Timer = function(element, options) {
	this.element = element;
	this.options = options;
	element.text(options.starttime);
	var interval = setInterval(function() {
		if(parseInt(element.text()) !== options.endtime && options.starttime > options.endtime) {
			element.text(parseInt(element.text(), 10)-1);
		}
		else {
			clearInterval(interval);
			window.location.href = (options.redirect ? options.redirect : defineURL('home'));
		}
	}, 1000);
};