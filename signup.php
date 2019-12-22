<?php

// 新規登録

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/Exception/error.php');

//親クラスのインスタンス化は不要
// new Controller();

//configにcontrollerクラス、modelクラスを作っている。
$app = new Sinki();

$app->run();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Sign Up</title>
</head>
<body>
  <div id="container">
    <form action="" method="post" id="signup">
      <p>
        <input type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? $app->getValues()->email : ''; ?>">
      </p>
      <p class="err"><?= $app->getErrors('email'); ?></p>
      <p>
        <input type="password" name="password" placeholder="password">
      </p>
      <p class="err"></p>
      <div class="btn" onclick="document.getElementById('signup').submit();">新規登録</div>
      <p class="fs12"><a href="/login.php">ログイン/a></p>
    </form>
  </div>
</body>
</html>