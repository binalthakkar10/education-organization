
<script type="text/javascript" src="js/mootools-1.2.4-core-nc.js"></script>
<script type="text/javascript" src="js/smoothcalendar.js"></script>
<link href="css/smoothcalendar.css" rel="stylesheet" type="text/css" />
<link href="css/smoothcalendareditor.css" rel="stylesheet" type="text/css" />
<link href="css/smoothcalendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/smoothcalendar_editor.js"></script>


<script type="text/javascript">
    window.addEvent('domready', function() {
        var smoothCalendar = new SmoothCalendar("calendar");
    });

</script>

<div id="calendar"></div>

<link href="css/smoothcalendar.css" rel="stylesheet" type="text/css" />

<div class="create"> <h1>Add new event</h1>

<form id="create_form" method="POST" action="manager.php"> <p>
<label>Title *</label> </p>
<p>

<input name="title" class="input_title" type=\"text\"/> </p>
<p>
<label>Date *</label> </p>
<p>

<input name="date" id="create_date" class="input_date" type=\"text\"/>
</p>
<p>

<label>Description *</label> </p>
<p>
<textarea name="content" class="input_content" ></textarea> </p>
<div style="overflow:hidden;text-align:right">
<input type="reset" id="create_reset_button" value="Reset"/> <input type="button" id="create_submit_button" value="Submit"/>
</div>

<input type="hidden" name="event" value="create"> </form>
</div>
<div class="filter"> <h1>Filter events</h1>
<form id="filter_form" method="post" action="manager.php?event=filter"> <p>
<input name="date" id="filter_edit" type="text" value=""/> <input id="search_button" class="submit" type="submit"
value="Search"/>

<input id="upcoming_button" class="submit" type="button" value="Show up coming"/>
</p>
<input type="hidden" name="event" value="filter"/> </form>
</div>

<div id="msg_container"></div> <div id="editor"></div>

<script type="text/javascript">

make sure that you pass in the id of the DIV element 

that contains the calendar HTML. In this case the id is “editor” 

window.addEvent("domready", function(){ new SmoothCalendarEditor({
container : "editor"
});
});

</script>


