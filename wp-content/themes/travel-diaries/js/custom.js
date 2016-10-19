jQuery(document).ready(function ($) {
	
    $('#site-navigation').meanmenu({
	    meanScreenWidth: "767",
	    meanRevealPosition: "center"
	});
    
    $('#top-menu-button').sidr({
      name: 'top-menu',
      source: '.top-menu'
    });
    
    jcf.replaceAll();
});