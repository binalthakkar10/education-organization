<?php

class Payment {

    function __construct() {
        global $itfmysql;
        $this->dbcon = $itfmysql;
    }

    function addOrder($datas) {
        $datas['created_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
        $datas['status'] = 0;
        $datas['payment_status'] = 'start';
        return $this->dbcon->Insert('itf_orders', $datas);
    }

    function addPaymentLog($datas) {
        $datas['created_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
        return $this->dbcon->Insert('itf_payment_log', $datas);
    }

    function addCreditCardDetails($datas) {
        $datas['created_date'] = date('Y-m-d H:i:s');
        $datas['modified_date'] = date('Y-m-d H:i:s');
//        return $this->dbcon->Insert('itf_card_info', $datas);
	return TRUE;
    }

    function addOrderDetails($datas) {
        return $this->dbcon->Insert('itf_order_detail', $datas);
    }

    public function addPaymentInfo($datas) {
        $datas['order_id'] = $datas['custom'];
        $datas['payment_amount'] = $datas['payment_gross'];
        unset($datas['id']);
        $this->dbcon->Insert('itf_payment', $datas);
    }

    public function updatepaymenttable($datas) {
        $checkOrder = $this->checkOrder($datas['id']);
        if ($checkOrder['id'] != '') {
            $condition = array('id' => $datas['id']);
            $this->dbcon->Modify('itf_orders', $datas, $condition);
            unset($datas['id']);
            unset($datas['source']);
            unset($datas['first_name']);
            unset($datas['last_name']);
            $condition1 = array('id' => $checkOrder['student_id']);
            if ($datas['payment_status'] != 'failure') {
                $this->dbcon->Modify('itf_student', $datas, $condition1);
                $condition2 = array('reference_id' => $checkOrder['student_id']);
                $this->dbcon->Modify('itf_subscriber', $datas, $condition2);
            }
            $_SESSION['order_id'] = '';
            $_SESSION['amount'] = '';
            $_SESSION['student_id'] = '';
            unset($_SESSION['order_id']);
            unset($_SESSION['amount']);
            unset($_SESSION['student_id']);
        }
    }

    public function updateTournamentPaymenttable($datas) {
        $checkOrder = $this->checkOrder($datas['id']);
        if ($checkOrder['id'] != '') {
            $condition1 = array('id' => $checkOrder['student_id']);
            $studentData['reg_status'] = 1;
            $studentData['status'] = 1;
            $datas['status'] = 1;
            unset($datas['source']);
            unset($datas['first_name']);
            unset($datas['last_name']);
            if ($datas['payment_status'] != 'failure') {
                $this->dbcon->Modify('itf_tournament_student', $studentData, $condition1);
                $condition = array('id' => $datas['id']);
                $this->dbcon->Modify('itf_orders', $datas, $condition);
                $condition2 = array('reference_id' => $checkOrder['student_id']);
//              $this->dbcon->Modify('itf_subscriber', $datas, $condition2);
            }
            $_SESSION['order_id'] = '';
            $_SESSION['amount'] = '';
            $_SESSION['student_id'] = '';
            unset($_SESSION['order_id']);
            unset($_SESSION['amount']);
            unset($_SESSION['student_id']);
        }
    }

    public function getStudentDeatils($studentId, $source) {
        if ($source == 'tournament') {
            $sql = "SELECT ts.first_name, ts.last_name, ts.email, l.name AS loc_name
                    FROM `itf_tournament_student` ts, itf_tournaments t, itf_location l
                    WHERE ts.tournament_id = t.id AND l.id = t.loc_id AND ts.id ='$studentId'";
        } else {
            $sql = "SELECT ts.first_name, ts.last_name, ts.primary_email as email, l.name AS loc_name
                    FROM `itf_student` ts, itf_class t, itf_location l
                    WHERE ts.class_id = t.id AND l.id = t.loc_id AND ts.id ='$studentId'";
        }
        return $this->dbcon->Query($sql);
    }

    public function checkOrder($orderId) {
        $sql = "select *  from itf_orders where id = '$orderId'";
        return $this->dbcon->Query($sql);
    }

}

?>
