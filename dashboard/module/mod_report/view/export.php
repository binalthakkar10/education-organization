<?php
if ($_REQUEST['requestFrom'] == 'admin') {
    $objstudent = new Student();
    $downloadInfo = $objstudent->getStudentsDownload($_REQUEST['type']);

    foreach ($downloadInfo as $temp) {
    	if($temp['type']=='1'){
    		$temp['type']='Class';
    	}elseif($temp['type']=='2'){
    		$temp['type']='Summer Camp';
    	}
        $datas[] = array('Location' => $temp['loc_name'],
            'Course Name' => $temp['course_name'],
            'Class Code' => $temp['class_code'],
            'Created date' => date('m/d/Y', strtotime($temp['student_created_date'])),
            'Student First name' => $temp['first_name'],
            'Student Last Name' => $temp['last_name'],
            'Grade' => $temp['grade_desc'],
            'Primary Contact Name' => $temp['primary_name'],
            'Primary Relationship' => $temp['primary_rel'],
            'Primary Phone' => $temp['primary_contact'],
            'Primary Email' => $temp['primary_email'],
            'Secondary Contact Name' => $temp['sec_name'],
            'Secondary Contact' => $temp['sec_name'],
            'Secondary Relationship' => $temp['sec_rel'],
            'Secondary Phone' => $temp['sec_contact'],
            'Secondary Email' => $temp['sec_email'],
            'payment_option' => $temp['payment_option_name'],
            'registration_status' => $temp['status_name'],
        	'Class Type' => $temp['type'],
        	'Class Start Date' => $temp['start_date'],
        	'Class End Date' => $temp['end_date'],
        	'Paypal Txn Id' => $temp['paypal_txn_id'],
        	'Paypal Amount' => $temp['payment_amt'],
        	'Installment Booking Amount' => $temp['installment_booking_amt'],
        	'Number of Installments' => $temp['no_of_installments'],
        	'Installment Amount' => $temp['installment_amt'],
        	'Installment Start date' => $temp['installment_start_date'],
        );
    }
    $obj = new ITFExport($datas);
    $obj->download();
} 
else if ($_REQUEST['requestFrom'] == 'Tournament')
{

     $objTournament = new Tournament();
     
    $downloadInfo = $objTournament->downloadTournamentStudentDetail($_REQUEST['tournamentId']);

    foreach ($downloadInfo as $temp) {
        $attend=$temp['attend_tournament']==1?'Yes':'No';
        $datas[] = array(
            'Tournament Name' => $temp['title'],
            'First name' => $temp['first_name'],
            'Last Name' => $temp['last_name'],
            'Email' => $temp['email'],
            'Phone' => $temp['phone'],
            'Date of Birth' => $temp['dob']==''?'':$temp['dob'],
            'Attend Tournaments ' => $attend,
            'Tournament Topic Choices ' => $temp['student_answer'],
        );
    }
    $obj = new ITFExport($datas);
    $obj->download();
}
else if ($_REQUEST['requestFrom'] == 'classes') {
    $objclass = new Class1();
    $downloadInfo = $objclass->exportClassListDetails($_REQUEST['type']);
	
    foreach ($downloadInfo as $temp) {
	$teachesNames = explode(',',$temp['teacher_assigned']);
		$fullName = '';
		foreach($teachesNames as $techId){
			$techName = mysql_query("select first_name, last_name from itf_teachers where id = '".$techId."'");
			$Name	= mysql_fetch_array($techName);
			$fullName .= $Name[0]." ".$Name[1]."," ;
		}
		if($temp['registration_status']=='1'){ 
			$temp['registration_status']='Scheduled and Confirmed';
		}elseif($temp['registration_status']=='2'){
			$temp['registration_status']='Cancelled';
		}elseif($temp['registration_status']=='3'){
			$temp['registration_status']='Scheduled but not yet confirmed';
		}elseif($temp['registration_status']=='4'){
			$temp['registration_status']='Scheduled and likely to get confirmed';
		}
        $datas[] = array(
			'Room' => $temp['room'],
			'Location' => $temp['loc_name'],
			'Address' => $temp['address'],
            'Course Name' => $temp['course_name'],
            'Start date' => date('m/d/Y', strtotime($temp['start_date'])),
            'End date' => date('m/d/Y', strtotime($temp['end_date'])),
            'Registration Status' => $temp['registration_status'],
            'Days of week' => $temp['day_of_week'],
            'Class Time' => $temp['class_time'],
            'Duration' => $temp['duration'],
            'Number of Class' => $temp['no_of_class'],			
			'Teacher(s)' => substr($fullName,0,-1),
            'Notes' => $temp['notes'],
            'Registration Type' => $temp['registration_type'],
            'Single Payment' => '$'.$temp['single_pay_amt'],
            'Installment Amount' => '$'.$temp['installment_amt'],
            'Number of Installment' => $temp['no_of_installment'],
            'Installment Start Date' => date('m/d/Y', strtotime($temp['installment_start_date']))
        );
    }
    $obj = new ITFExport($datas);
    $obj->download();
} 
else {
    flash('Something went wrong !', '2');
}
?>
