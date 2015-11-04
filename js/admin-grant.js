jQuery(document).ready(function($) {

	// Repeatable fields handling.
	$( '.cahnrswp-add-repeatable-meta' ).on( 'click', 'a', function(e) {
		
		e.preventDefault();

		var click_parent = $(this).parent(),
				added = click_parent.siblings( '.cahnrswp-repeatable-meta' ).first().clone(),
				attrs = 'name,id,for';

		console.log(click_parent);

		added.find( 'input' ).val( '' );
		click_parent.before( added );
		attrs = attrs.split( ',' );

		$(this).parent().siblings( '.cahnrswp-repeatable-meta' ).each(function( index ) {
			$(this).find( 'input, select, label' ).each(function() {
				for ( var i = 0; i < attrs.length; i++ ) {
					if ( undefined != $(this).attr( attrs[i] ) ) {
						$(this).attr( attrs[i], $(this).attr( attrs[i] ).replace( '0', index ) );
					}
				}
			});
		});

	});

});