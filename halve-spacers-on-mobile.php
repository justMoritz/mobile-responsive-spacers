<?php
/**
 * Plugin Name:     Halve Spacers on Mobile
 * Plugin URI:      https://github.com/justMoritz/halve-spacers-on-mobile
 * Description:     Takes the every Wordpress Gutenberg Spacer Block and halves it on mobile
 * Author:          Moritz Zimmer
 * Author URI:      https://www.moritzzimmer.com
 * Text Domain:     halve-spacers-on-mobile
 * Domain Path:     /languages
 * Version:         1.0.4
 *
 * @package         halve-spacers-on-mobile
 */


$gloablVersion = "1.0.0";


class Maz_hspm_Fields_Plugin {

  /**
   * Hooks used below
   */
  public function __construct() {
    add_action( 'admin_menu', array( $this, 'create_maz_hspm_plugin_settings_page' ) );
    add_action( 'admin_init', array( $this, 'maz_hspm_setup_sections' ) );
  }


  /**
   * Adds the menu item and page
   */
  public function create_maz_hspm_plugin_settings_page() {
    $page_title = 'Halve Spacers On Mobile Page';
    $menu_title = 'Halve Spacers On Mobile';
    $capability = 'manage_options';
    $slug = 'maz_hspm_fields';
    $callback = array( $this, 'maz_hspm_settings_content' );
    $icon = 'dashicons-admin-plugins';
    $position = 100;

    add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
  }


  /**
   * Callback for the breakpoint field init
   * Also sets the default if not set
   */
  public function maz_hspm_field_callback_breakpoint( $arguments ) {
    if ( get_option( 'maz_hspm_breakpoint' ) === false ){
      update_option( 'maz_hspm_breakpoint', '768' );
    }
    echo '<input name="maz_hspm_breakpoint" id="maz_hspm_breakpoint" type="text" value="' . get_option( 'maz_hspm_breakpoint' ) . '" />';
  }


  /**
   * Callback for the ratio field init
   * Also sets the default if not set
   */
  public function maz_hspm_field_callback_ratio( $arguments ) {
    if ( get_option( 'maz_hspm_ratio' ) === false ){
      update_option( 'maz_hspm_ratio', '0.5' );
    }
    echo '<input name="maz_hspm_ratio" id="maz_hspm_ratio" type="text" value="' . get_option( 'maz_hspm_ratio' ) . '" />';
  }


  /**
   * Sets up the section with the input field in the code below
   */
  public function maz_hspm_setup_sections() {
    # Adds section
    add_settings_section( 'maz_hspm_section', '', false, 'maz_hspm_fields' );

    # Adds both fields
    add_settings_field( 'maz_hspm_breakpoint_section', 'Mobile Breakpoint', array( $this, 'maz_hspm_field_callback_breakpoint' ), 'maz_hspm_fields', 'maz_hspm_section' );
    add_settings_field( 'maz_hspm_breakpoint_section_2', 'Ratio', array( $this, 'maz_hspm_field_callback_ratio' ), 'maz_hspm_fields', 'maz_hspm_section' );

    # Registers those fields
    register_setting( 'maz_hspm_fields', 'maz_hspm_breakpoint' );
    register_setting( 'maz_hspm_fields', 'maz_hspm_ratio' );
  }


  /**
   * The Callback function called in the add_options_page action
   */
  public function maz_hspm_settings_content() {
    ?>
      <div class="wrap">
        <h2>Halve Spacers On Mobile Settings</h2>
        <form method="post" action="options.php">
          <br>
          <hr>
          <br>Breakpoint configures the screen width in pixels when the spacers change size.
          <br>Ratio defines the amount of change. For example, 0.5 will halve the size, 2 will double it.
          <br>

          <?php
            # Places all the fields defined above and the submit button in the markup
            settings_fields( 'maz_hspm_fields' );
            do_settings_sections( 'maz_hspm_fields' );
            submit_button();
          ?>
        </form>
        <div class="">
          A Plugin by Moritz Zimmer, 2020
        </div>
      </div>
    <?php
    }


}

new Maz_hspm_Fields_Plugin();



/*** ENQUEUE ***/
function maz_halve_spacers_on_mobile_init() {
  global $gloablVersion;

  $phpInfo = array(
    'breakpoint' => get_option( 'maz_hspm_breakpoint' ),
    'ratio' => get_option( 'maz_hspm_ratio' )
  );

  wp_enqueue_script('maz_hspm_scripts', plugin_dir_url( __FILE__ ) . 'assets/halve-spacers-on-mobile.js', ['jquery'], $gloablVersion, true);
  wp_localize_script( 'maz_hspm_scripts', 'mazHspmVars', $phpInfo );
}
add_action('wp_enqueue_scripts', 'maz_halve_spacers_on_mobile_init');


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_maz_hspm_action_links' );
function add_maz_hspm_action_links ( $links ) {
    $settingslinks = array(
      '<a href="' . admin_url( 'options-general.php?page=maz_hspm_fields' ) . '">Settings</a>',
    );
  return array_merge( $links, $settingslinks );
}



