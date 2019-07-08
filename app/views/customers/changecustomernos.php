<script language="javascript">
	//xajax_changecustomernos();
	//alert('i was called');
</script>
<?php
 $arr=array();

		$sth = mysql_query("select mem_no from member");
		$x=0;
		while($row=mysql_fetch_assoc($sth)){
		$arr[$x]=$row['mem_no'];
		$x++;
		}

		//echo count($arr);
		for($x=0;$x < count($arr);$x++){
//$qry="update member set pin='".$pin."',mm_no='".$mm_no."',subscribe='".$subscribe."' where id='".$memid."'";
		mysql_query("update member set mem_no='KY".$arr[$x]."' where mem_no='".$arr[$x]."'");
		}
		
		/*
		$arr=array();

		$sth = mysql_query("select id,mem_no from member");
		$x=0;$y=1;$z=0;
		while($row=mysql_fetch_assoc($sth)){
		$arr[$x][$z]=$row['id'];
		$arr[$x][$y]=$row['mem_no'];
		$x++;
		}

		//echo count($arr);
		//echo $arr[1][0];
		//echo "<br>";
		//echo $arr[1][1];
		$y=1;$z=0;
		for($x=0;$x < count($arr);$x++){
//$qry="update member set pin='".$pin."',mm_no='".$mm_no."',subscribe='".$subscribe."' where id='".$memid."'";
		mysql_query("update member set mem_no='KY".$arr[$x][$y]."' where id='".$arr[$x][$z]."'");
		}	
		
		*/	
		
?>
