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

// http://www.php.net/manual/de/function.mb-detect-encoding.php#85294
function is_utf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
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
        if (!is_utf8($string)) {
            $string = utf8_encode($string);
        }
        return $string;
    }
}
