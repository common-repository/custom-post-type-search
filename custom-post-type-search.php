<?php
/*
Plugin Name: Custom Post Type Search - taxonomies and metadata
Description: Search into your custom post type, taxonomies and metadata.
Plugin URI: http://wordpress.org/extend/plugins/custom-post-type-search/
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
	public static function search( $_pousts = '' )
	{
            /* 
             *  - post_types
             *          - taxonomies
             *              - custom data
             */
            global $wpdb, $wp_query;

            $search = !empty($_GET['search'])? strtolower($_GET['search']): '';
            $search = (empty($_GET['search']) && !empty($_POST['search']))? strtolower($_POST['search']): $search;
            $searched = $search;
            $search = preg_replace('/[ \t\n\r\f\v.,!?:...+-_]/', '|', $search);
            $post_types = !empty($_pousts)? $_pousts: get_post_types();
            $str = ' ';
            $posts_ids = array();
            $found = false;
            
            foreach($post_types as $post_type)
            {          
                $the_query = new WP_Query( array( 'post_type' => $post_type ) );
                
                while ( $the_query->have_posts() ) : $the_query->the_post();
                    
                    $taxonomies = get_the_taxonomies(get_the_ID());
                    
                    foreach ( $taxonomies as $key => $value )
                    {
                        $terms = get_terms($key);
                        
                        foreach ($terms as $term)
                        {   
                            //print "<BR>post type: $post_type<BR>key: $key => term slug: $term->slug<BR>";   
                            $args = array(
                                'post_type' => $post_type,
                                'post_status' => 'publish',
                                $key => $term->slug,
                                'nopaging' => true
                            );
                            
                            $loop = new WP_Query($args);
                            
                            while ( $loop->have_posts() ) : $loop->the_post();
                                
                                if( !in_array(get_the_ID(),$posts_ids) )
                                {
                                    $posts_ids[] = get_the_ID();
                                    $str .= ' '.strtolower(get_the_title());
                                    $str .= ' '.strtolower(get_the_excerpt());
                                    $str .= ' '.strtolower(get_the_content());
                                    $str .= ' '.strtolower(get_the_attachment_link());
                                    $str .= ' '.strtolower(get_the_author());
                                    $str .= ' '.strtolower(get_the_category());
                                    $str .= ' '.strtolower(get_the_category_list());
                                    $str .= ' '.strtolower(get_the_category_rss());
                                    $str .= ' '.strtolower(get_the_content_feed());
                                    $str .= ' '.strtolower(get_the_date());
                                    $str .= ' '.strtolower(get_the_generator());
                                    $str .= ' '.strtolower(get_the_tag_list());
                                    $str .= ' '.strtolower(get_the_tags());
                                    
                                    $post_custom = get_post_custom();
                                    
                                    foreach ($post_custom as $custom)
                                    {
                                        $str .= $custom[0];
                                    }
                                   
                                    if ( preg_match("/($search)/i", $str, $matches, PREG_OFFSET_CAPTURE) ) 
                                    {
                                        $title = get_the_title();
                                        $excerpt = get_the_excerpt();
                                        
                                        if( !empty($matches[0]) && !empty($title) && !empty($excerpt) )
                                        {
                                            ?><br><?php
                                            ?><h3><a href="<?php echo the_permalink(); ?>"><?php the_title(); ?></a></h3><?php
                                            ?><div><a href="<?php echo the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></div><?php
                                            ?><div><?php the_excerpt(); ?></div><?php
                                            ?><br><?php
                                            $str = '';
                                            $found = true;
                                        }
                                    }
                                }
                            endwhile;
                        }
                    }
                endwhile;
            }
           
            if($found)
            {
                ?><?php
            }
            else
            {
                echo '<br>';
                ?><h3><?php echo $searched; ?> not found!</h3><?php
            }
	}

        public static function searchForm( $post_types = '' )
	{
        ?>
            <form method="post">
        <?php
            if( !empty($post_types) )
            {
                foreach($post_types as $post_type)
                {
                    ?><input type="hidden" name="post_type[]" value="<?php echo $post_type; ?>" /><?php
                }
            }
        ?>
                    <input type="text" name="search" value="Search..." />
                    <input type="submit" value="OK" />
            </form>
        <?php
        }
}
if( isset($_POST['search']) && !empty($_POST['search']) )
    customPostType::search();
?>