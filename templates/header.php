<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" type="image/x-icon"  href="<?= $GLOBALS['BASE_URI'] ?>images/icons/favicon.ico">
<? if ($GLOBALS['USER'] && $GLOBALS['REFRESH']) : ?>
    <meta http-equiv="refresh" content="<?= $GLOBALS['REFRESH'] * 60 ?>; URL='?dispatch=logout'">
<? endif ?>
    <title>Stud.IP Plugin Marktplatz</title>
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/newstyle/style.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/newstyle/style_content.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/basis.css" type="text/css">
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/tagcloud.css" type="text/css">
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/jquery/jquery.ui.all.css" type="text/css">
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/jquery.lightbox-0.5.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/star_rating.css" type="text/css" media="all">
    <link rel="stylesheet" href="<?= $GLOBALS['BASE_URI'] ?>css/basis.css" type="text/css">

    <script type="text/javascript" src="<?= $BASE_URI ?>lib/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/scriptaculous/prototype.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/scriptaculous/scriptaculous.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/scriptaculous/effects.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/scriptaculous/controls.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/accordion.js"></script>

    <script type="text/javascript" src="<?= $BASE_URI ?>js/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/jquery/jquery-ui-1.8.custom.min.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/jquery/jquery.watermark.js"></script>
    <script type="text/javascript" src="<?= $BASE_URI ?>js/lightbox/jquery.lightbox-0.5.js"></script>
    <script type="text/javascript">var $j = jQuery.noConflict();</script>

    <script type="text/javascript" src="<?= $BASE_URI ?>js/marketplace.js"></script>
</head>
<body>
