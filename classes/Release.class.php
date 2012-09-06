<?php
/**
* @author               Jan Kulmann <jankul@zmml.uni-bremen.de>
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

class Release {

    private $release_id = "";
    private $plugin_id = "";
    private $version = "";
    private $studip_min_version = "";
    private $studip_max_version = "";
    private $file_id = "";
    private $mkdate = 0;
    private $user_id = "";
    private $release_type = "";
    private $origin = "";
    private $dependencies = array();
    private $downloads = 0;
    private $chdate = 0;

    public function __construct() {

    }

    public function getPluginId() {
        return $this->plugin_id;
    }

    public function getReleaseId() {
        return $this->release_id;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getStudipMinVersion() {
        return $this->studip_min_version;
    }

    public function getStudipMaxVersion() {
        return $this->studip_max_version;
    }

    public function getFileId() {
        return $this->file_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getOrigin() {
        return $this->origin;
    }

    public function getChdate() {
        return $this->chdate;
    }

    public function getAuthor() {
        if ($this->release_id) {
            $db = DBManager::get();
            $r = $db->query(sprintf("SELECT * FROM users WHERE user_id='%s'",$this->user_id))->fetchAll();
            if (count($r)) return $r[0];
            else return FALSE;
        }
        return FALSE;
    }

    public function getFile() {
        if ($this->file_id) {
            $f = new MFile();
            $f->load($this->file_id);
            return $f;
        } else return FALSE;
    }

    public function getReleaseType() {
        return $this->release_type;
    }

    public function getDownloads() {
        return $this->downloads;
    }
    
    public function setPluginId($s) {
        $this->plugin_id = $s;
        return $this;
    }

    public function setReleaseId($s) {
        $this->release_id = $s;
        return $this;
    }

    public function setVersion($s) {
        $this->version = $s;
        return $this;
    }

    public function setStudipMinVersion($s) {
        $this->studip_min_version = $s;
        return $this;
    }

    public function setStudipMaxVersion($s) {
        $this->studip_max_version = $s;
        return $this;
    }

    public function setFileId($s) {
        $this->file_id = $s;
        return $this;
    }

    public function setUserId($s) {
        $this->user_id = $s;
        return $this;
    }

    public function setReleaseType($s) {
        $this->release_type = $s;
        return $this;
    }

    public function setDependencies($s) {
        $this->dependencies = $s;
        return $this;
    }

    public function setOrigin($s) {
        $this->origin = $s;
        return $this;
    }

    public function save() {
        $db = DBManager::get();
        if (!$this->release_id) {
            $this->release_id = md5(uniqid(time().$this->plugin_id.$this->user_id));
            $this->mkdate = time();
            $stmt = $db->prepare("INSERT INTO releases (release_id, plugin_id, version, studip_min_version, studip_max_version, mkdate, user_id, file_id, release_type, origin) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute(array($this->release_id,$this->plugin_id,$this->version,$this->studip_min_version,$this->studip_max_version,$this->mkdate,$this->user_id,$this->file_id,$this->release_type,$this->origin));
        } else {
            $stmt = $db->prepare("UPDATE releases SET version=?, studip_min_version=?, studip_max_version=?, file_id=?, release_type=?, origin=?, chdate=UNIX_TIMESTAMP() WHERE release_id=?");
            $stmt->execute(array($this->version,$this->version,$this->studip_max_version,$this->file_id,$this->release_type,$this->origin,$this->release_id));
        }
        $db->query(sprintf("DELETE FROM dependencies WHERE release_id='%s'",$this->release_id));
                foreach ($this->dependencies as $d) {
                        $db->query(sprintf("INSERT INTO dependencies (dependent_id, release_id) VALUES ('%s','%s')",$d, $this->release_id));
                }
        }

    public function getReleaseFromFileId($fid) {
        $r = DBManager::get()->query(sprintf("SELECT release_id FROM releases WHERE file_id='%s'",$fid))->fetch(PDO::FETCH_NUM);
        return $this->load($r[0]);
    }

    public function load($id) {
        $db = DBManager::get();
        $r = $db->query(sprintf("SELECT * FROM releases WHERE release_id='%s'",$id))->fetchAll();
        if (count($r)) {
            $this->release_id = $r[0]['release_id'];
            $this->version = $r[0]['version'];
            $this->studip_min_version = $r[0]['studip_min_version'];
            $this->studip_max_version = $r[0]['studip_max_version'];
            $this->file_id = $r[0]['file_id'];
            $this->plugin_id = $r[0]['plugin_id'];
            $this->mkdate = $r[0]['mkdate'];
            $this->chdate = $r[0]['chdate'];
            $this->user_id = $r[0]['user_id'];
            $this->downloads = $r[0]['downloads'];
            $this->release_type = $r[0]['release_type'];
            $this->origin = $r[0]['origin'];
            $rr = $db->query(sprintf("SELECT dependent_id FROM dependencies WHERE release_id='%s'",$this->release_id))->fetchAll();
                        foreach ($rr as $d)
                                array_push($this->dependencies, $d['dependent_id']);
            return TRUE;
        } else return FALSE;
    }

    public function setTags($t) {
                if (trim($t) == "") return $this;
                $tags = explode(',',$t);
                $current_tags = $this->getTags();
        foreach ($tags as $t) {
                        $t = trim(strtolower($t));
                        if ($t == "") continue;
                        if (in_array($t, $current_tags)) continue;
                        $statement = DBManager::get()->prepare("SELECT * FROM tags WHERE tag = ?");
                        $statement->execute(array($t));
                        $r = $statement->fetchAll();
                        if (count($r)) {
                                DBManager::get()->query(sprintf("INSERT INTO tags_objects (object_id, tag_id) VALUES ('%s','%s')", $this->release_id, $r[0]['tag_id']));
                        } else {
                                $id = md5(uniqid(time().$this->plugin_id));
                                $stmt = DBManager::get()->prepare("INSERT INTO tags (tag_id, tag, owner) VALUES (?,?,?)");
                                $stmt->execute(array($id, $t, $GLOBALS['USER']['user_id']));
                                DBManager::get()->query(sprintf("INSERT INTO tags_objects (object_id, tag_id) VALUES ('%s','%s')", $this->release_id, $id));
                        }
                        array_push($current_tags, $t);
                }

                return $this;
        }

    public function getTags()
    {
        $query = "SELECT tag
                  FROM tags_objects
                  JOIN tags USING (tag_id)
                  WHERE object_id = ?
                  ORDER BY tag ASC";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($this->release_id));
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function removeTag($t) {
        if (trim($t) != '') {
            $query = "SELECT tag_id FROM tags WHERE LOWER(tag) = LOWER(?)";
            $statement = DBManager::get()->prepare($query);
            $statement->execute(array($t));
            $r = $statement->fetchAll();

            $query = "DELETE FROM tags_objects WHERE tag_id = ? AND object_id = ?";
            $statement = DBManager::get()->prepare($query);
            $statement->execute(array($r[0]['tag_id'], $this->release_id));

            if (count($r) == 1) {
                $query = "DELETE FROM tags WHERE tag_id = ? AND owner != 'root'";
                $statement = DBManager::get()->prepare($query);
                $statement->execute(array($r[0]['tag_id']));
            }
        }
    }

    public function getDependencies() {
        $ret = array();
        $rr = DBManager::get()->query(sprintf("SELECT * FROM dependencies WHERE release_id='%s'",$this->release_id))->fetchAll();
        foreach ($rr as $r) {
            $rel = new Release();
            $rel->load($r['dependent_id']);
            array_push($ret, $rel);
        }
        return $ret;
    }

    public function remove() {
        DBManager::get()->query(sprintf("DELETE FROM tags_objects WHERE object_id='%s'", $this->release_id));
        DBManager::get()->query(sprintf("DELETE FROM file_content WHERE file_id='%s'", $this->file_id));
        DBManager::get()->query(sprintf("DELETE FROM ratings WHERE range_id='%s'", $this->release_id));
        DBManager::get()->query(sprintf("DELETE FROM comments WHERE range_id='%s'", $this->release_id));
        DBManager::get()->query(sprintf("DELETE FROM dependencies WHERE release_id='%s'", $this->release_id));
        DBManager::get()->query(sprintf("DELETE FROM releases WHERE release_id='%s'", $this->release_id));
        @unlink($GLOBALS['DYNAMIC_CONTENT_PATH'] . '/releases/' . $this->file_id . '.zip');
    }

    public function increaseDownloadCounter() {
        DBManager::get()->query(sprintf("UPDATE releases SET downloads=downloads+1 WHERE release_id='%s'",$this->release_id));
    }

}

?>
