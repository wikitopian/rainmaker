<?php
/*
* Plugin Name: Rainmaker
* Plugin URI: http://www.github.com/wikitopian/rainmaker
* Description: Constituent Relationship Manager for BuddyPress
* Version: 0.1
* Author: Matt Parrott
* Author URI: http://www.swarmstrategies.com/matt
* License: GPLv2
* */

class Rainmaker {

    public function __construct() {

        add_action( 'bp_groups_admin_meta_boxes', array( &$this, 'add_rainmaker_box' ) );
        add_action( 'admin_init', array( &$this, 'rainmaker_box_save' ) );

    }

    public function add_rainmaker_box() {

        add_meta_box(
            'bp_group_rainmaker',
            'Rainmaker',
            array( &$this, 'rainmaker_box' ),
            get_current_screen()->id,
            'side',
            'core'
        );

    }

    public function rainmaker_box( $item ) {

        $group_id = $item->id;

        $is_project = groups_get_groupmeta( $item->id, 'rainmaker_is_project' );

        wp_nonce_field( plugin_basename( __FILE__ ), 'rainmaker_nonce' );

        echo "<input type='hidden' name='rainmaker_group_id' value ='{$group_id}' />";

        echo "<input type='checkbox' name='rainmaker_is_project' value='1' ";
        checked( $is_project );
        echo "  /> ";

        echo "Project<br /><br />";

    }

    public function rainmaker_box_save() {

        if(
            !isset( $_POST['rainmaker_nonce'] )
            ||
            !wp_verify_nonce( $_POST['rainmaker_nonce'], plugin_basename( __FILE__ ) ) 
        ) {
            return;
        }

        $group_id = intval( $_POST['rainmaker_group_id'] );

        $is_project = intval( $_POST['rainmaker_is_project'] );

        groups_update_groupmeta( $group_id, 'rainmaker_is_project', $is_project );

    }

}

$rainmaker = new Rainmaker();
