<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <title>掲示板</title>

</head>

<body>
<?php
//データベースの接続
$dsn='******';
$user='******';
$password='******';
$pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql="CREATE TABLE IF NOT EXISTS allinfo"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "time TEXT,"
    . "password TEXT"
    .");";
//編集ボタンの処理
if(isset($_POST["submit3"])){
    if(isset($_POST["password_3"])){
        $password_3=$_POST["password_3"];
    }
    if(isset($_POST["edit_num"])){
        $edit_num=$_POST["edit_num"];
    }
    $stmt = $pdo->prepare("SELECT * FROM allinfo WHERE id=:id and password=:password");
    $stmt->bindParam(":id", $edit_num, PDO::PARAM_INT);
    $stmt->bindParam(":password", $password_3, PDO::PARAM_INT);
    $res = $stmt->execute();
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        $edit_name=$row["name"];
		$edit_comment=$row["comment"];
		$edit_password=$row["password"];
	}
}   
?>
[投稿フォーム]
<form action="" method="post">
名前：<input type="text" name="name" placeholder="名前を入力してください" 
value="<?php 
       if(isset($edit_name)){
           echo $edit_name;
        }
    ?>"
    >
<br>
コメント：<input type="text" name="comment" placeholder="コメントを入力してください"
value="<?php
       if(isset($edit_comment)){
           echo $edit_comment;
        }
    ?>"
    >
<br>
パスワード：<input type="text" name="password_1" placeholder="パスワードを入力してください" 
value="<?php 
       if(isset($edit_password)){
           echo $edit_password;
        }
    ?>"
    >
<br>
<input type="submit" name="submit1" value="送信">
<br>
<br>
[削除フォーム]
<br>
削除対象番号:<input type="number" name="del_num" placeholder="削除番号を入力してください">
<br>
パスワード：<input type="text" name="password_2" placeholder="パスワードを入力してください" >
<br>
<input type="submit" name="submit2" value="送信">
<br>
<br>
[編集番号指定用フォーム]
<br>
編集対象番号:<input type="number" name="edit_num" placeholder="編集番号を入力してください">
<br>
パスワード：<input type="text" name="password_3" placeholder="パスワードを入力してください" >
<br>
<input type="submit" name="submit3" value="編集">
<br>
<br>
<input type="hidden" name="edit_num_hidden"
value="<?php
        if(isset($edit_num)){
          echo $edit_num;
        }
    ?>"
    >
<br>
<コメント欄>
</form>
<?php
//投稿ボタンの処理
if(isset($_POST["submit1"])){
    if(isset($_POST["name"])){
        $name=$_POST["name"];
    }
    if(isset($_POST["comment"])){
        $comment=$_POST["comment"];
    }
    if(isset($_POST["password_1"])){
        $password_1=$_POST["password_1"];
    }
    $date = date("Y/m/d H:i:s");
    if(isset($_POST["edit_num_hidden"])){
        $edit_num=$_POST["edit_num_hidden"];
    }
    if($edit_num!=NULL){
        $stmt = $pdo->prepare("UPDATE allinfo SET name=:name,comment=:comment,password=:password  WHERE id=:id");
        $stmt->bindParam(':id', $edit_num, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password_1, PDO::PARAM_INT);
        $stmt->execute();
    }
    else{
        if($name!=NULL && $comment!=NULL && $password_1!=NULL){
        $stmt = $pdo->query($sql);
        $sql = $pdo -> prepare("INSERT INTO allinfo (name, comment, time, password) VALUES (:name, :comment, :time, :password)");
        $sql -> bindParam(":name", $name, PDO::PARAM_STR);
        $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
        $sql -> bindParam(":time", $date, PDO::PARAM_STR);
        $sql -> bindParam(":password", $password_1, PDO::PARAM_STR);
        $sql -> execute();
        }
    }
}
//削除ボタンの処理
else if(isset($_POST["submit2"])){
    if(isset($_POST["del_num"])){
        $del_num=$_POST["del_num"];
    }
    if(isset($_POST["password_2"])){
        $password_2=$_POST["password_2"];
    }
    $stmt = $pdo->prepare("DELETE FROM allinfo WHERE id=:id and password=:password");
    $stmt->bindParam(":id", $del_num, PDO::PARAM_INT);
    $stmt->bindParam(":password", $password_2, PDO::PARAM_INT);
    $res = $stmt->execute();
}
//コメントがないとき
if(!isset($pdo)){
    echo "コメントが投稿されていません";
}
//コメントがあるときの表示
else{
$sql = 'SELECT * FROM allinfo';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['time'].'<br>';
    echo "<hr>";
    }
}
?>