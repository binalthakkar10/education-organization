<?php
class Quote
{
	public $dbcon;
    public $datas;

	function __construct()
	{
			global $itfmysql;
			$this->dbcon = $itfmysql;
	}

	public function addToQuote($data)
    {
        $product_id = $data['id'];
        $quantity = $data['quantity'];
        $_SESSION['quote'][$product_id] += (int)$quantity;

        return true;
    }

    public function getQuote()
    {
        $datas = array();

        if(isset($_SESSION['quote']) and count($_SESSION['quote']) > 0){

            $product = new Product();
            foreach($_SESSION['quote'] as $key=>$quote){
                $datas[] = array('quantity'=>$quote,'product'=>$product->CheckProductFront($key));
            }

            return $datas;
        } else{

            return $datas;
        }


    }

    public function getTotalQuote()
    {
        $total = 0;

        if(isset($_SESSION['quote']) and count($_SESSION['quote']) > 0){

            foreach($_SESSION['quote'] as $key=>$quote){
                $total += $quote;
            }

            return $total;
        } else{

            return $total;
        }


    }

    public function removeQuote($product_id)
    {

        if(isset($_SESSION['quote']) and count($_SESSION['quote']) > 0){

            unset($_SESSION['quote'][$product_id]);

            return true;
        } else{

            return false;
        }


    }

    public function discardQuote()
    {

        if(isset($_SESSION['quote']) and count($_SESSION['quote']) > 0){

            unset($_SESSION['quote']);

            return true;
        } else{

            return false;
        }


    }

    public function addCustomQuote($datas)
    {
        
              //echo "<pre>";print_r($datas);die;
        $loc=$datas['location'];
        $admin_mail1 = $this->CheckUser($loc);
        foreach($admin_mail1 as   $admin_mail)
        {
        $maildatavalue = $this->GetEmail(15);
            $objmail = new ITFMailer();
            $objmail->SetSubject($maildatavalue['mailsubject']);
            $objmail->SetBody($maildatavalue['mailbody'],array('name'=>$datas['quote_name'],"time"=>$datas['finish_time']));
            $objmail->SetTo($admin_mail['email']);
            $objmail->MailSend();
        }
        $datas['user_id'] = $_SESSION['FRONTUSER']['id'];
          $datas['service_id'] = implode(",", $datas['service_id']);
        $user = new User();
        $info = $user->CheckUser($_SESSION['FRONTUSER']['id']);
        if($info['payment_type'] == 'account')
        {
            $datas['approve'] = 0;
        } else {
            $datas['approve'] = 1;
        }
        if(isset($_FILES['attachment']['name']) and !empty($_FILES['attachment']['name']))
        {
            $fimgname="plucka_".rand();
            $objimage= new ITFUpload();
            $objimage->load($_FILES['attachment']);
            $objimage->save(PUBLICFILE."products/".$fimgname);
            $imagename = $objimage->createnames;

            $datas['attachment'] = $imagename;
        }
        $quote_id = $this->dbcon->Insert('itf_quote',$datas);

        $quotes = $this->getQuote();

        if(isset($quote_id)){

                $info = array("quote_id"=>$quote_id,"product_id"=>$datas['product_id'],"quantity"=>$datas['quantity']);
                $this->dbcon->Insert('itf_quote_detail',$info);

        }

        $this->discardQuote();
    }

    public function addQuote($datas)
    {
        //echo "<pre>";print_r($datas);die;
        $loc=$datas['location'];
         $admin_mail1 = $this->CheckUser($loc);
       //echo "<pre>";print_r($admin_mail);die;
        //echo "<pre>";print_r($datas);die;
        $datas['user_id'] = $_SESSION['FRONTUSER']['id'];
        $user = new User();
        $info = $user->CheckUser($_SESSION['FRONTUSER']['id']);
        foreach($admin_mail1 as   $admin_mail)
        {
        $maildatavalue = $this->GetEmail(14);
            $objmail = new ITFMailer();
            $objmail->SetSubject($maildatavalue['mailsubject']);
            $objmail->SetBody($maildatavalue['mailbody'],array('name'=>$datas['quote_name'],"emailid"=>$datas['quote_desc'],"time"=>$datas['finish_time']));
            $objmail->SetTo($admin_mail['email']);
            $objmail->MailSend();
        }
        if($info['payment_type'] == 'account')
        {
            $datas['approve'] = 0;
        } else {
            $datas['approve'] = 1;
        }


        $quote_id = $this->dbcon->Insert('itf_quote',$datas);

        $quotes = $this->getQuote();

        if(isset($quote_id)){
            foreach($quotes as $quote)
            {
                $info = array("quote_id"=>$quote_id,"product_id"=>$quote['product']['id'],"quantity"=>$quote['quantity'],"price"=>$quote['product']['price']);
                $this->dbcon->Insert('itf_quote_detail',$info);
            }
        }

        $this->discardQuote();
    }

    public function checkQuote($quote_id)
    {
      $sql = "select Q.*,U.registration_id as customer from itf_quote Q
                LEFT JOIN itf_users U ON U.id=Q.user_id
                where Q.id ='".$quote_id."' and Q.status = 1"; 
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    public function getQuoteDetails($quote_id)
    {
        $sql = "select Q.*,P.*,C.catname,S.catname as service_name from itf_quote_detail Q
                LEFT JOIN itf_product P ON Q.product_id = P.id
                LEFT     JOIN itf_category C ON P.category_id = C.id
                     LEFT     JOIN itf_quote W ON W.id = Q.quote_id
                   LEFT     JOIN itf_service_category S ON W.service_id = S.id
                   
                where Q.quote_id ='".$quote_id."' and Q.status = 1 order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function getOrder()
    {
        $user_id = $_SESSION['FRONTUSER']['id'];
        $sql = "select *,UNIX_TIMESTAMP(date_added) as create_date from itf_quote where user_id ='".$user_id."' and status = 1 and payment = 0 Having (UNIX_TIMESTAMP(STR_TO_DATE(finish_time,'%m/%d/%Y %H:%i:%s')) - UNIX_TIMESTAMP(now())) > 0  order by id desc ";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function getActiveQuote()
    {
        $user_id = $_SESSION['FRONTUSER']['id'];
        $sql = "select Q.*,UNIX_TIMESTAMP(Q.date_added) as create_date,U.registration_id,L.name as location_name from itf_quote Q
                LEFT JOIN itf_users U ON U.id = Q.user_id
                LEFT JOIN itf_state L ON L.id = Q.location
                where Q.user_id ='".$user_id."' and Q.status = 1 and Q.payment = 1 and Q.quote_status IN(0,1) order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function getClosedQuote()
    {
        $user_id = $_SESSION['FRONTUSER']['id'];
        $sql = "select Q.*,UNIX_TIMESTAMP(Q.date_added) as create_date,U.registration_id,L.name as location_name from itf_quote Q
                LEFT JOIN itf_users U ON U.id = Q.user_id
                LEFT JOIN itf_state L ON L.id = Q.location
                where Q.user_id ='".$user_id."' and Q.status = 1 and Q.payment = 1 and Q.quote_status = 2  order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function getExpiredQuote()
    {
        $user_id = $_SESSION['FRONTUSER']['id'];
        $sql = "select Q.*,UNIX_TIMESTAMP(Q.date_added) as create_date,U.registration_id,L.name as location_name from itf_quote Q
                LEFT JOIN itf_users U ON U.id = Q.user_id
                LEFT JOIN itf_state L ON L.id = Q.location
                where Q.user_id ='".$user_id."' and Q.status = 1 and Q.id NOT IN(select quote_id from itf_bid) Having (UNIX_TIMESTAMP(STR_TO_DATE(finish_time,'%m/%d/%Y %H:%i:%s')) - UNIX_TIMESTAMP(now())) < 0 order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function getEnquiryByLocation($city_ids,$cat_ids=0)
    {
        if(!empty($city_ids)){
        $sql = "select *,UNIX_TIMESTAMP(date_added) as added_date,UNIX_TIMESTAMP(STR_TO_DATE(finish_time,'%m/%d/%Y %H:%i:%s')) - UNIX_TIMESTAMP(now()) as endTime from itf_quote where (location IN(".$city_ids.") and service_cat IN(".$cat_ids.")) or (location IN(".$city_ids.") and service_cat = 0 ) and id NOT IN (SELECT quote_id FROM itf_bid where user_id = '".$_SESSION['FRONTUSER']['id']."' ) and  status = 1 order by id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        } else {
            $datas = array();
        }
        return $datas;
    }

    public function getActiveQuoteByLocation($city_ids)
    {
        if(!empty($city_ids)){
        $sql = "select Q.*,UNIX_TIMESTAMP(Q.date_added) as added_date,UNIX_TIMESTAMP(STR_TO_DATE(Q.finish_time,'%m/%d/%Y %H:%i:%s')) - UNIX_TIMESTAMP(now()) as endTime,L.name as location_name,U.registration_id as customer from itf_quote Q
                LEFT JOIN itf_state L ON L.id = Q.location
                LEFT JOIN itf_users U ON U.id = Q.user_id
                where location IN(".$city_ids.")  and Q.id IN(select quote_id from itf_bid where user_id ='".$_SESSION['FRONTUSER']['id']."' and status=1) and Q.status = 1 and Q.payment = 1 and Q.quote_status IN(0,1) order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        } else {
            $datas = array();
        }

        return $datas;
    }

    public function getClosedQuoteByLocation($city_ids)
    {
        if(!empty($city_ids)){
        $sql = "select Q.*,UNIX_TIMESTAMP(Q.date_added) as added_date,UNIX_TIMESTAMP(STR_TO_DATE(Q.finish_time,'%m/%d/%Y %H:%i:%s')) - UNIX_TIMESTAMP(now()) as endTime,L.name as location_name from itf_quote Q
                LEFT JOIN itf_state L ON L.id = Q.location
                where location IN(".$city_ids.")  and Q.id IN(select quote_id from itf_bid where user_id ='".$_SESSION['FRONTUSER']['id']."'and status=1) and Q.status = 1 and Q.quote_status = 2 order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        } else {
            $datas = array();
        }

        return $datas;
    }

    public function addQuoteChat($datas)
    {
        $datas['user_id'] = $_SESSION['FRONTUSER']['id'];
        unset($datas['id']);
        $this->dbcon->Insert('itf_quote_chat',$datas);
    }

    public function getQuoteChat($quote_id)
    {
        $sql = "select Q.chat_text,UNIX_TIMESTAMP(Q.date_added) as added_date,U.name,UP.profile_image from itf_quote_chat Q
                LEFT JOIN itf_users U ON Q.user_id = U.id
                LEFT JOIN itf_user_profile UP ON U.profile_id = UP.id
                where Q.quote_id ='".$quote_id."' and Q.status = 1 order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);

        return $datas;
    }

    public function showTotalQuoteAccept()
    {
        return Count($this->getActiveQuote());

    }

    public function showTotalQuoteRequest()
    {
        return Count($this->getOrder());
    }

    public function addActiveQuoteChat($datas)
    {
        
     //echo "<pre>";print_r($datas);
        $quote_id = $datas['quote_id'];
        $quote_status = isset($datas['quote_status'])?$datas['quote_status']:'';
        $datas['user_id'] = $_SESSION['FRONTUSER']['id'];
        
       $admin_mail= $this->CheckWork($datas['reciever_id']);
          //echo "<pre>";print_r($admin_mail);
        $maildatavalue = $this->GetEmail(17);
          $objmail = new ITFMailer();
           $objmail->SetSubject($maildatavalue['mailsubject']);
           $objmail->SetBody($maildatavalue['mailbody'],array('name'=>$datas['chat_text']));
          $objmail->SetTo($admin_mail['email']);
          $objmail->MailSend();
        //echo "<pre>";print_r($admin_mail1);die;
        
        unset($datas['id']);
        $this->dbcon->Insert('itf_active_quote_chat',$datas);

        if(!empty($quote_status)) {
            $datas = array('quote_status'=>$quote_status);
            $condition = array('id'=>$quote_id);
            $this->dbcon->Modify(' itf_quote',$datas,$condition);
        }
    }
    function getuseruniqueid(){
        
        $sql="select * from itf_users where id='".$_SESSION['FRONTUSER']['id']."'"; 
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }
function getuseridfromunique($id){
        
        $sql="select * from itf_users where registration_id='".$id."'"; 
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    public function getActiveQuoteChat($quote_id,$reciever_id)
    {
        
        //echo "<pre>";print_r($reciever_id);die;
        $sql1 = "select Q.chat_text,UNIX_TIMESTAMP(Q.date_added) as added_date,U.name,UP.profile_image from 
            itf_active_quote_chat Q
                LEFT JOIN itf_users U ON Q.user_id = U.id
                LEFT JOIN itf_user_profile UP ON U.profile_id = UP.id
                where Q.quote_id ='".$quote_id."' and Q.user_id='".$_SESSION['FRONTUSER']['id']."' and Q.reciever_id ='".$reciever_id."' and Q.status = 1 order by Q.id desc";
        $datas = $this->dbcon->FetchAllResults($sql1);
        
     $useridtoreceiverid=  $this->getuseruniqueid();
     $reciveridtouserid=  $this->getuseridfromunique($reciever_id);
//echo "<pre>";print_r($data);die;
         $sql2 = "select Q.chat_text,UNIX_TIMESTAMP(Q.date_added) as added_date,U.name,UP.profile_image from 
            itf_active_quote_chat Q
                LEFT JOIN itf_users U ON Q.user_id = U.id
                LEFT JOIN itf_user_profile UP ON U.profile_id = UP.id
                where Q.quote_id ='".$quote_id."' and Q.user_id='".$reciveridtouserid['id']."' and Q.reciever_id ='".$useridtoreceiverid['registration_id']."' and Q.status = 1 order by Q.id desc";
        $datas1 = $this->dbcon->FetchAllResults($sql2);

       $qdata= array_merge($datas, $datas1);
       $this->array_sort_by_column($qdata, 'added_date');
      // echo "<pre>";print_r($qdata);
        //echo "datas2"."<pre>";print_r($datas1);
      // die;
     


 
       
       
        return $qdata;
    }
 function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
   $sort_col = array();
   foreach ($arr as $key=> $row) {
       $sort_col[$key] = $row[$col];
   }

   array_multisort($sort_col, $dir, $arr);
}

    public  function addBid($datas)
    {
            //echo "<pre>";print_r($datas);die;
        $id=$datas['quote_id'];
        
        
          //echo "<pre>";print_r($admin_mail);die;
       if(isset($_FILES['attachment']['name']) and !empty($_FILES['attachment']['name']))
        {
            $fimgname="plucka_".rand();
            $objimage= new ITFUpload();
            $objimage->load($_FILES['attachment']);
            $objimage->save(PUBLICFILE."pdf/".$fimgname);
            $imagename = $objimage->createnames;

            $datas['attachment'] = $imagename;
        }
        $datas['user_id'] = $_SESSION['FRONTUSER']['id'];
        unset($datas['id']);
        $this->dbcon->Insert('itf_bid',$datas);
       
        $admin_mail = $this->CheckSupp($id);
        //echo "<pre>";print_r($admin_mail);die;
         $maildatavalue = $this->GetEmail(16);
		$objmail = new ITFMailer();
		$objmail->SetSubject($maildatavalue['mailsubject']);
		 $objmail->SetBody($maildatavalue['mailbody'],array('name'=>$datas['bid_desc'],"time"=>$datas['bid_amount']));
		$objmail->SetTo($admin_mail['email']);
		$objmail->MailSend();
    }

    public function deleteBid($id)
    {
        $sql="delete from itf_bid where id in(".$id.")";
        

        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }

     public function deleteExpQu($id)
    {
        $sql="delete from itf_quote where id in(".$id.")";
        

        $this->dbcon->Query($sql);
        return $this->dbcon->querycounter;
    }
    
    public function getBids()
    {
        $sql = "select B.*,Q.quote_name,Q.quote_desc from itf_bid B
                LEFT JOIN itf_quote Q ON Q.id = B.quote_id
                where B.user_id ='".$_SESSION['FRONTUSER']['id']."' order by B.id desc ";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function getBidsById($id)
    {
        $sql = "select B.*,Q.quote_name,Q.quote_desc,U.registration_id as supplier_id from itf_bid B
                LEFT JOIN itf_quote Q ON Q.id = B.quote_id
                LEFT JOIN itf_users U ON U.id = B.user_id
                where B.id ='".$id."' ";
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    public function getBidsByQuote($quote_id)
    {
         $sql = "select B.*,Q.quote_name,Q.quote_desc,U.registration_id as supplier_id from itf_bid B
                LEFT JOIN itf_quote Q ON Q.id = B.quote_id
                LEFT JOIN itf_users U ON U.id = B.user_id
                where B.quote_id ='".$quote_id."' ";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }
     public function getBidsByQuotes($quote_id)
    {
         $sql = "select B.*,Q.quote_name,Q.quote_desc,U.registration_id as supplier_id from itf_bid B
                LEFT JOIN itf_quote Q ON Q.id = B.quote_id
                LEFT JOIN itf_users U ON U.id = B.user_id
                where B.quote_id ='".$quote_id."' and B.status='1' ";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }
    
    
    public function getDetailFromPayment($quote_id,$uid)
    {
         $sql = "select b.* ,u.registration_id from itf_bid b
                LEFT JOIN itf_users u ON u.id = b.user_id
        
        where b.quote_id ='".$quote_id."' and b.user_id='".$uid."'";
        $datas = $this->dbcon->Query($sql);
        return $datas;
    }

    public function isBidded($quote_id)
    {

        $sql = "Select * from itf_bid where quote_id = '".$quote_id."' and user_id = '".$_SESSION['FRONTUSER']['id']."'  ";
        $datas = $this->dbcon->Query($sql);

        if($datas) {

            return true;
        } else {

            return false;
        }

    }

    public function getBidStatus($status){
        if($status == 0){
            $datas = 'Pending';
        } elseif($status == 1){
            $datas = 'Accepted';
        } elseif($status == 2){
            $datas = 'Not Accepted';
        }


        return $datas;

    }

    public function getQuoteStatus($status){
        if($status == 0){
            $datas = 'In Progress';
        } elseif($status == 1){
            $datas = 'Pending';
        } elseif($status == 2){
            $datas = 'Complete';
        }


        return $datas;

    }

    public function addCart($datas)
    {
        unset($_SESSION['cart']);
        foreach($datas['bid_check'] as $bid){
      
            $_SESSION['cart'][$bid] = $datas['quote_id'];
        }

        return true;
    }

    public function removeCart($id)
    {
        
        unset($_SESSION['cart'][$id]);

        return true;
    }

    public function getCart()
    {
        $datas = array();

        if(isset($_SESSION['cart']) and count($_SESSION['cart']) > 0){

            foreach($_SESSION['cart'] as $key=>$cart){
                $data = $this->getBidsById($key);
                $productData = $this->getQuoteDetails($cart);
                $data['product'] = $productData;
                $data['quote_id'] = $cart;
                $datas[] = $data;
            }

            return $datas;
        } else{

            return $datas;
        }
    }


    public function getTotalPrice()
    {
        $total = 0;

        if(isset($_SESSION['cart']) and count($_SESSION['cart']) > 0){

            foreach($_SESSION['cart'] as $key=>$cart){
                $datas = $this->getBidsById($key);

                $total += $datas['bid_amount'];
            }

            return $total;
        } else{

            return $total;
        }
    }

    public function emptyCart()
    {
        unset($_SESSION['cart']);

        return true;
    }

    public function addReview($datas)
    {
        unset($datas['id']);
        $this->dbcon->Insert('itf_quote_review',$datas);
    }

    public function getCustomerReviews($quote_id)
    {
        $sql = "select R.*,U.registration_id from itf_quote_review R
                INNER JOIN itf_users U ON R.user_id = U.id and U.usertype = 2
                where R.quote_id ='".$quote_id."' ";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function getSupplierReviews($quote_id)
    {
        $sql = "select R.*,U.registration_id from itf_quote_review R
                INNER JOIN itf_users U ON R.user_id = U.id and U.usertype = 3
                where R.quote_id ='".$quote_id."' ";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function getTotalQuoteByUser($user_id){
        $sql = "select count(id) as total from itf_quote where user_id ='".$user_id."' ";
        $datas = $this->dbcon->Query($sql);

        return isset($datas['total'])?$datas['total']:"0";

    }

    public function totalMoney()
    {
        $total = 0;
        $sql = "Select B.*,SUM(B.bid_amount) as total from itf_bid B
                INNER JOIN itf_quote Q on Q.id = B.quote_id and Q.payment = 1
                where B.user_id = '".$_SESSION['FRONTUSER']['id']."'";
        $datas = $this->dbcon->FetchAllResults($sql);

        foreach($datas as $data)
        {
            $total += $data['bid_amount'];
        }


        return $total;
    }

    public function addMoney($datas)
    {
        unset($datas['id']);
        $this->dbcon->Insert('itf_money_request',$datas);
    }

    public function getAllTransactions()
    {
        $user_id = $_SESSION['FRONTUSER']['id'] ;
        $sql = "select O.*,Q.quote_name,Q.quote_desc,P.txn_id,P.date_added as payment_date,P.payment_status from itf_order O
                LEFT JOIN itf_quote Q on Q.id = O.quote_id
                LEFT JOIN itf_payment P on P.order_id = O.id
                 where O.user_id ='".$user_id."' and O.status = 1 order by O.id desc";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    function CheckState($id)
    {
        $sql="select * from itf_quote where id='".$id."'";
        return $this->dbcon->Query($sql);
    }
    //Function for change status
    function PublishBlock($ids)
    {

        $infos = $this->CheckState($ids);
        if($infos['approve']=='1')
            $datas=array('approve'=>'0');
        else
            $datas=array('approve'=>'1');

        $condition = array('id'=>$ids);
        $this->dbcon->Modify('itf_quote',$datas,$condition);

        return ($infos['approve']=='1')?"0":"1";

    }
    function CheckUser($id)
	{
         
		$sql="select * from itf_users where FIND_IN_SET($id,city_id)";
	 	return $this->dbcon->FetchAllResults($sql);
	}
        
        function CheckSupp($id)
	{
            
         
		  $sql="select  u.email from itf_bid b 
                    INNER JOIN itf_quote q on b.quote_id = q.id 
                    INNER JOIN itf_users u on q.user_id = u.id
                    WHERE b.quote_id = '".$id."'";
                
	 	return $this->dbcon->Query($sql);
	}
        
        function CheckWork($id)
	{
         
		$sql="select * from itf_users where registration_id='".$id."'";
	 	return $this->dbcon->Query($sql);
	}
        
        function GetEmail($id)
	{
		$sql="select * from itf_mails where id='".$id."'";
		return $this->dbcon->Query($sql);
	}

}
?>