<?
require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/../include/inc_photograph.php");
require_once(dirname(__FILE__)."/../include/pub_oxwindow.php");
require_once(dirname(__FILE__)."/inc/inc_archives_functions.php");

if(!isset($iscommend)) $iscommend = 0;
if(!isset($ispic)) $ispic = 0;
if(!isset($isbold)) $isbold = 0;

if( empty($channelid)||empty($ID) ){
	ShowMsg("�ĵ�Ϊ��ָ�������ͣ���������������ʱ�Ƿ�Ϸ���","-1");
	exit();
}

//�Ա�������ݽ��д���
//--------------------------------
$iscommend = $iscommend + $isbold;

$pubdate = GetMkTime($pubdate);
$sortrank = AddDay($senddate,$sortup);

if($ishtml==0) $ismake = -1;
else $ismake = 0;

$title = cn_substr($title,60);
$color =  cn_substr($color,10);
$writer =  cn_substr($writer,30);
$source = cn_substr($source,50);
$description = cn_substr($description,250);
if($keywords!="") $keywords = trim(cn_substr($keywords,50))." ";
if($cuserLogin->getUserRank() < 5){ $arcrank = -1; }

if(!empty($picname)) $litpic = $picname;
else $litpic = "";

//������������

//----------------------------------

$inQuery = "
update #@__archives set
typeid='$typeid',
typeid2='$typeid2',
sortrank='$sortrank',
iscommend='$iscommend',
ismake='$ismake',
arcrank='$arcrank',
money='$money',
title='$title',
color='$color',
source='$source',
writer='$writer',
litpic='$litpic',
pubdate='$pubdate',
description='$description',
keywords=' $keywords '
where ID='$ID'; ";

$dsql = new DedeSql();
$dsql->SetQuery($inQuery);
if(!$dsql->ExecuteNoneQuery()){
	$dsql->Close();
	ShowMsg("�������ݿ�archives��ʱ���������飡","-1");
	exit();
}

//���������б�
$urls = "";
for($i=1;$i<=9;$i++)
{
	if(!empty(${'softurl'.$i}))
	{ 
		$servermsg = stripslashes(${'servermsg'.$i});
		$softurl = stripslashes(${'softurl'.$i});
		if($servermsg=="") $servermsg = "���ص�ַ".$i;
		if($softurl!="" && $softurl!="http://")
		{ $urls .= "{dede:link text='$servermsg'} $softurl {/dede:link}\r\n"; }
  }
}

$urls = addslashes($urls);

$softsize = $softsize;

//���¸��ӱ�
//----------------------------------
$row = $dsql->GetOne("Select aid,typeid From #@__addonsoft where aid='$ID'");
if(!is_array($row))
{
  $inQuery = "
  INSERT INTO #@__addonsoft(aid,typeid,filetype,language,softtype,accredit,
  os,softrank,officialUrl,officialDemo,softsize,softlinks,introduce) 
  VALUES ('$ID','$typeid','$filetype','$language','$softtype','$accredit',
  '$os','$softrank','$officialUrl','$officialDemo','$softsize','$urls','$body');
  ";
  $dsql->SetQuery($inQuery);
  if(!$dsql->ExecuteNoneQuery()){
	  $dsql->Close();
	  ShowMsg("�����ݱ��浽���ݿ⸽�ӱ� addonsoft ʱ����������ԭ��","-1");
	  exit();
  }
}
else
{
	$inQuery = "
  update #@__addonsoft
  set typeid ='$typeid',
  filetype ='$filetype',
  language ='$language',
  softtype ='$softtype',
  accredit ='$accredit',
  os ='$os',
  softrank ='$softrank',
  officialUrl ='$officialUrl',
  officialDemo ='$officialDemo',
  softsize ='$softsize',
  softlinks ='$urls',
  introduce='$body'
  where aid='$ID';";
  $dsql->SetQuery($inQuery);
  if(!$dsql->ExecuteNoneQuery()){
	  $dsql->Close();
	  ShowMsg("�������ݿ⸽�ӱ� addonsoft ʱ����������ԭ��","-1");
	  exit();
  }
} 
 
$dsql->Close();

//����HTML
//---------------------------------

$artUrl = MakeArt($ID,true);
if($artUrl=="") $artUrl = $cfg_plus_dir."/view.php?aid=$ID";

//---------------------------------
//���سɹ���Ϣ
//----------------------------------
$msg = "
������ѡ����ĺ���������
<a href='soft_add.php?cid=$typeid'><u>����������</u></a>
&nbsp;&nbsp;
<a href='archives_do.php?aid=".$ID."&dopost=editArchives'><u>�鿴����</u></a>
&nbsp;&nbsp;
<a href='$artUrl' target='_blank'><u>�鿴����</u></a>
&nbsp;&nbsp;
<a href='catalog_do.php?cid=$typeid&dopost=listArchives'><u>�ѷ�����������</u></a>
&nbsp;&nbsp;
<a href='catalog_main.php'><u>��վ��Ŀ����</u></a>
";

$wintitle = "�ɹ�����һ��������";
$wecome_info = "���¹���::��������";
$win = new OxWindow();
$win->AddTitle("�ɹ�����������");
$win->AddMsgItem($msg);
$winform = $win->GetWindow("hand","&nbsp;",false);
$win->Display();
?>