<?php
session_start();
require('dbconnect.php');
// 理解度の説明
if (!isset($_SESSION['join'])) {
    header ('Location: register.php');
    exit();
}
// 理解度の説明 
$hash = password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT);
// 理解度の説明
if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        $hash));
    unset($_SESSION['join']);
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<title>ユーザ登録確認画面</title>
</head>
<body>
	<h1>ユーザ登録確認画面</h1>
	<form action="" method="post">

		<input type="hidden" name="action" value="submit">
		<!-- 理解度の説明 -->
		<p>
			名前
			<span class="check"><?php echo (htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?></span>
		</p>
		<p>
			email
			<span class="check"><?php echo (htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?></span>
		</p>
		<p>
			パスワード
			<span class="check">[セキュリティのため非表示] </span>
		</p>
		<!-- 理解度の説明 -->
		<input type="button" onclick="event.preventDefault();location.href='register.php?action=rewrite'" value="修正する" name="rewrite" class="button02">
		<input type="submit" value="登録する" name="registration" class="button">
	</form>
</body>
</html>
