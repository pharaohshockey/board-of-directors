<?php
    /*
    Plugin Name: Pharaohs Board of Directors
    Plugin URI: https://github.com/pharaohshockey/
    Description: Create a custom post type for the Board of Directors.
    Version: 0.1
    Author: Pat McGhen
    Author URI: http://mcghen.com
    License: WTFPL
    License URI: http://www.wtfpl.net/
    */

    function pharaohs_create_board () {
        register_post_type('board', array (
            'labels' => array (
                'name'          => __('Board Members'),
                'singular_name' => __('Board Member'),
                'add_new_item'  => __('Add New Board Member'),
                'edit_item'     => __('Edit Board Member')
            ),
            'description'   => 'These folks keep the lights on.',
            'public'        => true,
            'menu_position' => 6,
            'menu_icon'     => 'dashicons-groups',
            'has_archive'   => true,
            'supports'      => array (
                'title',
                'editor',
                'thumbnail',
                'page-attributes',
                'custom-fields',
            )
        ));
    }

    function pharaohs_board_admin () {
        add_meta_box('pharaohs-board-position-meta-box',
                     'Position held:',
                     'pharaohs_display_board_position_meta_box',
                     'normal',
                     'high');
    }

    function pharaohs_display_board_position_meta_box ($board_member) {
        // Retrieve the current board member's name and position.
        $board_member_name = the_title();
        $board_member_position = esc_html(get_post_meta($board_member->position_held, 'board_member_position', true));
    ?>
        <div class="pharaohs-meta-field">
            <label for="pharaohs-board-member-name">Name:</label>
            <input name="pharaohs-board-member-name">
        </div>
        <div class="pharaohs-meta-field">
            <label for="pharaohs-board-position">Position held:</label>
            <input name="pharaohs-board-position">
        </div>
    <?php
    }

    function pharaohs_add_board_member ($board_member_id, $board_member) {
        // Check the post type for board member
        if ($board_member->post_type == 'board') {
            // Store data in post meta table
            if (isset($_POST['pharaohs-board-member-name']) && $_POST['pharaohs-board-member-name'] != '') {
                update_post_meta($board_member_id, 'board_member', $_POST['pharaohs-board-member-name']);
            }

            if (isset($_POST['pharaohs-board-position']) && $_POST['pharaohs-board-position'] != '') {
                update_post_meta($board_member_position, 'board_member', $_POST['pharaohs-board-position']);
            }
        }
    }

    add_action('save_post', 'pharaohs_add_board_member', 10, 2);
    add_action('admin_init', 'pharaohs_board_admin');
    add_action('init', 'pharaohs_create_board');
?>
