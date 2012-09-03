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

class Content
{

    private $content_id = '';
    private $content_txt = '';
    private $key = '';

    public function __construct($id = null) {
        if ($id !== null) {
            $this->load($id);
        }
    }

    public function load($key)
    {
        $query = "SELECT content_id, content_txt, ckey FROM mp_content WHERE ckey = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($key));
        $r = $statement->fetch(PDO::FETCH_ASSOC);

        $this->content_id  = $r['content_id'];
        $this->content_txt = $r['content_txt'];
        $this->key         = $r['ckey'];
    }

    public function setContentTxt($s)
    {
        $this->content_txt = $s;
        return $this;
    }

    public function getContentTxt()
    {
        return $this->content_txt;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function save()
    {
        $query = "UPDATE mp_content SET content_txt = ? WHERE ckey = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($this->content_txt, $this->key));
    }

}
