<?php

$stocks = array(
    "0x2fa0ac498d01632f959d3c18e38f4390b005e200",
	"0xc908a34165d2720d12ffcfb6b99b47161b1c9946",
	"0xa9e20fdd18c792f302e273b09aa142984f25ea64",
	"0x344b603d852143dd9b7d6d95a92df77061486765",
	"0xc83caf102846bcce72c81a1d22d981756efadd31",
	"0x029d0650c81817afb1810ae10270823318321878",
	"0xf39729a98d936199c8260af86018ebf21ed8f846",
	"0x3854962ad8861f44fc617171ddb91eb5c3f1782a",
	"0x2309ec057db80fbacfd57cf7e275b096f65d1e75",
	"0xce3632ecb106ec5f2cbfb49c89a118058737b5a5",
	"0xda9018d28bc96f4509645fbc28e872c7d678931d",
	"0xea788fc8313c3c84810489cd7724f3251da624a3",
	"0xc6b5756b2ac3c4c3176ca4b768ae2689ff8b9cee",
	"0x8c9ae207ee452c1a94e0653e5db1c7b4de7d76c2",
	"0xfc8decea972435b89be675996b6bf825aad06f9d",
	"0xfdfcfa5dce03a67fde31d674784312bed25f0b90",
	"0x5ac108d7b48979f261cf57ed7f9df95f8cb56865",
	"0xa6230691b2b1cff2f9737ccfa3ff95d580e482a0",
	"0x4a3a469f39360f48a92e829e61757ab39bdf0d8f",
	"0x47a03f5bf46bbc95cadd4a5311bcead23597d4e8",
	"0x443013c1557b329d97c2983461a441d33791a31e",
	"0xd086d846758613f3640b9396e759cdb92f5e7592",
	"0xc9179478a6605f78365f3c8c4c216b995a245008",
	"0xe1a1ee035cff3a830bc236ff01cbee0c65ce4c25",
	"0xe0af40d534685f46718d07cdee8f2d78af9916dd",
	"0xfd7107eec2f21f69d75d0d7479a7ff9de477e5c9",
	"0x2914101a152f4d65177949adb99794dee14f308e",
	"0x9058b302c785e4b136f13c35ddb3e9a2f9bfe4aa",
	"0x98fd6adfbf79b83f675e7214f70c98a1b7101b85",
	"0x237d43f6218c4e5680119c0be95aaa6e19f50528",
	"0x5037c9fbfccbbb8409157d72cb9579ac3d05661e",
	"0x1ab8c9fc5f3b9ec59216777cc4514ab2a0a96d55"
);

$minadr="";
$minval=999999999;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://etherscan.io/find-similiar-contracts?a=0x4c27ec9ca9528e16a1ab51c3979af75ea8ce85cb");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch); 
curl_close($ch);

file_put_contents("graphdata.txt",time()." ",FILE_APPEND);

while(true){
	$pos=strpos($output,"Exact [100]");
	if($pos==false)break;
	$output=substr($output,$pos);
		
	$pos=strpos($output,"/address/0x");
	if($pos==false)break;
	$output=substr($output,$pos);
		
	$adr=substr($output,9,42);
		
	$pos=strpos($output,"<td>");
	if($pos==false)break;
	$output=substr($output,$pos);
	$end=strpos($output," Ether");
		
	$value=substr($output,4,$end-4);
	$value=str_replace('<b>','',$value);
	$value=str_replace('</b>','',$value);
	
	$apos=-1;
	
	for($i=0;$i<count($stocks);$i++){
		if($stocks[$i]==$adr){
			$apos=$i;
			break;
		}
	}
	
	if($apos!=-1){
		file_put_contents("graphdata.txt","$apos $value ",FILE_APPEND);
		if(floatval($value)<$minval){
			$minval=floatval($value);
			$minadr=(string)$adr;
		}
	}
	
	$output=substr($output,$pos);
}

file_put_contents("graphdata.txt","\r\n",FILE_APPEND);

$nextpump = trim(file_get_contents('nextpump'));
if(time()>($nextpump-20)){
	$out = fopen("pump", "w");
	fwrite($out,$minadr);
	fwrite($out," ".(time()+3600));
	fclose($out);

	file_put_contents("nextpump",time()+14400);
}
?>