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
<div class="wrap nosubsub buddypress-links-admin-general">

	<?php screen_icon( 'bp-links' ); ?>

	<h2><?php _e( 'BuddyPress Links', 'buddypress-links' ) ?></h2>

	<h3><?php _e( 'Thank you for installing BuddyPress Links!', 'buddypress-links' ) ?></h3>
	<p>
		There are some additional steps required to get this plugin working after
		the first activation. These steps are documented further down on this page.
	</p>
	
	<table border="0" class="widefat">
		<thead>
			<tr>
				<th colspan="2">
					Version Information
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Base:</th>
				<th><?php print BP_LINKS_VERSION ?></th>
			</tr>
			<tr>
				<th>Pro Extension:</th>
				<th>
					<?php print $bp_links_pro_version ?>
					<?php if ( !$bp_links_pro_installed ): ?>
						<a href="http://shop.presscrew.com/shop/buddypress-links/" target="_blank" style="margin-left: 10px;">Purchase</a>
					<?php endif; ?>
				</th>
			</tr>
		</tbody>
	</table>

	<h3><?php _e( 'Administrative Options:', 'buddypress-links' ) ?></h3>
	<ul>
		<li><a href="?page=buddypress-links-admin-links"><?php _e( 'Manage Links', 'buddypress-links' ) ?></a></li>
		<li><a href="?page=buddypress-links-admin-cats"><?php _e( 'Edit Categories', 'buddypress-links' ) ?></a></li>
	</ul>
	
	<h3>Additional Activation Steps:</h3>
	<ol>
		<li>Click on <strong>Settings</strong> under the <strong>Dashboard</strong> menu.</li>
		<li>Click on <strong>BuddyPress</strong> under the <strong>Settings</strong> menu.</li>
		<li>Click the <strong>Pages</strong> tab on the <strong>BuddyPress</strong> settings screen.</li>
		<li>Under directories, assign a page to the <strong>Links</strong> component by selecting an existing page, or creating a new one.</li>
		<li>Click the <strong>Save</strong> button.</li>
		<li>The <strong>Links</strong> item in your site navigation should now load the links component!</li>
	</ol>

	<!-- h3>Documentation</h3>
	<p>
		TODO
	</p -->

	<h3>Support</h3>
	<p>
		There are two levels of support:
	</p>
	<ul>
		<li>For support on the community version, head over to this plugin's <a href="http://buddypress.org/community/groups/buddypress-links/home/" target="_blank">official group</a> on BuddyPress.org</li>
		<li>For premium support on the community and pro versions, head over to the Press Crew <a href="http://community.presscrew.com/discussion/premium-plugins/" target="_blank">premium plugin forums</a>.</li>
	</ul>

	<h3>Pro Extension</h3>
	<p>
		The pro extension adds the following additional features:
	</p>
	<h4>Member Links Sharing</h4>
	<ul>
		<li>Share other member's links on their profile.</li>
		<li>Share any link with a group they are a member of.</li>
	</ul>
	<h4>Groups Integration</h4>
	<ul>
		<li>Group members can add a link to any group they are a member of, directly from the group.</li>
		<li>Fully integrated with the group activity stream.</li>
		<li>Each group has their own links mini-directory which lists only that group's links.</li>
		<li>Separate tabs for listing all group links, or just my group links.</li>
		<li>The same powerful category and order filtering is available.</li>
		<li>Group administrators can remove group links, with prejudice.</li>
	</ul>
	<p>
		The pro extension is available for purchase in the <a href="http://shop.presscrew.com/shop/buddypress-links/" target="_blank">Press Crew Shop</a>
	</p>
	
	<h3>Developer Extras</h3>
	<ul>
		<li><a href="http://plugins.trac.wordpress.org/log/buddypress-links" target="_blank">Trac Revision Log</a></li>
		<li><a href="http://plugins.trac.wordpress.org/browser/buddypress-links/" target="_blank">Trac Browser</a></li>
	</ul>

	<h3><?php _e( 'About the Author:', 'buddypress-links' ) ?></h3>
	<ul>
		<li><a href="http://marshallsorenson.com/" target="_blank">Marshall Sorenson's Blog</a></li>
		<li><a href="http://buddypress.org/community/members/MrMaz/" target="_blank">MrMaz on BuddyPress.org</a></li>
	</ul>

	<h3><?php _e( 'Credits:', 'buddypress-links' ) ?></h3>
	<ul>
		<li>
			Logo Elements:
			&quot;Share&quot; symbol by The Noun Project, from <a href="http://thenounproject.com" target="_blank">The Noun Project</a> collection.</p>
		</li>
	</ul>

</div>
