<?php
error_reporting(E_ALL); //エラーの表示
ini_set('display_errors', 'On'); //ブラウザ画面にエラーを表示

// CSRF対策 トークン情報で認証
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'] = $token;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = $_POST['name'];
  $mail = $_POST['mail'];


  //バリデーションの処理
  if (empty($name) || 10 < mb_strlen($name)) {
    $error['name'] = 'お名前は10文字以内で入力してください';
  }

  if (empty($mail) || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $error['mail'] = 'メールアドレスを正しい形式で入力してください';
  }

  if (empty($error)) {

    header('Location: confirm.php');
    $_SESSION['name'] = $name;
    $_SESSION['mail'] = $mail;

    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <title>データの取得とGETとPOSTの違いについて</title>
</head>

<body>
  <div class="container">
    <form action="" method="POST">

      <!-- input -->
      <div class="form-group">
        <label>お名前</label>
        <input type="text" name="name" class="form-control">
        <p class="text-danger"><?php if (!empty($error['name'])) echo $error['name'] ?></p>
      </div>
      <!-- input -->

      <div class="form-group">
        <label>メールアドレス</label>
        <input type="mail" name="mail" class="form-control">
        <p class="text-danger"><?php if (!empty($error['mail'])) echo $error['mail'] ?></p>
      </div>

      <input type="hidden" name="token" value="<?php echo $token ?>">
      <button type="submit" class="btn btn-primary">確認する</button>
    </form>
  </div>

</body>

</html>
