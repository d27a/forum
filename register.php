<?php
session_start();
require('dbconnect.php');
// ★ポイント1★
if (!empty($_POST) ){
	// ★ポイント2★
    if ($_POST['name'] == "") {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == "") {
        $error['email'] = 'blank';
    } else {
		// ★ポイント3★
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}
    if ($_POST['password'] == "") {
        $error['password'] = 'blank';
    }
    if ($_POST['password2'] == "") {
        $error['password2'] = 'blank';
    }
	// ★ポイント4★
    if (strlen($_POST['password'])< 6) {
        $error['password'] = 'length';
    }
	// ★ポイント5★
    if (($_POST['password'] != $_POST['password2']) && ($_POST['password2'] != "")) {
        $error['password2'] = 'difference';
    }

    // 追加：送信後の処理（次回解説します）
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: confirm.php');
        exit();
    }
    
    // 追加：セッションに保存しておいたPOSTデータを取り出す
    if (isset($_SESSION['join']) && isset($_REQUEST['action']) && ($_REQUEST['action'] == 'rewrite')) {
        $_POST =$_SESSION['join'];
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <title>会員登録をする</title>
	<style>
		.error { color: red;font-size:0.8em; }
	</style>
</head>
<body>
	<h1>会員登録をする</h1>
	<form action="" method="post" class="registrationform">
		<label>
			名前
			<input type="text" name="name" style="width:150px" value="<?php echo $_POST['name']??""; ?>">
			<?php if (isset($error['name']) && ($error['name'] == "blank")): ?>
			<p class="error">名前を入力してください</p>
			<?php endif; ?>
		</label>
		<br>
		<label>
			email
			<input type="text" name="email" style="width:150px" value="<?php echo $_POST['email']??""; ?>">
			<?php if (isset($error['email']) && ($error['email'] == "blank")): ?>
			<p class="error">emailを入力してください</p>
			<?php endif; ?>
			<?php if (isset($error['email']) && ($error['email'] == "duplicate")): ?>
			<p class="error">すでにそのemailは登録されています。</p>
			<?php endif; ?>
		</label>
		<br>
		<label>
			パスワード
			<input type="password" name="password" style="width:150px" value="<?php echo $_POST['password']??""; ?>">
			<?php if (isset($error['password']) && ($error['password'] == "blank")): ?>
			<p class="error"> パスワードを入力してください</p>
			<?php endif; ?>
			<?php if (isset($error['password']) && ($error['password'] == "length")): ?>
			<p class="error"> 6文字以上で指定してください</p>
			<?php endif; ?>
		</label>
		<br>
		<label>
			パスワード再入力<span class="red">*</span>
			<input type="password" name="password2" style="width:150px">
			<?php if (isset($error['password2']) && ($error['password2'] == "blank")): ?>
			<p class="error"> パスワードを入力してください</p>
			<?php endif; ?>
			<?php if (isset($error['password2']) && ($error['password2'] == "difference")): ?>
			<p class="error"> パスワードが上記と違います</p>
			<?php endif; ?>
		</label>
		<br>
		<input type="submit" value="確認する" class="button">
	</form>
    <a href="login.php">ログイン画面に戻る</a>
</body>
</html>