<div class="content">
    <div class="container">
        <div class="load_more">
            <div class="row">

                <div class="col-lg-12 col-lg-offset-2">

                    <h1>新たな記事を作成する</h1>

                    <form method="post" action="index.php" role="form">

                        <input type="hidden" name="controller" value="articles">
                        <input type="hidden" name="action" value="create">

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
                                        <input id="title" type="text" name="title" class="form-control" placeholder="記事のタイトルを入力ください *" required="required" data-error="タイトルが必要。">
                                        <div class="help-block with-errors"></div>
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
                                        <input id="tag" type="text" name="tag" class="form-control" placeholder="記事のタグを入力ください *" required="required" data-error="タグが必要。">
                                        <div class="help-block with-errors"></div>
                                        <small> This is a backend project so im gonna not target on tag selector</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content">Content *</label>
                                        <?php if ($error['title'] == 'blank') : ?>
                                            <span class="error">内容を入力ください</span>
                                        <?php endif; ?>
                                        <textarea id="content" name="content" class="form-control" placeholder="Content of your post *" required="required" rows="30" data-error="Kindly write your post's content"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
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