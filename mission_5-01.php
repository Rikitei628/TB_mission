    <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mission_5-01</title>
</head>
<body>
    <?php
    // DB接続設定
    $dsn = '(データベース名)';
    $user = '(ユーザー名)';
    $password = '(パスワード)';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    // テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    // テーブルを表示
    // $sql ='SHOW TABLES';
    // $result = $pdo -> query($sql);
    // foreach ($result as $row){
    //     echo $row[0];
    //     echo '<br>';
    // }
    // echo "<hr>";
    // テーブル構成詳細を確認する
    // $sql ='SHOW CREATE TABLE tbtest';
    // $result = $pdo -> query($sql);
    // foreach ($result as $row){
    //     echo $row[1];
    // }
    // echo "<hr>";
    // 入力したデータレコードを抽出し、表示する
    // $sql = 'SELECT * FROM tbtest';
    // $stmt = $pdo->query($sql);
    // $results = $stmt->fetchAll();
    // foreach ($results as $row){
    //     //$rowの中にはテーブルのカラム名が入る
    //     echo $row['id'].',';
    //     echo $row['name'].',';
    //     // echo $row['comment'].',';
    //     echo $row['comment'].'<br>';
    // echo "<hr>";
    // }
    //テーブルの削除
    // $sql = 'DROP TABLE tbtest';
    // $stmt = $pdo->query($sql);
    ?>
    <?php
    //編集実装機能
        
    //Notice: Undefined indexを非表示にするため
    if(!isset($_POST["for_edit_num"])) $_POST["for_edit_num"] = "";
    //POST送信の変数
    $forEdit_num = $_POST["for_edit_num"];
    //編集対象の番号に数字が入っている時以下の処理をする
    if(!empty($_POST["for_edit_num"])) {
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
    //編集対象番号とテーブルに書かれている文字列の番号が一致する時以下の処理をする
                if($forEdit_num == $row['id']) {
    //変更する投稿番号
                    $id = $forEdit_num;
    //各種変数を用意
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
    //編集対象の番号に数字が入っていない時以下の処理をする    
    }else{
    //新規投稿機能
        //Notice: Undefined indexを非表示にするため
        if(!isset($_POST["name"], $_POST["comment"])) {
            $_POST["name"] = "";
            $_POST["comment"] = "";
        }
        if(!isset($_POST["pass"])) $_POST["pass"] = "";
        //フォームが空でない時に以下の処理を行う
        if(strlen($_POST["name"] && $_POST["comment"] && $_POST["pass"])) {
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pass) VALUES (:name, :comment, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $pass = $_POST["pass"];
            $sql -> execute();
        }
    }
    ?>
    <?php
    //削除機能
        //Notice: Undefined indexを非表示にするため
    if(!isset($_POST["delete_num"], $_POST["delete_pass"])) {
        $_POST["delete_num"] = "";
        $_POST["delete_pass"] = "";
    }
        //POST変数を用意
    $delete_pass = $_POST["delete_pass"];
        //フォームが空でない時に以下の処理を行う
    if(strlen($_POST["delete_num"] && $_POST["delete_pass"])) {
                    $sql = 'SELECT * FROM tbtest';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        if($delete_pass == $row['pass']){
                            $id = $_POST["delete_num"];
                            $sql = 'delete from tbtest where id=:id';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
    }
    ?>
    <?php
    //編集選択機能
        //Notice: Undefined indexを非表示にするため
        if(!isset($_POST["edit_num"], $_POST["edit_pass"])) {
            $_POST["edit_num"] = "";
            $_POST["edit_pass"] = "";
        }
        $edit_pass = $_POST["edit_pass"];
        //フォームが空でない時に以下の処理を行う
        if(strlen($_POST["edit_num"] && $_POST["edit_pass"])) {
        //編集対象番号が一致かつパスワードが一致する場合
            $id = $_POST["edit_num"]; //変更する投稿番号
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
        //編集対象番号が一致かつパスワードが一致する場合
                if($id == $row['id'] && $edit_pass == $row['pass']){
                    $show_edit_number = $row['id'];
                    $show_edit_name = $row['name'];
                    $show_edit_comment = $row['comment'];
                    $show_edit_pass = $row['pass'];
                    }
            }
        }
    ?>
    なにかコメントお願いします！
    【　　投稿フォーム　　】
       <form action = "" method = "post">
           名前:　　　　<input type ="text" name = "name" placeholder = "名前を入力" value = <?php if(isset($show_edit_name)) { echo $show_edit_name;} ?>> <br>
           コメント:　　<input type ="text" name = "comment" placeholder = "コメントを入力" value = <?php if(isset($show_edit_comment)) { echo $show_edit_comment;} ?>> <br>
           パスワード:　<input type ="text" name = "pass" placeholder = "パスワードの設定" value = <?php if(isset($show_edit_pass)) { echo $show_edit_pass;} ?>> <br>
           <input type = "hidden" name = "for_edit_num" value = <?php if(isset($show_edit_number)) { echo $show_edit_number;} ?>>
           <input type = "submit" name = "submit" style= "margin-top: 10px">
       </form>
       <br>
　　【　　削除フォーム　　】
       <form action = "" method = "post">
            削除番号:　　<input type = "text" name = "delete_num" placeholder = "削除対象番号"> <br>
            パスワード:　<input type = "text" name = "delete_pass"> <br>
                         <input type = "submit" name = "submit" value = "削除">
       </form>
       <br>
    【　　編集フォーム　　】
        <form action ="" method = "post">
            投稿番号　:  <input type = "text" name = "edit_num" placeholder = "編集対象番号"> <br>
            パスワード:　<input type = "text" name = "edit_pass"> <br>
            <input type = "submit" name = "submit" value = "編集">
        </form>
        <br>
    【　　　投稿一覧　　　】
    <br>
    <?php
    $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        // echo $row['comment'].',';
        echo $row['comment'].'<br>';
    echo "<hr>";
    }
    ?>
</body>
</html>