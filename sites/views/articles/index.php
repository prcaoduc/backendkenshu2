<!-- 記事リスト閲覧ページ -->
<div class="container-fluid">
  <div class="row content">

    <div class="col-sm-12 d-block justify-content-center">
      <h4><small>ARTICLES</small></h4>
      <ul class="list-unstyled">
        <?php
        foreach ($articles as $article) {
          echo '<li class="media">
        <img class="mr-3" style="height: 110px; width = 200px;" src="' . $article->getThumbnail()->url . '" alt="Generic placeholder image">';
          echo '<div class="media-body">
        <h5 class="mt-0 mb-1">' . $article->title . '</h5>
        <h6 class="mt-0 mb-1"> Post by ' . $article->author()->nickname . ', ' . $article->created_at . '</h6>';
          foreach ($article->tags() as $tag) {
            echo '<span class="badge badge-secondary">#' . $tag->name . '</span>';
          }
          echo '<p>' . $article->content . '</p>
      </div>';
        }
        ?>
      </ul>
    </div>
  </div>
</div>