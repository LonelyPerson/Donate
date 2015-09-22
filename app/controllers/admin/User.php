<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\DB;
use \Donate\Vendor\Pagination;
use \Donate\Vendor\Session;

class User {
	public function get_index($page = 1) {
		$server = Session::get('active_server_id');

		$total = DB::first("SELECT COUNT(*) as cnt FROM users WHERE server = ?", [$server]);

		$limit = Pagination::limit($page, 15);
	    $pagination = Pagination::render($page, $total->cnt, 15, 1, '/admin/user');

	    $users = DB::get("SELECT * FROM users LIMIT " . $limit['start'] . ',' . $limit['limit']);

		$servers = config('database.servers');

		return view('admin/master', [
			'content' => view('admin/user', [
				'users' => $users,
				'servers' => $servers,
				'pagination' => $pagination
			], true),
		]);
	}
}