<?php

namespace Donate\Controller\Admin;

if ( ! defined('STARTED')) exit;

use \Donate\Vendor\Redirect;
use \Donate\Vendor\Session;
use \Donate\Vendor\Input;
use \Donate\Vendor\DB;
use \Donate\Vendor\Server;
use \Donate\Vendor\Pagination;

class Shop {
	public function get_index($page = 1) {
		$server = Session::get('active_server_id');

		$total = DB::first('SELECT COUNT(*) as cnt FROM shop as s LEFT JOIN items as i ON s.item_id = i.item_id WHERE i.chronicle = ? AND s.server = ? GROUP BY s.group_id', [Server::getItemChronicle(), $server]);

	    $limit = Pagination::limit($page, 15);
	    $pagination = Pagination::render($page, $total->cnt, 15, 1, '/admin/shop');

		$items = DB::get('SELECT s.title, s.id, s.group_id, s.is_group, s.item_id, s.price, s.quantity, i.item_name, i.icon FROM shop as s LEFT JOIN items as i ON s.item_id = i.item_id WHERE i.chronicle = ? AND s.server = ? GROUP BY s.group_id LIMIT ' . $limit['start'] . ',' . $limit['limit'], [Server::getItemChronicle(), $server]);

		return view('admin/master', [
			'content' => view('admin/shop', [
				'items' => $items,
				'pagination' => $pagination
			], true)
		]);
	}

	public function get_form($id = 0) {
		$item = [];
		if ($id)
			$item = DB::first('SELECT * FROM shop WHERE id = ?', [$id]);

		return view('admin/master', [
			'content' => view('admin/shop_item', [
				'id' => $id,
				'item' => $item
			], true)
		]);
	}	
	public function get_groupForm($id = 0) {
		$item = [];
		if ($id)
			$items = DB::get('SELECT * FROM shop WHERE group_id = ?', [$id]);

		return view('admin/master', [
			'content' => view('admin/shop_items_group', [
				'id' => $id,
				'items' => $items
			], true)
		]);
	}	

	public function post_item() {
		$id = Input::get('id');
		$groupId = 0;

		$title = Input::get('title');
		$itemId = Input::get('item_id');
		$price = Input::get('price');
		$quantity = Input::get('quantity');
		$server = Session::get('active_server_id');

		if ( ! $itemId || ! $price || ! $quantity)
			return Redirect::back(['type' => 'danger', 'message' => 'Fill all required fields']);

		if ( ! $id) {
			$result = DB::first("SELECT MAX(group_id) as maxid FROM shop");
			$groupId = $result->maxid + 1;
		}

		if ($id)
			DB::query("UPDATE shop SET item_id = ?, price = ?, title = ?, quantity = ? WHERE id = ?", [
				$itemId, $price, $title, $quantity, $id
			]);
		else
			DB::query("INSERT INTO shop SET group_id = ?, item_id = ?, price = ?, title = ?, quantity = ?, server = ?", [
				$groupId, $itemId, $price, $title, $quantity, $server
			]);

		if ($id)
			Redirect::back(['type' => 'success', 'message' => 'Updated successfully']);
		else
			Redirect::back(['type' => 'success', 'message' => 'Added successfully']);
	}

	public function post_itemGroup() {
		$groupId = Input::get('id');
		$realGroupId = Input::get('id');

		$itemId = $_POST['item_id'];
		$price = $_POST['price'];
		$quantity = $_POST['quantity'];

		$server = Session::get('active_server_id');

		if ($groupId) {
			DB::query("DELETE FROM shop WHERE group_id = ?", [
				$groupId
			]);
		} else {
			$result = DB::first("SELECT MAX(group_id) as maxid FROM shop");
			$groupId = $result->maxid + 1;
		}	

		foreach ($_POST['title'] as $key => $title) {
			if ($itemId[$key] && $price[$key]) {
				if ( ! $quantity[$key])
					$quantity[$key] = 1;

				if ($groupId) {
					// update
					DB::query("INSERT INTO shop SET group_id = ?, is_group = ?, item_id = ?, price = ?, title = ?, quantity = ?, server = ?", [
						$groupId, 1, $itemId[$key], $price[$key], $title, $quantity[$key], $server
					]);
				} else {
					// insert
					DB::query("INSERT INTO shop SET group_id = ?, is_group = ?, item_id = ?, price = ?, title = ?, quantity = ?, server = ?", [
						$groupId, 1, $itemId[$key], $price[$key], $title, $quantity[$key], $server
					]);
				}
			}
		}

		if ($realGroupId)
			Redirect::back(['type' => 'success', 'message' => 'Updated successfully']);
		else
			Redirect::back(['type' => 'success', 'message' => 'Added successfully']);
	}

	public function get_delete($id) {
		DB::query("DELETE FROM shop WHERE id = ?", [
			$id
		]);

		Redirect::back();
	}
	public function get_deleteGroup($id) {
		DB::query("DELETE FROM shop WHERE group_id = ?", [
			$id
		]);

		Redirect::back();
	}
}