var nietLeeg = "Dit veld is verplicht!";

var price = new LiveValidation('adPrice', {validMessage:" "});
price.add(Validate.Presence,{failureMessage:nietLeeg});

var loc = new LiveValidation('adLocation', {validMessage:" "});
loc.add(Validate.Presence,{failureMessage:nietLeeg});

var title = new LiveValidation('title', {validMessage:" "});
title.add(Validate.Presence,{failureMessage:nietLeeg});	