<?php
class Report
{
	
	function __construct()
	{
		global $itfmysql;
		$this->dbcon=$itfmysql;
	}
		
	function showAllOrders()
    {
        $sql="select O.*,D.bid_id,B.bid_desc,U.name as order_user,UD.name as bid_user,Q.quote_name  from itf_order O
              LEFT JOIN itf_order_detail D on D.order_id = O.id
              LEFT JOIN itf_bid B on B.id = D.bid_id
              LEFT JOIN itf_users U on U.id = O.user_id
              LEFT JOIN itf_users UD on UD.id = B.user_id
              LEFT JOIN itf_quote Q on Q.id = O.quote_id
              where O.status='1' order by O.id desc";

        return $this->dbcon->FetchAllResults($sql);
    }

    function deleteOrder($id)
    {
        $sql="delete from itf_order_detail where order_id in(".$id.")";
        $this->dbcon->Query($sql);

        $sql="delete from itf_order where id in(".$id.")";
        $this->dbcon->Query($sql);

        return $this->dbcon->querycounter;
    }

    function showAllTransactions()
    {
        $sql="select P.*,O.quote_id,O.date_added as order_date,Q.quote_name,U.name as order_user from itf_payment P
              LEFT JOIN itf_order O on O.id = P.order_id
              LEFT JOIN itf_quote Q on Q.id = O.quote_id
              LEFT JOIN itf_users U on U.id = Q.user_id
              order by P.id desc";

        return $this->dbcon->FetchAllResults($sql);
    }

    function showAllEnquiries()
    {
        $sql="select E.*,U.name,L.name as location from itf_quote E
              INNER JOIN itf_users U on U.id = E.user_id
              LEFT JOIN itf_state L on L.id = E.location
              order by E.id desc";

        return $this->dbcon->FetchAllResults($sql);
    }

    function showBidsByQuote($quote_id)
    {
        $sql = "select B.*,Q.quote_name,Q.quote_desc,U.registration_id as supplier_id,U.name as supplier_name from itf_bid B
                LEFT JOIN itf_quote Q ON Q.id = B.quote_id
                LEFT JOIN itf_users U ON U.id = B.user_id
                where B.quote_id ='".$quote_id."' ";
        $datas = $this->dbcon->FetchAllResults($sql);
        return $datas;
    }

    public function showQuoteChat($quote_id)
    {
        $sql = "select Q.chat_text,UNIX_TIMESTAMP(Q.date_added) as added_date,U.name,UP.profile_image,U.registration_id from itf_quote_chat Q
                LEFT JOIN itf_users U ON Q.user_id = U.id
                LEFT JOIN itf_user_profile UP ON U.profile_id = UP.id
                where Q.quote_id ='".$quote_id."' and Q.status = 1 order by Q.id asc";
        $datas = $this->dbcon->FetchAllResults($sql);

        return $datas;
    }

    function showQuoteByAccount()
    {
   $sql="select O.*,U.name as order_user,D.bid_id,B.bid_desc,Q.id as quote_id,Q.quote_name,Q.quote_status,Q.approve   
                  from itf_order O
                  INNER JOIN itf_users U on U.id = O.user_id
                  LEFT JOIN itf_order_detail D on D.order_id = O.id
                  LEFT JOIN itf_bid B on B.id = D.bid_id
                  LEFT JOIN itf_quote Q on Q.id = O.quote_id
            
              where O.status='0' and U.payment_type='account' order by O.id desc";
       
        return $this->dbcon->FetchAllResults($sql);

    }

    function deleteQuote($id)
    {
        $sql="delete from itf_quote_detail where quote_id in(".$id.")";
        $this->dbcon->Query($sql);

        $sql="delete from itf_bid where quote_id in(".$id.")";
        $this->dbcon->Query($sql);

        $sql="delete from itf_quote where id in(".$id.")";
        $this->dbcon->Query($sql);

        $sql="delete from itf_order where quote_id in(".$id.")";
        $this->dbcon->Query($sql);


        $sql="delete from itf_quote_chat where quote_id in(".$id.")";
        $this->dbcon->Query($sql);

        $sql="delete from itf_active_quote_chat where quote_id in(".$id.")";
        $this->dbcon->Query($sql);

        $sql="delete from itf_quote_review where quote_id in(".$id.")";
        $this->dbcon->Query($sql);

        return $this->dbcon->querycounter;
    }
	
	
}
?>