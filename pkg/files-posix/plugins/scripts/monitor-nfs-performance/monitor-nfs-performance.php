<?php
$port = $_SERVER['UPTIME_UPTIME_PORT'];
$host = $_SERVER['UPTIME_UPTIME_HOSTNAME'];
$pass = $_SERVER['UPTIME_UPTIME_NFS_PASSWORD'];
$scri = $_SERVER['UPTIME_UPTIME_NFS_SCRIPT'];
if($host == ''){
    $host = $_SERVER['UPTIME_HOSTNAME'];
}
$cmd = "../../../scripts/agentcmd -p $port $host rexec $pass $scri";
$output = shell_exec($cmd);
$out_arr = preg_split("/Server rpc:/",$output);
$out_arr_sin = preg_split("/\n\n/",$out_arr[1]);

$cnt = count($out_arr_sin);
$heading = '';
for($i=0;$i< $cnt;$i++)
{
	$split = preg_split("/\n/",$out_arr_sin[$i]);
	if($i == 0) $sp = "Server rpc:";
	if(preg_match("/:/",$split[0]) && $i!=0)
	{
		$head = preg_split("/:/",$split[0]);
		$heading = trim($head[0]);
		$heading = str_replace(" ","_",$heading);
		$heading = str_replace(".","_",$heading);
		
		
	}
	elseif(preg_match("/:/",$sp) && $i==0)
	{
		$head = preg_split("/:/",$sp);
                $heading = trim($head[0]);
                $heading = str_replace(" ","_",$heading);
                $heading = str_replace(".","_",$heading);
	}
	if($i==0)
	$j=0;
	else $j=1;
	$splcnt = count($split);
	while($j < $splcnt)
	//for($j=1;$j<count($split)-1;$j++)
	{
		if(preg_match("/:/",$split[$j]))
		{
			$head1 = preg_split("/:/",$split[$j]);
			$heading1 = trim($head1[0]);
			$heading1 = str_replace(" ","_",$heading1);
			$heading1 = str_replace(".","_",$heading1);
		 	$hea = $heading."-".$heading1;
			
		}
		elseif(!preg_match("/:/",$split[$j]))
		{	
			$col = preg_split("/\s+/",$split[$j]);
			
			if(preg_match('/[a-zA-Z]+/',$col[0]))
			{
			if(!preg_match("/-/",$hea))
			$hea = $heading;
			$val = preg_split("/\s\s+/",$split[$j+1]);	
			$col = array_filter($col);
			//$val = array_filter($val);
			$colcnt = count($col);
			$colarr = array('calls','badcalls','retrans','badxids','timeouts','newcreds','null','nullPCT');
			for($k=0;$k<$colcnt;$k++)
			{
				if(preg_match("/%/",$val[$k]))
				{	
					$nVal=preg_split("/\s/",$val[$k]);
					if (in_array("$col[$k]", $colarr, TRUE))
                                        {
						echo $hea.".".$col[$k]." ".$nVal[0]."\n";
					}
					$pct = $col[$k]."PCT";
					if (in_array("$pct", $colarr, TRUE))
                                        {
						echo $hea.".".$col[$k]."PCT ".$nVal[1]."\n";
					}
				}
				else
				{
					if (in_array("$col[$k]", $colarr, TRUE))
					{
						echo $hea.".".$col[$k]." ".$val[$k]."\n";
					}
				}
			}
			}
		}
	$j++;
	}
	$hea='';
//	echo "\n\n";
}

//print_r($out_arr_sin[0]);exit;
?>
