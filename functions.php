<?php

class svn_feed_widget extends WP_Widget {
  public function __construct() {
    parent::__construct(
      'svn_feed_widget',
      __('SVN Feed Widget', 'text_domain'),
      array( 'description' => __( 'An SVN commit log widget.', 'text_domain' ), )
    );
  }
  
  public function widget( $args, $instance ) {    
    svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_USERNAME, 'user');
    svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_PASSWORD, 'pass');
    svn_auth_set_parameter(PHP_SVN_AUTH_PARAM_IGNORE_SSL_VERIFY_ERRORS, true);
    
    $title = $instance['title'];
    $svn_url = $instance['svn_url'];
    $number = ($instance['number'] > 0) ? $instance['number'] : 10;
    $log_word_limit = ($instance['log_word_limit'] > 0 ? $instance['log_word_limit'] : 20);
    
    $html = '<aside class="widget clearfix"><h3 class="widget-title">' . $title . '</h3>';
    
    $svn_logs = svn_log($svn_url, null, null, $number);
    
    if ( count($svn_logs) ) {
      $html .= '<ul>';

      foreach ( $svn_logs as $svn_log ) {
        $datetime = date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($svn_log['date']));

	$html .= '<li>';
	$html .= '<a href="' . $instance['svn_url'] . '?p=' . intval($svn_log['rev']) . '" title="' . $datetime . '">r' . intval($svn_log['rev']) . '</a> ' . esc_attr($svn_log['author']) . ': ' . $this->limit_words(esc_attr($svn_log['msg']), $log_word_limit);
	$html .= '</li>';
      }

      $html .= '</ul>';
    } else {
      $html .= '<p>No SVN logs to show.</p>';
    }

    $html .= '</aside>';

    echo $html;
  }

  private function limit_words($string, $word_limit) {
    $words = explode(' ', $string, ($word_limit + 1));
    $append = '';
    
    if ( count($words) > $word_limit) {
      array_pop($words);
      $append = '...';
    }
    
    return implode(' ', $words) . $append;
  }
	
  public function form( $instance ) {
    $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
    $svn_url = isset( $instance['svn_url'] ) ? esc_attr( $instance['svn_url'] ) : '';
    $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 10;
    $log_word_limit = isset( $instance['log_word_limit'] ) ? absint( $instance['log_word_limit'] ) : 20;
?>
<p>
  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'svn_url' ); ?>"><?php _e('SVN URL:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'svn_url' ); ?>" name="<?php echo $this->get_field_name( 'svn_url' ); ?>" type="text" value="<?php echo esc_attr( $svn_url ); ?>" />
</p>        
<p>
  <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of log messages to show:' ); ?></label>
  <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
</p>    
<p>
  <label for="<?php echo $this->get_field_id( 'log_word_limit' ); ?>"><?php _e( 'Max words to show per log message:' ); ?></label>
  <input id="<?php echo $this->get_field_id( 'log_word_limit' ); ?>" name="<?php echo $this->get_field_name( 'log_word_limit' ); ?>" type="text" value="<?php echo $log_word_limit; ?>" size="3" />
</p>
<?php 
  }

  public function update( $new_instance, $old_instance ) {
    // processes widget options to be saved

    $instance = array();
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['svn_url'] = strip_tags($new_instance['svn_url']);
    $instance['number'] = (int) $new_instance['number'];
    $instance['log_word_limit'] = (int) $new_instance['log_word_limit'];

    return $instance;
  }
}

if (! function_exists( 'svn_feed_init' )) {
  function svn_feed_init() {
    register_widget( 'svn_feed_widget' );
  }
}

?>