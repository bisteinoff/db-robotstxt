<?php // THE SETTINGS PAGE

	$custom_rules = get_option('db_robots_custom');

	if ( isset($_POST['submit']) )
	{

		if ( function_exists('current_user_can') &&
			 !current_user_can('manage_options') )
				die( _e('Error: You do not have the permission to update robots.txt file' , 'robotstxt') );

		if ( function_exists('check_admin_referrer') )
			check_admin_referrer('db_robotstxt_form');

		$custom_rules = $_POST['custom_rules'];

		update_option('db_robots_custom', $custom_rules);

	}

?>

<div class='wrap db-rbt-admin'>

	<h1><?php _e('DB Robots.txt', 'robotstxt'); ?></h1>

	<h2><?php _e('Settings', 'robotstxt'); ?></h2>

	<form name="db-robotstxt" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=db-robotstxt&amp;updated=true">

		<?php
			if (function_exists ('wp_nonce_field') )
				wp_nonce_field('db_robotstxt_form');
		?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row" class="db-rbt-custom">
					<p><?php _e('Custom rules' , 'robotstxt') ?></p>
					<p class="td-rbt-field-description">Add custom rules into your file robots.txt. They will appear after the basic directives Disallow and Allow.</p>
				</th>
				<td>
					<textarea id="custom_rules" name="custom_rules" rows="27" cols="80"><?php echo $custom_rules; ?></textarea>
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="action" value="update" />
		
		<input type="hidden" name="page_options" value="db_robots_custom" />
		
		<p class="submit">
			<input type="submit" name="submit" value="<?php _e('Save Changes') ?>" />
		</p>

	</form>

</div>