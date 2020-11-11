<!-- 登録ページ -->
<form action="index.php" method="post" enctype="multipart/form-data">
  <div class="container">
    <h1>新規登録</h1>
    <p>必要事項をご記入頂きまして、「確認」ボタンをクリックください。</p>
    <hr>

    <input type="hidden" name="controller" value="authentications">
    <input type="hidden" name="action" value="check">
    <input type="hidden" name="csrftoken" value="<?php echo htmlentities($token); ?>" />

    <label for="email"><b>メールアドレス（ログインするとき使用）：</b></label>
    <input type="email" placeholder="メールアドレス入力" 　size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>" name="email" id="email" required><br>
    <?php if ($errors_array['email'] == 'blank'): ?>
    <p class="errors_array">* メールアドレスを入力してください</p> 
    <?php endif; ?>
    <?php if ($errors_array['email'] == 'duplicate'): ?>
    <p class="errors_array">* メールアドレスがありました</p> 
    <?php endif; ?>

    <label for="nickname"><b>ニックネーム：</b></label>
    <input type="text" placeholder="ニックネーム入力" 　size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['nickname'], ENT_QUOTES); ?>" name="nickname" id="nickname" required><br>
    <?php if ($errors_array['nickname'] == 'blank'): ?>
    <p class="errors_array">* ニックネームを入力してください</p> 
    <?php endif; ?>
    <?php if ($errors_array['nickname'] == 'duplicate'): ?>
    <p class="errors_array">* ニックネームがありました</p> 
    <?php endif; ?>

    <label for="pwd"><b>パスワード：</b></label>
    <input type="password" placeholder="パスワード入力" 　size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['pwd'], ENT_QUOTES); ?>" name="pwd" id="pwd" required><br>
    <?php if ($errors_array['pwd'] == 'blank'): ?>
    <p class="errors_array">* パスワードを入力してください</p> 
    <?php endif; ?>
    <?php if ($errors_array['pwd'] == 'length'): ?>
    <p class="errors_array">* パスワードは4文字以上で入力してください</p> 
    <?php endif; ?>

    <label for="pwd-repeat"><b>パスワード再入力：</b></label>
    <input type="password" placeholder="パスワード再入力"　size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['pwd-repeat'], ENT_QUOTES); ?>" name="pwd-repeat" id="pwd-repeat" required><br>
    <?php if ($errors_array['pwd-repeat'] == 'blank'): ?>
    <p class="errors_array">* パスワードを再入力してください</p> 
    <?php endif; ?>
    <?php if ($errors_array['pwd-repeat'] == 'notsame'): ?>
    <p class="errors_array">* パスワードと一致ではありません</p> 
    <?php endif; ?>
    <hr>
    <button type="submit" class="registerbtn">入力内容を確認する</button>
  </div>

  <div class="container signin">
    <p>すでにアカウントをお持ちですか？ <a href="#">ログイン</a>.</p>
  </div>
</form>