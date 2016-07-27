<?php
//$server   = "[SERVER LOCATION]";
//$username = "[USERNAME]"; 
//$password = "[PASSWORD]";
//$dbname   = "[DATABASE NAME]";

class SmoothCalendar {

    private $options = null,
            $get,
            $post;

    //private $connection;

    function __construct($options) {
        
//        $this->connection = mysql_connect($GLOBALS["server"  ],
//                                          $GLOBALS["username"],
//                                          $GLOBALS["password"]);
//
//        mysql_select_db($GLOBALS["dbname"],$this->connection);

        
        global $itfmysql;
		$this->dbcon=$itfmysql;
        
        
        $this->options = array_merge(array(
            'dateFormat' => "%a %b %d %Y %H:%M:%S",
            'safe'       => true,
            'view'       => true,
            'destroy'    => false,
            'edit'       => false,
            'create'     => false
        ), $options);
        
        $this->post = $_POST;
        $this->get  = $_GET;
    }
    

    function query($query) {
        $result = mysql_query($query, $this->connection);
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        return $result;
    }
    
    function end() {
        mysql_close($this->connection);
    }

    function __destruct() {
        $this->end();
        unset($this->connection);
    }
    
  	public function fireEvent($event){
        if (!$event) {
            $this->throw_server_exception("view.unspecified_event");
            return;
        }

		$event = $event ? 'on'.ucfirst($event) : null;
		if (!$event || !method_exists($this, $event)) 
		    $event = 'onView';
		$this->{$event}();
	}

    private function throw_server_exception($message) {
        echo json_encode(array(
            "error" => $message
        ));
    }

    private function onView() {
        if (!$this->options["view"]) {
            $this->throw_server_exception("view.disabled");
            return;
        }

        $from_date = (isset($this->get["from"])) ? $this->get["from"] : null;
        $to_date   = (isset($this->get["to"])) ? $this->get["to"] : null;

        if (!$from_date) {
            $this->throw_server_exception("view.date_range");
            return;
        }

        if ($to_date)
           $sql = "SELECT * FROM `events` WHERE `Date` >= '$from_date' AND `Date` <= '$to_date'";
        else
           $sql = "SELECT * FROM `events` WHERE `Date` >= '$from_date'";

        $data   = array();
        $result = $this->query($sql);

        while ($row = mysql_fetch_assoc($result)) {
            $data[sizeof($data)] = array(
                "id" => $row["ID"],
                "content" => $row["Description"],
                "date" => strftime($this->options["dateFormat"] ,strtotime($row["Date"]))
            );            
        }
  		echo json_encode($data);
    }
    
    private function onRemove() {
        if (!$this->options["remove"]) {
            $this->throw_server_exception("delete.disabled");
            return;
        }

        if (!isset($this->get["id"])) {
            $this->throw_server_exception("delete.badrequest");
            return;
        }
        
        $id     = $this->get["id"];
        $sql    = "DELETE FROM `events` WHERE `ID` = $id";
        $result = $this->query($sql);

        echo json_encode(array(
            "message" => "Removed successfully",
            "removed" => $id
        ));
    }
    
    private function onEdit() {
        if (!$this->options["edit"]) {
            $this->throw_server_exception("edit.disabled");
            return;
        }
        
        $id      = $this->post["id"  ];
        $date    = $this->post["date"];
        $content = mysql_real_escape_string($this->post["content"]);
        
        if (!$id || !$date) {
            $this->throw_server_exception("edit.badrequest");
            return;
        }

        if ($this->options["safe"])
            $content = $this->strip_html_tags($content);

        $sql = "UPDATE `events` SET `description` = '$content', `date` = '$date' WHERE `ID` = $id";  
        $result = $this->query($sql);
        echo json_encode(array("message" => "Edited successfully"));
    }
    
    private function onCreate(){
        if (!$this->options["create"]){
            $this->throw_server_exception("create.disabled");
            return;
        }

        $content = stripslashes($this->post["content"]);
        $content = mysql_real_escape_string($content);
        $date    = $this->post["date"   ];
      
        if (!$content || !$date) {
            $this->throw_server_exception("create.badrequest");
            return;
        }
        
        if ($this->options["safe"])
            $content = $this->strip_html_tags($content);
        
        $sql = "INSERT INTO `events` (`description`, `date`) VALUES('$content','$date')";  
        $result = $this->query($sql);
        echo json_encode(array("message" => "Created new event"));
    }
    
    private function strip_html_tags($text){
        $text = preg_replace(
            array(
              // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
              // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $text );
        return strip_tags($text);
    }
}

$calendar = new SmoothCalendar(array(
    'view'   => true,
    'remove' => true,
    'edit'   => true,
    'create' => true,
    'safe'   => false
));

$event = (isset($_GET["event"])) ? $_GET["event"] : ((isset($_POST["event"])) ? $_POST["event"] : null);
$calendar->fireEvent($event);
?>