<?php 
$objmodule=new Lessonroll();
//var_dump($objmodule);
$currentpagenames=isset($_GET['itfpage'])?$_GET['itfpage']:'';//echo "hello";die();
$pagetitle="Lesson wise Roll Call";

         $actions=isset($_REQUEST['actions'])?$_REQUEST['actions']:'';
	           
        switch($actions)
	{
		case 'insert':
			include(ITFModulePath.'view/attendanceinsert.php');
			break;
		
		case 'display':
			include(ITFModulePath.'view/attendanceview.php');
			break;
                
               case 'morning_roll':
			include(ITFModulePath.'view/morning_roll.php');
			break;    
                    
//default:
//include(ITFModulePath.'view/role_list.php');

	}
                        
			
	?>