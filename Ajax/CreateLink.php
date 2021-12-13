<?php

$createLink =  dirname(__DIR__);
include $createLink."\Mvc\Model\Operations\Core.php";

$Core = new \Model\Operations\Core();

$title = $_REQUEST['Title'];
$hint = "";


if ($title !== "") {
    $hint = $Core->toLowerUrl($title);
}

echo $hint === "" ? "no suggestion" : $hint;
