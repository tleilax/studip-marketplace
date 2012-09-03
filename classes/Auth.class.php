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

require_once 'StudipAuth.class.php';

class Auth
{
    public function authenticateFromDev($loginkey, $userinformation)
    {
        Session::get()->startSession();
        $_SESSION['user_id'] = '';

        $first_name = trim(CryptMP::decryptPrivate(base64_decode($userinformation['first_name'])));
        $last_name  = trim(CryptMP::decryptPrivate(base64_decode($userinformation['last_name'])));
        $username   = trim(CryptMP::decryptPrivate(base64_decode($userinformation['user_name'])));
        $email      = trim(CryptMP::decryptPrivate(base64_decode($userinformation['email'])));

        if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($email) && $loginkey == $GLOBALS['REMOTE_LOGIN_KEY']) {
            $user_id = '';
            if (!$GLOBALS['UM']->userAlreadyExists($username)) {
                $GLOBALS['UM']->addNewUser($username, $first_name, $last_name, $email, '', 'Herr', '', 'studip');
            } else {
                $GLOBALS['UM']->updateUserInformation($username, array('first_name'=>$first_name, 'last_name'=>$last_name, 'email'=>$email));
            }
            $user_id = $GLOBALS['UM']->getUserIdByUsername($username);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['sid'] = session_id();
            return TRUE;
        }
        return FALSE;
    }

    public function authenticate($username, $passwort)
    {
        Session::get()->startSession();
        $_SESSION['user_id'] = '';

        $query = "SELECT user_id FROM users WHERE LOWER(username) = LOWER(?) AND passwort = MD5(?) AND auth = 'standard'";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($username, $passwort));
        if ($user_id = $statement->fetchColumn()) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['sid']     = session_id();
            return true;
        }

        $studipauth = new StudipAuth();
        $userinformation = $studipauth->authenticate($username, $passwort);
        if ($userinformation) {
            $user_id = '';
            if (!$GLOBALS['UM']->userAlreadyExists($username)) {
                $GLOBALS['UM']->addNewUser($username, $userinformation['first_name'], $userinformation['last_name'], $userinformation['email'], '', 'Herr', '', 'studip');
            } else {
                $GLOBALS['UM']->updateUserInformation($username, $userinformation);
            }
            $user_id = $GLOBALS['UM']->getUserIdByUsername($username);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['sid'] = session_id();
            return TRUE;
        }

        return FALSE;
    }

    public function getAuthenticatedUser()
    {
        Session::get()->startSession();
        if ($_SESSION['user_id'] == '') {
            return FALSE;
        }

        $query = "SELECT * FROM users WHERE user_id = ?";
        $statement = DBManager::get()->prepare($query);
        $statement->execute(array($_SESSION['user_id']));
        if ($user = $statement->fetch(PDO::FETCH_ASSOC)) {
            return $user;
        }

        return false;
    }
    
    public function checkPerm($perm)
    {
        if (!$GLOBALS['PERM']->have_perm($perm)) {
            GUIRenderer::showIndex($GLOBALS['FACTORY']->open('not_logged_in')->render());
            die();
        }
    }

}
