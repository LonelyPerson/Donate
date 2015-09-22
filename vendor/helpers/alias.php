<?php

class_alias('\Donate\Vendor\Auth', 'Auth');
class_alias('\Donate\Vendor\Admin', 'Admin');
class_alias('\Donate\Vendor\Currency', 'Currency');
class_alias('\Donate\Vendor\DB', 'DB');
class_alias('\Donate\Vendor\File', 'File');
class_alias('\Donate\Vendor\Histuar', 'Histuar');
class_alias('\Donate\Vendor\Input', 'Input');
class_alias('\Donate\Vendor\Item', 'Item');
class_alias('\Donate\Vendor\L2', 'L2');
class_alias('\Donate\Vendor\Language', 'Language');
class_alias('\Donate\Vendor\Mail', 'Mail');
class_alias('\Donate\Vendor\Menu', 'Menu');
class_alias('\Donate\Vendor\Output', 'Output');
class_alias('\Donate\Vendor\Paypal', 'Paypal');
class_alias('\Donate\Vendor\Player', 'Player');
class_alias('\Donate\Vendor\Router', 'Router');
class_alias('\Donate\Vendor\Server', 'Server');
class_alias('\Donate\Vendor\Session', 'Session');
class_alias('\Donate\Vendor\String', 'String');
class_alias('\Donate\Vendor\URL', 'URL');
class_alias('\Donate\Vendor\View', 'View');
class_alias('\Donate\Vendor\Settings', 'Settings');
class_alias('\Donate\Vendor\Form', 'Form');

function __($string, $params = []) {
	return \Donate\Vendor\Language::_($string, $params);
}

function config($key) {
	return \Donate\Vendor\Settings::get($key);
}

function input($key) {
	return \Donate\Vendor\Input::get($key);
}

function view($template, $array = [], $save = false) {
	return \Donate\Vendor\View::make($template, $array, $save);
}

function route($route) {
	return config('app.base_url') . '/' . $route;
}

function admin_nav() {
	return \Donate\Vendor\Admin::nav();
}

function _log($description, $type = '', $author = '') {
	\Donate\Vendor\Log::add($description, $type, $author);
}