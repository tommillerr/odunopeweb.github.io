(function ($){
	//$( 'p:empty' ).remove();
	/*function blogPost(){
	//	$(this).addClass('aaaaaaaaaaaaaaaaaaaaaaa');
		console.log('aaaaaaaaaaaaaaaaaaa');
	}*/

	function headerJs(){

		var $header = $(this);
			$navButton = $header.find('.header__nav-button'),
			$mobileMenu = $header.find('.header__menu-search-js');

		$navButton.on('click', function(){
			$mobileMenu.slideToggle(250);
			$header.toggleClass('menu-open');
		});
	}

	 /*var text = 'Some textRTTAnd some more\r\nAnd yet\rThis is the end'; // \n and \r are new line and carriage return characters (both new line characters for different platforms (mac, linux))
        var lines = text.split(/\r\n|\r|\n|RTT/); // matches at each occurence and (.split()) returns an array of the text split at each point 
        console.log(lines);
        console.log(text.split(/\s/));*/

	$(document).ready(function(){

		$('.header').each(headerJs);
	})
})(jQuery);