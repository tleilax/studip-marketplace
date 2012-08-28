<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="shortcut icon" type="image/x-icon"  href="<?= $GLOBALS['BASE_URI'] ?>images/icons/favicon.ico">
<? if ($GLOBALS['USER']) : ?>
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
</head>
<body>
