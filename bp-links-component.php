<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class BP_Links_Component extends BP_Component {

	function __construct()
	{
		parent::start(
			'links',
			_x( 'User Links', 'Link screen page <title>', 'buddypress-links' ),
			BP_LINKS_PLUGIN_DIR
		);

		$this->includes();

		bp_links_init_settings();

		buddypress()->active_components[$this->id] = '1';
	}

	function includes()
	{
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-core.php' );
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-embed.php' );
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-classes.php' );
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-ajax.php' );
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-templatetags.php' );
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-widgets.php' );
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-filters.php' );
		require ( BP_LINKS_PLUGIN_DIR . '/bp-links-dtheme.php' );

		do_action( 'bp_links_includes' );
	}

	function setup_globals()
	{
		$bp = buddypress();

		$global_tables = array(
			'table_name'				=> $bp->table_prefix . 'bp_links',
			'table_name_categories'		=> $bp->table_prefix . 'bp_links_categories',
			'table_name_votes'			=> $bp->table_prefix . 'bp_links_votes',
			'table_name_linkmeta'		=> $bp->table_prefix . 'bp_links_linkmeta',
		);

		// Set up the $globals array to be passed along to parent::setup_globals()
		$globals = array(
			'slug'                  => BP_LINKS_SLUG,
			'root_slug'             => isset( $bp->pages->{$this->id}->slug ) ? $bp->pages->{$this->id}->slug : BP_LINKS_SLUG,
			'has_directory'         => true, // Set to false if not required
			'notification_callback' => 'bp_links_format_notifications',
			'search_string'         => __( 'Search Links...', 'buddypress-links' ),
			'global_tables'         => $global_tables
		);

		// Let BP_Component::setup_globals() do its work.
		parent::setup_globals( $globals );

		$this->forbidden_names =
			apply_filters( 'bp_links_forbidden_names',
				array(
					// internal
					'add',
					'admin',
					'all',
					'create',
					'delete',
					'feed',
					'links',
					'my-links',
					'submit',
					// order by filter
					'active',
					'high-votes',
					'most-votes',
					'newest',
					'popular',
					// category url slug
					BP_LINKS_CAT_URL_SLUG
				)
			);
		
	}

	function setup_nav( $main_nav = array(), $sub_nav = array() )
	{
		// nothing special yet
		parent::setup_nav( $main_nav, $sub_nav );
	}

}

function bp_links_setup_component()
{	
	buddypress()->links = new BP_Links_Component();
}
add_action( 'bp_loaded', 'bp_links_setup_component' );