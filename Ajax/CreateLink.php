<?php

$createLink =  dirname(__DIR__);
include $createLink."\Mvc\Model\Operations\Core.php";

$Core = new \Model\Operations\Core();

$title = $_REQUEST['Title'];
$link = "";


if ($title !== "") {
    $link = $Core->toLowerUrl($title);
}

echo $link === "" ? "no suggestion" : $link;
