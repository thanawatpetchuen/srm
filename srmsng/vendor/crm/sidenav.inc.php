<? 
	
	include("_classes/systems/MenuService.class.php");
	$sideNavMenu_lists = $menuService->getSessionSideNavMenus($_connection);
	
	//echo "sideNavMenu_lists >>".sizeof($sideNavMenu_lists)."<br>";
	//print_r($sideNavMenu_lists);
	
?>