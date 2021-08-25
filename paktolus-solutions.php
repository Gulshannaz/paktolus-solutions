<?php
/*
Plugin Name: Paktolus Custome Plugin
Description: This is the Mechine test for Paktolus Solution company. 
Author: Gulshan
Version: 1.0.0
*/
function add_paktolus_custom_menu()
{
	add_menu_page( 
	"paktolusmachinetest", 
	"Pakt Machine Test",
	"manage_options",
	"paktolusmt",
	"pak_custom_menu",
	"dashicons-clipboard",
	11);
}
add_action("admin_menu", "add_paktolus_custom_menu");

function pak_custom_menu(){
	
	?><h1>Paktolus Machine Test</h1>
    <form action="options.php" method="post">
	<?php settings_fields( 'myplugin_options_group' ); ?>
	<table>
    <tr>
    <td class="wd"><label for="authtoken">Auth Token</label></td>
    <td><input type="text" name="authtoken" value="<?php echo get_option('authtoken'); ?>"></td>
    </tr>
     
    <tr>
    <td class="wd"><label for="apiurl">API URL</label></td>
    <td><input type="text" name="apiurl" value="<?php echo get_option('apiurl'); ?>"></td>
    </tr>
    
	</table>
	 <?php  submit_button(); ?>
    </form>
	<?php
}

function myplugin_register_settings() {
   add_option( 'authtoken', '');
   add_option( 'apiurl', '');
   register_setting( 'plugin_options_group', 'authtoken', 'myplugin_callback' );
   register_setting( 'plugin_options_group', 'apiurl', 'myplugin_callback' );
}
add_action( 'admin_init', 'myplugin_register_settings' );
?>

<?php

    //shortcode [paktolus_form]
    function paktolus_frontend_form($params, $content = null) {

    extract(shortcode_atts(array(
        'type' => 'style1'
    ), $params));
	
	if(isset($_POST['submit']) && ($_POST["submit"]=="Submit")) {
		$fname=$_POST['fname'];
		$mname=$_POST['mname'];
		$lname=$_POST['lname'];
		$stradd=$_POST['stradd'];
		$city=$_POST['city'];
		$state=$_POST['state'];
		$zipcode=$_POST['zipcode'];
		$dob=date("mdY",strtotime($_POST['dob']));
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$unit=$_POST['unit'];
		$authtoken = get_option('authtoken');
		$apiurl = get_option('apiurl');
		$url = $apiurl."?auth_token=".$authtoken."&street=".$stradd."&city=".$city."&state=".$state."&zip=".$zipcode."&first_name=".$fname."&last_name=".$lname."&email=".$email."&phone=".$phone."&date_of_birth=".$dob;
		
		$response = wp_remote_request($url,array('method'=> 'GET'));
		$body = wp_remote_retrieve_body($response);
		$result = json_decode($body);
		if (isset($result->errors)) {
			print_r($result->errors[0]->message);			
		} else {
			print_R($result);
		}
	}
    ob_start();
    ?>
   <form action="" method="POST">
    <table style="width:70%">
        <tr>
            <td class="look"><label>First Name *</label><br><input name="fname" type="text" required></td>
			<td class="look"><label>Middle Name</label><br><input name="mname" type="text"></td>
			<td class="look"><label>Last Name *</label><br><input name="lname" type="text" required></td>
		</tr>
        <tr>
            <td class="look"><label>Street Address *</label><br><input name="stradd" type="text" required></td>
			<td class="look"><label>City *</label><br><input name="city" type="text" required></td>
			<td class="look"><label>State *</label><br>
             <select name="state" id="state">
  <option value="" selected="selected">Select a State</option>
  <option value="AL">Alabama</option>
  <option value="AK">Alaska</option>
  <option value="AZ">Arizona</option>
  <option value="AR">Arkansas</option>
  <option value="CA">California</option>
  <option value="CO">Colorado</option>
  <option value="CT">Connecticut</option>
  <option value="DE">Delaware</option>
  <option value="DC">District Of Columbia</option>
  <option value="FL">Florida</option>
  <option value="GA">Georgia</option>
  <option value="HI">Hawaii</option>
  <option value="ID">Idaho</option>
  <option value="IL">Illinois</option>
  <option value="IN">Indiana</option>
  <option value="IA">Iowa</option>
  <option value="KS">Kansas</option>
  <option value="KY">Kentucky</option>
  <option value="LA">Louisiana</option>
  <option value="ME">Maine</option>
  <option value="MD">Maryland</option>
  <option value="MA">Massachusetts</option>
  <option value="MI">Michigan</option>
  <option value="MN">Minnesota</option>
  <option value="MS">Mississippi</option>
  <option value="MO">Missouri</option>
  <option value="MT">Montana</option>
  <option value="NE">Nebraska</option>
  <option value="NV">Nevada</option>
  <option value="NH">New Hampshire</option>
  <option value="NJ">New Jersey</option>
  <option value="NM">New Mexico</option>
  <option value="NY">New York</option>
  <option value="NC">North Carolina</option>
  <option value="ND">North Dakota</option>
  <option value="OH">Ohio</option>
  <option value="OK">Oklahoma</option>
  <option value="OR">Oregon</option>
  <option value="PA">Pennsylvania</option>
  <option value="RI">Rhode Island</option>
  <option value="SC">South Carolina</option>
  <option value="SD">South Dakota</option>
  <option value="TN">Tennessee</option>
  <option value="TX">Texas</option>
  <option value="UT">Utah</option>
  <option value="VT">Vermont</option>
  <option value="VA">Virginia</option>
  <option value="WA">Washington</option>
  <option value="WV">West Virginia</option>
  <option value="WI">Wisconsin</option>
  </select>
        </td>
		</tr>
		
		<tr>
            <td class="look"><label>Zip code *</label><br><input name="zipcode" type="number" required></td>
			<td class="look"><label>Date Of Birth *</label><br><input name="dob" type="date" required></td>
			<td class="look"><label>Email Address *</label><br><input name="email" type="email" required></td>
        </tr>
		
		<tr>
			<td class="look"><label>Phone Number *</label><br><input name="phone" type="number" required></td>
			<td class="look"><label>Unit #</label><br><input name="unit" type="text"></td>
		</tr>
     </table>  <br>
		<input type="submit" name="submit" value="Submit">
    </form>

<?php return ob_get_clean();
}
add_shortcode('paktolus_form','paktolus_frontend_form');
    ?>
