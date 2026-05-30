<?php
$data = array();
if (isset($_POST['packagename'])) 
	
            {
				$packagename = $_POST['packagename'];
				
	  //	[1]  Tube Video
		
				if(stripos($packagename, 'App Packagename....') !== false)
				
				{					
					array_push($data, array(

					"am_AppID"=>"",
					"am_Banner1"=>"",
					"am_Interstitial1"=>"",
					"am_Native1"=>"",
					
					"MORE_APP"=>"",  // Google Play Store Account Ni Link Add Karvani
					
					"app_privacyPolicyLink"=>"",
					"app_needInternet"=>"", 
					"app_updateAppDialogStatus"=>"",
					"app_versionCode"=>"",
					"app_redirectOtherAppStatus"=>"",
					"app_newPackageName"=>"",
					"app_dialogBeforeAdShow"=>"",
					"app_adShowStatus"=>"",
					"app_howShowAd"=>"",
					"app_adPlatformSequence"=>"",
					"app_alernateAdShow"=>"",
					"app_mainClickCntSwAd"=>"",
					"app_innerClickCntSwAd"=>"",
					"am_ad_showAdStatus"=>""
					
					));

					if(empty($data))
					{
						$data=array("success"=>0, "message"=>"there is no data found...!!");
					}else
					{
						$data = array( "success"=>1, "data"=>$data);	
					}
				}	
			
			}
		else {
  $data=array("success"=>0, "message"=>"package name required...!!");
}
header ( 'Content-type: application/json' );
echo json_encode($data);
?>