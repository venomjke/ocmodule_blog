$.fn.blog_tabs = function(options) {
	var selector = this;

	var defOptions = {
		selected : ''
	};

	options = $.extend({}, defOptions, options);
	
	this.each(function() {
		var obj = $(this); 
		
		$(obj.attr('href')).hide();
		
		$(obj).click(function() {
			$(selector).removeClass('selected');
			
			$(selector).each(function(i, element) {
				$($(element).attr('href')).hide();
			});
			
			$(this).addClass('selected');
			
			$($(this).attr('href')).show();
			
			return false;
		});
	});

	$(this).show();
	
	if( options.selected ){
		$(options.selected).click();
	} else {
		$(this).first().click();
	}

};