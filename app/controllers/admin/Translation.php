<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Input;
use \Donate\Vendor\Redirect;

class Translation {
	public static function get_index() {
		$path = APP_PATH . '/languages';
		if ($handle = opendir($path)) {
		    while (false !== ($entry = readdir($handle))) {
		        if ($entry != "." && $entry != "..") {
		        	$ex = explode('.', $entry);	
		        	if ($ex[0] != 'base')
		           		$options .= '<option value="' . $ex[0] . '">' . strtoupper($ex[0]) . '</option>';
		        }
		    }

		    closedir($handle);
		}

		return view('admin/master', [
			'content' => view('admin/translation', [
				'options' => $options
			], true)
		]);
	}

	public static function get_translation($language) {
		$languagePath = APP_PATH . '/languages/' . $language . '.lang.php';
		$translations = include $languagePath;

		return view('admin/master', [
			'content' => view('admin/translation_edit', [
				'translations' => $translations,
				'language' => $language
			], true)
		]);
	}

	public static function get_new() {
		return view('admin/master', [
			'content' => view('admin/translation_new', [], true)
		]);
	}

	public static function get_delete($language) {
		$languagePath = APP_PATH . '/languages/' . $language . '.lang.php';
		if (file_exists($languagePath)) {
			unlink($languagePath);
		}

		return Redirect::back(['type' => 'success', 'message' => 'Language deleted successfully']);
	}

	public static function post_save() {
		$language = Input::get('language');
		$languagePath = APP_PATH . '/languages/' . $language . '.lang.php';

		unset($_POST['language']);
		unset($_POST['submit']);

		file_put_contents($languagePath, '<?php return ' . var_export($_POST, true) . ';');

		Redirect::back(['type' => 'success', 'message' => 'Saved successfully']);
	}

	public static function post_add() {
		$language = Input::get('language');
		$languagePath = APP_PATH . '/languages/' . $language . '.lang.php';

		if ( ! $language) {
			return Redirect::back(['type' => 'danger', 'message' => 'Please enter language code']);
		}

		if (file_exists($languagePath)) {
			return Redirect::back(['type' => 'danger', 'message' => 'Language already exists']);
		}

		$base = include APP_PATH . '/languages/base.php'; 

		file_put_contents($languagePath, '<?php return ' . var_export($base, true) . ';');

		return Redirect::back(['type' => 'success', 'message' => 'Language created successfully']);
	}
}