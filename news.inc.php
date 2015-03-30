<?
$postday = time();
$tbName = $run_app["TABLE"];
$work_dir = $run_app["DIR"];
$upload_dir = WEB_UPLOAD_DIR."/".$work_dir;
mkdirs($upload_dir);

$file_fields = array("FILE1","FILE2","FILE3","FILE4");

$ynAry = getParams("yn");
$keynAry = getKeynAry("WHERE KCDE_1='DEFAULT_CONTENT' AND KCDE_2='".$_GET["bid"]."'");
$default_content = $keynAry[$_GET["bid"]];

if (varIsNull($_GET["page_name"])) $_GET["page_name"] = "list";
if (varIsNull($_GET["pageNo"]) || $_GET["pageNo"] < 1) $_GET["pageNo"] = 1;

if ($_GET["page_name"] == "list") {

if ($_GET["action"] == "delete") {
	$strWhe = "WHERE `IID`='".sql_quote($_GET["iid"])."'";
	$strSQL = "SELECT * FROM $tbName $strWhe";
	$result = sql_query($strSQL, $dbi);
	if($row = sql_fetch_array($result)){
		foreach($file_fields as $key=>$field_name){
			if(varIsNull($row[$field_name])) continue;
			if(delete($upload_dir,$row[$field_name])){
				$errmsg = "�ɮקR�����ѡG".$file_path;
			}
		}
		if(!varIsNull($errmsg))
			reDirect($errmsg, base64_url_decode($_GET["backurl"]));

		$msg = (sql_delete($tbName, $strWhe, $dbi)) ? "��Ƥw�R��":"��ƧR������";
		reDirect($msg, base64_url_decode($_GET["backurl"]));
	}
}
else if ($_POST["action"] == "sort"){
	foreach ($_POST["iid"] as $key => $val){
		sql_query("update $tbName set SORT = '" . $_POST["sort"][$key] . "' where IID = '" . $val . "'", $dbi);
	}
	reDirect("�����Ƨ�", base64_url_decode($_POST["backurl"]));
}

if($_SESSION["admin_login_pid"] > 0){
	$strWhe = "WHERE `PID`=".$_SESSION["admin_login_pid"]."";
}else{
	$strWhe = "WHERE 1";
}

$rowCount = getRowNum($tbName, $strWhe);

$pageCount = 10;
$pageNum = ceil($rowCount / $pageCount);
$pageNum = ($pageNum <= 0) ? 1:$pageNum;
$_GET["pageNo"] = ($_GET["pageNo"] > $pageNum) ? $pageNum : $_GET["pageNo"];
$pageStart = ($_GET["pageNo"]-1) * $pageCount;

for ($i = 1; $i <= $pageNum; $i++) {
	$pNum[$i] = $i;
}

//�D����v�}�e�Ϊ��P�_���A�U�����ܦU��Ǫ���ơA�D����ܩҦ����
if (in_array($run_app["TABLE"], array("NEWS", "DEPT_NEWS", "DEPT_NEWS2", "DEPT_NEWS3", "DEPT_NEWS4", "DEPT_NEWS5"))){
	$strOrd = "order by SORT ASC ";
	if ($_GET["bid"] == "107"){
		$strOrd .= ", PID ASC";
	}else{
		$strOrd .= ", STIME DESC";
	}
}else{
	if ($_GET["bid"] == "107"){
		$strOrd = "order by PID ASC";
	}else{
		$strOrd = "order by STIME DESC";
	}
}
$strSQL = "SELECT * FROM $tbName $strWhe $strOrd LIMIT $pageStart, $pageCount";
$result = sql_query($strSQL, $dbi);

$colspan = 10;
$backurl = base64_url_encode("main.php?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&pageNo=".$_GET["pageNo"]);
?>
<script language="JavaScript" type="text/javascript">
<!--

function chkForm1() {
	return true;
}

//-->
</script>

<form action="" method="post" id="form1" name="form1" onsubmit="return chkForm1();">
<?=pntTableOpen();?>
<table bgcolor="#FFFFFF" align="center" border="0" cellspacing="1" cellpadding="1" width="98%">
	<tr bgcolor="#006699">
		<td nowrap="nowrap" align="center" valign="top" colspan="<?=$colspan?>"><font color="#ffffff"><?=$run_app["TITLE"]?>�s��</font><input type="hidden" name="action" id="action" value=""><input type="hidden" name="backurl" id="backurl" value="<?php echo $backurl;?>"></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td nowrap="nowrap" align="right" valign="top" colspan="<?=$colspan?>">
			<div align="right"><font color="blue">��<?=pntSelField($_GET["pageNo"], $pNum, false, "pageNo", "onchange=\"window.location.href='?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&pageNo=' + this.value\""); ?>�� / �@<font color="red"><? pnt($pageNum); ?></font>�� �`�@����:<font color="red"><? pnt($rowCount); ?></font>&nbsp;&nbsp;</font></div>
			<hr size="1" /></td>
	</tr>
	<tr bgcolor="#b0c4de">
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;&nbsp;</td>
		<?php if (in_array($run_app["TABLE"], array("NEWS", "DEPT_NEWS", "DEPT_NEWS2", "DEPT_NEWS3", "DEPT_NEWS4", "DEPT_NEWS5"))):?><td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;<input type="submit" value="�Ƨ�" onclick="document.getElementById('action').value='sort';">&nbsp;</td><?php endif;?>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;���D&nbsp;</td>
		<!--td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;���e&nbsp;</td-->
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;�O�_�ҥ�&nbsp;</td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;�إߤ��&nbsp;</td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;�̫��s&nbsp;</td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;�إߤH��&nbsp;</td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;��s�H��&nbsp;</td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="#d0dce0">&nbsp;&nbsp;</td>
	</tr>
<?
if (!$rowCount) {
?>
	<tr bgcolor="#FFCCFF">
		<td nowrap="nowrap" align="center" valign="top" colspan="<?=$colspan?>"><font color="red">�ثe��Ʈw�L�����Ʀs�b</font></td>
	</tr>
<?
}
else {
	$srno = $pageStart;
	while ($row = sql_fetch_array($result, $dbi)) {
		$srno++;
		if ($srno % 2 == 0) $aColor="#99ff99";
		else $aColor="#f0e68c";
?>
	<tr>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="skyblue"><?=$srno?></td>
		<?php if (in_array($run_app["TABLE"], array("NEWS", "DEPT_NEWS", "DEPT_NEWS2", "DEPT_NEWS3", "DEPT_NEWS4", "DEPT_NEWS5"))):?><td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><input type="hidden" name="iid[]" id="iid[]" value="<?php echo $row["IID"];?>"><input type="text" name="sort[]" id="sort[]" value="<?php echo $row["SORT"];?>" size="3"></td><?php endif;?>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="<?=$aColor?>"><? pnt("<a href='main.php?bid=".$_GET["bid"]."&page_name=modify&iid=" . $row["IID"] . "' title='".$row["TITLE"]."'>".getLimitStr(strip_tags($row["TITLE"]),20)."</a>");?></td>
		<!--td nowrap="nowrap" align="left" valign="top" bgcolor="<?=$aColor?>"><?=getLimitStr(strip_tags(stripslashes($row["MO1"])), 30, " ..."); ?></td-->
		<td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><?=$ynAry[$row["ACTIVE"]]?></td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><? pnt(fmtDateTime($row["FDATE"], "/", ":")); ?></td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><? pnt(fmtDateTime($row["UDATE"], "/", ":")); ?></td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><?=creator($row["FMAN"])?></td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><?=creator($row["UMAN"])?></td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><? pnt("<a href='main.php?bid=".$_GET["bid"]."&page_name=modify&iid=" . $row["IID"] . "' title='�s��'>�s��</a>");?></td>
		<td nowrap="nowrap" align="center" valign="top" bgcolor="<?=$aColor?>"><? pnt("<a href=\"javascript:goConfirm('�аݬO�_�T�w�n�R����� ? �Ы��y�T�w�z�~�����', 'main.php?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&action=delete&iid=" . $row["IID"] . "&backurl=$backurl');\" title='�R��'>�R��</a>");?></td>
	</tr>
<?
	}
}
?>
	<tr bgcolor="#FFFFFF">
		<td nowrap="nowrap" align="center" valign="top" colspan="<?=$colspan?>"><hr size="1" />
			<div align="right"><a href="main.php?bid=<?=$_GET["bid"]?>&page_name=modify&iid=">�s�W���</a></div></td>
	</tr>
</table>
<?=pntTableClose();?>
</form>
<p align="center">
<?
if ($_GET["pageNo"] > 1) {
	pnt("<a href='main.php?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&pageNo=" . ($_GET["pageNo"]-1) . "'>�W�@��</a>");
}
else {
	pnt("�W�@��");
}
?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?
if ($_GET["pageNo"] < $pageNum) {
	pnt("<a href='main.php?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&pageNo=" . ($_GET["pageNo"]+1) . "'>�U�@��</a>");
}
else {
	pnt("�U�@��");
}
?>
</p>
<?
}

else if ($_GET["page_name"] == "modify") {

if ($_POST["action"] == "update") {
	$stime=mktime(0,0,0,$_POST["smon"],$_POST["sday"],$_POST["syear"]);
	$etime=mktime(0,0,0,$_POST["emon"],$_POST["eday"],$_POST["eyear"])+86399;
	$wtime=time();

	$strWhe = "WHERE IID='".sql_quote($_GET["iid"])."'";
	$cond = array();
	$cond["TITLE"] = $_POST["title"];
	$cond["SORT"] = $_POST["sort"];
	$cond["MO1"] = $_POST["mo1"];
	foreach($file_fields as $key=>$field_name){
		$val = strtolower($field_name);
		if ($_FILES[$val]["error"] === 0) {
			$old_file = (!varIsNull($_POST["iid"])) ?  getFieldValue($tbName, $strWhe, $field_name):"";
			$new_file = md5($_FILES[$val]["name"]."-".microtime()).strrchr($_FILES[$val]["name"], ".");
			$errmsg = uploadFile($_FILES[$val]["tmp_name"], $_FILES[$val]["name"], $_FILES[$val]["size"], $upload_dir, $new_file, $old_file, "\.+[" . $COMMON_UPLOAD_FILETYPE . "]+$");
			if (varIsNull($errmsg)) {
				$cond[$val] = $new_file;
			}else{
				pntJsAlert($errmsg);
			}
		}
	}
	
	$cond["FILE1_PS"] = $_POST["file1_ps"];
	$cond["FILE2_PS"] = $_POST["file2_ps"];
	$cond["FILE3_PS"] = $_POST["file3_ps"];
	$cond["FILE4_PS"] = $_POST["file4_ps"];
	$cond["LINK1"] = $_POST["webk1"];
	$cond["LINK2"] = $_POST["webk2"];
	$cond["LINK3"] = $_POST["webk3"];
	$cond["LINK4"] = $_POST["webk4"];
	$cond["LINK1_NAME"] = $_POST["webk1_name"];
	$cond["LINK2_NAME"] = $_POST["webk2_name"];
	$cond["LINK3_NAME"] = $_POST["webk3_name"];
	$cond["LINK4_NAME"] = $_POST["webk4_name"];
	$cond["STIME"] = $stime;
	$cond["ETIME"] = $etime;
	$cond["VIEWS"] = $_POST["views"];
	$cond["PID"] = $_SESSION["admin_login_pid"];
	$cond["FMAN"] = $_SESSION["admin_login_uid"];
	$cond["UMAN"] = $_SESSION["admin_login_uid"];
	$cond["FDATE"] = date("YmdHis");
	$cond["UDATE"] = date("YmdHis");
	$cond["ACTIVE"] = $_POST["active"];

	if (!varIsNull($_POST["iid"]) && getRowNum($tbName, $strWhe)) {
		unset($cond["PID"]);
		unset($cond["FMAN"]);
		unset($cond["FDATE"]);
		$msg = (sql_update($tbName, $cond, $strWhe, $dbi)) ? "��Ƨ�s ���\�C":"��Ƨ�s ���ѡC";
		$msg .= (varIsNull($errmsg)) ? $errmsg : "";
		reDirect($msg, base64_url_decode($_POST["backurl"]));
	}else{
		$msg = (sql_insert($tbName, $cond, $dbi)) ? "��Ʒs�W ���\�C":"��Ʒs�W ���ѡC";
		$msg .= (varIsNull($errmsg)) ? $errmsg : "";
		$_GET["iid"] = mysql_insert_id();
		reDirect($msg, "main.php?bid=".$_GET["bid"]."&page_name=list");
	}
}
else if ($_GET["action"] == "del_file") {
	$field_name = sql_quote(strtoupper($_GET["field_name"]));
	$strWhe = "WHERE IID='".sql_quote($_GET["iid"])."'";
	$old_file = getFieldValue($tbName, $strWhe, $field_name);
	if (delete($upload_dir,$old_file)){
		$showmsg = "�R���ɮ׮ɵo�Ϳ��~";
	}
	if (varIsNull($showmsg)) {
		$strSQL = "UPDATE $tbName SET ".strtoupper($_GET["field_name"])."='' $strWhe";
		sql_query($strSQL, $dbi);
		$showmsg = "�����ɮקR��";
	}
	reDirect($showmsg, "main.php?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&iid=".$_GET["iid"]);
}

$strSQL = "SELECT * FROM $tbName WHERE IID='".sql_quote($_GET["iid"])."'";
$result = sql_query($strSQL, $dbi);
if($row = sql_fetch_array($result, $dbi)) {
	$postday = $row["STIME"];
	$downday = $row["ETIME"];
}else{
	$downday = $postday + (86400*30);
}
$syear=date("Y", $postday);
$smon=date("m", $postday);
$sday=date("d", $postday);
$eyear=date("Y", $downday);
$emon=date("m", $downday);
$eday=date("d", $downday);

$colspan = 4;
?>
<script language="JavaScript" type="text/javascript">
<!--

function chkForm1() {
	if (!chkField((varIsNull(document.getElementById("title").value)), document.getElementById("title"), "�п�J���D")) return false;
  <? for($i=1; $i<=count($file_fields); $i++){ ?>
  if (!chkField((varIsNull(document.getElementById("file<?=$i?>_ps").value) && !varIsNull(document.getElementById("file<?=$i?>").value)), document.getElementById("file<?=$i?>_ps"), "�п�J����<?=$i?>����")) return false;
  <?}?>
	return true;
}

//-->
</script>

<form action="" method="post" id="form1" name="form1" onsubmit="return chkForm1();" enctype="multipart/form-data">
<?=pntTextField("update", "hidden", "action", ""); ?>
<?=pntTextField($row["IID"], "hidden", "iid", ""); ?>
<?=pntTextField(base64_url_encode("main.php?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&iid=".$_GET["iid"]), "hidden", "backurl", ""); ?>
<?=pntTableOpen();?>
<table bgcolor="#FFFFFF" align="center" border="0" cellspacing="1" cellpadding="1" width="98%">
	<tr bgcolor="#FFFFFF">
		<td nowrap="nowrap" align="center" valign="top" colspan="<?=$colspan?>">&nbsp;</td>
	</tr>
	<tr bgcolor="#006699">
		<td nowrap="nowrap" align="center" valign="top" colspan="<?=$colspan?>"><font color="#ffffff"><?=$run_app["TITLE"]?>�s��</font></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td nowrap="nowrap" align="left" valign="top" colspan="<?=$colspan?>">
			<a href="main.php?bid=<?=$_GET["bid"]?>&page_name=list">�^�s����</a>
			<hr size="1" /></td>
	</tr>
	<?php if (in_array($run_app["TABLE"], array("NEWS", "DEPT_NEWS", "DEPT_NEWS2", "DEPT_NEWS3", "DEPT_NEWS4", "DEPT_NEWS5"))):?>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">�Ƨ�:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntTextField($row["SORT"], "text", "sort", "size=4"); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<?php endif;?>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">���D:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntTextField($row["TITLE"], "text", "title", "size=60"); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">���e:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntTextArea(retIsNull($row["MO1"], $default_content), "mo1", 'cols="60" rows="10"'); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<? for($i=1; $i<=count($file_fields); $i++){ ?>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">����<?=$i?>:&nbsp;</td>
		<td align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?
			$field_name = "FILE".$i;
			echo "<input type=\"file\" id=\"" . strtolower($field_name) . "\" name=\"" . strtolower($field_name) . "\">&nbsp;<font color=\"red\">����W�Ƿ|�̹Ϥ����ɮצ۰ʰϤ�</font><br> ";
			echo "�ثe�ɮ�:<br><div align=left>";
			if (!varIsNull($row[$field_name])) {
				echo getLink($row[$field_name], $work_dir, $row[$field_name."_PS"], 'width="150"', TRUE, TRUE);
				echo "<a href=\"main.php?bid=".$_GET["bid"]."&page_name=".$_GET["page_name"]."&iid=".$row["IID"]."&action=del_file&field_name=" . strtolower($field_name) . "\">[�R��]</a> ";
			}
			echo "</div>";
			?>
		</td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">����<?=$i?>����:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntTextField($row["FILE".$i."_PS"], "text", "file".$i."_ps", "size=20"); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<? } ?>
	<?php for ($x = 1;$x < 5;$x++):?>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">�s��<?php echo $x;?>:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntTextField($row["LINK" . $x], "text", "webk$x", "size=60"); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">�s��<?php echo $x;?>�W��:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntTextField($row["LINK" . $x . "_NAME"], "text", "webk" . $x . "_name", "size=60"); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<?php endfor;?>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">�ҥΤ��:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntSelField($syear, getParams("year"), false, "syear", ""); ?>�~
			<?=pntSelField($smon, getParams("month"), false, "smon", ""); ?>��
			<?=pntSelField($sday, getParams("mdate"), false, "sday", ""); ?>���
			<?=pntSelField($eyear, getParams("year"), false, "eyear", ""); ?>�~
			<?=pntSelField($emon, getParams("month"), false, "emon", ""); ?>��
			<?=pntSelField($eday, getParams("mdate"), false, "eday", ""); ?>��</td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">�s���H��:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntTextField($row["VIEWS"], "text", "views", 'size="5"'); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td nowrap="nowrap" align="right" valign="top">&nbsp;&nbsp;</td>
		<td nowrap="nowrap" align="right" valign="top" bgcolor="#d0dce0">�O�_�ҥ�:&nbsp;</td>
		<td nowrap="nowrap" align="left" valign="top" bgcolor="#e6e6fa">&nbsp;
			<?=pntRadField(retIsNull($row["ACTIVE"], "Y"), $ynAry, false, "active", "", "&nbsp;&nbsp;"); ?></td>
		<td align="left" valign="top">&nbsp;&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td nowrap="nowrap" align="center" valign="top" colspan="<?=$colspan?>">&nbsp;</td>
	</tr>
	<tr bgcolor="#ffffff">
		<td nowrap="nowrap" align="center" valign="top" colspan="<?=$colspan?>">
			<hr size="1" />
			<input type="submit" value="�x�s�ܧ�" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="reset" value="�٭�w�]" /></td>
	</tr>
</table>
<?=pntTableClose();?>
</form>
<?
}
?>
