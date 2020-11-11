<!-- ヘッダーパーシャル -->
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-dark border-bottom box-shadow">
    <h5 class="my-0 mr-md-auto font-weight-normal"><a class="text-white" href="index.php?">Backendkenshuu2</a></h5>
    <nav class="my-2 my-md-0 mr-md-3">
      <a class="p-2 text-white" href="?controller=articles">記事</a>
      <a class="p-2 text-white" href="?controller=users">ユーザー</a>
    </nav>
    <?php
    if (Authentication::check()) {
      echo '<div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ' . Authentication::user()->nickname . '
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="?controller=articles&action=add">記事作成</a>
              <a class="dropdown-item" href="?controller=users&action=articles">自分の記事</a>
            </div>
          </div>';
    } else {
      echo '<a class="btn btn-outline-primary" href="?controller=authentications&action=login">ログイン</a>';
    }
    ?>
  </div>
</div>