var SmoothCalendar = new Class({

    MONTHLY_VIEW         : 0,
    DAILY_VIEW           : 1,
    events               : [],
    daysInMonth          : [31,28,31,30,31,30,31,31,30,31,30,31],
    weekDays             : ["sunday","monday","tuesday","wednesday","thursday","friday","saturday"],
    monthsOfYear         : ["January","February","March","April","May","June","July","August","September","October","November","December"],
    dayNumbers           : {"sunday" : 0, "monday" : 1, "tuesday" : 2, "wednesday" : 3, "thursday" : 4, "friday" : 5, "saturday" : 6},
    isIE6                : (Browser.Engine.trident && Browser.Engine.version == 4), 
    isIE                 : (Browser.Engine.trident), 
    container            : null,
    monthlyViewContainer : null,
    dailyViewContainer   : null,
    currentDate          : null,
    dateBeingViewed      : null,
    dayBeingViewed       : null,
    boxingWidth          : null,
    EVENTBOARD_PADDING   : 30,
    
    initialize : function(container, startDate){
        this.container   = container;
        this.currentDate = (startDate) ? startDate : new Date(); 

        if ($type(this.container) != 'element')
            this.container = $(this.container);
        this.container.set('class','smoothcalendar');
        this.load();        
    },

    load : function(){ 
        new Request.JSON({
            method: "get",
            url: "smoothcalendar.php",
            data: {
                event: "view", 
                from: this.currentDate.getFullYear() + "-" + this.currentDate.getMonth() + "-01 00:00:00"
            },
            onSuccess : function(responseJSON){
                this.events = responseJSON;
                this.sortEvents();
                this.generateCalendar();
            }.bind(this)
        }).send();      
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

    dayHasEvents : function(dayOfMonth) {
        var year  = this.currentDate.getFullYear();
        var month = this.currentDate.getMonth();
        var date; 

        for (var i = 0; i < this.events.length; i++){
             date = this.events[i].date;
             if (date.getFullYear() == year && date.getMonth() == month && date.getDate() == dayOfMonth) {
                 return true;
             }
        }
        
        return false;
    },

    getEventsOfDay : function(dayOfMonth) {
        var year  = this.currentDate.getFullYear();
        var month = this.currentDate.getMonth();
        var event, result = []; 

        for (var i = 0; i < this.events.length; i++){
             event = this.events[i];

             if (event.date.getFullYear()  == year && 
                 event.date.getMonth() == month && 
                 event.date.getDate()  == dayOfMonth) {
                 result.push(event);
             }
        }
        return result;
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
    
    firstDayOfMonth : function (dateObject){
        dateObject.setDate(1);
        return dateObject.getDay();
    },
    
    getDayContent : function (dayNumber) {
        var eventCount  = this.getEventsOfDay(dayNumber).length;
        var dayNumberEl = new Element("p", {'class':'day_number'     , 'html':((dayNumber  < 10) ? '0' + dayNumber          : dayNumber)});
        var dayCountEl  = new Element("p", {'class':'day_event_count', 'html':((eventCount != 0) ? eventCount + ' Event(s)' : '&nbsp;' )});
        return [dayNumberEl, dayCountEl]; 
    },

    isToday : function (dayNumber) {
        var today = new Date();
        return (today.getDate() == dayNumber && today.getFullYear() == this.currentDate.getFullYear() && today.getMonth() == this.currentDate.getMonth());
    },

    getNavigationRow : function() {
        var row    = new Element('div', {'class':'navigations' });
        var center = new Element('div', {'class':((!this.isIE6) ? 'current_date' : 'current_date_IE6')});
        var left   = new Element('div');
        var right  = new Element('div');

        var width  = Math.floor(this.container.offsetWidth / 3);
        left  .setStyle('width', width + 'px');
        right .setStyle('width', width + 'px');
        center.setStyle('width', width + 'px');
        
        var dateString = this.monthsOfYear[this.currentDate.getMonth()] + ', ' + this.currentDate.getFullYear();

        var prevYear   = new Element("a", {id:'previousYear' , html:"&nbsp;",href:"Previous Year" ,title:"Previous Year" });
        var prevMonth  = new Element("a", {id:'previousMonth', html:"&nbsp;",href:"Previous Month",title:"Previous Month"});
        var nextMonth  = new Element("a", {id:'nextMonth'    , html:"&nbsp;",href:"Next Month"    ,title:"Next Month"    });
        var nextYear   = new Element("a", {id:'nextYear'     , html:"&nbsp;",href:"Next Year"     ,title:"Next Year"     });

        prevYear .addEvent('click', this.prevYear .bind(this));
        prevMonth.addEvent('click', this.prevMonth.bind(this));
        nextYear .addEvent('click', this.nextYear .bind(this));
        nextMonth.addEvent('click', this.nextMonth.bind(this));

        center.set('html',dateString);        
        left  .adopt([prevYear , new Element('span',{'html':'&nbsp;&nbsp;'}) , prevMonth]);        
        right .adopt([nextMonth, new Element('span',{'html':'&nbsp;&nbsp;'}) , nextYear ]);        
        
        row.adopt([left, center, right]);
        return row;
    },
    
    adjustWidthOfBox : function(element) {
        var value  = (this.container.offsetWidth / 7) - 1;
        element.setStyles({'width': (this.container.offsetWidth / 7) - 1 + 'px'});
    },

    getDayNames : function() {
        var days = [];    
        var day = null;
        for (var i = 0; i < this.weekDays.length; i++){
            days.push(new Element('div',{'html': this.weekDays[i], 'class':'dayNames'}));
        }
        return days;
    },
    
    getWeekDaysRow : function() {
        var row                = new Element("div", {'class':'weekDays'}); 
        var firstDayOfMonth    = this.firstDayOfMonth    (this.currentDate);
        var totalDaysInMonth   = this.numberOfDaysInMonth(this.currentDate);
        var dayBox             = null;
        var currentPrintedDays = 0;
        var actualPrintedDays  = 0;
        var columns = [];

        for (var i = 0; i < 7; i++){
            columns.push(new Element('div',{'class':'columns'}));
            this.adjustWidthOfBox(columns[i]);
        }    

        var weekDays = this.getDayNames();
        for (i = 0; i < weekDays.length; i++){
            columns[i].adopt(weekDays[i]);    
        }

        var ownerColumnIndex = 0;
        while (actualPrintedDays != totalDaysInMonth && currentPrintedDays != (totalDaysInMonth + firstDayOfMonth + Math.ceil(totalDaysInMonth / 7))) {
            
            dayBox      = new Element('div');
            columnIndex = (currentPrintedDays % 7 == 0) ? 0 : columnIndex + 1;
            
            if (currentPrintedDays >= firstDayOfMonth && actualPrintedDays <= totalDaysInMonth - 1) {
                dayBox.adopt(this.getDayContent(actualPrintedDays + 1));

                if (this.dayHasEvents(actualPrintedDays + 1)) {
                    dayBox.addEvent('mouseover',this.onCalendarDayMouseOver.bind(this));
                    dayBox.addEvent('mouseout' ,this.onCalendarDayMouseOut.bind(this));
                    dayBox.addEvent('click'    ,this.onCalendarDayMouseClick.bind(this));
                    
                    if (this.isToday(actualPrintedDays + 1))
                        dayBox.set('class','day_content_today_with_event');
                    else
                        dayBox.set('class','day_content_with_event');
                        
                } 
                else 
                {
                    if (this.isToday(actualPrintedDays + 1))
                        dayBox.set('class','day_content_today');
                    else {
                        dayBox.set('class','day_content');
                    }    
                }

                actualPrintedDays++;
            } else {
                dayBox.set('html','<p class="day_number">&nbsp</p><p class="day_event_count">&nbsp</p>');
                dayBox.set('class','emptyBox');
            }

            currentPrintedDays++;
            columns[columnIndex].adopt(dayBox);
        }
        
        row.adopt(columns);
        return row;
    },
    
    generateStackPanels : function()
    {
        this.monthlyViewContainer = new Element("div", { "class" : "monthly-view" });
        this.dailyViewContainer   = new Element("div", { "class" : "daily-view" });
        
        this.monthlyViewContainer.adopt(this.getNavigationRow());
        this.monthlyViewContainer.adopt(this.getWeekDaysRow  ());

        this.container.adopt([
            this.dailyViewContainer, 
            this.monthlyViewContainer 
        ]);

        this.positionStackPanels();
    },

    positionStackPanels : function()
    {
        var padding = this.dailyViewContainer.getStyle("padding").toInt() * 2; 
        var height  = this.monthlyViewContainer.offsetHeight;
        var width   = this.monthlyViewContainer.offsetWidth;
    
        this.monthlyViewContainer.setStyles({
            "position" : "absolute",
            "top"      : "0px",
            "height"   : height + "px",
            "width"    : width + "px"
        });

        this.dailyViewContainer.setStyles({
            "position" : "absolute",
            "top"      : -height + "px",
            "height"   : (height - padding) + "px",
            "width"    : (width - padding) + "px"
        });

        this.container.setStyles({ "height" : height + "px" });
    },

    generateCalendar : function() {
        this.container.empty();
        this.generateStackPanels();
    },

    extractTargetFromEvent : function(event) {
        var container = ((event.target.nodeName == 'P') ? event.target.parentNode : event.target);
        return container;
    },
    
    onCalendarDayMouseOver : function(event) {
        if (this.dayBeingViewed)
            return;
        var container = this.extractTargetFromEvent(event); 
        if (!container) 
            return;
        var day = container.getFirst();
        var dayNumber = day.get("text").toInt();
        
        if (this.isToday(dayNumber))
            container.morph(".day_content_today_with_event_mouseover");
        else
            container.morph(".day_content_with_event_mouseover");
    },
    
    onCalendarDayMouseOut : function(event) {
        if (this.dayBeingViewed)
            return;
        var container = this.extractTargetFromEvent(event); 
        if (!container) 
            return;
        var day = container.getFirst();
        var dayNumber = day.get("text").toInt();
        
        if (this.isToday(dayNumber))
            container.morph(".day_content_today");
        else
            container.morph(".day_content_with_event");
    },
    
    onCalendarDayMouseClick : function (event) {
        if (this.dayBeingViewed)
            return;
        var container = this.extractTargetFromEvent(event); 
        if (!container) 
            return;
        var day = container.getFirst();
        var dayNumber = day.get('text');
        
        dayNumber = (dayNumber[0] == '0') ? dayNumber.substr(1,dayNumber.length).toInt() : dayNumber.toInt();

        this.dateBeingViewed = this.currentDate;
        this.dateBeingViewed.setDate(dayNumber);
        this.animateOpenningDayEvents(dayNumber);
    },
    
    animateOpenningDayEvents : function (dayNumber, target) {
        var eventsOfDay = this.getEventsOfDay(dayNumber);
        
        if (eventsOfDay && eventsOfDay.length == 0)
            return;        
            
        if (!target) {
            var paragraphs = this.container.getElements('p');
            for (var i = 0; i < paragraphs.length; i++){
                 target = (paragraphs[i].get('text') == dayNumber) ? paragraphs[i].parentNode : null;       
                 if (target) 
                     break;
            } 
            if (!target) return;
        }
        
        var children = target.getChildren();
        
        for(var i = 0; i < children.length; i++) 
            this.dailyViewContainer.adopt(children[i].clone());
        
        this.dayBeingViewed = {day : dayNumber, target : target, animationEl : this.dailyViewContainer};
        this.animateStackPanelIntoView(this.DAILY_VIEW, this.onOpenningDayEventAnimationComplete.bind(this));
    },

    animateStackPanelIntoView : function(stackIndex, onComplete)
    {
        var height = this.monthlyViewContainer.offsetHeight;

        this.monthlyViewContainer.set('morph', {transition: Fx.Transitions.Sine.easeOut, duration : 800});
        this.dailyViewContainer  .set('morph', {transition: Fx.Transitions.Sine.easeOut, duration : 800});

        var morph0 = this.monthlyViewContainer.get("morph");
        var morph1 = this.dailyViewContainer  .get("morph");

        if (onComplete)
            morph0.onComplete = onComplete;

        switch(stackIndex)
        {
            case 0:
                morph0.start({"top" : "0px", "opacity" : "1"         });
                morph1.start({"top" : -height +"px", "opacity" : "0" });
            break;        

            case 1:
                morph0.start({"top" : height + "px", "opacity" : "0" });
                morph1.start({"top" : "0px", "opacity" : "1"         });
            break;        
        }
    },
    
    getTimeOfEvent : function(date)
    {
        var result = "";
        var h      = date.getHours();
        var m      = date.getMinutes();
        
        result += ((h > 12) ? h - 12 : h);
        result += ":" + ((m >= 10) ? m : "0" + m); 
        result += ((h > 12) ? " pm" : " am");
        
        return result;
    },
    
    onOpenningDayEventAnimationComplete : function () {

        with (this.dateBeingViewed) {
             var dayText = this.weekDays[getDay()] + ' ' + 
                           this.monthsOfYear[getMonth()] + ' ' + 
                           getDate() + ', ' + 
                           getFullYear();
        } 

        var animationPanel = this.dayBeingViewed.animationEl;
        var dayNumber      = animationPanel.getFirst();
        var eventCountText = animationPanel.childNodes[1];
        var fullDayText    = dayNumber.cloneNode(true);
        fullDayText.set({html:dayText.toUpperCase(), 'id': 'fullDateText', 'style' : '', 'class' : ''});
 
        var div = new Element('div');
        animationPanel.insertBefore(div, animationPanel.getFirst());
        div.setStyles({'height' : dayNumber.offsetHeight + 'px', 'overflow' : 'hidden', 'position' : 'relative'});
        div.adopt([dayNumber, fullDayText]);
        
        dayNumber  .setStyles({'position' : 'absolute', 'top' : '0px', 'left' : '0px'});
        fullDayText.setStyles({'position' : 'absolute', 'top' : dayNumber.offsetHeight + 'px', 'left' : '0px'});
        
        var eventListContainer        = new Element('div', {id : 'eventListContainer'  });
        this.dayBeingViewed.eventList = eventListContainer;

        
        var eventsOfDay        = this.getEventsOfDay(this.dateBeingViewed.getDate());
        
        var closeElement = new Element('a', {'id':'smoothcalendarclose', 'html':'&times;', 'href':'javascript:;'});
        closeElement.addEvent('click', this.onCloseClick.bind(this));
        animationPanel.adopt(closeElement);
        
        for (var i = 0; i < eventsOfDay.length; i++){
            var e = eventsOfDay[i];
            
            eventListContainer.adopt([
                new Element('p', { "class" : "event-time"   , "text" : this.getTimeOfEvent(e.date) }),
                new Element('p', { "class" : "event-details", "text" : e.content                   })
            ]);
        }
        
        eventListContainer.setStyles({'visibility':'hidden','opacity':'0','overflow':'auto' });
        animationPanel.adopt(eventListContainer);    

        var fx = new Fx.Morph(dayNumber  ,{duration: 500}).start({'top': -dayNumber.offsetHeight + 'px'});
        var fx = new Fx.Morph(fullDayText,{duration: 500}).start({'top': '0px'});

        eventListContainer.fade('in', 500);
    },
        
    onGoBackClick : function(event) {
        var listContainer   = this.dayBeingViewed.listContainer;
        var detailContainer = this.dayBeingViewed.detailContainer;

        with (listContainer.get('morph')) {
            set({transition : Fx.Transitions.Elastic.easeOut});
            options.duration = 300;
            start({'left': '0px'});
        }
        
        with (detailContainer.get('morph')) {
            set({transition : Fx.Transitions.Elastic.easeOut});
            options.duration = 300;
            start({'left': listContainer.parentNode.offsetWidth + 'px'});
        }
        
        event.target.setStyle('display','none');
    },
    
    onCloseClick : function(event) {
        this.dayBeingViewed.closingStage = 0;
        this.animateClosingDayEvents(event);
    },
    
    animateClosingDayEvents : function(event) {
        var animationPanel  = this.dayBeingViewed.animationEl;
        var detailContainer = this.dayBeingViewed.detailContainer;
        var listContainer   = this.dayBeingViewed.listContainer;
        var dayNumber       = animationPanel.getFirst().getFirst();
        var dayName         = dayNumber.getNext();

        this.dayBeingViewed.closingStage++;
        
        switch (this.dayBeingViewed.closingStage) {

            case 1 : 
                event.target.fade('out');
                this.dayBeingViewed.eventList.fade('out');

                this.dayBeingViewed.target.setStyle('borderColor','');
                this.dayBeingViewed.target.fade('in');

                var fx = new Fx.Morph(dayNumber,{duration: 500});
                fx.onComplete = this.animateClosingDayEvents.bind(this);
                fx.start({'top': '0px'});
                
                var fx = new Fx.Morph(dayName,{duration: 500});
                fx.start({'top': dayNumber.offsetHeight + 'px'});
            break;

            case 2 : 
                this.animateStackPanelIntoView(this.MONTHLY_VIEW, this.animateClosingDayEvents.bind(this));
            break;
            
            case 3 : 
                animationPanel.empty();    
                this.dayBeingViewed = null;
            break;
        }
    },
    
    refresh : function() {
        this.load();
    },

    nextMonth : function (event) {
        if (event) event.stop();
        if (this.dayBeingViewed) return;
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);     
        this.load();
    },

    nextYear : function (event) {
        if (event) event.stop();
        if (this.dayBeingViewed) return;
        this.currentDate.setYear(this.currentDate.getFullYear() + 1);     
        this.load();
    },

    prevMonth : function (event) {
        if (event) event.stop();
        if (this.dayBeingViewed) return;
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);     
        this.load();
    },

    prevYear : function (event) {
        if (event) event.stop();
        if (this.dayBeingViewed) return;
        this.currentDate.setYear(this.currentDate.getFullYear() - 1);     
        this.load();
    }
});