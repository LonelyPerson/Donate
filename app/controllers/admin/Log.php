<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Session;
use \Donate\Vendor\DB;
use \Donate\Vendor\Pagination;

class Log {
	public function get_index($page = 1) {
		$server = Session::get('active_server_id');

		$total = DB::first("SELECT COUNT(*) as cnt FROM logs WHERE server = ?", [$server]);

		$limit = Pagination::limit($page, 15);
	    $pagination = Pagination::render($page, $total->cnt, 15, 1, '/admin/logs');

		$items = DB::get('SELECT * FROM logs WHERE server = ? ORDER BY created_at DESC LIMIT ' . $limit['start'] . ',' . $limit['limit'], [$server]);

		return view('admin/master', [
			'content' => view('admin/log', [
				'items' => $items,
				'pagination' => $pagination
			], true)
		]);
	}
}