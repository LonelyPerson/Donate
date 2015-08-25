<?php

class XML {
    public static function findXMLByItemID($itemID) {
        $foundedXML = false;

        $xmls = [];
        $directory = new RecursiveDirectoryIterator(CONFIG_PATH . '/xml/items', FilesystemIterator::SKIP_DOTS);
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
            return simplexml_load_file(CONFIG_PATH . '/xml/items/' . $foundedXML);
        }

        return false;
    }

    public static function findItemByID($itemID) {
        $result = [];

        $xml = self::findXMLByItemID($itemID);
        if ($xml) {
            foreach ($xml as $item) {
                if ($itemID == (int) $item->attributes()->id) {
                    $result = $item;
                    break;
                }
            }
        }

        return $result;
    }

    function getItemConsumeType($itemID) {
        $consumeType = false;

        $item = self::findItemByID($itemID);
        if ($item) {
            $itemType = strtolower($item->attributes()->type);
            if ($itemType == 'etcitem') {
                foreach ($item->set as $set) {
                    if ($set->attributes()->name == 'is_stackable' && $set->attributes()->val == 'true') {
                        $consumeType = 'stackable';
                        break;
                    }
                }
            }
        }

        return $consumeType;
    }

    function getTitle($itemID) {
        $item = self::findItemByID($itemID);
        return $item->attributes()->name;
    }

    function getType($itemID) {
        $item = self::findItemByID($itemID);
        return $item->attributes()->type;
    }
}
