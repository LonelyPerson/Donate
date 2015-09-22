<?php

namespace Donate\Vendor;

if ( ! defined('STARTED')) exit;

class Admin {
	public static function navStructure() {
		$menu[] = [
			'route' => '#user',
			'title' => 'Back to DS',
			'icon' => 'fa-arrow-left'
		];

		$menu[] = [
			'route' => 'sep',
			'title' => '',
			'icon' => ''
		];
		
		if ( ! self::isHome()) {
			$menu[] = [
				'route' => 'admin',
				'title' => 'Home',
				'icon' => 'fa-home'
			];
		}

		$menu[] = [
			'route' => 'admin/user',
			'title' => 'Users',
			'icon' => 'fa-user'
		];

		$menu[] = [
			'key' => 'shop',
			'route' => 'admin/shop',
			'title' => 'Shop',
			'icon' => 'fa-shopping-cart',
			'sub' => [
				[
					'route' => 'admin/shop/item',
					'title' => 'New item',
					'icon' => 'fa-plus-square'
				],
				[
					'route' => 'admin/shop/item-group',
					'title' => 'New items group',
					'icon' => 'fa-plus-square'
				]
			]
		];

		$menu[] = [
			'route' => 'admin/config',
			'title' => 'Configs',
			'icon' => 'fa-cogs',
			'sub' => [
				[
					'route' => 'admin/config/general',
					'title' => 'General',
					'icon' => 'fa-cog'
				],
				[
					'route' => 'admin/config/player',
					'title' => 'Player',
					'icon' => 'fa-male'
				],
				[
					'route' => 'admin/config/registration',
					'title' => 'Registration',
					'icon' => 'fa-pencil-square-o'
				],
				[
					'route' => 'admin/config/shop',
					'title' => 'Shop',
					'icon' => 'fa-shopping-cart'
				],
				[
					'route' => 'admin/config/inventory',
					'title' => 'Inventory',
					'icon' => 'fa-archive'
				],
				[
					'route' => 'admin/config/paypal',
					'title' => 'Paypal',
					'icon' => 'fa-credit-card'
				],
				[
					'route' => 'admin/config/paysera',
					'title' => 'Paysera',
					'icon' => 'fa-credit-card'
				],
				[
					'route' => 'admin/config/paygol',
					'title' => 'Paygol',
					'icon' => 'fa-credit-card'
				],
				[
					'route' => 'admin/config/mail',
					'title' => 'Mail',
					'icon' => 'fa-envelope-o'
				],
				[
					'route' => 'admin/config/security',
					'title' => 'Security',
					'icon' => 'fa-lock'
				],
				
			]
		];

		$menu[] = [
			'route' => 'admin/sms-keywords',
			'title' => 'SMS',
			'icon' => 'fa-mobile',
			'sub' => [
				[
					'route' => 'admin/sms-keywords/keyword',
					'title' => 'New keyword',
					'icon' => 'fa-plus-square'
				],
			]
		];

		$menu[] = [
			'route' => 'admin/translation',
			'title' => 'Translations',
			'icon' => 'fa-file-text-o',
			'sub' => [
				[
					'route' => 'admin/translation/add',
					'title' => 'New translation',
					'icon' => 'fa-plus-square'
				],
			]
		];

		$menu[] = [
			'route' => 'sep',
			'title' => '',
			'icon' => ''
		];

		$menu[] = [
			'route' => 'admin/logs',
			'title' => 'Logs',
			'icon' => 'fa-book'
		];

		return $menu;
	}

	public static function nav() {

		$menu = self::navStructure();

		$content = '';
		foreach ($menu as $key => $row) {
			$content .= view('admin/menu_line', ['menu' => $row]);
		}

		return $content;
	}

	public static function isHome() {
		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$ex = explode('/', $uri);
		$last = end($ex);

		if ($last == 'admin')
			return true;

		return false;
	}

	public static function hasAccess() {
		if (Session::has('access')) {
			if (Session::get('access') == 1)
				return true;
		}

		return false;
	}
}