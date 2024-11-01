<?php
/**
 * Plugin Name:       WP-AvoideR
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Avoid landing page redirects error shown in site audit results of gtmatrix and google page-speed insights.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-avoider
 * Domain Path:       /languages
 */

 /** Step 2 (from text above). */
add_action( 'admin_menu', 'alpredirectwpavoider_plugin_menu' );

/** Step 1. */
function alpredirectwpavoider_plugin_menu() {
	add_options_page( 'Avoid landing page redirects', 'Avoid landing page redirects', 'manage_options', 'wp-avoiderR', 'alpredirectwpavoider_rediection_settings' );
}

$versions = [
    "http://" => "Non-www",
    "http://www." => "WWW",
    "https://" => "Non-www (secure)",
    "https://www." => "WWW (secure)"
];
$hostname = str_replace("www.","", $_SERVER['HTTP_HOST']);

/** Step 3. */
function alpredirectwpavoider_rediection_settings() {
    global $versions,$hostname,$wpdb;

    $saved = false;
    if(isset($_POST['version'])){
        $saved = alpredirectwpavoider_setVersion();
    }



	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    echo '<div class="wrap">';
    echo '<h1>Avoid redirection</h1>';
    echo '<b>Our investigation recommends you save the setting marked below.</b><br> However, you are welcome to make a different selection.<hr/>';
    echo '<p>Choose pereffered version of your website.</p>';

    $options = "";
    $i = 0;
    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_name = 'siteurl'", OBJECT );
    $currentVersion = $results[0]->option_value;


    foreach($versions as $k => $v){
        $checked = $k.$hostname == $currentVersion ? "checked":"";

        $options .= '<div style="margin-top:5px;"><input '.$checked.'  id="version_'.$i.'" type="radio" name="version" value="'.$i.'"/> <label for="version_'.$i.'">'.$k.$hostname.'</lable></div><br/>';
        $i++;
    }

    echo <<<form
        <form method="post">
            $options
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Set Version"></p>
        </form>
form;
    echo $saved ? "<b>Changes saved!</b><hr/>You will be redirected now...":"";
	echo '</div>';
}

function alpredirectwpavoider_setVersion(){    
    global $hostname,$wpdb,$versions;

    $choosenVersion = sanitize_text_field($_POST['version']);

    if(!is_numeric($choosenVersion) || $choosenVersion < 0 || $choosenVersion > 3){
        echo "<p>There was an error in the submitted value.</p>";
        die();
    }

    $url = array_keys($versions)[$choosenVersion].$hostname;

    //Update wp db
    $wpdb->query("UPDATE {$wpdb->prefix}options SET option_value='$url' WHERE option_id <= 2");

    //Sanitized File paths
    $hta                    = ABSPATH.'.htaccess';
    $hta_backup_location    = ABSPATH.'htaccess.'.time();

    //Using __DIR__ is safed than ABSPATH for the files in plugin directory
    $template_hta = __DIR__."/htaccess"; 
    $rule_path = __DIR__."/".sanitize_file_name($choosenVersion.".rule");


    //Update htaccess
    $template = file_get_contents($template_hta);
    $rule = file_get_contents($rule_path);

    $htaccess = str_replace("#RULES",str_replace("yourdomain.com",$hostname,$rule),$template);

    if(is_writable($hta)){
        //take backup of original htaccess
        $htcontent  = file_get_contents($hta);
        rename($hta,$hta_backup_location);

        //Insert with marker
        $wpedit = insert_with_markers($hta,"Wordpress Redirect Avoider Rules",$htaccess);

        if(!$wpedit){
            //Insertion failed, restore the original htaccess
            rename($hta_backup_location,$hta);
        }
    
    }else{
        echo '<h1>.htaccess not writable</h1>';
        echo '<p>Your .htaccess file is not writable,<br/> please put the following code in your .htaccess file and reload this page.</p>';
        echo "<pre style='background:#fff;padding:20px'>$htaccess</pre>";
        die();
    }
    
 

    echo <<<red
    <script>
        setTimeout(function(){ window.location.reload() },3000);
    </script>
red;

    return true;
}