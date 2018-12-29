<?php 
session_start();
// define configurations
define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

// now require file
require_once "config.php";
require_once "library/database.class.php";
require_once "library/model.class.php";
require_once "library/view.class.php";
require_once "library/controller.class.php";

function __autoload($className) {

	$fileName = str_replace("\\", DS, $className) . '.php';
	if(!file_exists($fileName)) {
		return false;
	}
	include $fileName;
}
 

 // now let begin for make it MVC
$page = (isset($_GET['page']) && _GET['page']) ? $_GET['page'] : 'home';
$controller = ROOT . DS . 'modules' . DS . 'controllers' . DS . $page .'Controller.php';

if(file_exists($controller)) {

	require_once $controller;
	$action = (isset($_GET['action']) && $_GET['action']) ? $_GET['action'] : 'index';
	$controllerName = ucfirst($page) . 'Controller';

	$obj = new $controllerName();

	if(method_exists($obj, $action)) {

		$args = array();
		if(count($_GET) > 2) {
			$parts = array_slice($_GET, 2);

			foreach ($parts as $part) {
				array_push($args, $part);
			}
		}
		call_user_func_array(array($obj, $action), $args);
	}else die ('Action Not Found !');

}else die('Controller Not Found !');

?>