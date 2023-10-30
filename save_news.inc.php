<?php

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$title = $news->db->escapeString($_POST['title']);
	$category = $news->db->escapeString($_POST['category']);
	$description = $news->db->escapeString($_POST['description']);
	$source = $news->db->escapeString($_POST['source']);

	if(empty($title) || empty($description)) {
		$errMsg = "Заполните все поля формы!";
	} else if(!$news->saveNews($title, $category, $description, $source)) {
		$errMsg = "Произошла ошибка при добавлении новости";
	}

	$news->createRss();

	header("Localhost: news.php");
}