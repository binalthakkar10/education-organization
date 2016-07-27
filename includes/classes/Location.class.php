
<?php
/* 
Version 1.0 - Initial Version
Version 1.1 - 6/3/16 - Enh#7, 12 and 20 
*/



class Location {



    function __construct() {

        global $itfmysql;

        $this->dbcon = $itfmysql;

    }



    function admin_addState($datas) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
        unset($datas['id']);

        $this->dbcon->Insert('itf_location', $datas);

    }



    function admin_updateState($datas) {
    if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
    	
        $condition = array('id' => $datas['id']);

        unset($datas['id']);

        $this->dbcon->Modify('itf_location', $datas, $condition);

    }



    function State_deleteAdmin($Id) {

        $sql = "delete from itf_location where id in(" . $Id . ")";

        $this->dbcon->Query($sql);

        return $this->dbcon->querycounter;

    }



    function showAllState() {

        $sql = "select S.*  from itf_location S where 1 ";

        return $this->dbcon->FetchAllResults($sql);

    }



    function showAllLocation($city = '') {
    	if(!empty($_SESSION['LoginInfo']['org_id']) && isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		
    		if ($city != '') {
    		
    			$where = " where itf_partner.org_id='".$org_id."' AND itf_location.city='".$city."'";
    		
    		}else{
    			$where = " where itf_partner.org_id='$org_id'";
    		}
    		$sql = "select itf_location.*,itf_partner.partner_name from itf_location join itf_partner ON itf_partner.id= itf_location.partner_id  $where";
    	}else{	
    		if ($city != '') {
    		
    			$where = " where org_id='".$org_id."' AND city='".$city."'";
    		
    		}else{
    			$where = " where org_id='$org_id'";
    		}
    		$sql = "select * from itf_location  $where";
    	}
        
        

        return $this->dbcon->FetchAllResults($sql);

    }



    function showAllCourse() {

        $sql = "select distinct(course) from itf_course ";

        return $this->dbcon->FetchAllResults($sql);

    }



    function ShowAllStateSearch($txtsearch) {

        $sql = "select * from itf_location where  name like ( '%" . $this->dbcon->EscapeString($txtsearch) . "%')";

        return $this->dbcon->FetchAllResults($sql);

    }



    function getActiveCity() {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$sql = "select distinct(city) from itf_location l where l.status='1' and city !='' and org_id=".$org_id;
    	}else{
    		$sql = "select distinct(city) from itf_location l where l.status='1' and city !=''";
    	}
        return $this->dbcon->FetchAllResults($sql);

    }



    function LocationDetail($UsId) {
    	if(!empty($_SESSION['LoginInfo']['org_id'])&& isset($_SESSION['LoginInfo']['org_id'])){
    		$org_id = $_SESSION['LoginInfo']['org_id'];
    		$datas['org_id'] = $org_id;
    	}
    	
        $sql = "select U.* from itf_location U where U.id='" . $UsId . "'";

        return $this->dbcon->Query($sql);

    }



    function PublishBlock($ids) {

        $infos = $this->CheckState($ids);

        if ($infos['status'] == '1')

            $datas = array('status' => '0');

        else

            $datas = array('status' => '1');



        $condition = array('id' => $ids);

        $this->dbcon->Modify('itf_location', $datas, $condition);



        return ($infos['status'] == '1') ? "0" : "1";

    }



    //front end============================================================

    function getAllStateFront($parentid = "0") {

        $sql = "select *  from itf_location where status='1' and parentid='" . $parentid . "' order by name ";

        return $this->dbcon->FetchAllResults($sql);

    }



    function getAllStateCity() {

        $allstate = $this->showAllState(0);

        foreach ($allstate as &$citystate)

            $citystate["CITY"] = $this->showAllState($citystate["id"]);

        return $allstate;

    }



  



    function getLocationDetailForMap($searchCriteria, $type = 1) {



        if ($searchCriteria[grade] == 0) {
            if ($type == 2) {

                $whereCondition = " and (type='city' OR type='thirdParty') and class_registration='1'";

            }
            if($searchCriteria['registration_type']=='1'){
            /* this is for list of classes under map */
            	$whereCondition = "and class_registration='1'";
            }else if($searchCriteria['registration_type']=='2'){
            	$whereCondition = "and summer_camp_registration='1'";
            }

            $sql = "select id,name as loc_name,latitude,longitude,address,"

                    . "city,state,country,zip from itf_location where status='1' $whereCondition";
			
			/*echo "<pre>";
			print_r ($sql);
			exit;*/
			

			
            $results = $this->dbcon->FetchAllResults($sql);

            $i = 1;

            $loc_name = '';

            $i = 1;

            $centerLocName = 'Bay Area Debate Club<br/>';

            $centerAddress = 'Weibel Fremont CA 94539 USA';

            $centerCity = 'Fremont<br/>';

            $centerState = 'California<br/>';

            $centerZip = '94539<br/>';



            //  $mapData = "['$centerLocName $centerAddress $centerCity $centerState $centerZip', 37.5081652, -121.92740029999999, $i],";

            // $i++;

            foreach ($results as $values) {

                $loc_name = $values[loc_name] . '<br/>';

                $city = $values[city] . '<br/>';

                $state = $values[state] . '<br/>';

                $zip = $values[zip] . '<br/>';

                $address = str_replace(',', ' ', $values[address]);

                $classDetails = $this->getClassNameByLocationId($values['id'], $type);

                $mapData.="['$loc_name $classDetails', $values[latitude], $values[longitude], $i],";



                $i++;

            }

        } else {

            $sql = "SELECT c.id as class_id,c.start_date,c.end_date,c.room,c.code,c.start_date,l.name as loc_name,l.zip,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"

                    . "c.end_eligibility,c.no_of_class,c.duration,c.teacher_assigned,l.longitude,l.latitude,l.id as loc_id FROM  itf_class c,"

                    . "itf_location l WHERE "

                    . " (date(c.end_date) > '" . date('Y-m-d') . "') and"

                    . " ( c.loc_id=l.id and c.status='1' and  ( c.start_eligibility <= '$searchCriteria[grade]' AND  c.end_eligibility>='$searchCriteria[grade]') and c.type='$searchCriteria[type]')

            ";



            $results = $this->dbcon->FetchAllResults($sql);

            if (count($results) > 0) {

                $i = 1;

                $utilsObj = new Utils;

                foreach ($results as $values) {

                    $loc_name = $values[loc_name] . '<br/>';

                    $classDetails = $this->getClassNameByLocationIdAndGrade($values['loc_id'], $type, $searchCriteria[grade]);

                    $mapData.="['$loc_name $classDetails', $values[latitude], $values[longitude], $i],";



                    $i++;

                }

            }

        }

        return substr($mapData, 0, -1);

    }



    function getClassNameByLocationId($locId, $type) {

        $sql = "SELECT c.id as class_id,c.start_date,c.end_date,co.name as course_name,c.room,c.code,c.start_date,l.name as loc_name,l.zip,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"

                . "c.end_eligibility,c.no_of_class,c.duration,c.teacher_assigned,l.longitude,l.latitude FROM  itf_class c,"

                . "itf_location l,itf_course co WHERE co.id=c.course_id and "

                . " (date(c.end_date) > '" . date('Y-m-d') . "') and"

                . " ( c.loc_id=l.id and c.status='1' and c.type='$type' and c.loc_id='$locId')

            ";



        $results = $this->dbcon->FetchAllResults($sql);

        $loc_name = '';

        $utilsObj = new Utils;

        foreach ($results as $values) {

            $loc_name .= $values[course_name] . '. ' . date('m/d/y', strtotime($values[start_date])) . ' - ' . date('m/d/y', strtotime($values[end_date])) . '<br>';

        }

        if ($loc_name == '') {

            $typeName = $type == 1 ? 'classes' : 'summer camps';

            $loc_name = "There are no active $typeName at this location at this time."

                    . '<br/> Please check back again later. '

                    . '<br/>Subscribe to our newsletter or like our'

                    . '<br/> facebook page to get updates on registration.';

        } else {

            if ($type == 1) {

                $loc_name .= '<a href="index.php?itfpage=classes&itemid=classes_listing&loc_id=' . $utilsObj->encryptIds($locId) . '&cityname='.$values['city'].'">More Information</a>';

            } else {

                $loc_name .= '<a href="index.php?itfpage=summer_camps&itemid=summer_camps_listing&loc_id=' . $utilsObj->encryptIds($locId) . '&cityname='.$values['city'].'">More Information</a>';

            }

        }



        // if(count($results))

        return $loc_name;

    }



    function getClassNameByLocationIdAndGrade($locId, $type, $grade) {

        $sql = "SELECT c.id as class_id,c.start_date,c.end_date,co.name as course_name,c.room,c.code,c.start_date,l.name as loc_name,l.zip,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"

                . "c.end_eligibility,c.no_of_class,c.duration,c.teacher_assigned,l.longitude,l.latitude FROM  itf_class c,"

                . "itf_location l,itf_course co WHERE co.id=c.course_id and "

                . " (date(c.end_date) > '" . date('Y-m-d') . "') and ( c.start_eligibility <= '$grade' AND  c.end_eligibility>='$grade') and  "

                . " ( c.loc_id=l.id and c.status='1' and c.type='$type' and c.loc_id='$locId') 

            ";



        $results = $this->dbcon->FetchAllResults($sql);

        $loc_name = '';

        $utilsObj = new Utils;

        foreach ($results as $values) {

            $loc_name .= $values[course_name] . '. ' . date('m/d/y', strtotime($values[start_date])) . ' - ' . date('m/d/y', strtotime($values[end_date])) . '<br>';

        }



        if ($type == 1) {

            $loc_name .= '<a href="index.php?itfpage=classes&itemid=classes_listing&loc_id=' . $utilsObj->encryptIds($values[location_id]) . '&grade=' . $utilsObj->encryptIds($grade) . '&cityname='.$values['city'].'">More Information</a>';

            // $loc_name .= '<a href="index.php?itfpage=classes&itemid=classes_listing&loc_id=' . $utilsObj->encryptIds($locId) . '">More Information</a>';

        } else {

            $link = '<a href="index.php?itfpage=summer_camps&itemid=summer_camps_listing&loc_id=' . $utilsObj->encryptIds($values[location_id]) . '&grade=' . $utilsObj->encryptIds($grade) . '&cityname='.$values['city'].'">More Information</a>';



            $loc_name .= $link;

        }





        // if(count($results))

        return $loc_name;

    }



    function getCityName() {

        //$sql = "select id,city from itf_location where status='1'";
        $sql = "select distinct b.city from itf_class a, itf_location b where a.type=2 and a.end_date > sysdate() and a.loc_id=b.id order by b.city";

        return $this->dbcon->FetchAllResults($sql);

    }
    function ShowAllPartner(){
    	$org_id = $_SESSION['LoginInfo']['org_id'];
    	$sql="SELECT distinct(`partner_name`),`id` FROM `itf_partner` WHERE org_id='".$org_id."' AND`status`='1'";
    	return $this->dbcon->FetchAllResults($sql);
    }

 function getCityNameNew() {
 
 
	$sql = "select distinct b.city from itf_class a, itf_location b where a.type=1 and a.end_date > sysdate() and a.loc_id=b.id order by b.city";
       // $sql = "select id,city from itf_location where status='1' group by city";
		$allCity = $this->dbcon->FetchAllResults($sql);  
		return  $allCity;

    }
    /*

    function Testdb() {

        $sql = "DROP TABLE itf_class";

        //$this->dbcon->Query($sql);

    }

     * 

     */

     



}



?>
