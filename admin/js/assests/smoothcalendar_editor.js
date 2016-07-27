var SmoothCalendarEditor = new Class({
    
    Implements : Options,
    events     : [],
    
    options : {
        container    : null,
        currentDate  : new Date(),
        msgContainer : "msg_container",
        url          : "smoothcalendar.php"
    },
    
    isIE : Browser.Engine.trident,
    
    initialize : function(options){
        this.setOptions(options);   
        this.container    = $(this.options.container   );
        this.msgContainer = $(this.options.msgContainer);        
        
        if (!this.container) {
            alert("Could not locate \"" + options.container + "\" element!");
            return;
        } else 
            this.container.set("class", "smoothcalendareditor");

        $("create_submit_button").addEvent("click" , this.createEvent .bind(this));
        $("upcoming_button"     ).addEvent("click" , this.showUpComing.bind(this));
        $("search_button"       ).addEvent("click" , this.filter      .bind(this));
        $("filter_form"         ).addEvent("submit", this.filter      .bind(this));

        $("create_date").addEvents({
            "click" : this.showDatePicker.bind(this),
            "focus" : this.showDatePicker.bind(this)
        });

        $("filter_edit").addEvents({
            "click" : this.showDatePicker.bind(this),
            "focus" : this.showDatePicker.bind(this)
        });

        this.load();
    },
    
    showUpComing : function() {
        $("filter_edit").set("value","");
        this.load();
    },
    
    showDatePicker : function(event) {
        event.stop();
        
        if (this.datePicker) {
            this.datePicker.destroy(event,true);
            this.datePicker = null;
        }

        var inputElement = event.target;
        
        function handleDateSelect(date) {
            inputElement.set("value", this.toDateString(date));
        }

        var position = event.target.getPosition();
        position.y += event.target.offsetHeight; 

        this.datePicker = new DatePicker();
        this.datePicker.addEvent("select", handleDateSelect.bind(this));
        this.datePicker.show(position);    
    },

    filter : function(event){
        event.stop();    
        var input = $("filter_edit");
        var date = (input.value == "") ? new Date() : new Date(input.value);

        if (date == "Invalid Date") {
            this.showError("date.invalid}");
            return;
        }
        
        var from = this.toDateString(date, true);
		var to = from + " 23:59";
        this.load(from, (input.value != "") ? to : null);
    },

    sortEvents : function(){
        var j, tmp, events;

        for (var i = 0; i < this.events.length; i++) {
            this.events[i].date = new Date(this.events[i].date);
        }
        
        events = this.events;
        
        for (var i = 1; i < events.length; i++) {
            tmp = this.events[i];
            for(j = i; j > 0 && events[j - 1].date > tmp.date; j--)
                events[j] = events[j-1];
            events[j] = tmp;
        }
    },
    
    load : function(from, to) {
        var data = {
            f : new Date().getTime(),
            event : "view"
        };
        data.from  = (from) ? from : this.toDateString(this.options.currentDate, true);

        if (to)
            data.to = to;

        new Request.JSON({
            method: "get",
            data: data,
            url: this.options.url,
            onSuccess : this.handleResponse.bind(this)
        }).send();
    },
    
    handleResponse : function(jsonResponse, textResponse){
        if (!jsonResponse.error) {
            this.events = jsonResponse;
            this.sortEvents(),
            this.render();
        } else 
            this.showError(jsonResponse.error);
    },
    
    render : function() {
        this.container.empty();
        
        if (this.events.length == 0) {
            this.showError("There are no events found.", true);        
            return;
        }
        
        for (var i = 0; i < this.events.length; i++) {
            var e = this.events[i];
            
            var html = "";
            html += "<p class=calendar_item_titlebar>";
            html +=    "<span class=\"calendar_item_title\">" + e.content + "</span>";
            html +=    "<span class=\"calendar_item_date\">(" + this.toDateString(e.date, false, true) + ")</span>";
            html += "</p>";
            html += "<div class=\"calendar_item_edit\">";
            html +=    "<form id=\"edit" + i + "\" method=\"POST\" action=\"smoothcalendar.php\">";
            html +=      "<p><label>Date *</label></p>";
            html +=      "<p><input name=\"date\" class=\"input_date\" type=\"text\"/></p> ";
            html +=      "<p><label>Time *</label></p>";
			html +=      "<p><select name=\"hours\" class=\"input_hours\"><option>00</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option><option>22</option><option>23</option><option>24</option></select> ";
			html +=      ": <select name=\"minutes\" class=\"input_minutes\"><option>00</option><option>05</option><option>10</option><option>15</option><option>20</option><option>25</option><option>30</option><option>35</option><option>40</option><option>45</option><option>50</option><option>55</option></select></p>";
            html +=      "<p><label>Description *</label></p>";
            html +=      "<p><textarea name=\"content\" class=\"input_content\" ></textarea></p>";
            html +=      "<div style=\"overflow:hidden;text-align:right\"><button class=\"delete_button\">Delete</button><button class=\"submit_button\">Submit</button><button class=\"cancel_button\">Cancel</button></div>";
            html +=      "<input type=\"hidden\" name=\"event\" value=\"edit\">";
            html +=      "<input type=\"hidden\" name=\"id\" value=\"" + e.id + "\">";
            html +=    "</form>";
            html += "</div>";
            
            var item = new Element("div", {
                "class": "calendar_item",
                "html": html
            });
            
			var titlebar = item.getElements(".calendar_item_titlebar")[0];
            titlebar.addEvent("click", this.showEditor.bind(this));
            titlebar.eid = e.id;
            
            var caption = item.getElements(".calendar_item_title")[0];
            caption.addEvent("click", this.showEditor.bind(this));
            caption.eid = e.id;

            var captiondate = item.getElements(".calendar_item_date")[0];
            captiondate.addEvent("click", this.showEditor.bind(this));
            captiondate.eid = e.id;

            item.data = e;
            this.container.adopt(item);
            item.getElements(".calendar_item_edit")[0].slide("hide");

            item.getElements(".input_date")[0].addEvents({
                "click" : this.showDatePicker.bind(this),
                "focus" : this.showDatePicker.bind(this)
            });

            var cancelButton = item.getElements(".cancel_button")[0];
            var submitButton = item.getElements(".submit_button")[0];
            var deleteButton = item.getElements(".delete_button")[0];
            
            cancelButton.addEvent("click", this.showEditor.bind(this));
            cancelButton.eid = e.id;
            submitButton.addEvent("click", this.edit.bind(this));
            submitButton.eid = i;
            
            deleteButton.addEvent("click", this.remove.bind(this));
            deleteButton.eid = e.id;
        }
    },
    
    showError : function(message, isNote){
        var text = message;
        if (!isNote)
            text = (this.errors[message]) ? this.errors[message] : "Internal error. Please contact the administrator"; 
 
        if (!this.msgContainer) {
            alert(text);
            return;
        }

        var message = new Element("div",{"class":"message", "text": text});
        if (!isNote) 
            message.addClass("error");        
            
        this.msgContainer.adopt(message);    
        
        (function(){
            message.dispose();
        }).delay(7000, this);
    },

    getElementByEventId : function(id){
        for (var i = 0; i < this.container.childNodes.length; i++)
            if (this.container.childNodes[i].data.id == id) 
                return this.container.childNodes[i];
    },
    
    showEditor : function(event){
        event.stop();

        var itemElement = this.getElementByEventId(event.target.eid);
        if (!itemElement)
            return;

        var textarea = itemElement.getElements(".input_content" )[0];
        var date     = itemElement.getElements(".input_date"    )[0]; 
        var hours    = itemElement.getElements(".input_hours"   )[0]; 
        var minutes  = itemElement.getElements(".input_minutes" )[0]; 
        var event    = itemElement.data;
        
        textarea.value = itemElement.data.content;
        date    .value = this.toDateString(event.date);
        
        hours   .selectedIndex = this.getIndexOfOption(hours, event.date.getHours());
        minutes .selectedIndex = this.getIndexOfOption(minutes, event.date.getMinutes());

        var editElement = itemElement.getElements(".calendar_item_edit")[0];
        var fx = new Fx.Slide(editElement, {duration: 200});
        fx.toggle();
    },
    
    getIndexOfOption : function(select, value)
    {
    	for(var i = 0; i < select.options.length; i++)
    		if (select.options[i].text == value)
    			return i;
    	return -1;
    },
    
    remove : function(event) {
        event.stop();
        
        var index = event.target.eid;
        var itemElement = this.getElementByEventId(event.target.eid);
        if (!itemElement)
            return;
            
        if (confirm("Are you sure you want to delete this event?") == false)
        	return;    
        
        new Request.JSON({
            url: this.options.url,
            method: "get",
            data: {
                f : new Date().getTime(),
                event: "remove",        
                id: itemElement.data.id
            },
            onSuccess : function(jsonResponse){
                if (jsonResponse.error) {
                    this.showError(jsonResponse.error);
                } else {
                    this.showError(jsonResponse.message, true);
                    var toDestroy = this.getElementByEventId(jsonResponse["removed"]);
                    if (toDestroy)
                        toDestroy.dispose();
                }
            }.bind(this)
        }).send();
    },
    
    edit : function(event) {
        event.stop();    
        var eid = event.target.eid;
        var form = $("edit" + eid);
        if (!form)
            return;

        var dateInput = form.getElements(".input_date"   )[0];
        var hours     = form.getElements(".input_hours"  )[0];
        var minutes   = form.getElements(".input_minutes")[0];
        var date      = this.toDate(dateInput.value);

        if (!date) {
            this.showError("date.invalid");
            return;
        } else {
			date.setHours(hours.options[hours.selectedIndex].text);
			date.setMinutes(minutes.options[minutes.selectedIndex].text);
            dateInput.value = this.toDateString(date, true, true);
        }

        form.set("send", {
            onComplete: function(response){
                var jsonResponse = eval("(" + response + ")");
                if (jsonResponse.error){
                    this.showError(jsonResponse.error);
                } else {
                    this.showError(jsonResponse.message, true);
                    this.load();
                }
            }.bind(this)
        });

        form.send();
        dateInput.value = this.toDateString(date);
    },
    
    createEvent : function(event){
        event.stop();    
        var form = $("create_form");

        if (!form)
            return;

        var dateInput = $("create_date");
        var hours     = $("create_hours");
        var minutes   = $("create_minutes");
        var date      = this.toDate(dateInput.value);

        if (!date) {
            this.showError("date.invalid");
            return;
        } else {
			date.setHours(hours.options[hours.selectedIndex].text);
			date.setMinutes(minutes.options[minutes.selectedIndex].text);
            dateInput.value = this.toDateString(date, true, true);
        }
        
        form.set("send", {
            onComplete: function(response){
                var jsonResponse = eval("(" + response + ")");
                if (jsonResponse.error){
                    this.showError(jsonResponse.error);
                } else {
                    this.load();
                    form.reset();
                    this.showError(jsonResponse.message, true);
                }
            }.bind(this)
        });

        form.send();
        dateInput.value = this.toDateString(date);
    },

    toDate : function(str) {
        var result = new Date(str);
        return (result == "Invalid Date" || result == "NaN") ? false : result;
    },
    
    toDateString : function(date, toSubmit, withTime) {
     	var sep = (!toSubmit) ? "/" : "-";
        var result = date.getFullYear() + sep + (date.getMonth() + 1) + sep + date.getDate();

        if (withTime)
        {
        	result += " " + date.getHours();
        	result += ":" + ((date.getMinutes() < 10) ? "0" + date.getMinutes() : date.getMinutes());
        }
	        
	    return result;    
    }
});

SmoothCalendarEditor.implement({
    errors : {
        "view.unspecified_event" : "There was an internal problem. Please contact the administrator.",
        "view.disabled"          : "Viewing events is disabled!",
        "view.date_range"        : "There was an internal problem. Please contact the administrator.",
        "delete.disabled"        : "Deleting events is disabled!",
        "delete.badrequest"      : "There was an internal problem. Please contact the administrator.",
        "edit.disabled"          : "Editing events is disable!",
        "edit.badrequest"        : "There was a problem. Either the date or the description is missing.",
        "date.invalid"           : "Invalid date input.",
        "create.disabled"        : "Creating events is disabled",
        "create.badrequest"      : "There was a problem. Either the date or the description is missing."
    }
});

var DatePicker = new Class({
    Implements   : Events, 
    daysInMonth  : [31,28,31,30,31,30,31,31,30,31,30,31],
    weekDays     : ["s","m","t","w","t","f","s"],
    monthsOfYear : ["January","February","March","April","May","June","July","August","September","October","November","December"],
    dayNumbers   : {"sunday" : 0, "monday" : 1, "tuesday" : 2, "wednesday" : 3, "thursday" : 4, "friday" : 5, "saturday" : 6},
   
    initialize : function() {
        this.currentDate = new Date();
        this.container   = new Element("div", {"class" : "datepicker"});
        this.generate();
        $$(document.body).adopt(this.container);
        $(document).addEvent("mousedown", this.destroy.bind(this));
    },

    firstDayOfMonth : function (dateObject){
        dateObject.setDate(1);
        return dateObject.getDay();
    },

    numberOfDaysInMonth : function(dateObject){
        var month = dateObject.getMonth();
        if (month == 1){
            var leapYear = (new Date(dateObject.getYear(),1,29).getDate()) == 29;
            if (leapYear) 
                return 29; 
            else 
                return 28;
        } else return this.daysInMonth[month];
    },

    getDayNames : function() {
        var days = [];    
        var day = null;
        for (var i = 0; i < this.weekDays.length; i++){
            days.push(new Element('div',{'html': this.weekDays[i], 'class':'dayNames'}));
        }
        return days;
    },

    getArrows : function(){
        var previousMonth = new Element("div", {"text":" ", "class" : "previousMonth"});
        var nextMonth     = new Element("div", {"text":" ", "class" : "nextMonth"});
        
        previousMonth.addEvent("click", function(e){
            e.stop();
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.generate();
        }.bind(this));

        nextMonth.addEvent("click", function(e){
            e.stop();
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.generate();
        }.bind(this));

        return {
            previousMonth : previousMonth,
            nextMonth : nextMonth
        };
    },

    select : function(event) {
        event.stop();
        this.currentDate.setDate(event.target.get("text"));
        this.fireEvent("select", this.currentDate);
        this.destroy(event, true);
    },

    generate : function() {
        var firstDayOfMonth    = this.firstDayOfMonth    (this.currentDate);
        var totalDaysInMonth   = this.numberOfDaysInMonth(this.currentDate);
        var dayBox             = null;
        var currentPrintedDays = 0;
        var actualPrintedDays  = 0;
        var columns            = [];
        var arrows             = this.getArrows();
        this.dateText          = new Element("div",{"class": "dateText"});
        this.dateText.set("text", this.monthsOfYear[this.currentDate.getMonth()] + " " + this.currentDate.getFullYear());

        for (var i = 0; i < 7; i++)
            columns.push(new Element('div',{'class':'columns'}));

        var weekDays = this.getDayNames();
        for (i = 0; i < weekDays.length; i++)
            columns[i].adopt(weekDays[i]);    

        var ownerColumnIndex = 0;
        while (actualPrintedDays != totalDaysInMonth && currentPrintedDays != (totalDaysInMonth + firstDayOfMonth + Math.ceil(totalDaysInMonth / 7))) {
            columnIndex = (currentPrintedDays % 7 == 0) ? 0 : columnIndex + 1;
            dayBox      = new Element('div');

            if (currentPrintedDays >= firstDayOfMonth && actualPrintedDays <= totalDaysInMonth - 1) {

                dayBox.addEvent("click", this.select.bind(this));
                
                dayBox.set({
                    "text": actualPrintedDays + 1,
                    "class": "dayBox"
                });
                actualPrintedDays++;
            } else {
                dayBox.set({
                    "html": "&nbsp;",
                    "class": "emptyBox"
                });
            }

            currentPrintedDays++;
            columns[columnIndex].adopt(dayBox);
        }
        
        this.container.empty();
        this.container.adopt([this.dateText, arrows.previousMonth, columns, arrows.nextMonth]);
    },
    
    show : function(position) {
        this.generate(0);    
        this.container.setStyles({
            "display" : "block",
            "left" : position.x + "px",
            "top" : position.y + "px"
        });
        
    },
    
    destroy : function(e, force) {
        var clickOutside = ($chk(e) && e.target != this.container && !this.container.hasChild(e.target));
        if (clickOutside || force) {
            this.container.setStyle("display", "none");
            this.removeEvents("select");
            this.container.dispose();
            this.currentDate = null;
        }
    }
});
