var nietLeeg = "Dit veld is verplicht!";

var title = new LiveValidation('title', {validMessage:" "});
title.add(Validate.Presence,{failureMessage:nietLeeg});	

var street = new LiveValidation('projectStreet', {validMessage:" "});
street.add(Validate.Presence,{failureMessage:nietLeeg});
street.add(Validate.Length,{maximum:30, tooLongMessage: "Maximum 30 tekens lang!"});

var city = new LiveValidation('projectCity', {validMessage:" "});
city.add(Validate.Presence,{failureMessage:nietLeeg});
city.add(Validate.Length,{maximum:20, tooLongMessage: "Maximum 20 tekens lang!"});

var zipcode = new LiveValidation('projectZipcode', {validMessage:" "});
zipcode.add(Validate.Presence,{failureMessage:nietLeeg});
zipcode.add(Validate.Length,{is:4, wrongLengthMessage: "Een postcode moet 4 cijfers bevatten!"});
zipcode.add(Validate.Numericality,{onlyInteger:true, notANumberMessage: "Een postcode moet een getal zijn!"});         