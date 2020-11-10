<!-- 登録情報の確認ページ -->
<form action="index.php" method="post">
    <input type="hidden" name="controller" value="authentications">
    <input type="hidden" name="action" value="signup">
    <input type="hidden" name="csrftoken" value="<?php echo htmlentities($token); ?>" />
    <dl>
        <dt>メールアドレス</dt>
        <dd>
        <?php echo htmlspecialchars($check_data['email'], ENT_QUOTES); ?>
        </dd>
        <dt>ニックネーム</dt>
        <dd>
        <?php echo htmlspecialchars($check_data['nickname'], ENT_QUOTES); ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
        【表示されません】
        </dd>
    </dl>
    <div><a href="index.php?controller=users&action=register">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
</form>