var Mosaic = function(container, elements) {
	if (typeof jQuery !== "undefined" && jQuery !== null) {
		$ = jQuery.noConflict();
	}

	var top, left, container_height, i = 0;
	var generateMosaic = function() {
		if(i < $(elements).length) {
			element = $(elements+":eq("+i+")");
			element.fadeIn(50,function() {
			element.stop().animate({
				top:(i-2 >= 0) ? $(elements+":eq("+(i-2)+")").outerHeight(true)+$(elements+":eq("+(i-2)+")").position().top : element.css("top"),
				left: (i-1 >= 0 && i%2 !== 0) ? $(elements+":eq("+(i-1)+")").outerWidth(true) : element.css("left")
			}, 100, function() {
					i = i+1;
					if(element.prev(elements).length && element.outerHeight(true) + element.position().top < element.prev(elements).outerHeight(true) + element.prev(elements).position().top) {
						$(container).height(element.prev(elements).outerHeight(true) + element.prev(elements).position().top);
					}
					else {
						$(container).height(element.outerHeight(true) + element.position().top);
					}
					return generateMosaic();
				});
			});
		}
	};
	var regenerateMosaic = function() {
		i = 0;
		return generateMosaic();
	}
	var rebuildMosaic = function() {
		$(elements).each(function() {
			$(this).css("top", 0);
			$(this).css("left", 0);
			return regenerateMosaic();
		});
	}
	this.generateMosaic = generateMosaic;
	this.regenerateMosaic = regenerateMosaic;
	this.rebuildMosaic = rebuildMosaic;
};