<?php
//エラーをブラウザに出す
error_reporting(E_ALL); //エラーの表示
ini_set('display_errors', 'On'); //ブラウザ画面にエラーを表示

//Sessionを開始
session_start();

//メール送信設定
mb_language('Japanese');
mb_internal_encoding('UTF-8');

//送信元
$from = '運営事務局';
$from_to = 'info@startkit.jp';

//件名
$subject = 'サイトからお問い合わせがありました';

//自動返信
$to = $_SESSION['mail'];
$subject_re = 'お問い合わせありがとうございました';

//メールヘッダー
$header = '';
$header .= "Content-Type: text/plain \r\n"; //データ形式
$header .= "Return-Path: " . $from_to . " \r\n"; //受取不可の場合のエラー通知が行くメールアドレス
$header .= "From: " . $from_to . " \r\n"; //差出人アドレス
$header .= "Sender: " . $from . " \r\n"; //送信者の名前（会社名）とメールアドレス
$header .= "Reply-To: " . $to . " \r\n"; //返信アドレス
$header .= "Organization: " . $from . " \r\n"; //送信者名（会社名）


$message = <<<EOM

以下の内容でお問い合わせがりました。
==================
お名前：{$_SESSION['name']}
メールアドレス：{$_SESSION['mail']}
==================

EOM;

mb_send_mail($from_to, $subject, $message, $header);


//自動返信メッセージ
$message_re = <<<EOM

お問い合わせありがとうございます。
以下の内容で承りました。
=================
お名前：{$_SESSION['name']}
メールアドレス：{$_SESSION['mail']}
=================

EOM;

mb_send_mail($to, $subject_re, $message_re, $header);

// CSRF対策 トークン情報で認証
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'] = $token;

if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
  echo '不正なPOSTです。';
  exit();
}

unset($_SESSION['token']);

//Session削除
$_SESSION = [];
session_destroy();

//クライアント側のクッキーを削除
if (isset($_COOKIE['PHPSESSID'])) {
  setcookie('PHPSESSID', '', time(), -3600, '/');
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
    <h1>お問い合わせありがとうございます。</h1>
    <p><a href="index.php">フォームに戻る</a></p>
  </div>

</body>

</html>
