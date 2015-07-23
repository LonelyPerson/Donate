<?php

class Shop {
    public function index() {
        $items = simplexml_load_file(CONFIG_PATH . '/xml/shop.xml');

        $pagination = '';
        if (Settings::get('app.shop.per_page')) {
            $itemsPerPage = Settings::get('app.shop.per_page');
            $totalItems = count($items);
            $totalPages = ceil($totalItems / $itemsPerPage);

            $pagination = '<ul class="pagination">';
            for($p=1;$p<=$totalPages;$p++) {
                $active = ($p == 1) ? 'class="active"' : '';

                $pagination .= '<li ' . $active . '><a href="javascript: void(0)" data-page="' . $p . '">' . $p .  '</a></li>';
            }
            $pagination .= '</ul>';
        }

        return View::make('shop', ['items' => $items, 'pagination' => $pagination]);
    }
}
