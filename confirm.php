<?php
//エラーをブラウザに出す
error_reporting(E_ALL); //エラーの表示
ini_set('display_errors', 'On'); //ブラウザ画面にエラーを表示

//Sessionを開始
session_start();

// CSRF対策 トークン情報で認証
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'] = $token;

if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
  echo '不正なPOSTです。';
  exit();
}

//XSS対策
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>メールフォーム実践</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <form action="thanks.php" method="POST">

      <div class="form-group">
        <label>お名前</label>
        <p><?php echo h($_SESSION['name']) ?></p>
      </div>

      <div class="form-group">
        <label>メールアドレス</label>
        <p><?php echo h($_SESSION['mail']) ?></p>
      </div>
      <button type="button" onclick="history.back()" class="btn btn-primary">戻る</button>
      <button type="submit" class="btn btn-primary">送信する</button>
    </form>
  </div>

</body>

</html>
