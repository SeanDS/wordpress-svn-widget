<?php 
/*
Plugin Name: SVN Recent Commits Widget
Plugin URI: http://attackllama.com/
Description: Recent SVN commit messages in your sidebar!
Version: 0.7
Author: Sean Leavey
Author URI: http://attackllama.com
License: GPL2
*/

include('functions.php');

add_action( 'widgets_init', 'svn_widget_init');