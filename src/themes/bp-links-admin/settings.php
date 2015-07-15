<?php
/**
 * BP Links admin manage settings
 */
?>

<?php if ( isset( $message ) ) { ?>
	<div id="message" class="<?php echo $type ?> fade">
		<p><?php echo $message ?></p>
	</div>
<?php } ?>

<div class="wrap" style="position: relative">

	<h2 class="nav-tab-wrapper">
		<?php bp_links_admin_settings_tabs(); ?>
	</h2>
	
	<div class="buddypress-links-admin-content">

		<?php BP_Links_Settings::instance()->settings() ?>

		<p>
			<strong>&dagger;</strong> -
			<em><a href="http://shop.presscrew.com/shop/buddypress-links/" target="_blank"><?php _e( 'Setting applies to pro extension only', 'buddypress-links' ) ?></a></em>
		</p>
	
	</div>
	
	<?php include 'sidebar.php'; ?>
	
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('div.buddypress-links-admin-settings input.disabled').attr('disabled', 'disabled');
	});
</script>

