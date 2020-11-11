<!-- 記事投稿ページ -->
<div class="content">
    <div class="container">
        <div class="load_more">
            <div class="row">

                <div class="col-lg-12 col-lg-offset-2">

                    <h1>新たな記事を作成する</h1>

                    <form method="post" action="?controller=articles&action=create" role="form" enctype="multipart/form-data">

                        <input type="hidden" name="csrftoken" value="<?php echo htmlentities($token); ?>" />
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
                                        <input id="title" type="text" name="title" class="form-control" placeholder="記事のタイトルを入力ください *" required="required" data-error="タイトルが必要。">
                                        <small>　タイトルの最大長さは５０キャラクター</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tag">Tag * </label>
                                        <?php if ($error['tag'] == 'blank') : ?>
                                            <span class="error">タグを入力ください</span>
                                        <?php endif; ?>
                                        <div id ="tag_wrapper">
                                            <div class="form-inline">
                                                <input type="text" name="tag[]" class="form-control inline" placeholder="記事のタグを入力ください *" required="required">
                                                <button class="btn btn-warning remove_tag_button" type="button"><i class="fa fa-times"></i></button>
                                            </div>
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
                                        <textarea id="content" name="content" class="form-control" placeholder="内容 *" required="required" rows="30" data-error="Kindly write your post's content"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="images">イメージ</label>
                                        <input type="file" id="images" name="images[]" multiple="multiple" accept="image/*" class="form-control" onload="{(e) => {this.loadMe(e, index)}}" />
                                    </div>
                                </div>
                                <ul id="image_show">

                                </ul>
                                <div class="col-md-12">
                                    <input type="submit" name="add_submit" class="btn btn-success btn-send" value="Add">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-muted"><strong>*</strong> フィールドが必要です。</p>
                                </div>
                            </div>
                        </div>

                    </form>
                    </body>

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $('input[type="file"]').on('change', function() {
        for (var i = 0; i < this.files.length; i++) {
            var fr = new FileReader();
            fr.onload = (function(i) {
                return function(e) {
                    $('#image_show').append('<li> <input type="radio" name="thumbnail" value="' + i + '" id="image_checkbox' + i + '" /> ' +
                        '<label for="image_checkbox' + i + '"><img src="' + e.target.result + ' " width="200px" height="100px"/></label>');
                };
            })(i);
            fr.readAsDataURL(this.files[i]);
        }
    });
    $('.tag_new').on('click',function(e){
        $('#tag_wrapper').append('<div class="form-inline"><input type="text" name="tag[]" class="form-control inline" placeholder="記事のタグを入力ください *" required="required"><button class="btn btn-warning remove_tag_button" type="button"><i class="fa fa-times"></i></button></div>');
        // $(this).css("background", "#f99");
    });
</script>