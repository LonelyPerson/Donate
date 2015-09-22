<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Redirect;
use \Donate\Vendor\Session;
use \Donate\Vendor\Input;
use \Donate\Vendor\DB;
use \Donate\Vendor\Server;
use \Donate\Vendor\Pagination;

class SmsKeywords {
	public function get_index($page = 1) {
		$server = Session::get('active_server_id');
		$total = DB::first("SELECT COUNT(*) as cnt FROM sms_keywords WHERE server = ?", [$server]);

		$limit = Pagination::limit($page, 15);
	    $pagination = Pagination::render($page, $total->cnt, 15, 1, '/admin/sms-keywords');

		$items = DB::get('SELECT * FROM sms_keywords WHERE server = ? LIMIT ' . $limit['start'] . ',' . $limit['limit'], [$server]);

		return view('admin/master', [
			'content' => view('admin/sms_keywords', [
				'items' => $items,
				'pagination' => $pagination
			], true)
		]);
	}

	public function get_form($id = 0) {
		$types = [
			'paysera'
		];

		$item = [];
		if ($id)
			$item = DB::first('SELECT * FROM sms_keywords WHERE id = ?', [$id]);

		foreach ($types as $type) {
			$selected = (isset($item->type) && $type == $item->type) ? 'selected="selected"' : '';

			$types .= '<option value="' . $type . '" ' . $selected . '>' . $type . '</option>';
		}

		return view('admin/master', [
			'content' => view('admin/sms_keyword', [
				'id' => $id,
				'item' => $item,
				'types' => $types
			], true)
		]);
	}	

	public function post_item() {
		$id = Input::get('id');

		$keyword = Input::get('keyword');
		$number = Input::get('number');
		$country = Input::get('country');
		$currency = Input::get('currency');
		$price = Input::get('price');
		$points = Input::get('points');
		$response = Input::get('response');
		$type = Input::get('type');

		$server = Session::get('active_server_id');

		if ( ! $keyword || ! $number || ! $country || ! $currency || ! $price || ! $points || ! $response || ! $type)
			return Redirect::back(['type' => 'danger', 'message' => 'Fill all fields']);

		if ($id)
			DB::query("UPDATE sms_keywords (keyword,phone,country,currency,price,points,response,type) VALUES (?,?,?,?,?,?,?,?) WHERE id = ?", [
				$keyword, $number, $country, $currency, $price, $points, $response, $type, $id
			]);
		else
			DB::query("INSERT INTO sms_keywords (keyword,phone,country,currency,price,points,response,type,server) VALUES (?,?,?,?,?,?,?,?,?)", [
				$keyword, $number, $country, $currency, $price, $points, $response, $type, $server
			]);

		if ($id)
			Redirect::back(['type' => 'success', 'message' => 'Updated successfully']);
		else
			Redirect::back(['type' => 'success', 'message' => 'Added successfully']);
	}

	public function get_delete($id) {
		DB::query("DELETE FROM sms_keywords WHERE id = ?", [
			$id
		]);

		Redirect::back();
	}
}