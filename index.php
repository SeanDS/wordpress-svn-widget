<?php 
/*
Plugin Name: SVN Feed
Plugin URI: http://attackllama.com/
Description: SVN commit log widget in your sidebar!
Version: 0.6
Author: Sean Leavey
Author URI: http://attackllama.com
License: GPL2
*/

include('functions.php');

add_action( 'widgets_init', 'svn_feed_init');