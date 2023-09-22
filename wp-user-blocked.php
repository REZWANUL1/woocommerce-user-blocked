<?php
/*
 * Plugin Name:       Wp User Blocked
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rezwanul Haque
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       wub
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
   exit;
}
function  wub_load_my_plugin_translation()
{
   load_plugin_textdomain('your-plugin-textdomain', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'wub_load_my_plugin_translation');

//? adding user role
add_action('init', 'wub_adding_role');
function wub_adding_role()
{
   add_role('wub_user_blocked', __('Blocked', 'wub'), ['blocked' => true]);
   add_rewrite_rule('blocked/?$', 'index.php?blocked=1', 'top');
}

//? process blocked permalink
add_filter('query_vars', 'wub_process_query');
function wub_process_query()
{
   $query_vars[] = 'blocked';
   return $query_vars;
}
//? process query vars
add_action('template_redirect', 'wub_blocked_redirect');
function wub_blocked_redirect()
{
   $is_blocked = intval(get_query_var('blocked'));
   if ($is_blocked) {
      _e("You are blocked", 'wub');
      die();
   }
}
