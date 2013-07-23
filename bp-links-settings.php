<?php

global $wpsf_settings;

// pro only vars
$dagger = ' <sup>&dagger;</sup>';
$pro_field_class = ( defined( 'BP_LINKS_PRO_VERSION' ) ) ? null : 'disabled';

// defaults
$defaults = array();

// global settings section
$wpsf_settings[] = array(
    'section_id' => 'global',
    'section_title' => __( 'Global Settings', 'buddypress-links' ),
//    'section_description' => '',
    'section_order' => 5,
    'fields' => array(
        array(
            'id' => 'avsize',
            'title' => __( 'List Avatar Size', 'buddypress-links' ),
            'desc' => __( 'Set the default avatar size for link lists.', 'buddypress-links' ),
            'type' => 'select',
            'std' => 100,
			'choices' => array(
				50 => 50,
				60 => 60,
				70 => 70,
				80 => 80,
				90 => 90,
				100 => 100,
				110 => 110,
				120 => 120,
				130 => 130
			)
        ),
        array(
            'id' => 'linklocal',
            'title' => __( 'Link to local page?', 'buddypress-links' ),
            'desc' => __( 'The default behavior is to link the title link to the local link page when a link is clicked. Set this to No to send them directly to the external url.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
        array(
            'id' => 'linkslug',
            'title' => __( 'Link to slug or ID?', 'buddypress-links' ),
            'desc' => __( 'The default behavior is to use the link\'s unique text slug in permalink URLs. Set this to Numeric ID to use the link ID instead.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => __( 'Text Slug', 'buddypress-links' ),
				0 => __( 'Numeric ID', 'buddypress-links' )
			)
        ),
        array(
            'id' => 'catslug',
            'title' => __( 'Category URL Slug', 'buddypress-links' ),
            'desc' => __( 'The default slug is "category"; Enter a different term to customize the category URL slug.', 'buddypress-links' ),
            'type' => 'text',
            'std' => 'category'
        ),
        array(
            'id' => 'ordertext',
            'title' => __( 'Custom Order By', 'buddypress-links' ),
            'desc' => __( 'To override the default order and/or text of the Order By filter options, edit or remove them above. To reset them to the default, delete the entire contents of the box.', 'buddypress-links' ),
            'type' => 'textarea',
            'std' => bp_links_settings_validate_order_text( null )
        )
    )
);

// directory settings section
$wpsf_settings[] = array(
    'section_id' => 'directory',
    'section_title' => __( 'Directory Settings', 'buddypress-links' ),
//    'section_description' => '',
    'section_order' => 7,
    'fields' => array(
        array(
            'id' => 'mylinks',
            'title' => __( 'My Links Tab', 'buddypress-links' ),
            'desc' => __( 'The default behavior is to show the My Links tab. Set this to "No" to hide the My Links tab.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
        array(
            'id' => 'maxtabs',
            'title' => __( 'Maximum Tabs', 'buddypress-links' ) . $dagger,
            'desc' => __( 'Set the maximum number of tabs to display on directory top navigation to enable magic tabs.', 'buddypress-links' ),
            'type' => 'select',
            'class' => $pro_field_class,
            'std' => false,
			'choices' => array(
				0 => 'Disabled',
				3 => 3,
				4 => 4,
				5 => 5,
				6 => 6,
				7 => 7,
				8 => 8,
				9 => 9,
				10 => 10,
				11 => 11,
				12 => 12,
				13 => 13,
				14 => 14,
				15 => 15
			)
        ),
        array(
            'id' => 'cattabs',
            'title' => __( 'Category Tabs', 'buddypress-links' ) . $dagger,
            'desc' => __( 'The default behavior is to filter categories using a select box. Set this to "Yes" to add a tab for each category to the directory navigation instead.', 'buddypress-links' ),
            'type' => 'radio',
            'class' => $pro_field_class,
            'std' => false,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
        array(
            'id' => 'ordertabs',
            'title' => __( 'Order By Tabs', 'buddypress-links' ) . $dagger,
            'desc' => __( 'The default behavior is to change sorting order using a select box. Set this to "Yes" to add a tab for each order option to the directory sub-navigation instead.', 'buddypress-links' ),
            'type' => 'radio',
            'class' => $pro_field_class,
            'std' => false,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        )
    )
);

// content settings section
$wpsf_settings[] = array(
    'section_id' => 'content',
    'section_title' => __( 'Content Settings', 'buddypress-links' ),
//    'section_description' => '',
    'section_order' => 10,
    'fields' => array(
        array(
            'id' => 'dupeurl',
            'title' => __( 'Allow Duplicate URLs?', 'buddypress-links' ),
            'desc' => __( 'Set this to "No" to prevent duplicate URLs from being added to the directory.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
        array(
            'id' => 'maxurl',
            'title' => __( 'Max. URL Characters', 'buddypress-links' ),
            'desc' => __( 'Set this to the maximum number of characters allowed for a link URL. (Must be 255 or lower)', 'buddypress-links' ),
            'type' => 'text',
            'std' => 255
        ),
        array(
            'id' => 'maxname',
            'title' => __( 'Max. Name Characters', 'buddypress-links' ),
            'desc' => __( 'Set this to the maximum number of characters allowed for a link name/title. (Must be 255 or lower)', 'buddypress-links' ),
            'type' => 'text',
            'std' => 125
        ),
        array(
            'id' => 'maxdesc',
            'title' => __( 'Max. Description Characters', 'buddypress-links' ),
            'desc' => __( 'Set this to the maximum number of characters allowed for a link description.', 'buddypress-links' ),
            'type' => 'text',
            'std' => 500
        ),
		array(
            'id' => 'reqdesc',
            'title' => __( 'Is description required?', 'buddypress-links' ),
            'desc' => __( 'By default, every link must have a description. Set this to No to allow empty descriptions.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
		array(
            'id' => 'catselect',
            'title' => __( 'Category Input Type', 'buddypress-links' ),
            'desc' => __( 'The default behavior is to use radio buttons to display categories on the create form. Set this to Select to use a select box instead.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => false,
			'choices' => array(
				0 => 'Radio',
				1 => 'Select'
			)
        ),
		array(
            'id' => 'pagefetch',
            'title' => __( 'Enable Page Fetching?', 'buddypress-links' ),
            'desc' => __( 'Set this to "No" to disable page content fetching.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
		array(
            'id' => 'editavatar',
            'title' => __( 'Show Avatar Options?', 'buddypress-links' ),
            'desc' => __( 'Set this to "No" to hide the Edit Avatar Options.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
		array(
            'id' => 'editadvanced',
            'title' => __( 'Show Advanced Settings?', 'buddypress-links' ),
            'desc' => __( 'Set this to "No" to hide the Edit Advanced Settings.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
		array(
            'id' => 'modsuspend',
            'title' => __( 'Suspend New Links?', 'buddypress-links' ) . $dagger,
            'desc' => __( 'Set this to "Yes" to mark all new links as suspended when they are first created.', 'buddypress-links' ),
            'type' => 'radio',
			'class' => $pro_field_class,
            'std' => false,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        )
    )
);

// voting settings section
$wpsf_settings[] = array(
    'section_id' => 'voting',
    'section_title' => __( 'Voting Settings', 'buddypress-links' ),
//    'section_description' => '',
    'section_order' => 15,
    'fields' => array(
        array(
            'id' => 'enabled',
            'title' => __( 'Allow members to vote on links?', 'buddypress-links' ),
            'desc' => __( 'Voting is enabled by default.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
        array(
            'id' => 'change',
            'title' => __( 'Can members change their vote?', 'buddypress-links' ),
            'desc' => __( 'The default behavior is to allow members to change their vote. Set this to No to prevent vote changing.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        ),
        array(
            'id' => 'downvote',
            'title' => __( 'What kind of voting would you like?', 'buddypress-links' ),
            'desc' => __( 'The default behavior is to allow members to vote UP or DOWN. Set this to "Up Only" to disable down votes.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Up and Down',
				0 => 'Up Only'
			)
        ),
        array(
            'id' => 'activity',
            'title' => __( 'Record voting activity?', 'buddypress-links' ),
            'desc' => __( 'The default behavior is to record voting activity the first time a member votes on a link. Set this to No to disable voting activity recording.', 'buddypress-links' ),
            'type' => 'radio',
            'std' => true,
			'choices' => array(
				1 => 'Yes',
				0 => 'No'
			)
        )
    )
);

// profile settings section
$wpsf_settings[] = array(
    'section_id' => 'profile',
    'section_title' => __( 'Profile Settings', 'buddypress-links' ),
//    'section_description' => '',
    'section_order' => 20,
    'fields' => array(
        array(
            'id' => 'navpos',
            'title' => __( 'Nav Position', 'buddypress-links' ),
            'desc' => __( 'Enter a number to set the position of the Links tab in the profile main navigation.', 'buddypress-links' ),
            'type' => 'text',
            'std' => 100
        ),
        array(
            'id' => 'actnavpos',
            'title' => __( 'Activity Nav Position', 'buddypress-links' ),
            'desc' => __( 'Enter a number to set the position of the Links tab in the profile activity navigation.', 'buddypress-links' ),
            'type' => 'text',
            'std' => 35
        ),
        array(
            'id' => 'acthist',
            'title' => __( 'Max. Activity History', 'buddypress-links' ),
            'desc' => __( 'Limitations of the activity API require that we pass all link ids that we want to display activity for if we are limiting results to links owned by a single user. This settings allows you to override the default number of links that have recent entries in the activity stream which are passed to the activity API.', 'buddypress-links' ),
            'type' => 'text',
            'std' => 100
        )
    )
);

// groups settings section
$wpsf_settings[] = array(
    'section_id' => 'groups',
    'section_title' => __( 'Groups Settings', 'buddypress-links' ),
//    'section_description' => '',
    'section_order' => 25,
    'fields' => array(
        array(
            'id' => 'enable',
            'title' => __( 'Groups integration', 'buddypress-links' ) . $dagger,
            'desc' => __( 'Integration with the groups component is On by default. Set this to Off to disable all integration with groups.', 'buddypress-links' ),
            'type' => 'radio',
			'class' => $pro_field_class,
            'std' => true,
			'choices' => array(
				1 => 'On',
				0 => 'Off'
			)
        ),
        array(
            'id' => 'navpos',
            'title' => __( 'Nav Position', 'buddypress-links' ) . $dagger,
            'desc' => __( 'Enter a number to set the position of the Links tab in the groups navigation.', 'buddypress-links' ),
            'type' => 'text',
			'class' => $pro_field_class,
            'std' => 81
        ),
        array(
            'id' => 'acthist',
            'title' => __( 'Max. Activity History', 'buddypress-links' ) . $dagger,
            'desc' => __( 'This setting is identical to Profile Max Activity history, except it applies to links that have been shared with a single group.', 'buddypress-links' ),
            'type' => 'text',
			'class' => $pro_field_class,
            'std' => 100
        )
    )
);

//
// Validation callbacks
//

/**
 * Validate settings data.
 *
 * @param array $input
 * @return array
 */
function bp_links_settings_validate_filter( $input )
{
	// sanitize category slug
	if ( isset( $input['buddypress_links_global_catslug'] ) ) {
		$input['buddypress_links_global_catslug'] =
			sanitize_title(
				$input['buddypress_links_global_catslug'],
				'category'
			);
	}

	if ( isset( $input['buddypress_links_global_ordertext'] ) ) {
		$input['buddypress_links_global_ordertext'] =
			bp_links_settings_validate_order_text(
				$input['buddypress_links_global_ordertext']
			);
	}

	// return entire input array
	return $input;
}
add_filter( 'buddypress_links_settings_validate', 'bp_links_settings_validate_filter' );

function bp_links_settings_parse_order_text( $string )
{
	// final config
	$config = array();

	// default config
	$defaults =
		array(
			'popular' => __( 'Most Popular', 'buddypress-links' ),
			'high-votes' => __( 'Highest Rated', 'buddypress-links' ),
			'most-votes' => __( 'Most Votes', 'buddypress-links' ),
			'newest' => __( 'Newly Created', 'buddypress-links' ),
			'active' => __( 'Last Active', 'buddypress-links' )
		);

	// split at line endings
	$lines = preg_split( '#[\n\r]+#', $string, 10, PREG_SPLIT_NO_EMPTY );

	// loop every line
	foreach ( $lines as $line ) {
		// split at equals
		$parts = explode( '=', $line, 2 );
		// trim them up
		$clean_parts = array_map( 'trim', $parts );
		// check for meat
		if (
			false === empty( $clean_parts[0] ) &&
			false === empty( $clean_parts[1] ) &&
			true === isset( $defaults[ $clean_parts[0] ] )
		) {
			// append to array
			$config[ $clean_parts[0] ] = $clean_parts[1];
		}
	}

	// is config completely empty?
	if ( empty( $config ) ) {
		// yes, use defaults
		return $defaults;
	} else {
		// return custom config
		return $config;
	}
}

function bp_links_settings_validate_order_text( $string )
{
	// content to return
	$content = '';
	
	// parse the config text
	$config = bp_links_settings_parse_order_text( $string );

	// loop config and format neatly
	foreach ( $config as $key => $value ) {
		$content .= sprintf( "%s = %s\n", $key, $value );
	}

	// return it!
	return $content;
}
