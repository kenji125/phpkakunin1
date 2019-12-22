<?php

// ログイン

require_once(__DIR__ . '/config.php');
//configにまとめてやってみたが、かなり長くなるので分けた）
require_once(__DIR__ . '/Controller/Login.php');


$app = new Login();

$app->run();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Log In</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div>
    <form action="" method="post" id="login">
      <p>
        <input type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email) : ''; ?>">
      </p>
      <p>
        <input type="password" name="password" placeholder="password">
      </p>
      <p class="err"><?= h($app->getErrors('login')); ?></p>

      <div class="btn" onclick="document.getElementById('login').submit();">ログイン</div>
      <p class="fs12"><a href="/signup.php">新規登録</a></p>
    </form>
  </div>
</body>
</html>