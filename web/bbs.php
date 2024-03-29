<?php

//データベースに接続
$link = mysql_connect('10.33.0.91', '', '');
if (!$link) {
    die('データベースに接続できません：' . mysql_error());
}

//データベースを選択する
mysql_select_db('oneline_bbs', link);

$errors = array();

//POSTなら保存処理実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //名前チェック
    $name = null;
    if (!isset($_POST['name']) || !strlen($_POST['name'])) {
        $errors['name'] = '名前を入力してください';
    } else if (strlen($_POST['name'] > 40)) {
        $errors['name'] = '名前は40字以内で入力してください';
    } else {
        $name = $_POST['name'];
    }

    //ひとことが正しく入力されているかチェック
    $comment = null;
    if (!isset($_POST['comment']) || !strlen($_POST['comment'])) {
        $errors['comment'] = 'ひとこと入力してください';
    } else if (strlen($_POST['comment'] > 200)) {
        $errors['commment'] = 'ひとことは２００字以内で入力してください';
    } else {
        $name = $_POST['comment'];
    }

    //エラーがなければ保存
    if (count($errors) === 0) {
        //保存するためのSQLを作成
        $sql = "INSERT INTO `post` (`name`,`comment`,`created_at`) VALUES ('" . mysql_real_escape_string($name) . "',
        '" . mysql_real_escape_string($comment) . "','" . date('Y-m-d H:i:s') . "',)";
        //保存する
        mysql_query($sql, $link);
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http;//ww.w3.org/TR/xhtml1-trasitional.dtd">
<html>

<head>
    <title>ひとこと掲示板</title>
</head>

<body>
    <h1>ひとこと掲示板 </h1>

    <form action="bbs.php" method="post">
        <?php if (count($errors)) : ?>
            <ul class="error_list">
                <?php foreach ($errors as $error) : ?>
                    <li>
                        <?php echo htmlspecialchars($error) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        名前：<input type="text" name="name"><br>
        ひとこと: <input type="text" name="comment" id="" size="60"><br>
        <input type="submit" valie="送信">
    </form>
    <?php
    $sql = "SELECT * FROM `post` ORDER BY `created_at` DESC";
    $result = mysql_query($sql, $link);
    ?>
    <?php if ($result !== false && mysql_num_rows($result)) : ?>
        <ul>
            <?php while ($post = mysql_fetch_assoc($result)) : ?>
                <li>
                    <?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>
                    <?php echo htmlspecialchars($post['comment'], ENT_QUOTES, 'UTF-8'); ?>
                    - <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
    <?php
    mysql_free_result($result);
    mysql_close($link);
    ?>
</body>

</html>
