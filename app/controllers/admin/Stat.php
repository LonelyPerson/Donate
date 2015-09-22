<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

class Stat {
	public function get_index() {
		return view('admin/master', [
			'content' => view('admin/stat', [], true)
		]);
	}
}