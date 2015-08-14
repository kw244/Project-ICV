<?php

/**
 * A simple, clean and secure PHP Login Script / MINIMAL VERSION
 * For more versions (one-file, advanced, framework-like) visit http://www.php-login.net
 *
 * Uses PHP SESSIONS, modern password-hashing and salting and gives the basic functions a proper login system needs.
 *
 * @author Panique
 * @link https://github.com/panique/php-login-minimal/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("libraries/password_compatibility_library.php");
}

// include the configs / constants for the database connection
require_once("config/db.php");

// load the login class
require_once("classes/Login.php");

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. So now we populate the admin panel accordingly
    //put in the same html header
	include("views/html_header.php");
	
	//we dynamically set up our menu bar
	
	include("views/menu_bar_open.php");
		
	//menu sections to be populated
	$menu_items = array("main","contacts","SMS","logout");
	
	foreach ($menu_items as $menu_item){
		//we populate the menu items here

		if (isset($_GET[$menu_item])) {
			echo '<li><a href="?' . $menu_item . '" class="active"> ' . ucfirst($menu_item) . '</a></li>';
			$activePage = "views/" . $menu_item . ".php";
		}
		else {
			echo '<li><a href="?' . $menu_item . '"> ' . ucfirst($menu_item) . '</a></li>';
		}

	}
	include("views/menu_bar_close.php");
	
	// Include the correct menu page
	if (isset($activePage))
	{
		include $activePage;
		
		//we also populate any sub-menu if selected
		if (isset($_GET["menu"])) {
			include "views/" . $_GET["menu"] . ".php";
		}	
	}
	else
	{
		include "views/main.php";
	}
	
	include("views/html_footer.php");

} else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    include("views/not_logged_in.php");
}
