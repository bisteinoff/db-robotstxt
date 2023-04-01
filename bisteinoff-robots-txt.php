<?php
/*
Plugin Name: DB Robots.txt 
Plugin URI: https://github.com/bisteinoff/db-robotstxt
Description: The plugin makes a virtual file robots.txt good for both Google and Yandex, and gives suggestions how to make the correct settings.
Version: 3.1
Author: Denis Bisteinov
Author URI: https://bisteinoff.com/
License: GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : bisteinoff@gmail.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	add_option('db_robots_custom');
	add_option('db_robots_custom_google');
	add_option('db_robots_custom_yandex');
	add_option('db_robots_custom_other');

	add_action( 'admin_enqueue_scripts', function() {
					wp_register_style('db-robotstxt-admin', '/wp-content/plugins/db-robotstxt/css/admin.css');
					wp_enqueue_style( 'db-robotstxt-admin' );
				},
				99
	);



	function publish_robots_txt() {

		// BASIC VARIABLES

		// Basic rules for all user-agents

		$db_rules = array(

			'Disallow' => array (
				'/cgi-bin',
				'/?',
				'/wp-',
				'/wp/',
				'*/admin/',
				'*/login/',
				'*?p=',
				'*&p=',
				'*?s=',
				'*&s=',
				'/search/',
				'/trackback/',
				'*/feed/',
				'*/rss/',
				'*/embed/',
				'*/wlwmanifest.xml',
				'/xmlrpc.php',
				'*?*utm*=',
				'*?*openstat=',
				'*?*from=',
				'*?*yclid=',
				'*?*ymclid=',
				'*?*gclid='
			),
			'Allow' => array (
				'/wp-*/uploads/',
				'/wp-*.webp',
				'/wp-*.avif',
				'/wp-*.jpg',
				'/wp-*.jpeg',
				'/wp-*.gif',
				'/wp-*.png',
				'/wp-*.svg',
				'/wp-*.js',
				'/wp-*.css',
				'/wp-*.doc',
				'/wp-*.docx',
				'/wp-*.xls',
				'/wp-*.xlsx',
				'/wp-*.ppt',
				'/wp-*.ppts',
				'/wp-*.pptx',
				'/wp-*.pdf',
				'/wp-*.txt',
				'/wp-admin/admin-ajax.php'
			)
		);

		$db_basic_rules = '';

		foreach ($db_rules as $db_rules_category => $db_rules_categories) {

			foreach ($db_rules_categories as $key => $db_rules) {

				$db_basic_rules .= "{$db_rules_category}: {$db_rules}\n";

			}

			$db_basic_rules .= "\n";

		}



		// Basic rules for Google

		$db_rules = array(

			'Disallow' => array (
				'/feed/turbo',
				'/feed/zen'
			),
			'Allow' => array (
				'*/amp'
			)
		);

		$db_basic_rules_google = '';

		foreach ($db_rules as $db_rules_category => $db_rules_categories) {

			foreach ($db_rules_categories as $key => $db_rules) {

				$db_basic_rules_google .= "{$db_rules_category}: {$db_rules}\n";

			}

			$db_basic_rules_google .= "\n";

		}



		// Basic rules for Yandex

		$db_rules = array(

			'Disallow' => array (
				'*/amp'
			),
			'Allow' => array (
				'/feed/turbo',
				'/feed/zen'
			)
		);

		$db_basic_rules_yandex = '';

		foreach ($db_rules as $db_rules_category => $db_rules_categories) {

			foreach ($db_rules_categories as $key => $db_rules) {

				$db_basic_rules_yandex .= "{$db_rules_category}: {$db_rules}\n";

			}

			$db_basic_rules_yandex .= "\n";

		}



		// ROBOTS.TXT

		// User-agent: *

		$db_robots = "# This virtual robots.txt file was created by DB Robots.txt WordPress plugin: \n# https://www.wordpress.org/plugins/bisteinoff-robots-txt/";
		$db_robots .= "\n\n\nUser-agent: *\n\n";
		$db_robots .= $db_basic_rules;
		$db_robots .= preg_replace('/&amp;/', '&', get_option('db_robots_custom') ) . "\n";

		// User-agent: GoogleBot

		$db_robots .= "\n\n\nUser-agent: GoogleBot\n\n";
		$db_robots .= $db_basic_rules . $db_basic_rules_google;
		$db_robots .= preg_replace('/&amp;/', '&', get_option('db_robots_custom_google') ) . "\n";

		// User-agent: Yandex

		$db_robots .= "\n\n\nUser-agent: Yandex\n\n";
		$db_robots .= $db_basic_rules . $db_basic_rules_yandex;
		$db_robots .= preg_replace('/&amp;/', '&', get_option('db_robots_custom_yandex') ) . "\n";

		// Other
		$db_robots .= "\n\n\n" . preg_replace('/&amp;/', '&', get_option('db_robots_custom_other') ) . "\n";



		// SITEMAP

		// Checking the host

		if ( ( !empty($_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) {
			$protocol = 'https://';
			$host = 'https://'.$_SERVER['HTTP_HOST'];
		}
		else { 
			$protocol = 'http://';
			$host = $_SERVER['HTTP_HOST'];
		}

		// checking if XML sitemap files exist
		$sitemap = '';
		$db_check_files = array(
			'sitemap.xml',
			'sitemap_index.xml',
			'sitemap.xml.gz',
			'wp-sitemap.xml'
			);

		foreach( $db_check_files as $db_file ) {

			$url = $host . '/' . $db_file;
			$ch = curl_init ($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);

			$ch_output = curl_exec ($ch);

			if (curl_getinfo($ch)['http_code'] == 200)
				$sitemap .= '\n'.'Sitemap: '.$url;

			curl_close($ch);

		}

		if ( !empty ($sitemap) )
			$db_robots .= "\n\n\n" . $sitemap;


		$db_robots = stripcslashes($db_robots);

		header('Status: 200 OK', true, 200);
		header('Content-type: text/plain; charset=' . get_bloginfo('charset'));
		echo $db_robots;
		exit; 

	} // end function

	remove_action( 'do_robots', 'do_robots' );
	add_action( 'do_robots', 'publish_robots_txt' );



	function db_robots_admin()
	{

		if ( function_exists('add_options_page') )
		{

			add_options_page(
				'DB Robots.txt Settings',
				'DB Robots.txt',
				'manage_options',
				'db-robotstxt',
				'db_robotstxt_admin_settings'
			);

		}

	}

	add_action( 'admin_menu', 'db_robots_admin' );



	function db_robotstxt_admin_settings()
	{

		require_once('inc/settings.php');

	}