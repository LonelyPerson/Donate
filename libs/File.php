<?php

class File {
    public static function findXMLByItemID($itemID) {
        $consumeType = false;
        $foundedXML = false;
        
        $xmls = [];
        $directory = new RecursiveDirectoryIterator(ROOT_PATH . '/settings/xml/items', FilesystemIterator::SKIP_DOTS);
        foreach (new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST) as $path) {
            $path->isDir() ? $dirs[] = $path->__toString() : $xmls[] = $path->getFilename();
        }

        foreach ($xmls as $filename) {
            $tempFilename = str_replace('.xml', '', $filename);
            $ids = explode('-', $tempFilename);
            if ($itemID >= $ids[0] && $itemID <= $ids[1]) {
                $foundedXML = $filename;
                break;
            }
        }

        if ($foundedXML) {
            $xml = simplexml_load_file(ROOT_PATH . '/settings/xml/items/' . $foundedXML);

            foreach ($xml as $item) {
                if ($itemID == $item->attributes()->id) {
                    $itemType = strtolower($item->attributes()->type);
                    if ($itemType == 'etcitem') {
                        foreach ($item->set as $set) {
                            if ($set->attributes()->name == 'is_stackable' && $set->attributes()->val == 'true') {
                                $consumeType = 'stackable';
                                break;
                            }
                        }
                    }

                    break;
                }
            }
        }
        
        return $consumeType;
    }
    
    public static function getItemIcon($imgName) {
        $iconPath = Settings::get('app.base_url') . '/assets/img/items';
        
        if ( $imgName && file_exists(ROOT_PATH . '/assets/img/items/full/' . $imgName) && ! file_exists(ROOT_PATH . '/assets/img/items/thumbs/' . $imgName)) {
            $img = new SimpleImage(ROOT_PATH . '/assets/img/items/full/' . $imgName);
            $img->adaptive_resize(100, 100)->save(ROOT_PATH . '/assets/img/items/thumbs/' . $imgName);
        }
        
        if ($imgName && file_exists(ROOT_PATH . '/assets/img/items/thumbs/' . $imgName)) {
            return '<a href="' . $iconPath . '/full/' . $imgName .  '" class="image-link"><img src="' . $iconPath . '/thumbs/' . $imgName .  '" /></a>';
        } else {
            return '<img src="' . $iconPath . '/default_no_img.jpg" />';
        }
    }

    public static function getFlagIcon($code, $width = 0) {
        $code = strtoupper($code);
        $flagPath = Settings::get('app.base_url') . '/assets/img/flags';

        if (file_exists(ROOT_PATH . '/assets/img/flags/' . $code . '.png'))
            return '<img src="' . $flagPath . '/' . $code . '.png" />';

        return $code;
    }
}