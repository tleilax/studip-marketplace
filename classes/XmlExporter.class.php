<?php
/**
 * @author Jan Kulmann <jankul@zmml.uni-bremen.de>
 * @author Jan-Hendrik Willms <tleilax+studip@gmail.com>
 */

// +---------------------------------------------------------------------------+
// Copyright (C) 2012 Jan Kulmann <jankul@zmml.uni-bremen.de>
// +---------------------------------------------------------------------------+
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or any later version.
// +---------------------------------------------------------------------------+
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// +---------------------------------------------------------------------------+

if(!function_exists('mb_detect_encoding')) {
    function mb_detect_encoding ($string, $enc = null) {
        static $list = array('utf-8', 'iso-8859-1', 'windows-1251');
        foreach ($list as $item) {
            $sample = iconv($item, $item, $string);
            if (md5($sample) == md5($string)) {
                if ($enc == $item) {
                    return true;
                } else {
                    return $item;
                }
            }
        }
        return null;
    }
}

if(!function_exists('mb_convert_encoding')) {
    function mb_convert_encoding($string, $target_encoding, $source_encoding) {
        $string = iconv($source_encoding, $target_encoding, $string);
        return $string;
    }
}

class XmlExporter
{
    private function __construct()
    {

    }

    public static function generatePluginsXml()
    {
        $doc = new DomDocument('1.0');
        $plugins = $doc->appendChild($doc->createElement('plugins'));

        $query = "SELECT plugin_id
                  FROM plugins
                  WHERE approved = 1
                  ORDER BY mkdate DESC";

        $plugin_ids = DBManager::get()->query($query)->fetchAll(PDO::FETCH_COLUMN);
        foreach ($plugin_ids as $id) {
            $p = new Plugin();
            $p->load($id);

            $releases = $p->getReleases();

            if($releases !== false) {
                $plugin = $plugins->appendChild($doc->createElement('plugin'));
                $plugin->setAttribute('name', rawurlencode($p->getName()));
                $plugin->setAttribute('homepage', rawurlencode($p->getUrl()));
                $plugin->setAttribute('description', self::xmlReady($p->getDescription()));
                if ($s = $p->getTitleScreen()) {
                    $plugin->setAttribute('image', $GLOBALS['BASE_URI'] . '?dispatch=download&file_id=' . $s->getFileId());
                }
                $plugin->setAttribute('score', 'TODO');

                foreach ($releases as $rel) {
                    $release = $plugin->appendChild($doc->createElement('release'));
                    $release->setAttribute('version', $rel->getVersion());
                    $release->setAttribute('studipMinVersion', $rel->getStudipMinVersion());
                    $release->setAttribute('studipMaxVersion', $rel->getStudipMaxVersion());
                    $release->setAttribute('url', $GLOBALS['BASE_URI'] . '?dispatch=download&file_id=' . $rel->getFileId());
                }
            }
        }

        return $doc->saveXML();
    }
    
    protected static function xmlReady($string)
    {
        if (!mb_detect_encoding($string, 'utf-8')) {
            $string = mb_convert_encoding($string, 'utf-8');
        }
        return $string;
    }
}
