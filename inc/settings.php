<?php // THE SETTINGS PAGE

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	$d = DB_PLUGIN_ROBOTSTXT_DIR; // domain for translate.wordpress.org

	$db_self = get_admin_url() . 'options-general.php?page=' . $d;
	$db_link = $_SERVER['DOCUMENT_ROOT'] . '/robots.txt';

	if ( isset( $_GET['action'] ) )
	{

		$db_action = esc_html( sanitize_text_field ( $_GET['action'] ) );

		switch ( $db_action )
		{
			case 'rename': 
				rename ( $db_link , $_SERVER['DOCUMENT_ROOT'] . '/robots_old.txt' );
				wp_redirect ( $db_self . '&action=renamed' );
				break;
			case 'renamed': 
				$db_renamed = true;
				break;
			case 'delete': 
				wp_delete_file ( $db_link );
				wp_redirect ( $db_self . '&action=deleted' );
				break;
			case 'deleted': 
				$db_deleted = true;
				break;
			default: 
				wp_redirect ( $db_self );
				break;
		}

	}

	$custom_rules        = get_option('db_robots_custom');
	$custom_rules_google = get_option('db_robots_custom_google');
	$if_yandex           = get_option('db_robots_if_yandex');
	$custom_rules_yandex = get_option('db_robots_custom_yandex');
	$custom_rules_other  = get_option('db_robots_custom_other');

	if ( isset( $_POST['submit'] ) && 
		 isset( $_POST[ $d . '_nonce' ] ) &&
		 wp_verify_nonce( $_POST[ $d . '_nonce' ], $d ) )
	{

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e( 'Error: You do not have the permission to update robots.txt file' , 'db-robotstxt' ) );

		$custom_rules        = esc_html( sanitize_textarea_field ( $_POST['custom_rules'] ) );
		$custom_rules_google = esc_html( sanitize_textarea_field ( $_POST['custom_rules_google'] ) );
		$if_yandex           = esc_html( sanitize_text_field     ( $_POST['if_yandex'] ) );
		$custom_rules_yandex = esc_html( sanitize_textarea_field ( $_POST['custom_rules_yandex'] ) );
		$custom_rules_other  = esc_html( sanitize_textarea_field ( $_POST['custom_rules_other'] ) );
		update_option('db_robots_custom',        $custom_rules);
		update_option('db_robots_custom_google', $custom_rules_google);
		update_option('db_robots_if_yandex',     $if_yandex);
		update_option('db_robots_custom_yandex', $custom_rules_yandex);
		update_option('db_robots_custom_other',  $custom_rules_other);

	}

?>

<div class='wrap db-rbt-admin'>

	<h1><?php _e( 'DB Robots.txt' , 'db-robotstxt' ); ?></h1>

	<div class="db-rbt-description">
		<p><?php _e( "DB Robots.txt automatically creates a virtual file robots.txt including special rules for Google and Yandex." , 'db-robotstxt' ) ?></p>
		<p><?php _e( "You can also add custom rules for Google, Yandex and any other robots or disable Yandex if you don't need it for search engines optimisation." , 'db-robotstxt' ) ?></p>
	</div>

	<h2><?php _e( 'Link' , 'db-robotstxt' ); ?></h2>

	<div class="db-rbt-link">
		<p><?php _e( 'You will find the file here:', 'db-robotstxt' ); ?> <a class="db-rbt-button" href="/robots.txt" target="_blank" title="robots.txt"><?php echo site_url() ?>/robots.txt</a></p>
	</div>

	<h2><?php _e( 'Settings' , 'db-robotstxt' ); ?></h2>

	<form name="db-robotstxt" method="post" action="<?php echo esc_html ( $_SERVER['PHP_SELF'] ) ?>?page=<?php echo esc_html ( $d ) ?>&amp;updated=true">

		<?php
			if (function_exists ('wp_nonce_field') )
				wp_nonce_field('db_robotstxt_form');
		?>

		<table class="form-table">
			<?php
				if ( file_exists ($db_link) ) {
		?>

			<tr valign="top">
				<th scope="row" class="db-rbt-error">
					<p><?php _e( 'Attention!' , 'db-robotstxt' ) ?> <?php _e( 'File robots.txt already exists' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'If you want to replace it with the virtual file, you need to rename or delete the existing one' , 'db-robotstxt' ) ?></p>
				</th>
				<td class="db-rbt-error">
					<a class="db-rbt-button" href="<?php echo esc_html( sanitize_text_field ( $db_self ) ) ?>&action=rename"><?php _e( 'Rename to' , 'db-robotstxt' ) ?> robots_old.txt</a>
					<a class="db-rbt-button" href="<?php echo esc_html( sanitize_text_field ( $db_self ) ) ?>&action=delete"><?php _e( 'Delete old' , 'db-robotstxt' ) ?> robots.txt</a>
				</td>
			</tr>
			<?php }

				else
				{
					if ( $db_renamed ) {
			?>
			<tr valign="top">
				<th colspan="2" scope="row" class="db-rbt-success">
					<p>
						<?php _e( 'File robots.txt has been sucessfully renamed. Now the virtual robots.txt works fine.' , 'db-robotstxt' ) ?>
						<?php _e( 'You can find the old robots.txt' , 'db-robotstxt' ) ?>
						<a href="/robots_old.txt" target="_blank"><?php _e( 'here' , 'db-robotstxt' ) ?></a>
					</p>
				</th>
			</tr>
			<?php } 

					if ( $db_deleted ) {
			?>
			<tr valign="top">
				<th colspan="2" scope="row" class="db-rbt-success">
					<p><?php _e( 'File robots.txt has been sucessfully deleted. Now the virtual robots.txt works fine.' , 'db-robotstxt' ) ?></p>
				</th>
			</tr>
			<?php }
				}
			?>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e( 'Regular basic rules' , 'db-robotstxt' ) ?> <?php _e( 'for all search engines' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'These directives will appear in robots.txt automatically for all User-agents' , 'db-robotstxt' ) ?></p>
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
					<p><?php _e( 'Custom rules' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'Add custom rules into your file robots.txt. They will appear in the block "User-agent: *" after the basic directives Disallow and Allow.' , 'db-robotstxt' ) ?></p>
				</th>
				<td>
					<textarea id="custom_rules" name="custom_rules" rows="13" cols="100"><?php echo strip_tags($custom_rules); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e( 'Regular rules' , 'db-robotstxt' ) ?> <?php _e( 'for Google only' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'These directives will appear in robots.txt automatically in the block "User-agent: Googlebot" after the basic rules' , 'db-robotstxt' ) ?></p>
				</th>
				<td>
					Allow: */amp<br />
					Disallow: /feed/turbo<br />
					Disallow: /feed/zen
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e( 'Custom rules' , 'db-robotstxt' ) ?> <?php _e( 'for Google only' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'Add custom rules into your file robots.txt. They will appear in the block "User-agent: Googlebot" after the basic directives Disallow and Allow.' , 'db-robotstxt' ) ?></p>
				</th>
				<td>
					<textarea id="custom_rules_google" name="custom_rules_google" rows="13" cols="100"><?php echo strip_tags($custom_rules_google); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e( 'Enable special rules for Yandex' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'You can disable the rules if the region of your website is not Russia, Belorussia, Kazakhstan or Ukraine where many people use Yandex as a search engine' , 'db-robotstxt' ) ?></p>
				</th>
				<td>
					<input type="checkbox" id="if_yandex" name="if_yandex" <?php if ( $if_yandex === 'on') { ?>checked<?php } ?> >
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e( 'Regular rules' , 'db-robotstxt' ) ?> <?php _e( 'for Yandex only' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'These directives will appear in robots.txt automatically in the block "User-agent: Yandex" after the basic rules' , 'db-robotstxt' ) ?></p>
				</th>
				<td>
					Disallow: */amp<br />
					Allow: /feed/turbo<br />
					Allow: /feed/zen
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e( 'Custom rules' , 'db-robotstxt' ) ?> <?php _e( 'for Yandex only' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'Add custom rules into your file robots.txt. They will appear in the block "User-agent: Yandex" after the basic directives Disallow and Allow.' , 'db-robotstxt' ) ?></p>
				</th>
				<td>
					<textarea id="custom_rules_yandex" name="custom_rules_yandex" rows="13" cols="100"><?php echo strip_tags($custom_rules_yandex); ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e( 'Other custom rules' , 'db-robotstxt' ) ?></p>
					<p class="td-rbt-field-description"><?php _e( 'Add custom rules into your file robots.txt. They will appear at the end of the file before Sitemap. Here you can add other necessary directives.' , 'db-robotstxt' ) ?></p>
				</th>
				<td>
					<textarea id="custom_rules_other" name="custom_rules_other" rows="13" cols="100"><?php echo strip_tags($custom_rules_other); ?></textarea>
				</td>
			</tr>
		</table>

		<input type="hidden" name="action" value="update" />

		<?php $nonce = wp_create_nonce( $d ); ?>

		<input type="hidden" name="<?php echo esc_html( sanitize_text_field( $d ) ) ?>_nonce" value="<?php echo esc_html( sanitize_text_field( $nonce ) ) ?>" />

		<?php submit_button(); ?>

	</form>

</div>