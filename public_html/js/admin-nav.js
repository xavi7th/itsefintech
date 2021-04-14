$( function () {
	/*
	-------------------------------------------------
	    Side Menu
	-------------------------------------------------
	*/
	var sideNav = $( '.main-navigation' );
	sideNavToggle = $( '.side-nav-toggle' );
	header = $( 'header' );
	main = $( 'main' );
	sideNavSub = $( '.nav-sub' );


	var sideNavShow = function () {
		sideNav.toggleClass( 'open-nav' );
		header.toggleClass( 'header-small' );
		$( 'html, body' ).toggleClass( 'overflow-hidden' );
	}

	sideNavToggle.click( function ( e ) {
		e.preventDefault();
		$( this ).toggleClass( 'closed' );
		sideNavShow();
	} );


	// side menu sub
	sideNavSub.find( '> a' ).click( function ( e ) {
		e.preventDefault();
		var $subMenu = $( this ).next( '.sub-menu' );
		if ( $subMenu.length < 1 )
			return;
		if ( $subMenu.is( ":visible" ) ) {
			$subMenu.slideUp( function () {
				$( '.menu > li.active' ).removeClass( 'active' );
				// console.log('1');
			} );
			$( this ).removeClass( 'active' );
			// console.log('2');

			return;
		}
		$( '.menu .sub-menu:visible' ).slideUp();
		$( '.menu li > a' ).removeClass( 'active' );
		$subMenu.slideToggle( function () {
			$( '.menu > li.active' ).removeClass( 'active' );
			// console.log('3');
		} );
		$( this ).addClass( 'active' );
	} );


	/*
	-------------------------------------------------
	    Full Screen Window
	-------------------------------------------------
	*/
	function toggleFullscreen( elem ) {
		elem = elem || document.documentElement;
		if ( !document.fullscreenElement && !document.mozFullScreenElement &&
			!document.webkitFullscreenElement && !document.msFullscreenElement ) {
			if ( elem.requestFullscreen ) {
				elem.requestFullscreen();
			} else if ( elem.msRequestFullscreen ) {
				elem.msRequestFullscreen();
			} else if ( elem.mozRequestFullScreen ) {
				elem.mozRequestFullScreen();
			} else if ( elem.webkitRequestFullscreen ) {
				elem.webkitRequestFullscreen( Element.ALLOW_KEYBOARD_INPUT );
			}
		} else {
			if ( document.exitFullscreen ) {
				document.exitFullscreen();
			} else if ( document.msExitFullscreen ) {
				document.msExitFullscreen();
			} else if ( document.mozCancelFullScreen ) {
				document.mozCancelFullScreen();
			} else if ( document.webkitExitFullscreen ) {
				document.webkitExitFullscreen();
			}
		}
	}

	$( '#fullScreen' ).click( function ( e ) {
		e.preventDefault();
		toggleFullscreen();
	} );

} );
