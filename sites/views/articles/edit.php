<!-- 記事編集ページ -->
<div class="content">
    <div class="container">
        <div class="load_more">
            <div class="row">

                <div class="col-lg-12 col-lg-offset-2">

                    <h1>記事を編集する</h1>

                    <form method="post" action="?controller=articles&action=update" role="form" enctype="multipart/form-data">

                        <input type="hidden" name="id" value="<?= $article->id ?>">
                        <input type="hidden" name="csrftoken" value="<?php echo htmlentities($token); ?>" />

                        <div class="messages"></div>

                        <div class="controls">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Title * </label>
                                        <?php if ($error['title'] == 'length') : ?>
                                            <span class="error">タイトルの最大長さは５０キャラクター</span>
                                        <?php endif; ?>
                                        <?php if ($error['title'] == 'blank') : ?>
                                            <span class="error">タイトルを入力ください</span>
                                        <?php endif; ?>
                                        <input id="title" type="text" name="title" class="form-control" placeholder="記事のタイトルを入力ください *" required="required" data-error="タイトルが必要。" value="<?= $article->title ?>">
                                        <small>　タイトルの最大長さは５０キャラクター</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tag">Tag * </label>
                                        
                                        <div id ="tag_wrapper">
                                        <?php
                                            foreach($article->tags() as $tag){
                                                echo '<div class="form-inline"><input type="text" name="tag[]" class="form-control inline" value="'.$tag->name.'" required="required">
                                                <button class="btn btn-warning remove_tag_button" type="button"><i class="fa fa-times"></i></button></div>';
                                            }
                                        ?>
                                        </div>
                                        <a class="btn btn-secondary tag_new">新しいタグ</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content">内容 * </label>
                                        <?php if ($error['content'] == 'blank') : ?>
                                            <span class="error">内容を入力ください</span>
                                        <?php endif; ?>
                                        <textarea id="content" name="content" class="form-control" placeholder="内容 *" required="required" rows="30" data-error="Kindly write your post's content" ><?= $article->content ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" name="add_submit" class="btn btn-success btn-send" value="編集">
                                </div>
                            </div>
                        </div>
                        <div id="image_show">

                        </div>
                    </form>
                    </body>

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $('.tag_new').on('click',function(e){
        $('#tag_wrapper').append('<div class="form-inline"><input type="text" name="tag[]" class="form-control inline" required="required"><button class="btn btn-warning remove_tag_button" type="button"><i class="fa fa-times"></i></button></div>');
        // $(this).css("background", "#f99");
    });
    $(document).on("click", '.remove_tag_button',function(e){
        var num_items = $('.remove_tag_button').length;
        console.log(num_items);
        if(num_items > 1){
            $(this).parent().remove();
        }
        else {
            $(this).parent().find('#once_tag').remove();
            $(this).parent().append('<span class="text-danger" id="once_tag">article have to have at least 1 tag</span>');
        }
    });
</script>