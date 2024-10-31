jQuery(function($) {
	
	//Fade-in shortcodes if within viewport
	function updateFadeInContent()
	{
		var windowTop = $(window).scrollTop();
		var windowBottom = windowTop + $(window).height();
		$(window.document.body).find('.prothemeswp-fade-in-shortcode').each(function(){
			if('hidden' != $(this).css('visibility')) {
				return;
			}
			var offset = $(this).offset();
			var top = offset.top;
			var bottom = offset.top + $(this).height();
			var duration = parseInt($(this).data('duration'));
			if(!duration) {
				$(this).css('opacity',0.0001).css('visibility','visible');
				return;
			}
			var delay = parseInt($(this).data('delay'));
			if(!delay) {delay=0;}
			var opacity = 0;
			var content = $(this);
			var intervalId;		
			if((top>=windowTop && top<=windowBottom) || (bottom>=windowTop && bottom<=windowBottom)) {
				$(this).css('opacity',0.0001).css('visibility','visible');
				var timeoutId = setTimeout(function() {
							content.data('prothemeswpFadeInShortcodeTimeout', '');
							intervalId = setInterval(updateContent, duration*1000/100);	
						    content.data('prothemeswpFadeInShortcodeInterval', intervalId);
							}, delay*1000);	
				content.data('prothemeswpFadeInShortcodeTimeout', timeoutId);
				function updateContent() {
					if (opacity == 100) {
						 content.css('opacity',1);
						  clearInterval(intervalId);
						  $(this).data('prothemeswpFadeInShortcodeInterval','');
						  return;
					}
					 content.css('opacity',opacity/100);
					 content.css('filter', 'alpha(opacity='+opacity+')');
					 opacity++;
				  }
			}
		});
	}
	
	$(window).scroll(updateFadeInContent);
	updateFadeInContent(window)
	
});