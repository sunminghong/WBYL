(function($) {
	$.fn.selectRange = function(start, end) {
		_this = this.get(0);
	    if(_this.setSelectionRange) {
	    	 setTimeout(function(){//fix firefox bug
	    		 _this.focus();
	    		 _this.setSelectionRange(start, end);
	    	 },0);
	    } else if(_this.createTextRange) {
	            var range = _this.createTextRange();
	            range.collapse(true);
	            range.moveEnd('character', end);
	            range.moveStart('character', start);
	            range.select();
	    }
	};
})(jQuery);