<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Admin as vAdmin;
use \Donate\Vendor\DB;
use \Donate\Vendor\Redirect;

class Config {
	public function get_index() {
		$nav = '';
		$menu = vAdmin::navStructure();
		foreach ($menu as $row) {
			if ($row['route'] == 'admin/config') {
				foreach($row['sub'] as $sub) {
					$nav .= view('admin/config_menu_line', ['menu' => $sub], true);
				}

				break;
			}
		}
		
		return view('admin/master', [
			'content' => view('admin/config', [
				'nav' => $nav
			], true)
		]);
	}

	public function get($segment) {
		$configs = DB::get("SELECT * FROM config WHERE cgroup = ?", [$segment]);

		return view('admin/master', [
			'content' => view('admin/configs/' . $segment, [
				'configs' => $configs,
				'segment' => $segment
			], true)
		]);
	}

	public function post($group) {
		foreach ($_POST as $key => $value) {
			if ($key == 'submit') continue;

			$key =  str_replace('|', '.', $key);

			DB::query('UPDATE config SET param_value = ? WHERE param_key = ? AND cgroup = ?', [$value, $key, $group]);
		}

		Redirect::back(['type' => 'success', 'message' => 'Saved successfully']);
	}
}