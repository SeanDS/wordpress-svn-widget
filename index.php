<?php 
/*
Plugin Name: SVN Recent Commits Widget
Plugin URI: http://attackllama.com/
Description: Recent SVN commit messages in your sidebar!
Version: 0.72
Author: Sean Leavey
Author URI: http://attackllama.com/
Plugin URI: http://github.com/SeanDS/wordpress-svn-widget/
License: GPL2
*/

include('functions.php');

add_action( 'widgets_init', 'svn_widget_init');