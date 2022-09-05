<?php
/*
Plugin Name:Add Post From Frontend
Description:This is a custom plugin for custom post from frontend for learning purpose
Version:1.0.0
Author:Rahman
*/

define("PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
define("PLUGINS_URL",plugins_url());


function sports_init()
    {
        $labels = array(
            'name'                  => 'Sports',
            'singular_name'         => 'Sports',
            'menu_name'             => 'Sports',
            'name_admin_bar'        => 'sports',
            'add_new'               => 'Add New sport',
            'add_new_item'          => 'Add New sport',
            'new_item'              => 'New sports',
            'edit_item'             => 'Edit sports',
            'view_item'             => 'View sports',
            'all_items'             => 'All sports',
            'search items'          => 'Search sports',
            'parent_item_colon'     => 'Parent sports',
            'not_found'             => 'No sports Found',
            'not_found_in_trash'    => 'No sports Found In Trash',
            );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicaly_queryable'   => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => 'sports' ),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'Hierarchical'          => false,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-wordpress-alt',
            'supports'              => array( 'title', 'editor','excerpt', 'author', 'thumbnail','hierarchical','revisions'),
            'taxonomies'            =>array('category'),
            );

        register_post_type('sports',$args);
    }
    add_action('init','sports_init');

    function sport_submenu()
    {
        add_submenu_page("edit.php?post_type=sports","Associate Sport","Associate Sport","manage_options","associte-sports","associate_submenu_sport");
    }
    add_action("admin_menu","sport_submenu");

    function associate_submenu_sport()
    {
        global $wpdb;
        $action=isset($_GET['action']) ? trim($_GET['action']):"";
        if($action == "trash-action")
        {
            $id=isset($_GET['id'])? intval($_GET['id']) :"";
            $trash=$wpdb->update(
                'wp_assoc_sports',
                array('status'=>'trash'),
                array('id'=>$id)
            );
            if($trash)
            {
                return include PLUGIN_DIR_PATH."/views/sport-table.php";
            }

        }
        else
        {
            include PLUGIN_DIR_PATH."/views/sport-table.php";
        }
    }

    function addSports()
    {
        include PLUGIN_DIR_PATH."/views/add-sport.php";
    }
    add_shortcode("sportPost","addSports");

    function associate_sports()
    {
        include PLUGIN_DIR_PATH."/views/sport-associate.php";
    }
    add_shortcode("sportAssociate","associate_sports");

    function all_post()
    {
        include PLUGIN_DIR_PATH."/views/all-posts.php";
    }
    add_shortcode("allposts","all_post");

   function delete_post()
    {
        global $wpdb;
        if(isset($_POST['param']) && $_POST['param'] == "postDel")
        {
            $id = $wpdb->escape($_POST['id']);
            $postmeta=get_post_meta($id);
            $metaId=$postmeta['_thumbnail_id'][0];
            $del_post=wp_delete_post($id);
            if($del_post)
            {
                wp_delete_post($metaId);
            delete_post_meta($id,$metaId);
            $success="Post Have Been Deleted Successfully.";
            echo json_encode(['status'=>200,"success"=>$success]);
            }
            exit();
        }
        //die();
    }
    add_action("wp_ajax_delete_post","delete_post");

    //user registration code 

    function register_user()
    {
        include PLUGIN_DIR_PATH."/views/user-registration.php";
    }
    add_shortcode("userRegister","register_user");



    // function new_action_methods( $actionmethods ) {
    //     $actionmethods['action'] = 'Action';
    //     return $actionmethods;  
    // }
    // add_filter( 'user_contactmethods', 'new_action_methods', 10, 1 );
    // function new_custom_user_table( $column_action ) {
    //     $column_action['action'] = 'Action';
    //     return $column_action;
    // }
    // add_filter( 'manage_users_columns', 'new_custom_user_table' );
    // function new_custom_user_table_row( $value, $column_action, $user_id ) {
    //      $userIds = get_current_user_id();
    //     switch ($column_action) {
    //         case 'action' :
    //            if($userIds == $user_id)
    //            {
    //                return $user_id;
    //            }
    //            else
    //            {
    //                return "Deactive";
    //            }
    //         default:  
    //     }
    //     return $value;
    // }
    // add_filter( 'manage_users_custom_column', 'new_custom_user_table_row', 10, 3 );
?>