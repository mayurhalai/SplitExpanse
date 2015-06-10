<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Split Expanse</title>
        <meta name="description" content="Manage bills easily">
        <meta name="keywords" content="splitbill,splitbills,split bill,split bills,splitexpanse,split expanse,manage bill,manage bills">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="<?php echo URL; ?>img/se.png" type="image/png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link href="<?php echo URL; ?>css/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo URL; ?>css/datepicker3.css" rel="stylesheet" type="text/css">
        <script src="<?php echo URL; ?>js/datepicker.js" type="text/javascript"></script>
    </head>
    <body>
        <?php @session_start(); ?>
        <div class="titlebar">
            <div class="logo">
                SplitExpanse
            </div>
            <?php
            $loggedin = FALSE;
            if (isset($_SESSION["logged"])) {
                $loggedin = $_SESSION["logged"];
            }
            if ($loggedin == TRUE):
                ?>
                <div style="position: absolute; top: 10px; right: 20px">
                    Hi, <?php echo $_SESSION["name"]; ?><button class="button" onclick="window.location.replace('<?php echo URL; ?>dashboard/logout')">Logout</button>
                </div>
            <?php
            else:
                session_destroy();
            endif;
            ?>
        </div>
