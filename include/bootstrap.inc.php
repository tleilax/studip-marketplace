<?
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

ini_set('date.timezone', 'Europe/Berlin');

$_never_globalize_request_params = array('msg','BASE_URI','BASE_PATH','CONVERT_PATH','DYNAMIC_CONTENT_URL','DYNAMIC_CONTENT_PATH','REFRESH','TMP_PATH','SERVER_NAME');
foreach($_never_globalize_request_params as $one_param){
        if (isset($_REQUEST[$one_param])){
                unset($GLOBALS[$one_param]);
        }
}

require_once dirname(__FILE__).'/../classes/Session.class.php';
require_once dirname(__FILE__).'/language.inc.php';

$SUPPORT_ADDRESS = 'tleilax+marketplace@gmail.com';
$SERVER_NAME = $_SERVER['SERVER_NAME']; //'plugins.studip.de';
$REFRESH = 0; // Minuten
Session::get()->startSession();
if (!$_SESSION['msg_type'])
        $_SESSION['msg_type'] = 'info';

$INSTALLED_LANGUAGES["de_DE"] = array ("path"=>"de", "picture"=>"lang_de.gif", "name"=>"Deutsch");
$INSTALLED_LANGUAGES["en_GB"] = array ("path"=>"en", "picture"=>"lang_en.gif", "name"=>"English");

$DEFAULT_LANGUAGE = "de_DE";  // which language should we use if we can gather no information from user?
$_language_path = 'de';

$_language_domain = "marketplace";

include 'include/config.inc.php';
require_once dirname(__FILE__).'/visual.inc.php';
require_once dirname(__FILE__).'/../lib/CssClassSwitcher.inc.php';
require_once dirname(__FILE__).'/../lib/MessageBox.class.php';
require_once dirname(__FILE__).'/../lib/flexi/flexi.php';
require_once dirname(__FILE__).'/../lib/DBManager.class.php';
require_once dirname(__FILE__).'/../lib/Request.class.php';
require_once dirname(__FILE__).'/../lib/Avatar.class.php';

spl_autoload_register(function ($class) {
    $filename = sprintf('%s/../classes/%s.class.php',
                        dirname(__FILE__),
                        $class);
    @include $filename;
});

$BASE_URI = sprintf('http%s://%s%s%s/',
                    @$_SERVER['HTTPS'] ? 's' : '',
                    $_SERVER['SERVER_NAME'],
                    ((@$_SERVER['HTTPS'] && $_SERVER['SERVER_PORT'] != 443) ||
                     (!@$_SERVER['HTTPS'] && $_SERVER['SERVER_PORT'] != 80)) ? ':' . $_SERVER['SERVER_PORT'] : '',
                    rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
$BASE_PATH = realpath(dirname(__FILE__) . '/..') . '/';
$TMP_PATH = '/tmp';
$FACTORY = new Flexi_TemplateFactory(dirname(__FILE__).'/../templates');
$IMAGES_URL = $BASE_URI . 'images';
$DYNAMIC_CONTENT_URL = $BASE_URI . 'content';
$DYNAMIC_CONTENT_PATH = $BASE_PATH . 'content';
$CONVERT_PATH = "/home/splugin/wwwroot/convert";

$ZIP_USE_INTERNAL = false;
$ZIP_PATH = "/home/splugin/wwwroot/zip";
$ZIP_OPTIONS = "-r";

DBManager::getInstance()
  ->setConnection('splugin',
                  'mysql:host='.$GLOBALS['DB_HOST'].
                  ';dbname='.$GLOBALS['DB_DATABASE'],
                  $GLOBALS['DB_USER'],
                  $GLOBALS['DB_PASSWORD']);

$UM = new UserManagement();
$GUI = new GUIRenderer();
$AUTH = new Auth();
$DBM = new MPDBM();
$MAIL = new MailRenderer();

$USER = $AUTH->getAuthenticatedUser();

$PERM = new Perm();

function setMessage($type, $msg) {
        $_SESSION['msg_type'] = $type;
        $_SESSION['msg'] = $msg;
}

define("MAX_RATING_VALUE",5);

?>
