/**
 * IRS redesign: WordPress integration.
 *
 * wp_nav_menu cannot output the drawer's toggle buttons, so they are injected
 * here. This file is enqueued before irs-redesign.js so the markup is ready
 * before the main script binds to it.
 */
( function () {
	'use strict';

	var drawer = document.getElementById( 'irs-drawer' );
	if ( ! drawer ) {
		return;
	}

	var CHEVRON = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>';

	drawer.querySelectorAll( '.irs-m-nav > li.menu-item-has-children' ).forEach( function ( li ) {
		var sub = li.querySelector( '.sub-menu' );
		var link = li.querySelector( 'a' );
		if ( ! sub ) {
			return;
		}

		var btn = document.createElement( 'button' );
		btn.type = 'button';
		btn.className = 'irs-m-toggle';
		btn.setAttribute( 'aria-expanded', 'false' );
		btn.setAttribute( 'aria-label', 'Toggle submenu' + ( link ? ' for ' + link.textContent.trim() : '' ) );
		btn.innerHTML = CHEVRON;

		btn.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			e.stopPropagation();
			var open = btn.getAttribute( 'aria-expanded' ) === 'true';
			btn.setAttribute( 'aria-expanded', String( ! open ) );
			sub.style.maxHeight = open ? null : sub.scrollHeight + 'px';
		} );

		li.insertBefore( btn, sub );
	} );
}() );
