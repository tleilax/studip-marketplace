<?php
/**
 * @author Jan-Hendrik Willms <tleilax+studip@gmail.com>
 * @author Jan Kulmann <jankul@zmml.uni-bremen.de>
 */

// +---------------------------------------------------------------------------+
// Copyright (C) 2012 Jan Kulmann <jankul@zmml.uni-bremen.de>
// Copyright (C) 2012 Jan-Hendrik Willms <tleilax+studip@gmail.com>
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

class MFile
{
    private $file_id = '';
    private $user_id = '';
    private $mkdate = 0;
    private $file_content = '';
    private $file_name = '';
    private $file_size = 0;
    private $file_type = '';

    public function __construct($id = null) {
        if ($id !== null) {
            $this->load($id);
        }
    }

    public function getFileId()
    {
        return $this->file_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getMkdate()
    {
        return $this->mkdate;
    }

    public function getFileName()
    {
        return $this->file_name;
    }

    public function getFileSize()
    {
        return $this->file_size;
    }

    public function getFileType()
    {
        return $this->file_type;
    }

    public function setFileId($s)
    {
        $this->file_id = $s;
        return $this;
    }

    public function setUserId($s)
    {
        $this->user_id = $s;
        return $this;
    }

    public function setMkdate($s)
    {
        $this->mkdate = $s;
        return $this;
    }

    public function setFileType($s)
    {
        $this->file_type = $s;
        return $this;
    }

    public function setFileName($s)
    {
        $this->file_name = $s;
        return $this;
    }

    public function setFileSize($s)
    {
        $this->file_size = $s;
        return $this;
    }

    public function load($fid)
    {
        $query = "SELECT file_id, user_id, mkdate, file_name, file_size, file_type
                  FROM file_content
                  WHERE file_id = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($fid));
        $r = $statement->fetch(PDO::FETCH_ASSOC);

        $this->file_id   = $r['file_id'];
        $this->user_id   = $r['user_id'];
        $this->mkdate    = $r['mkdate'];
        $this->file_name = $r['file_name'];
        $this->file_size = $r['file_size'];
        $this->file_type = $r['file_type'];

        return true;
    }

    public function save()
    {
        if (!$this->file_id) {
            $this->file_id = md5(uniqid(time() . $this->user_id, true));
            $query = "INSERT INTO file_content (file_id, user_id, mkdate, file_name, file_size, file_type)
                      VALUES (?, ?, UNIX_TIMESTAMP(), ?, ?, ?)";
            $stmt = DBManager::get()->prepare($query);
            $stmt->execute(array($this->file_id, $this->user_id, $this->file_name, $this->file_size, $this->file_type));
        } else {
            $query = "UPDATE file_content SET file_name = ?, file_size = ? WHERE file_id = ?";
            $stmt = DBManager::get()->prepare($query);
            $stmt->execute(array($this->file_name, $this->file_size, $this->file_id));
        }
    }

    public function remove()
    {
        $query = "DELETE FROM file_content WHERE file_id = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($this->file_id));
    }

}
