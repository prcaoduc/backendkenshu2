<div class="container">

  <div class="row">

    <div class="col-lg-12">

      <div class="card mt-4">
      <div id="carouselExampleIndicators" style="height: 550px;" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <?php
              for($i=0; $i<count($article->images()) ; $i++){
                if( $article->images()[$i]->isthumbnail ) echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$i.'" class="active"></li>';
                else echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$i.'"></li>';
              }
              ?>
            </ol>
            <div class="carousel-inner">
              <?php
              for($i=0; $i<count($article->images()) ; $i++){
                if( $article->images()[$i]->isthumbnail ) echo '<div class="carousel-item active">
                <img class="d-block w-100 h-100" src="'.$article->images()[$i]->url.'" alt="First slide">
              </div>';
                else echo '<div class="carousel-item">
                <img class="d-block w-100 h-100" src="'.$article->images()[$i]->url.'" alt="First slide">
              </div>';
              }
              ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        <div class="card-body">
          <h3 class="card-title"><?= $article->title ?></h3>
          <?php
          foreach($article->tags() as $tag){
          echo '<span class="badge badge-secondary">'.$tag->name.'</span>';
        }?>
          <h4>Created at : <?= $article->created_at ?></h4>
          <p class="card-text"><?= $article->content ?></p>
          
        </div>
      </div>
    </div>

  </div>

</div>