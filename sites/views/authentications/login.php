<form action="index.php" method="post">

    <input type="hidden" name="controller" value="authentications">
    <input type="hidden" name="action" value="signin">

    <dl>
        <dt>メールアドレス</dt>
        <dd>
            <input type="text" name="email" size="35" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" maxlength="255" />
            <?php if ($error['login'] == 'blank') : ?>
                <p class="error">* メールアドレスとパスワードをご記入ください</p> <?php endif; ?>
            <?php if ($error['login'] == 'failed') : ?>
                <p class="error">* ログインに失敗しました。正しくご記入ください。</p> <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
            <input type="password" name="pwd" size="35" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" maxlength="255" />
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
            <input id="save" type="checkbox" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
        </dd>
        </d<div><input type="submit" value="ログインする" /></div>
</form>