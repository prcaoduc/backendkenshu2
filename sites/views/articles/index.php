<?php
echo '<ul>';
foreach ($articles as $article) {
  echo '<li> '. $article->id .'
    <a href="index.php?controller=articles&action=show&id=' . $article->id . '">' . $article->title . '</a>
  </li>';
}
echo '</ul>';