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

	<div id="content">
		<div class="padder">

			<h3><?php _e( 'Create a Link', 'buddypress-links' ) ?> &nbsp;<a class="button" href="<?php echo bp_get_root_domain() . '/' . bp_links_root_slug() . '/' ?>"><?php _e( 'Links Directory', 'buddypress-links' ) ?></a></h3>

			<div class="item-list-tabs no-ajax" id="link-create-tabs">
				<ul>
					<?php bp_links_dtheme_creation_tabs() ?>
				</ul>
			</div>

			<?php do_action( 'template_notices' ); ?>

			<div class="item-body" id="link-create-body">
				<?php
					do_action( 'bp_before_link_creation_content' );
					bp_links_locate_template( array( 'single/forms/details.php' ), true );
					do_action( 'bp_after_link_creation_content' );
				?>
			</div>
		</div>
	</div>

<?php
	if ( current_theme_supports( 'buddypress' ) ):
		// close old containers ?>
		</div></div><?php
		get_sidebar( 'buddypress' );
		get_footer( 'buddypress' );
	else:
		// close legacy container ?>
		</div><?php
	endif;
?>