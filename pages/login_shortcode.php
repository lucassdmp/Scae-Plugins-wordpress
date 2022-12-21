<?php
function login_form_shortcode() {
    // Check if user is already logged in
    if ( is_user_logged_in() ) {
        wp_redirect(home_url());
        exit();
    }
  
    // Set up variables for the form
    $redirect_to = home_url();
    if ( isset( $_REQUEST['redirect_to'] ) ) {
      $redirect_to = urldecode( $_REQUEST['redirect_to'] );
    }
    $login_form_args = array(
      'echo' => true,
      'redirect' => $redirect_to,
      'form_id' => 'loginform',
      'label_username' => __( 'Username' ),
      'label_password' => __( 'Password' ),
      'label_remember' => __( 'Remember Me' ),
      'label_log_in' => __( 'Log In' ),
      'id_username' => 'user_login',
      'id_password' => 'user_pass',
      'id_remember' => 'rememberme',
      'id_submit' => 'wp-submit',
      'remember' => true,
      'value_username' => '',
      'value_remember' => false
    );
  
    // Output the login form
    return wp_login_form( $login_form_args) . '<div><a href="' . wp_lostpassword_url() . '">Lost your password?</a></div>'
    . '<div><a href="' . wp_registration_url() . '"> Register </a></div>';
  }
add_shortcode( 'login_form', 'login_form_shortcode' );
?>