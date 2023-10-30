<?php

$id = abs((int)$_GET['del']);

if($id) {
	if(!$news->deleteNews($id)) {
		$errMsg = "Произошла ошибка три удалении новости";
	} else {
		header("Location: news.php");
		exit;
	}
}
