<?php // THE SETTINGS PAGE

	$custom_rules = get_option('db_robots_custom');
	$custom_rules_google = get_option('db_robots_custom_google');
	$custom_rules_yandex = get_option('db_robots_custom_yandex');
	$custom_rules_other = get_option('db_robots_custom_other');

	if ( isset($_POST['submit']) )
	{

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e('Error: You do not have the permission to update robots.txt file' , 'robotstxt') );

		if ( function_exists('check_admin_referrer') )
			check_admin_referrer('db_robotstxt_form');

		$custom_rules = esc_html($_POST['custom_rules']);
		$custom_rules_google = esc_html($_POST['custom_rules_google']);
		$custom_rules_yandex = esc_html($_POST['custom_rules_yandex']);
		$custom_rules_other = esc_html($_POST['custom_rules_other']);
		update_option('db_robots_custom', $custom_rules);
		update_option('db_robots_custom_google', $custom_rules_google);
		update_option('db_robots_custom_yandex', $custom_rules_yandex);
		update_option('db_robots_custom_other', $custom_rules_other);

	}

?>

<div class='wrap db-rbt-admin'>

	<h1><?php _e('DB Robots.txt', 'robotstxt'); ?></h1>

	<h2><?php _e('Link', 'robotstxt'); ?></h2>

	<p><?php _e('You will find the file here:', 'robotstxt'); ?> <a href="/robots.txt" title="robots.txt"><?php echo site_url() ?>/robots.txt</a></p>

	<h2><?php _e('Settings', 'robotstxt'); ?></h2>

	<form name="db-robotstxt" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=db-robotstxt&amp;updated=true">

		<?php
			if (function_exists ('wp_nonce_field') )
				wp_nonce_field('db_robotstxt_form');
		?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Regular basic rules') ?> <?php _e('for all search engines' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description"><?php _e('These directives will appear in robots.txt automatically for all User-agents' , 'robotstxt') ?></p>
				</th>
				<td>
					Disallow: /cgi-bin<br />
					Disallow: /?<br />
					Disallow: /wp-<br />
					Disallow: /wp/<br />
					Disallow: */admin/<br />
					Disallow: */login/<br />
					Disallow: *?p=<br />
					Disallow: *&p=<br />
					Disallow: *?s=<br />
					Disallow: *&s=<br />
					Disallow: /search/<br />
					Disallow: /trackback/<br />
					Disallow: */feed/<br />
					Disallow: */rss/<br />
					Disallow: */embed/<br />
					Disallow: */wlwmanifest.xml<br />
					Disallow: /xmlrpc.php<br />
					Disallow: *?*utm*=<br />
					Disallow: *?*openstat=<br />
					Disallow: *?*from=<br />
					Disallow: *?*yclid=<br />
					Disallow: *?*ymclid=<br />
					Disallow: *?*gclid=<br />
					<br />
					Allow: /wp-*/uploads/<br />
					Allow: /wp-*.webp<br />
					Allow: /wp-*.avif<br />
					Allow: /wp-*.jpg<br />
					Allow: /wp-*.jpeg<br />
					Allow: /wp-*.gif<br />
					Allow: /wp-*.png<br />
					Allow: /wp-*.svg<br />
					Allow: /wp-*.js<br />
					Allow: /wp-*.css<br />
					Allow: /wp-*.doc<br />
					Allow: /wp-*.docx<br />
					Allow: /wp-*.xls<br />
					Allow: /wp-*.xlsx<br />
					Allow: /wp-*.ppt<br />
					Allow: /wp-*.ppts<br />
					Allow: /wp-*.pptx<br />
					Allow: /wp-*.pdf<br />
					Allow: /wp-*.txt<br />
					Allow: /wp-admin/admin-ajax.php
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Custom rules' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description"><?php _e('Add custom rules into your file robots.txt. They will appear in the block "User-agent: *" after the basic directives Disallow and Allow.' , 'robotstxt') ?></p>
				</th>
				<td>
					<textarea id="custom_rules" name="custom_rules" rows="13" cols="100"><?php echo $custom_rules; ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Regular rules') ?> <?php _e('for Google only' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description"><?php _e('These directives will appear in robots.txt automatically in the block "User-agent: Googlebot" after the basic rules' , 'robotstxt') ?></p>
				</th>
				<td>
					Allow: */amp<br />
					Disallow: /feed/turbo<br />
					Disallow: /feed/zen
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Custom rules' , 'robotstxt') ?> <?php _e('for Google only' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description"><?php _e('Add custom rules into your file robots.txt. They will appear in the block "User-agent: Googlebot" after the basic directives Disallow and Allow.' , 'robotstxt') ?></p>
				</th>
				<td>
					<textarea id="custom_rules_google" name="custom_rules_google" rows="13" cols="100"><?php echo $custom_rules_google; ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Regular rules') ?> <?php _e('for Yandex only' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description"><?php _e('These directives will appear in robots.txt automatically in the block "User-agent: Yandex" after the basic rules' , 'robotstxt') ?></p>
				</th>
				<td>
					Disallow: */amp<br />
					Allow: /feed/turbo<br />
					Allow: /feed/zen
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Custom rules' , 'robotstxt') ?> <?php _e('for Yandex only' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description"><?php _e('Add custom rules into your file robots.txt. They will appear in the block "User-agent: Yandex" after the basic directives Disallow and Allow.' , 'robotstxt') ?></p>
				</th>
				<td>
					<textarea id="custom_rules_yandex" name="custom_rules_yandex" rows="13" cols="100"><?php echo $custom_rules_yandex; ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Other custom rules' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description"><?php _e('Add custom rules into your file robots.txt. They will appear at the end of the file before Sitemap. Here you can add other necessary directives.' , 'robotstxt') ?></p>
				</th>
				<td>
					<textarea id="custom_rules_other" name="custom_rules_other" rows="13" cols="100"><?php echo $custom_rules_other; ?></textarea>
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="action" value="update" />
		
		<input type="hidden" name="page_options" value="db_robots_custom" />
		
		<?php submit_button(); ?>

	</form>

</div>