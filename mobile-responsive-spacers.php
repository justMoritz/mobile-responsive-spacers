<?php
/**
 * Plugin Name:     Mobile Responsive Spacers
 * Plugin URI:      https://github.com/justMoritz/mobile-responsive-spacers
 * Description:     A simple Wordpress Plugin lets you globally adjust every Wordpress Gutenberg Spacer Block to be a different size on mobile.
 * Author:          Moritz Zimmer
 * Author URI:      https://www.moritzzimmer.com
 * Text Domain:     mobile-responsive-spacers
 * Domain Path:     /languages
 * Version:         1.2.1
 *
 * @package         mobile-responsive-spacers
 */


$gloablVersion = "1.2.1";


class Maz_Mrs_Plugin {

  /**
   * Hooks used below
   */
  public function __construct() {
    add_action( "admin_menu", array( $this, "create_maz_mrs_plugin_settings_page" ) );
    add_action( "admin_init", array( $this, "maz_mrs_setup_sections" ) );
  }


  /**
   * Adds the menu item and page
   */
  public function create_maz_mrs_plugin_settings_page() {
    $page_title = "Mobile Responsive Spacers Page";
    $menu_title = "Mobile Responsive Spacers";
    $capability = "manage_options";
    $slug       = "maz_mrs_fields";
    $callback   = array( $this, "maz_mrs_settings_content" );
    $icon       = "dashicons-admin-plugins";
    $position   = 100;

    add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
  }


  /**
   * Callback for the breakpoint field init
   * Also sets the default if not set
   */
  public function maz_mrs_field_callback_breakpoint( $arguments ) {
    if ( get_option( "maz_mrs_breakpoint" ) === false ){
      update_option( "maz_mrs_breakpoint", "768" );
    }
    ?>
      <input
        name="maz_mrs_breakpoint"
        id="maz_mrs_breakpoint"
        step="1"
        type="number"
        value="<?php echo esc_attr( get_option( "maz_mrs_breakpoint" ) );?>"
      />
    <?php
  }


  /**
   * Callback for the ratio field init
   * Also sets the default if not set
   */
  public function maz_mrs_field_callback_ratio( $arguments ) {
    if ( get_option( "maz_mrs_ratio" ) === false ){
      update_option( "maz_mrs_ratio", "0.5" );
    }
    ?>
      <input
        name="maz_mrs_ratio"
        id="maz_mrs_ratio"
        step="0.01"
        type="number"
        value="<?php echo esc_attr( get_option( "maz_mrs_ratio" ) );?>"
      />
    <?php
  }


  /**
   * Sets up the section with the input field in the code below
   */
  public function maz_mrs_setup_sections() {
    # Adds section
    add_settings_section( "maz_mrs_section", "", false, "maz_mrs_fields" );

    # Adds both fields
    add_settings_field( "maz_mrs_breakpoint_section", "Mobile Breakpoint", array( $this, "maz_mrs_field_callback_breakpoint" ), "maz_mrs_fields", "maz_mrs_section" );
    add_settings_field( "maz_mrs_breakpoint_section_2", "Ratio", array( $this, "maz_mrs_field_callback_ratio" ), "maz_mrs_fields", "maz_mrs_section" );

    # Registers those fields
    register_setting( "maz_mrs_fields", "maz_mrs_breakpoint" );
    register_setting( "maz_mrs_fields", "maz_mrs_ratio" );
  }


  /**
   * The Callback function called in the add_options_page action
   */
  public function maz_mrs_settings_content() {
  ?>
    <div class="wrap">
      <h2>Mobile Responsive Spacers Settings</h2>
      <form method="post" action="options.php">
        <br>
        <hr>
        <br><i>Breakpoint</i> configures the screen width in pixels when the spacers change size.
        <br>
        <br><i>Ratio</i> defines the amount of change. For example, 0.5 will halve the size, 2 will double it.
        <br>

        <?php
          # Places all the fields defined above and the submit button in the markup
          settings_fields( "maz_mrs_fields" );
          do_settings_sections( "maz_mrs_fields" );
          submit_button();
        ?>
      </form>
      <div class="">
        A Plugin by <a href="https://moritzzimmer.com">Moritz Zimmer</a>, <?php echo esc_html(Date("Y") )?>
      </div>
    </div>
  <?php
  }
}

new Maz_Mrs_Plugin();



/*** ENQUEUE ***/
function maz_halve_spacers_on_mobile_init() {
  global $gloablVersion;

  $vars_from_php = array(
    "breakpoint" => esc_attr( get_option( "maz_mrs_breakpoint" ) ),
    "ratio" => esc_attr( get_option( "maz_mrs_ratio" ) )
  );

  wp_enqueue_script( "maz_mrs_scripts", plugin_dir_url( __FILE__ ) . "assets/mobile-responsive-spacers.js", [], $gloablVersion, true );
  wp_localize_script( "maz_mrs_scripts", "mazMrsVars", $vars_from_php );
}
add_action("wp_enqueue_scripts", "maz_halve_spacers_on_mobile_init");


add_filter( "plugin_action_links_" . plugin_basename(__FILE__), "add_maz_mrs_action_links" );
function add_maz_mrs_action_links ( $links ) {
    $settingslinks = array(
      "<a href=" . esc_attr( admin_url( "options-general.php?page=maz_mrs_fields" ) ). ">Settings</a>",
    );
  return array_merge( $links, $settingslinks );
}



