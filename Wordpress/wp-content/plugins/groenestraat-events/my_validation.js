var nietLeeg = "Dit veld is verplicht!";

var title = new LiveValidation('title', {validMessage:" "});
title.add(Validate.Presence,{failureMessage:nietLeeg});	

var eventTime = new LiveValidation('eventTime', {validMessage:" "});
eventTime.add(Validate.Presence,{failureMessage:nietLeeg});

var eventEndTime = new LiveValidation('eventEndTime', {validMessage:" "});
eventEndTime.add(Validate.Presence,{failureMessage:nietLeeg});

var eventStartHour = new LiveValidation('eventStartHour', {validMessage:" "});
eventStartHour.add(Validate.Presence,{failureMessage:nietLeeg});
eventStartHour.add(Validate.Custom, {against: function checkTime(value){
   	re = /([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;
   	if(!re.test(value)) {
   		return false;
   	}
   	else return true;
}, failureMessage:"Een tijdstip moet de structuur (HH:MM) hebben!"});

var eventEndHour = new LiveValidation('eventEndHour', {validMessage:" "});
eventEndHour.add(Validate.Presence,{failureMessage:nietLeeg});
eventEndHour.add(Validate.Custom, {against: function checkTime(value){
   	re = /([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/;
   	if(!re.test(value)) {
       	return false;
   	}
   	else return true;
}, failureMessage:"Een tijdstip moet de structuur (HH:MM) hebben!"});

var loc = new LiveValidation('eventLocation', {validMessage:" "});
loc.add(Validate.Presence,{failureMessage:nietLeeg});