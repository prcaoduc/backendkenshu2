<!-- ユーザーの記事の閲覧 -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="container-fluid">
                <div class="row content">
                    <div class="col-sm-3 sidenav">
                        <h4>John's Blog</h4>
                        <ul class="nav nav-pills nav-stacked">
                            <li class="active"><a href="#section1">Home</a></li>
                            <li><a href="#section2">Friends</a></li>
                            <li><a href="#section3">Family</a></li>
                            <li><a href="#section3">Photos</a></li>
                        </ul><br>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search Blog..">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-9">
                        <?php
                        $articles = $user->articles();
                        if (count($articles)) {
                            echo '
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">タイトル</th>
                                    <th scope="col">作成時間</th>
                                    <th scope="col">編集</th>
                                </tr>
                            </thead>
                            <tbody>';
                            for ($i = 0; $i < count($articles); $i++) {
                                echo '<tr>
                                            <th scope="row">' . ($i + 1) . '</th>
                                            <td>' . $articles[$i]->title . '</td>
                                            <td>' . $articles[$i]->created_at . '</td>
                                            <td>
                                            <form action="index.php" method="get">
                                                <input type="hidden" name="controller" value="articles">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="csrftoken" value="' . $token . '" />
                                                <input type="hidden" name="id" value="' . $articles[$i]->id . '">
                                                <input type="submit" value="Edit" class="btn btn-outline-primary"/>
                                            </form>

                                            <form action="index.php" method="post">
                                                <input type="hidden" name="controller" value="articles">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="csrftoken" value="' . $token . '" />
                                                <input type="hidden" name="id" value="' . $articles[$i]->id . '">
                                                <input type="submit" value="Delete" class="btn btn-outline-danger"/>
                                            </form>
                                        </tr>';
                            }
                            echo    '</tbody>
                            </table>';
                        } else echo '<h4 class="mx-auto">まだ記事を投稿しません</h4>';?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>