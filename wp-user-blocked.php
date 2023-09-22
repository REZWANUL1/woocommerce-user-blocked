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

// //? adding user role
add_action('init', 'wub_adding_role');
function wub_adding_role()
{
   add_role('wub_user_blocked', __('Blocked', 'wub'), array('blocked' => true));
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
add_action('template_redirect', 'wub_redirect_to_blocked');
function wub_redirect_to_blocked()
{
   $is_blocked = intval(get_query_var('blocked'));
   if ($is_blocked || current_user_can('blocked')) {
?>
      <!DOCTYPE html>
      <html lang="en">

      <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title><?php _e('Blocked User', 'user-role-blocker'); ?></title>
         <?php
         wp_head();
         ?>
      </head>

      <body>
         <h2 style="text-align: center"><?php _e('You are blocked', 'user-role-blocker'); ?></h2>
         <?php
         wp_footer();
         ?>
      </body>

      </html>
<?php
      die();
   }
};




function remove_wub_user_blocked_role_when_deactivate()
{
   remove_role('wub_user_blocked');
}

register_deactivation_hook(__FILE__, 'remove_wub_user_blocked_role_when_deactivate');
