<?php 
use common\components\Application;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>

    <style type="text/css">

    * {
        margin: 0;
        padding: 0;
        font-size: 100%;
        font-family: 'Roboto', "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        line-height: 1.65;
    }
    img {
        max-width: 100%;
        margin: 0 auto;
        display: block;
    }
    body,
    .body-wrap {
        color: #222325;
        width: 100% !important;
        height: 100%;
        background: #efefef;
        -webkit-font-smoothing: antialiased;
        -webkit-text-size-adjust: none;
    }
    a {
        color: #1dbc9c;
        text-decoration: none;
    }
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .text-left {
        text-align: left;
    }
    .green {
        color: #1dbc9c;
    }
    .blue {
        color: #158cc9;
    }
    .button {
        display: inline-block;
        color: white;
        background: #1dbc9c;
        border: solid #1dbc9c;
        border-width: 10px 20px 8px;
        font-weight: bold;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    h1, h2, h3, h4, h5, h6 {
        margin-bottom: 20px;
        line-height: 1.25;
    }
    h1 {
        font-size: 32px;
    }
    h2 {
        font-size: 28px;
        font-weight: 300;
    }
    h3 {
        font-size: 24px;
    }
    h4 {
        font-size: 20px;
    }
    h5 {
        font-size: 16px;
    }
    p, ul, ol {
        color: #6F7072;
        font-size: 14px;
        font-weight: normal;
        margin-bottom: 20px;
    }
    .container {
        display: block !important;
        clear: both !important;
        margin: 0 auto !important;
        max-width: 580px !important;
    }
    .container table {
        width: 100% !important;
        border-collapse: collapse;
    }
    .container .masthead {
        padding: 35px 0;
        background: #1dbc9c;
        color: white;
    }
    .container .masthead img {
        margin: 0;
        width: 80px;
    }
    .container .masthead h1 {
        margin: 0 auto !important;
        max-width: 90%;
        text-transform: uppercase;
    }
    .container .content {
        background: white;
        padding: 50px 35px;
    }
    .container .content .callout {
        padding: 20px 0;
    }
    .container .content .callout h2 {
        font-weight: 600;
        margin: 0;
    }
    .container .content.footer {
        padding: 25px 35px;
        background: none;
    }
    .container .content.footer p {
        margin-bottom: 0;
        color: #888;
        text-align: center;
        font-size: 12px;
    }
    .container .content.footer a {
        color: #888;
        text-decoration: none;
        font-weight: bold;
    }

    </style>
</head>
<table class="body-wrap">
    <tr>
        <td class="container">
            <table>
                <tr>
                    <td align="right" class="masthead">
                        <img src="http://pullr.io/wp-content/uploads/2014/11/logo-light-sm.png" />
                    </td>
                </tr>
                <tr>
                    <td class="content">
                        <?= $content ?>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td class="container">
            <table>
                <tr>
                    <td class="content footer" align="center">
                    	<?= $footer ?>
                        <p>&copy; <a href="http://www.pullr.io">Pullr</a>, LLC. All Rights Reserved.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>