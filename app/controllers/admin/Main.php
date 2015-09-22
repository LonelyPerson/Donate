<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

class Main {
	public function get_index() {
		return view('admin/master', [
			'content' => view('admin/dashboard', [], true),
			'home' => true
		]);
	}
}