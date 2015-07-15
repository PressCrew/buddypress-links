<?php
/**
 * BP Links admin index
 */

if ( defined( 'BP_LINKS_PRO_VERSION' ) ) {
	$bp_links_pro_installed = true;
	$bp_links_pro_version = BP_LINKS_PRO_VERSION;
} else {
	$bp_links_pro_installed = false;
	$bp_links_pro_version = 'Not Installed';
}

?>
<div class="wrap">

	<h2><?php _e( 'BuddyPress Links', 'buddypress-links' ) ?></h2>

	<div class="buddypress-links-admin-content">
	
		<table border="0" class="widefat">
			<thead>
				<tr>
					<th>
						<span class="dashicons-before dashicons-admin-plugins">
							<?php _e( 'Plugin', 'buddypress-links' ) ?>
						</span>
					</th>
					<th>
						<span class="dashicons-before dashicons-tag">
							<?php _e( 'Version', 'buddypress-links' ) ?>
						</span>
					</th>
					<th>
						<span class="dashicons-before dashicons-groups">
							<?php _e( 'Community Support', 'buddypress-links' ) ?>
						</span>
					</th>
					<th>
						<span class="dashicons-before dashicons-businessman">
							<?php _e( 'Pro Help Desk', 'buddypress-links' ) ?>
						</span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>
						<span class="dashicons-before dashicons-share">
							<a href="https://wordpress.org/plugins/buddypress-links/" target="_blank"><?php _e( 'BuddyPress Links', 'buddypress-links' ) ?></a>
						</span>
					</th>
					<td>
						<span class="dashicons-before dashicons-yes">
							<?php print BP_LINKS_VERSION ?>
						</span>
					</td>
					<td>
						<span class="dashicons-before dashicons-yes">
							<a href="https://wordpress.org/support/plugin/buddypress-links" target="_blank"><?php _e( 'Get Help', 'buddypress-links' ) ?></a>
						</span>
					</td>
					<td>
						<?php if ( $bp_links_pro_installed ): ?>
							<span class="dashicons-before dashicons-yes">
								<a href="https://presscrew.freshdesk.com/support/home" target="_blank"><?php _e( 'Get Help', 'buddypress-links' ) ?></a>
							</span>
						<?php else: ?>
							<span class="dashicons-before dashicons-lock">
								<a href="http://shop.presscrew.com/shop/buddypress-links/" target="_blank"><?php _e( 'Upgrade Now', 'buddypress-links' ) ?></a>
							</span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>
						<span class="dashicons-before dashicons-share">
							<a href="http://shop.presscrew.com/product/plugins/buddypress-links/" target="_blank"><?php _e( 'BuddyPress Links Pro', 'buddypress-links' ) ?></a>
						</span>
					</th>
					<td>
						<?php if ( $bp_links_pro_installed ): ?>
							<span class="dashicons-before dashicons-yes">
								<?php print $bp_links_pro_version ?>
							</span>
						<?php else: ?>
							<span class="dashicons-before dashicons-no">
								<?php _e( 'Not Installed', 'buddypress-links' ) ?>
							</span>
						<?php endif; ?>
					</td>
					<td>
						<span class="dashicons-before dashicons-minus"></span>
					</td>
					<td>
						<?php if ( $bp_links_pro_installed ): ?>
							<span class="dashicons-before dashicons-yes">
								<a href="https://presscrew.freshdesk.com/support/home" target="_blank"><?php _e( 'Get Help', 'buddypress-links' ) ?></a>
							</span>
						<?php else: ?>
							<span class="dashicons-before dashicons-lock">
								<a href="http://shop.presscrew.com/shop/buddypress-links/" target="_blank"><?php _e( 'Upgrade Now', 'buddypress-links' ) ?></a>
							</span>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<h3 class="dashicons-before dashicons-flag"><?php _e( 'Additional Activation Steps:', 'buddypress-links' ) ?></h3>
		<p>
			<?php _e( 'These additional steps are required to get this plugin working after the first activation.', 'buddypress-links' ) ?>
		</p>
		<ul>
			<li><?php _e( 'Click on <strong>Settings &gt; BuddyPress</strong> under the <strong>Dashboard</strong> menu.', 'buddypress-links' ) ?></li>
			<li><?php _e( 'Click the <strong>Pages</strong> tab on the <strong>BuddyPress</strong> settings screen.', 'buddypress-links' ) ?></li>
			<li><?php _e( 'Under directories, assign a page to the <strong>Links</strong> component by selecting an existing page, or creating a new one.', 'buddypress-links' ) ?></li>
			<li><?php _e( 'Click the <strong>Save</strong> button.', 'buddypress-links' ) ?></li>
			<li><?php _e( 'The <strong>Links</strong> item in your site navigation should now load the links component!', 'buddypress-links' ) ?></li>
		</ul>

		<h3 class="dashicons-before dashicons-sos"><?php _e( 'Support', 'buddypress-links' ) ?></h3>
		<p>
			<?php _e( 'There are two levels of support:', 'buddypress-links' ) ?>
		</p>
		<ul>
			<li>
				<strong>
					<a href="https://wordpress.org/support/plugin/buddypress-links" target="_blank"><?php _e( 'Community Forums', 'buddypress-links' ) ?></a>
				</strong>
				<div>
					<?php _e( 'Get help from the plugin author and other users from our official plugin support forums.', 'buddypress-links' ) ?>
				<div>
			</li>
			<li>
				<strong>
					<?php if ( $bp_links_pro_installed ): ?>
						<a href="https://presscrew.freshdesk.com/support/home" target="_blank"><?php _e( 'PressCrew Help Desk', 'buddypress-links' ) ?></a>
					<?php else: ?>
						<?php _e( 'PressCrew Help Desk', 'buddypress-links' ) ?> -
						<a href="http://shop.presscrew.com/shop/buddypress-links/" target="_blank"><?php _e( 'Upgrade Now', 'buddypress-links' ) ?></a>
					<?php endif; ?>
				</strong>
				<div>
					<?php _e( 'Purchasing the Pro Extension entitles you to premium support on <em>both</em> the free and pro plugin.', 'buddypress-links' ) ?>
				</div>
			</li>
		</ul>

		<h3 class="dashicons-before dashicons-admin-plugins"><?php _e( 'Pro Extension', 'buddypress-links' ) ?></h3>
		<p>
			<?php _e( 'The pro extension, available for purchase in the' ); ?>
			<a href="http://shop.presscrew.com/shop/buddypress-links/" target="_blank"><?php _e( 'PressCrew Shop', 'buddypress-links' ) ?></a>
			<?php _e( 'adds the following additional features:', 'buddypress-links' ) ?>
		</p>
		<h4><?php _e( 'Additional Rich Media Support', 'buddypress-links' ) ?></h4>
		<ul>
			<li><a href="http://www.dailymotion.com/" target="_blank"><?php _e( 'Dailymotion', 'buddypress-links' ) ?></a></li>
			<li><a href="http://www.vimeo.com/" target="_blank"><?php _e( 'Vimeo', 'buddypress-links' ) ?></a></li>
		</ul>
		<h4><?php _e( 'Member Links Sharing', 'buddypress-links' ) ?></h4>
		<ul>
			<li><?php _e( "Share other member's links on their profile.", 'buddypress-links' ) ?></li>
			<li><?php _e( 'Share any link with a group they are a member of.', 'buddypress-links' ) ?></li>
		</ul>
		<h4><?php _e( 'Groups Integration', 'buddypress-links' ) ?></h4>
		<ul>
			<li><?php _e( 'Group members can add a link to any group they are a member of, directly from the group.', 'buddypress-links' ) ?></li>
			<li><?php _e( 'Fully integrated with the group activity stream.', 'buddypress-links' ) ?></li>
			<li><?php _e( "Each group has their own links mini-directory which lists only that group's links.", 'buddypress-links' ) ?></li>
			<li><?php _e( 'Separate tabs for listing all group links, or just my group links.', 'buddypress-links' ) ?></li>
			<li><?php _e( 'The same powerful category and order filtering is available.', 'buddypress-links' ) ?></li>
			<li><?php _e( 'Group administrators can remove group links, with prejudice.', 'buddypress-links' ) ?></li>
		</ul>

		<h3 class="dashicons-before dashicons-admin-tools"><?php _e( 'Developer Extras', 'buddypress-links' ) ?></h3>
		<ul>
			<li><a href="http://plugins.trac.wordpress.org/log/buddypress-links" target="_blank"><?php _e( 'Trac Revision Log', 'buddypress-links' ) ?></a></li>
			<li><a href="http://plugins.trac.wordpress.org/browser/buddypress-links/" target="_blank"><?php _e( 'Trac Browser', 'buddypress-links' ) ?></a></li>
		</ul>

		<h3 class="dashicons-before dashicons-id-alt"><?php _e( 'About the Author:', 'buddypress-links' ) ?></h3>
		<ul>
			<li><a href="http://marshallsorenson.com/" target="_blank"><?php _e( "Marshall Sorenson's Blog", 'buddypress-links' ) ?></a></li>
			<li><a href="https://profiles.wordpress.org/mrmaz/" target="_blank"><?php _e( 'MrMaz on WordPress.org', 'buddypress-links' ) ?></a></li>
		</ul>

	</div>
	
	<?php include 'sidebar.php'; ?>
	
</div>
