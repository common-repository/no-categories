<?php
/*
Plugin Name: No Categories
Plugin URI: http://wordpress.org/extend/plugins/no-categories
Description: A plugin to disable all use of categories in WordPress. This might be useful if you don't categorize any of your posts on your blog. Basicly it hides the category list in the post-edit, and removes the category tab from the Manage menu. Behind the scenes, all the posts now will have the default category (usually "Uncategorized"). 
Author: Herman Andresen
Version: 1.0
*/ 

/*  Copyleft 2008  Herman Andresen  (email : herman.andresen+blogg@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*

Stuff not removed (yet):
categories in post listings
categories in filtering

Stuff removed completely:
Widget		: removes the default widget that shows the categories
menus		: removes the "Edit categories" from the admin menu

Stuff just hidden in CSS:
(later versions will try to push these up in the removed completely section)
#categorydiv		: when editing posts, removes the box for selecting category
.entry-categories	: when showing the post, removes the "in <category>"-part of the post-header




*/


if (!class_exists('no_cats')) {
    class no_cats	{

		
		/**
		* PHP 4 Compatible Constructor
		*/
		function no_cats(){$this->__construct();}
		
		/**
		* PHP 5 Constructor
		*/		
		function __construct(){


		add_action("admin_menu", array(&$this,"mod_admin_pages"));
		add_action("admin_head", array(&$this,"add_css")); //add css to admin pages
		add_action("wp_head", array(&$this,"add_css")); //add css to regular site pages 
		add_action('widgets_init',array(&$this,'unregister_widget'));


		}
		/**
		* Disables the Categories widget.
		Wordpress seems to create the id for this widget almost
		randomly, and we have to test the $wp_registered_widget
		to get the actual id for this to work
		*/		
	function unregister_widget() {
			global $wp_registered_widgets;
			global $wdg_type;
			$wdg_type = "";
			foreach ($wp_registered_widgets as $wdg_key => $wdg_value) {
			    foreach ($wdg_value as $key => $value) {
				    if ($key == "classname" && $value == "widget_categories") {
					  $wdg_type = $wdg_key;
					}
				}
			}
			if ($wdg_type != "") {
				unregister_sidebar_widget($wdg_type);
			}
	}

	function mod_admin_pages(){
		global $submenu;
		$submenu['edit.php'][20] = '';
	}
		

	/**
	* Adds a link to the stylesheet to the header
	*/
	function add_css(){
		echo '<link rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/no-categories/style.css" type="text/css" media="screen" />';
		echo ''; 
	}

    }
}

//instantiate the class
if (class_exists('no_cats')) {
	$no_cats = new no_cats();
}



?>
