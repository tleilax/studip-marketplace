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

class Comment
{
    private $comment_text = '';
    private $comment_id = '';
    private $range_id = '';
    private $user_id = '';
    private $mkdate = 0;

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->load($id);
        }
    }

    public function getCommentId()
    {
        return $this->comment_id;
    }

    public function getRangeId()
    {
        return $this->range_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getCommentText()
    {
        return $this->comment_text;
    }

    public function getMkdate()
    {
        return $this->mkdate;
    }

    public function setRangeId($s)
    {
        $this->range_id = $s;
        return $this;
    }

    public function setUserId($s)
    {
        $this->user_id = $s;
        return $this;
    }

    public function setCommentText($s)
    {
        $this->comment_text = $s;
        return $this;
    }

    public function load($cid)
    {
        $query = "SELECT comment_id, range_id, user_id, comment_text, mkdate FROM comments WHERE comment_id = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($cid));
        $r = $statement->fetch(PDO::FETCH_ASSOC);

        $this->comment_id   = $r['comment_id'];
        $this->range_id     = $r['range_id'];
        $this->user_id      = $r['user_id'];
        $this->comment_text = $r['comment_text'];
        $this->mkdate       = $r['mkdate'];

        return true;
    }

    public function save() {
        if (!$this->comment_id) {
            $this->comment_id = md5(uniqid(time() . $this->range_id, true));
            $query = "INSERT INTO comments (comment_id, range_id, user_id, comment_text, mkdate)
                      VALUES (?, ?, ?, ?, UNIX_TIMESTAMP())";
            $stmt = DBManager::get()->prepare($query);
            $stmt->execute(array($this->comment_id, $this->range_id, $this->user_id, $this->comment_text, $this->mkdate));
        } else {
            $query = "UPDATE comments SET comment_text = ? WHERE comment_id = ?";
            $stmt = DBManager::get()->prepare($query);
            $stmt->execute(array($this->comment_text, $this->comment_id));
        }
    }

    public function delete()
    {
        $query = "DELETE FROM comments WHERE comment_id = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($this->comment_id));
    }
}
