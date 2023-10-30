<?php

$items = $news->getNews();

if ($items === false) {
	$errMsg = "Произошла ошибка при выводе новостной ленты";
} else if (!count($items)) {
	echo  "<br>Новостей нет<br>";
} else {
  foreach ($items as 
    ["id" => $id, 
    "title" => $title, 
    "category" => $category, 
    "description" => $description, 
    "source" => $source,
    "datetime" => $datetime]) {
    $datetime = date("d.m.Y H:i:s", $datetime); 
echo <<<_NEWS
	<div style="border-bottom: 1px solid black;">
	  <h3>$title</h3>
	  <h4>$category</h4>
	  <p>$description</p>
	  <p>$source</p>
	  <date>$datetime</date>
	  <p><a href="news.php?del={$id}">Удалить</a></p>
	</div>
_NEWS;
  }
}