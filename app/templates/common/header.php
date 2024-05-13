<?php

use App\Util\AppHelper;

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="<?= AppHelper::assetsUrl('js/app.js')?>"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= AppHelper::assetsUrl('moysklad/css/uikit.min.css')?>">
    <link rel="stylesheet" href="<?= AppHelper::assetsUrl('css/app.css')?>">
    <title>Мой склад - заказы покупателей</title>
</head>
<body>
<div id="container">
