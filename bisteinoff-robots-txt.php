<?php
/*
Plugin Name: DB Robots.txt 
Plugin URI: http://seogio.ru
Description: The plugin makes a virtual file robots.txt good for both Google and Yandex, and gives suggestions how to make the correct settings.
Version: 2.3
Author: Denis Bisteinov
Author URI: http://seogio.ru
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

	function publish_robots_txt() {

		// The list of rules for robots.txt

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

		// Making the contents for robots.txt

		$default_robots = '';

		foreach ($db_rules as $db_rules_category) {

			foreach ($db_rules_category as $db_rules) {

				$default_robots .= "{$db_rules_category}: {$db_rules}\n";

			}

		}

		$output = "# This virtual robots.txt file was created by DB Robots.txt WordPress plugin: \n# https://www.wordpress.org/plugins/bisteinoff-robots-txt/\n\n";
		$output .= "User-agent: *\n\n";
		$output .= $default_robots;

		// checking the host
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

		$output .= $sitemap;

		$output = stripcslashes($output);

		header('Status: 200 OK', true, 200);
		header('Content-type: text/plain; charset=' . get_bloginfo('charset'));
		echo $output;
		exit; 

	} // end function

	remove_action( 'do_robots', 'do_robots' );
	add_action( 'do_robots', 'publish_robots_txt' );