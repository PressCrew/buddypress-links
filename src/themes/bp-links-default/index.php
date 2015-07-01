<?php
	if ( current_theme_supports( 'buddypress' ) ):
		get_header( 'buddypress' );
		// spit out old containers ?>
		<div id="content">
			<div class="padder"><?php
	else:
		// spit out legacy container ?>
		<div id="buddypress"><?php
	endif;
?>

		<form action="" method="post" id="links-directory-form" class="dir-form">

			<?php do_action( 'bp_before_directory_links_content' ) ?>

			<div id="link-dir-search" class="dir-search">
				<?php bp_links_dtheme_search_form() ?>
			</div><!-- #link-dir-search -->

			<div class="item-list-tabs" data-links-role="topnav">
				<ul>
					<li class="selected" id="links-all"><a href="<?php bp_root_domain() ?>/<?php bp_root_slug( 'links' ) ?>"><?php _e( 'All Links', 'buddypress-links' ) ?> <span><?php echo bp_get_links_total_link_count() ?></span></a></li>
					<?php do_action( 'bp_links_directory_link_types' ) ?>
				</ul>
			</div><!-- .item-list-tabs -->

			<div class="item-list-tabs no-ajax" id="subnav">
				<ul>
					<li class="feed"><a href="<?php bp_directory_links_feed_link() ?>" title="RSS Feed"><?php _e( 'RSS', 'buddypress' ) ?></a></li>
					<?php do_action( 'bp_links_syndication_options' ) ?>
					<?php do_action( 'bp_links_item_list_tabs' ) ?>
				</ul>
			</div><!-- .item-list-tabs -->

			<div id="links-dir-list" class="links dir-list">
				<?php bp_links_locate_template( array( 'links-loop.php' ), true ) ?>
			</div><!-- #links-dir-list -->

			<?php do_action( 'bp_directory_links_content' ) ?>

			<?php wp_nonce_field( 'directory_links', '_wpnonce-links-filter' ) ?>

		</form><!-- #links-directory-form -->

		<?php do_action( 'bp_after_directory_links_content' ) ?>

<?php
	if ( current_theme_supports( 'buddypress' ) ):
		// close old containers ?>
		</div><!-- .padder -->
		</div><!-- #content --><?php
		get_sidebar( 'buddypress' );
		get_footer( 'buddypress' );
	else:
		// close legacy container ?>
		</div><?php
	endif;
?>
