<?php
session_start();
include_once "/sys/config.php";

$content = new FileDescr($_GET['id']);
$content->view();
$content->FileD();


$comment = new Comment($_GET['id']);
echo $comment->Comm();
?>