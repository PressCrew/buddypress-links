<div class="item-list-tabs no-ajax" id="subnav">
	<ul>
		<?php do_action( 'bp_links_group_item_list_tabs' ) ?>
		<?php do_action( 'bp_links_group_item_list_filters' ) ?>
	</ul>
</div>

<?php do_action( 'bp_before_group_body' ) ?>
<?php do_action( 'bp_before_group_links_content' ) ?>

<div id="links-mylinks" class="links mylinks">
	<?php bp_links_locate_template( array( 'links-loop.php' ), true ) ?>
</div>

<?php do_action( 'bp_after_group_links_content' ) ?>
<?php do_action( 'bp_after_group_body' ) ?>
