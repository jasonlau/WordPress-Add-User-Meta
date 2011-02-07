<?php
/**
 * @package Add User Meta
 * @version 1.0.0 
 * @author Jason Lau
 * @link http://jasonlau.biz
 * @copyright 2011
 * @license GNU/GPL 3+
 * @uses WordPress
 
Plugin Name: Add User Meta
Plugin URI: http://jasonlau.biz
Description: This plugin simply adds additional keys to the user meta table upon registration.
Author: Jason Lau
Version: 1.0.0
Author URI: http://jasonlau.biz
*/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

function jl_add_user_meta_manager(){
    global $wpdb;
if (isset($_POST['jl_add_user_meta_data'])){
    $original_meta_data = get_option('jl_add_user_meta_data');
    if($original_meta_data != ""):
    $metakeys = explode("\n",$original_meta_data);
    foreach($metakeys as $metakey):
    $mk = explode("=",$metakey);
    $wpdb->query("DELETE FROM ".$wpdb->usermeta." WHERE meta_key = '".$mk[0]."'");
    endforeach;
    endif;
    $jl_add_user_meta_data = stripslashes(trim($_POST['jl_add_user_meta_data']));
    if($jl_add_user_meta_data != ''):	
	update_option('jl_add_user_meta_data', $jl_add_user_meta_data );
    else:
    update_option('jl_add_user_meta_data', '' );
    endif;
    $status = "<img id='jl_add_user_meta_success' src='".site_url()."/wp-includes/images/smilies/icon_mrgreen.gif' alt=':-)' />";
 }
 
$jl_add_user_meta_data = get_option('jl_add_user_meta_data');

?>
<div class="wrap">
<div id="icon-users" class="icon32"><br /></div>
<h2>Add User Meta</h2>
<hr />
<?php if($status) echo "" . $status . ""; ?>
<p>This plugin adds custom user meta keys when a user registers an account.</p>


<form method="post" action="#">
<label><strong>Meta Keys &amp; Values</strong></label><br />
<textarea name="jl_add_user_meta_data" rows="10" cols="40">
<?php echo $jl_add_user_meta_data; ?>
</textarea><br />
<input type="submit" value="Save" /><br />
<p><strong>Instructions:</strong><br />
Insert key/value pairs in the field with each pair on a new line.</p>
<strong>Example:</strong><br />
<code>key1=value1<br />
key2=value2<br />
key3=value3</code>
</form>
<br />
<hr />
<a href="http://www.gnu.org/licenses/gpl.html" target="_blank"><img src="http://www.gnu.org/graphics/gplv3-127x51.png" alt="GNU/GPL" border="0" /></a><em><strong>Share And Share-Alike!</strong></em><br />
<code><strong>Another <em><strong>Quality</strong></em> Work From  <a href="http://JasonLau.biz" target="_blank">JasonLau.biz</a></strong> - &copy;2011 Jason Lau</code>
</div>
<?php
}

add_action('user_register', 'jl_add_user_meta_update_data');
function jl_add_user_meta_update_data() {
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM wp_usermeta ORDER BY user_id DESC LIMIT 1");
    $jl_add_user_meta_data = get_option('jl_add_user_meta_data');
    if($jl_add_user_meta_data != ""):
    $metakeys = explode("\n", $jl_add_user_meta_data);
    foreach($metakeys as $metakey):
    $mk = explode("=", $metakey);
    add_user_meta($data[0]->user_id, $mk[0], $mk[1], false);
    endforeach;
    endif;
}

function jl_add_user_meta_admin_menu(){
    add_submenu_page('users.php', 'Add User Meta', 'Add User Meta', 'edit_users', 'add-user-meta', 'jl_add_user_meta_manager');
}
function jl_add_user_meta_install(){
   add_option('jl_add_user_meta_data', '');
}
function jl_add_user_meta_deactivate(){
    delete_option('jl_add_user_meta_data');
}

add_action('admin_menu', 'jl_add_user_meta_admin_menu');
register_activation_hook(__FILE__, 'jl_add_user_meta_install');
register_deactivation_hook(__FILE__, 'jl_add_user_meta_deactivate');
?>