// AJAX Functions

jQuery(document).ready( function() {
	var j = jQuery;

	/**** Page Load Actions **********************/

	/* Link filter and scope set. */
	bp_init_objects( [ 'links' ] );

	/* Clear cookies on logout */
	j('a.logout').click( function() {
		j.cookie('bp-links-scope', null );
		j.cookie('bp-links-filter', null );
		j.cookie('bp-links-extras', null );
	});

	/**** Directory ******************************/

	var extras = bpl_split_extras( j.cookie('bp-links-extras' ) );

	// set currently selected category filter
	if ( extras && extras.category_id ) {
		j('select#links-category-filter').val( extras.category_id );
	}

	/* When the category filter select box is changed, re-query */
	j('select#links-category-filter').change( function(e)
	{
		var categ = j(this);
		
		if ( categ.val().length ) {
			bpl_filter_request({
				'extras': { 'category_id': categ.val() }
			});
		}

		e.preventDefault();
		e.stopPropagation();
	});

	/* When the order  select box is changed, re-query */
	j('select#links-order-by').change( function(e)
	{
		bpl_filter_request({
			'filter': j(this).val()
		});

		e.preventDefault();
		e.stopPropagation();
	});
	
	/**** Links Navigation *********************/
	
	j('div#link-dir-pag a').live( 'click', function( e ) {
		// page num is 1 by default
		var page_number = 1;
		// determine *real* page num
		if ( j(this).hasClass('next') ) {
			page_number = Number( j(this).siblings('span.current').html() ) + 1;
		} else if ( j(this).hasClass('prev') ) {
			page_number = Number( j(this).siblings('span.current').html() ) - 1;
		} else {
			page_number = Number( j(this).html() );
		}
		// send ajax request
		bpl_filter_request({
			'page': page_number
		});
		// kill any other events
		e.preventDefault();
		e.stopPropagation();
	});

	/**** Lightbox ****************************/

	j("a.link-play").live('click',
		function(e) {

			var link = j(this).attr('id')
			link = link.split('-');

			j.post( ajaxurl, {
				action: 'link_lightbox',
				'cookie': encodeURIComponent(document.cookie),
				'link_id': link[2]
			},
			function(response)
			{
				var rs = bpl_split_response(response);

				if ( rs[0] >= 1 ) {
					j.colorbox({
						html: rs[1],
						maxWidth: '90%',
						maxHeight: '90%',
						scalePhotos: false
					});
				}
			});

			e.preventDefault();
			return;
		}
	);

	/**** Voting ******************************/

	j("div.link-vote-panel a.vote").live('click',
		function(e) {

			bpl_get_loader().toggle();

			var link = j(this).attr('id')
			link = link.split('-');

			j.post( ajaxurl, {
				action: 'link_vote',
				'cookie': encodeURIComponent(document.cookie),
				'_wpnonce': j("input#_wpnonce-link-vote").val(),
				'up_or_down': link[1],
				'link_id': link[2]
			},
			function(response)
			{
				var rs = bpl_split_response(response);

				j("div#link-vote-panel-" + link[2]).fadeOut(200,
					function() {
						bpl_remove_msg();

						if ( rs[0] <= -1 ) {
							bpl_list_item_msg(link[2], 'error', rs[1]);
						} else if ( rs[0] == 0 ) {
							bpl_list_item_msg(link[2], 'updated', rs[1]);
						} else {
							bpl_list_item_msg(link[2], 'updated', rs[1]);
							j("div.link-vote-panel div#vote-total-" + link[2]).html(rs[2]);
							j("div.link-vote-panel span#vote-count-" + link[2]).html(rs[3]);
						}

						j("div#link-vote-panel-" + link[2]).fadeIn(200);
					}
				);

				bpl_get_loader().toggle();
			});

			e.preventDefault();
			return;
		}
	);

});

/*** Helpers **************************************************************/

function bpl_filter_request( options )
{
	var settings = {
		'object': 'links',
		'filter': jQuery.cookie('bp-links-filter'),
		'scope': jQuery.cookie('bp-links-scope'),
		'target': 'div.links',
		'search': jQuery('#links_search').val(),
		'page': 1,
		'extras': bpl_split_extras( jQuery.cookie('bp-links-extras') )
	}

	jQuery.extend( true, settings, options );

	bp_filter_request(
		settings.object,
		settings.filter,
		settings.scope,
		settings.target,
		settings.search,
		settings.page,
		bpl_join_extras( settings.extras )
	);
}

function bpl_get_loader(id)
{
	var x_id = (id) ? '#' + id : null;
	return jQuery('.ajax-loader' + x_id);
}

function bpl_split_response(str)
{
	return str.split('[[split]]');
}

function bpl_remove_msg()
{
	jQuery('#message').remove();
}

function bpl_list_item_msg(lid, type, msg)
{
	jQuery('ul#link-list li#linklistitem-' + lid)
		.prepend('<div id="message" class="' + type + ' fade"><p>' + msg + '</p></div>');
}

function bpl_join_extras( obj )
{
	var items = [];

	jQuery.each( obj, function(k,v) {
		items.push( k + ':' + v );
	});

	return items.join('|');
}

function bpl_split_extras( string )
{
	var items = (string) ? string.split( '|' ) : [],
		object = {},
		next;

	jQuery.each( items, function(k,v) {
		next = v.split( ':' );
		object[ next[0] ] = next[1];
	});

	return object;
}
