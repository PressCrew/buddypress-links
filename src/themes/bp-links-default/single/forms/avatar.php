<?php do_action( 'bp_before_link_avatar_form' ) ?>

<form action="<?php bp_link_admin_form_action() ?>" name="link-avatar-form" id="link-avatar-form" class="standard-form" method="post" enctype="multipart/form-data">

	<h2><?php _e( 'Link Avatar', 'buddypress-links' ); ?></h2>

	<div class="left-menu">
		<?php bp_link_avatar_form_avatar() ?>

		<?php if ( bp_get_link_has_avatar() ) : ?>
			<div class="generic-button" id="delete-link-avatar-button">
				<a class="edit" href="<?php bp_link_avatar_form_delete_link() ?>" title="<?php _e( 'Delete Avatar', 'buddypress-links' ) ?>"><?php _e( 'Delete Avatar', 'buddypress-links' ) ?></a>
			</div>
		<?php endif; ?>
	</div>

	<div class="main-column">

	<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>

		<p><?php _e( 'Upload an image to use as an avatar for this link. The image will be shown on the main link page, and in search results.', 'buddypress-links' ) ?></p>

		<p>
			<input type="file" name="file" id="file" />
			<input type="submit" name="upload" id="upload" value="<?php _e( 'Upload Image', 'buddypress-links' ) ?>" />
			<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
		</p>

		<?php wp_nonce_field( 'bp_avatar_upload' ) ?>

	<?php elseif ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

		<h3><?php _e( 'Crop Avatar', 'buddypress-links' ) ?></h3>

		<img src="<?php bp_avatar_to_crop() ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress-links' ) ?>" />

		<div id="avatar-crop-pane">
			<img src="<?php bp_avatar_to_crop() ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress-links' ) ?>" />
		</div>

		<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php _e( 'Crop Image', 'buddypress-links' ) ?>" />

		<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src() ?>" />
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />

		<?php wp_nonce_field( 'bp_avatar_cropstore' ) ?>

	<?php endif; ?>

	</div>

</form>

<?php do_action( 'bp_after_link_avatar_form' ) ?>