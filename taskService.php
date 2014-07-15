<?php
/*
Plugin Name: Task Service
Plugin URI: https://github/lev09/taskService
Description: Plugin to that is including services for tasks.
Version: 1.0
Author: Levon Hakopyan
Author URI: https://github/lev09
License: GPL2
*/


@include "../../../wp-load.php";

$dir = plugin_dir_path(__FILE__);
@include_once "$dir/query.php";

function task_api_init() {
  add_filter('rewrite_rules_array', 'task_api_rewrites');
}

function task_api_activation() {
  // Add the rewrite rule on activation
  global $wp_rewrite;
  add_filter('rewrite_rules_array', 'task_api_rewrites');
  $wp_rewrite->flush_rules();
}

function task_api_deactivation() {
  // Remove the rewrite rule on deactivation
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}

function task_api_rewrites($wp_rules) {
  //$base = 'task-api';
  $task_api_rules = array(
    "$base\$" => 'index.php?task_api=info',
    "$base/(.+)\$" => 'index.php?task_api=$matches[1]'
  );
  return array_merge($task_api_rules, $wp_rules);
}

// Add initialization and activation hooks
add_action('init', 'task_api_init');
register_activation_hook("$dir/taskService.php", 'task_api_activation');
register_deactivation_hook("$dir/taskService.php", 'task_api_deactivation');

?>
