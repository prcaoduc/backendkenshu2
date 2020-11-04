<?php
echo '<ul>';
foreach ($users as $user) {
  echo '<li> '. $user->id .'
    <a href="index.php?controller=users&action=show&id=' . $user->id . '">' . $user->nickname . '</a>
  </li>';
}
echo '</ul>';