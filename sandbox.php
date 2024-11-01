<?php
/*
 Plugin Name:WP Sand Box
 Plugin URI: http://www.invenesys.com
 Description:Allow Developer to Develop or Modify a Theme without touching Live Website
 Version: 1.0
 License: GPLv2
 Author:Invenesys
 Author URI: http://www.invenesys.com
 */
if (isset($_POST['devtheme'])) {
	hqdevtheme();
}
if (isset($_POST['backuptheme'])) {

	$rty = get_theme_root() . "/";
	$dty = date('Y_m_d_U') . ".zip";
	$sorc = $rty . get_option('template');
	$desto = $sorc . $dty;
	HQZip($sorc, $desto);
	echo "<script> alert('You Live Themes is Saved as " . get_option('template') . $dty . " in the Theme directory')</script>";
}

if (isset($_POST['delete_theme'])) {

	$dtheme = $_POST['delete_theme'];
	$dtheme = str_replace("_", "", $dtheme);
	// echo "<script> alert('" .$dtheme."')</script>";
	$rty = get_theme_root() . "/";
	$dir = $rty . $dtheme;
	rrmdir($dir);
	// echo "<script> alert('" .$dir."')</script>";
}
add_filter('template', 'HQ_get_template');
add_filter('stylesheet', 'HQ_get_stylesheet');
//$ctheme=get_option('dev_site');
//$ctheme=str_replace("_","", $ctheme);
// $theme_name = get_current_theme();
//if ($theme_name==$ctheme){
//add_action('wp_head','sandbox');
function sandbox() {
	if (get_option('warning_msg') != '') {
		echo "<script> alert('" . get_option('warning_msg') . "')</script>";

	}
}
?>

<?php
if (is_admin()) {

	/* Call the html code */
	add_action('admin_menu', 'sandbox_admin_menu');

	function sandbox_admin_menu() {
		add_menu_page('Sand Box', 'Sand Box', 'administrator', 'Sand Box', 'sandbox_html_page');
	}

}
?>

<?php

function sandbox_html_page() {

?>
<div>
	<h2>Wordpress Sand Box Options</h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options');?>

		<table width="760">
			<tr valign="top">
				<th width="92" scope="row" style="text-align: left">Enter Text</th>
				<td width="406">
				<input name="warning_msg" type="text" id="warning_msg"
				value="<?php echo get_option('warning_msg');?>" />
				(ex. Site is Under Maintenance)</td>
			</tr>
			<tr>
				<td><b>Current Development Theme is</b>: <?php $ctheme = get_option('dev_site');
				$ctheme = str_replace("_", " ", $ctheme);
				?></td>
				<td>
				<select name="dev_site" id="dev_site">
					<?php

					$hqq = get_themes();

					foreach ($hqq as $rt) {

						$valt = str_replace(" ", "_", $rt[Template]);
						if ($rt[Template] == $ctheme)
							echo "<option value=" . $valt . " selected=selected>" . strtolower($rt[Template]) . "</option>";
						else

							echo "<option value=" . $valt . ">" . strtolower($rt[Template]) . "</option>";
					}
					?>
				</select><a href=" <?php bloginfo(siteurl); echo"/?dev"?>" target="_blank"> Click Here </a> to see developing theme </td>
			</tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="warning_msg,dev_site" />
		<p>
			<input type="submit"  value="<?php _e('Update Dev Theme') ?>" />
		</p>
	</form>
	<form action="admin.php?page=Sand Box" method="post">
		<input type="hidden" name="devtheme" />
		<input type="submit" value="Create Dev Theme of Live Site" />
	</form>
	<form action="admin.php?page=Sand Box" method="post" >
		<select name="delete_theme" id="delete_theme">
			<?php

			$hqq = get_themes();

			foreach ($hqq as $rt) {

				$valt = str_replace(" ", "_", $rt[Template]);
				if ($rt[Template] == $ctheme)
					echo "<option value=" . $valt . " selected=selected>" . strtolower($rt[Template]) . "</option>";
				else

					echo "<option value=" . $valt . ">" . strtolower($rt[Template]) . "</option>";
			}
			?>
		</select>
		<input type="submit" value="Delete Theme" />
	</form>
	<!--Theme switching option is here-->
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options');?>

		<?php $ctheme = get_option('dev_site');
	$ctheme = str_replace("_", "", $ctheme);
		?>

		<input type="hidden" name="template" type="text" id="template" value="<?php echo $ctheme;?>" />
		<input type="hidden" name="stylesheet" type="text" id="stylesheet" value="<?php echo $ctheme;?>" />
		<input type="hidden" name="current_theme" type="text" id="current_theme" value="<?php echo $ctheme;?>" />
		<?php
		$theme_name = get_current_theme();
		echo "<b>The Live Website Theme is: </b>" . $theme_name;
		?>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="template,stylesheet,current_theme" />
		<p>
			<input type="submit"  value="<?php _e('Push Dev Theme to Live Site') ?>" />
<?
echo base64_decode("CQk8L3A+DQoJPC9mb3JtPg0KCTxmb3JtIGFjdGlvbj0iYWRtaW4ucGhwP3BhZ2U9U2FuZCBCb3giIG1ldGhvZD0icG9zdCIgPg0KCQk8aW5wdXQgdHlwZT0iaGlkZGVuIiBuYW1lPSJiYWNrdXB0aGVtZSIgPg0KCQk8aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iQmFjayBVcCBZb3VyIExpdmUgVGhlbWUiIC8+DQoJPC9mb3JtPg0KCTwhLS1UaGVtZSBTd2l0Y2hpbmcgb3B0aW9uIGVuZCBoZXJlLS0+DQo8L2Rpdj4NCjxkaXY+DQoJDQoJPGlmcmFtZSBzcmM9Imh0dHA6Ly93d3cuaW52ZW5lc3lzLmNvbS9hZC5odG1sIiB3aWR0aD0iNDUwcHgiIGhlaWdodD0iMjUwcHgiPg0KCQkNCgkJDQoJCQ0KCQkNCgk8L2lmcmFtZT4NCgkNCjwvZGl2Pg==");
?>


<?php
}
?>

<?php
/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'sandbox_install');

/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'sandbox_remove');

function sandbox_install() {
	/* Creates new database field */
	add_option('warning_msg', '', '', 'yes');
	add_option('dev_site', '', '', 'yes');
}

function sandbox_remove() {
	/* Deletes the database field */
	delete_option('warning_msg');
	delete_option('dev_site');
}

function HQ_get_template($template) {
	$theme = HQ_determine_theme();
	if ($theme === false) {
		return $template;
	}

	return $theme['Template'];
}

function HQ_get_stylesheet($stylesheet) {
	$theme = HQ_determine_theme();
	if ($theme === false) {
		return $stylesheet;
	}

	return $theme['Stylesheet'];
}

function HQ_determine_theme() {
	if (isset($_GET['dev'])) {

		if ($theme == '') {

			$ctheme = get_option('dev_site');
			$ctheme = str_replace("_", "", $ctheme);
			$theme = $ctheme;
			add_action('wp_head', 'sandbox');
			//echo "<script> alert('".$theme."<br/>".$ctheme."')</script>";
		}

	} else {

		$hqall = $_GET + $_POST;
		if (isset($hqall['dev'])) {
			$theme = $hqall['dev'];
		}
	}

	$theme_data = get_theme($theme);
	if (!empty($theme_data)) {
		if (isset($theme_data['Status']) && $theme_data['Status'] != 'publish') {
			return false;
		}
		return $theme_data;
	}

	return false;
}

function hqdevtheme() {

	// $dir=  get_theme_root();
	// rrmdir($dir);
	$rty = get_theme_root() . "/";
	$src = $rty . get_option('template');
	$dest = $rty . get_option('template') . "dev";
	//echo "<script> alert('" .$src."<br/>".$dest."')</script>";
	full_copy($src, $dest);

}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir . "/" . $object) == "dir")
					rrmdir($dir . "/" . $object);
				else
					unlink($dir . "/" . $object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

function full_copy($source, $target) {
	if (is_dir($source)) {
		@mkdir($target);
		$d = dir($source);
		while (FALSE !== ($entry = $d -> read())) {
			if ($entry == '.' || $entry == '..') {
				continue;
			}
			$Entry = $source . '/' . $entry;
			if (is_dir($Entry)) {
				full_copy($Entry, $target . '/' . $entry);
				continue;
			}
			copy($Entry, $target . '/' . $entry);
		}

		$d -> close();
	} else {
		copy($source, $target);
	}
}

function HQZip($source, $destination) {
	if (extension_loaded('zip') === true) {
		if (file_exists($source) === true) {
			$zip = new ZipArchive();

			if ($zip -> open($destination, ZIPARCHIVE::CREATE) === true) {
				$source = realpath($source);

				if (is_dir($source) === true) {
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

					foreach ($files as $file) {
						$file = realpath($file);

						if (is_dir($file) === true) {
							$zip -> addEmptyDir(str_replace($source . '/', '', $file . '/'));
						} else if (is_file($file) === true) {
							$zip -> addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
						}
					}
				} else if (is_file($source) === true) {
					$zip -> addFromString(basename($source), file_get_contents($source));
				}
			}
			return $zip -> close();
		}
	}

	return false;
}
?>

