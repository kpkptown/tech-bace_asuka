<?php
$dsn='mysql:dbname=co_***_99sv_coco_com;host=localhost';
$user='co-***.99sv-coco.com';
/*difineはプログラムのどこからでもアクセスできるグローバルな定数を定義する事ができる。変更する事がない値を定義するのに便利。*/
define(PASS,'password');

$pdo=new PDO($dsn,$user,PASS);
$sql="CREATE TABLE tbtest"
."("
."id INT,"
."name char(32),"
."comment TEXT"
.");";
$result=$pdo -> query($sql);


$password=$_POST['pass'];
$table_name="tbtest";

$name=$_POST['name'];
$comm=$_POST['comment'];
$edi_num=$_POST['edi_num'];
$edi_row=$_POST['edi_row'];
/*edi_boolは編集したかどうか（編集した場合は処理が変わるため。）*/
$edi_bool=$_POST['edi_bool'];
if(empty($edi_row))$edi_bool=false;

$del_num=$_POST['del_num'];

/*functionは自作関数でなんでも定義できる。　書き方：function 自作する関数名（引数）*/
function check_password($password)
	{
		$bool=false;
			if(empty($password))
				{ echo'パスワードが入力されていません。<br/>'; }
			else
			if($password===PASS)
				{$bool=true;}
				return $bool;
	}
	
/*例外処理(try&catchを使う。)
例外を検知したいコードの部分を try{ } で囲む。正常に終了すればそのままこの部分だけが実行さ、例外が検知された場合、次のcatch内が実行される。
例外処理のメリットはまとめてエラー処理ができることです。
もし戻り値をチェックすることでエラー処理を行おうとしたら関数を呼び出す度にエラーチェックをしなければならないが、例外処理でまとめることで、try内はエラー処理にわずらわされず本来の処理のみを記述していくことができる。*/
	//try
	//{
			if(!empty($password))
				{
/*PDOはPHP Data Objectの略で、データベースを操作するためのメソッドが用意されたクラスのこと。PDOの最大の特徴は他のAPI(mysql関数・mysqli)とは違い、MySQL以外のデータベースも操作することができる事。具体的にはドライバを切り替えることで、MySQL以外のデータベースとも接続できる。
PDOを使用してデータベースに接続するには、 最初にPDOクラスからインスタンスを作成する必要がある。インスタンスを作成するにはnew演算子を使う。*/
					
/*引数にはDSN・ユーザー名・パスワード・オプションを指定。DSNとは「Data Source Name」の略で、データベースのホスト名、データベース名、文字エンコードを指定する。
オプションは連想配列で指定。（属性名 => 属性値という形で設定）*/
					$pdo=new PDO	(
													$dsn,$user,$password,
													array(
																PDO::ATTR_ERRMODE => 
																PDO::ERRMODE_EXCEPTION,
																PDO::ATTR_EMULATE_PREPARES => false
																)
/*属性「PDO::ATTR_EMULATE_PREPARES」にfalseをセット。 falseにすることで、プリペアドステートメントのエミュレート機能をオフにする。逆にtrueにすると、プリペアドステートメントのエミュレート機能が動作し、動的プレースホルダとして動作するようになる。（データベースの機能にかかわらず同じ仕組みで データベースへのアクセスができることが保証される機能。）*/
												);
				}
				
//編集処理
			if($edi_bool)
				{
						if(check_password($password,'TUuwdBLt'))
							{
/*データを更新するときはUPDATE文を使う。
基本構文（書き方）⇨ UPDATE  表名
				　SET    列名 = 値 
					WHERE　更新する行を特定する条件; */								
								$sql="UPDATE $table_name SET name='$name',comment='$comm' WHERE id = $edi_row";
/*$result=$pdo ->query($sql);	は実行の決まり文句のようなもの。*/						
								$result=$pdo -> query($sql);		
							}
				}
			if(ctype_digit($edi_num))
				{
						if(check_password($password,'TUuwdBLt'))
							{
/*テーブルに格納されているデータを取得するにはSELECT文を使う。全てのカラムの値を取得する場合、カラムを全て列挙する代わりに「*」を記述することができる。*/
/*foreachは配列を全部見るという意味。forとは違い、終わりの指定をしなくても良い。*/
									$sql="SELECT * FROM $table_name";
									$result=$pdo -> query($sql);
									foreach($result as $row)
										{
											if($edi_num == $row['id'])
												{
														$edi_row=$edi_num;
														$edi_bool=true;
														$edi_name=$row['name'];
														$edi_comm=$row['comment'];
												}	
										}
							}
				}
				
//削除処理
			if(ctype_digit($del_num))
				{
						if(check_password($password,'TUuwdBLt'))
							{
/*テーブルに格納されているデータを削除するにはDELETE文を使う。指定のテーブルに含まれるデータを削除する。どのデータを削除するのかはWHERE句を使って指定。WHERE句は省略可能だが、その場合はテーブル内の全てのデータが削除される。
基本構文（書き方）⇨DELETE FROM tbl_name [WHERE where_condition];*/
									$sql="DELETE FROM $table_name WHERE id=$del_num";
									$result=$pdo -> query($sql);
							}
				}

//登録処理
			if(!empty($name) and !empty($comm) and !$edi_bool)
				{
						if(check_password($password,'TUuwdBLt'))
							{
/*preperは、引数に指定したSQL文をデータベースに対して発行する。queryメソッドとの違いは、SQL文の一部を変数のように記述しておき、その部分に当てはめる値を後から指定できる事。値が固定で無いSQLを使う場合には、queryメソッドではなくprepareメソッドを使うのが基本となる。*/
/*insert intoは入ったらすぐに入れられてしまうため、:nameや:commentで保留の状態にし、ユーザーが指定できるようにする。$nameでユーザーが入力したものをデータベースに挿入する。bindparamで:nameに$nameを入れる指定をする。*/
									$sql=$pdo -> prepare("INSERT INTO $table_name (name,comment) VALUES (:name,:comment)");
									$sql -> bindParam(':name',$name,PDO::PARAM_STR);
									$sql -> bindParam(':comment',$comm,PDO::PARAM_STR);
									$sql -> execute();
									$date=date("Y/m/d H:i");
									echo $name.",".$comm."を受け付けました。".$date;
								}
				}
	//}
/*例外処理は必ず行う。行わない場合、エラーが起きた場合、ユーザー名、パスワード等の情報が丸見えになり、危険。例外処理の方法はPDOでエラーが起きた場合、PDOExceptionが投げられる。なのでtry,catchでそのエラーをキャッチすれば、接続情報のモロ出しを防げる。（下記のcatchの形を覚える）*/
	//catch(PDOException $e)
		//{
				//echo 'パスワードが間違っています。<br/>';
				//$error=$e -> getMessage();
		//}
/*例外には例外メッセージを持たせることができる。例外メッセージはどのようなエラーが発生したかのか示すために使われる。getMessage()で例外メッセージを取得。*/

/*正式な値が入るまで一時的に場所を確保しておく措置のこと
例：パワーポイントで「クリックしてテキストを入力」とか表示されている枠*/		
/*hiddenはhtml上にはあるがフォームとしては見えない。*/
?>

<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<title></title>
		</head>
		<body>
			<form action="mission_4.php" method="post">
			投稿者名 <input type="text" name="name" value = <?php echo $edi_name;?> ><br/>
			コメント <input type="text" name="comment" value = <?php echo $edi_comm;?> ><br/>
			削除対象番号<input type"text" name="del_num" placeholder="半角" value=""><br/>
			編集対象番号<input type"text" name="edi_num" placeholder="半角" value=""><br/>
			パスワード<input type"text" name="pass" placeholder="パスワードは必須です。"><br/>
			<input type="hidden" name="edi_row" value= <?php echo $edi_row;?> >
			<input type="hidden" name="edi_bool" value= <?php echo $edi_bool;?> >
			<input type="submit" value="送信">
			</form>
		</body>
	</html>

<?php

//データベースの削除
//$sql='DELETE FROM'.$table_name;
//$result=$pdo -> query($sql);

//データベースの作成
/*データベースにid,name,commentの３つのカラムを作成するように指定している。*/
/*$sql="CREATE TABLE tbtest"
."("
."id INT,"
."name char(32),"
."comment TEXT"
.");";
$result=$pdo -> query($sql);
*/

//データベースの要素追加
//$sql="INSERT INTO $table_name VALUES('1','asuka','test')";

//データベースの要素削除
//$sql="DELETE FROM $table_name where id=3";
//$result=$pdo -> query($sql);

//型を変更、オートインクリメントに
//$sql="ALTER TABLE tbtest CHANGE id id INT AUTO_INCREMENT PRIMARY KEY";
//$result=$pdo -> query($sql);

//表示
$sql="SELECT * FROM $table_name";
$result=$pdo -> query($sql);
echo'データベース内部<br/>';
echo'id | '.' name | '.' comment '.' <br/>';
$db_count=0;
foreach($result as $row)
	{
			echo $row['id'].'|';
			echo $row['name'].'|';
			echo $row['comment'].'<br/>';
			$db_count++;
	}

//投稿番号リセット
if(!$db_count)
	{
/*ALTER TABLEはテーブルの構造を変更できる。 たとえば、カラムを追加または削除、インデックスを作成または破棄、既存のカラムの型を変更、カラムまたはテーブル自体の名前を変更したりできる。*/
/*AUTO_INCREMENT=0は、カラムに値が指定されなかった場合、MySQLが自動的に値を割り当てる。データ型は整数。
値は1ずつ増加して連番になる。*/
			$sql="ALTER TABLE $table_name AUTO_INCREMENT=0";
			$result=$pdo -> query($sql);
	}
?>	