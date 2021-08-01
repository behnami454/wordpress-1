jQuery(document).ready(function($) {
	$('.inside-navigation .search-item').on('click', function(e) {
      $('body').toggleClass("navsearchon"); //you can list several class names 
      e.preventDefault();
    });

	var wrapper = document.body;
	document.addEventListener( 'keydown', function( event ) {
		var modal, elements, selectors, lastEl, firstEl, activeEl, tabKey, shiftKey, escKey;
		if ( ! wrapper.classList.contains( 'navsearchon' ) ) {
			return;
		}
	
		modal = document.querySelector( '.inside-navigation' );
		selectors = 'input, a, button';
		elements = modal.querySelectorAll( selectors );
		elements = Array.prototype.slice.call( elements );
		tabKey = event.keyCode === 9;	
		shiftKey = event.shiftKey;
		escKey = event.keyCode === 27;
		activeEl = document.activeElement; // eslint-disable-line @wordpress/no-global-active-element
		lastEl = elements[ elements.length - 1 ];
		firstEl = elements[0];
	
		if ( ! shiftKey && tabKey && lastEl === activeEl ) {
			event.preventDefault();
			firstEl.focus();
		}
	
		if ( shiftKey && tabKey && firstEl === activeEl ) {
			event.preventDefault();
			lastEl.focus();
		}
	
		// If there are no elements in the menu, don't move the focus
		if ( tabKey && firstEl === lastEl ) {
			event.preventDefault();
		}
	} );

});