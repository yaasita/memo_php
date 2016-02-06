<?php
$scriptname = basename($_SERVER['PHP_SELF']);
$memo       = $_POST['detail'];
$datafile   = 'data/data.csv';
$list       = array();
# 追加
if ($memo != "" and $handle = fopen($datafile,'a')){
    fwrite($handle,time().",");
    fwrite($handle,base64_encode($memo));
    fwrite($handle,"\n");
    fclose($handle);
    unset($_POST['detail']);
}
# 一覧
if (is_readable("data/data.csv") and $handle = fopen($datafile,'r') ){
    while (!feof($handle)){
        $line = explode(",",fgets($handle));
        if ($line[0] != ""){
            $list[$line[0]] = $line[1];
        }
    }
    fclose($handle);
}
# 削除
if (count($_POST) > 0){
    foreach ($_POST as $key => $val){
        if ($val == "del"){
            unset($list[$key]);
        }
    }
    $handle = fopen($datafile,'w');
    foreach ($list as $key => $val){
        fwrite($handle,"$key,$val");
    }
    fclose($handle);
}
?>
<html>
	<head>
		<title>MemoPage2</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
    <p><a href="<?php echo $scriptname; ?>">TOP</a></p>
        <form action="<?php echo $scriptname; ?>" method="post">
			<?php print date ("Y/m/d H:i:s") ?><br>
			<textarea name="detail" row="4" cols="40"></textarea><br>
			<input type="submit" value="memo">
		</form>
		<hr>
        <form method="post" action="<?php echo $scriptname; ?>">
            <table border="3">
                <tr>
                    <th>time</th>
                    <th>detail</th>
                    <th>del</th>
                </tr>
<?php 
foreach ($list as $key => $val){
    $time = date("Y/m/d H:i:s", $key);
    $detail = htmlspecialchars(base64_decode($val),ENT_QUOTES);
?>
                <tr>
                    <td><?php echo $time; ?></td>
                    <td><?php echo $detail; ?></td>
                    <td><input name="<?php echo $key ?>" value="del" type="checkbox"></td>
               </tr>
<?php
}
?>
		</table>
            <input type="submit" value="delete">
		</form>
	</body>
</html>
