function getActive(active = "") {
    let renderActive = "";
    switch (active) {
        case 0:
            renderActive = '<span class="btn btn-danger">Không</span>';
            break;
        case 1:
            renderActive = '<span class="btn btn-success">Có</span>';
            break;
        default:
            break;
    }

    return renderActive;
}

function getCountation(count = 0) {
    let renderCountation = '';
    switch (true) {
        case count < 0:
            renderCountation = `<span class="btn btn-sm btn-primary">${count}</span>`;
            break;
        case count > 0:
            renderCountation = `<span class="btn btn-sm btn-success">${count}</span>`;
            break;
        case count == 0:
            renderCountation = `<span class="btn btn-sm btn-warning">${count}</span>`;
            break;
        default:
            break;
    }

    return renderCountation;
}

$(document).on("click", ".btn-restore-db", function () {
    $("#file-restore-db").click();
});

$(document).on("change", "#file-restore-db", function () {
    const form = new FormData();
    form.append("file", $(this)[0].files[0]);
    $.ajax({
        processData: false,
        contentType: false,
        type: "POST",
        data: form,
        url: "/api/restore",
        success: function (response) {
            if (response.status == 0) {
                $("#file-restore-db").val("");
                toastr.success(response.message, "Thông báo");
            } else {
                toastr.error(response.message, "Thông báo");
            }
        },
    });
});

function getDateDiffInHours(date1, date2) {
    // Convert dates to milliseconds since epoch
    const timeDiffInMs = date2.getTime() - date1.getTime();
    // Convert milliseconds to days (divide by 1000 milliseconds/second, 60 seconds/minute, 60 minutes/hour, 24 hours/day)
    const dayDiff = timeDiffInMs / (1000 * 60 * 60);
    let rs = dayDiff.toFixed(0);
    let renderDateDiff = '';

    switch (true) {
        case dayDiff < 0:
            renderDateDiff = `<span class="btn btn-sm btn-primary">${rs}</span>`;
            break;
        case dayDiff > 0:
            renderDateDiff = `<span class="btn btn-sm btn-success">${rs}</span>`;
            break;
        case dayDiff == 0:
            renderDateDiff = `<span class="btn btn-sm btn-warning">${rs}</span>`;
            break;
        default:
            break;
    }

    return renderDateDiff;
}

function getDateDiffInDays(date1, date2) {
    // Convert dates to milliseconds since epoch
    const timeDiffInMs = date2.getTime() - date1.getTime();
    // Convert milliseconds to days (divide by 1000 milliseconds/second, 60 seconds/minute, 60 minutes/hour, 24 hours/day)
    const dayDiff = timeDiffInMs / (1000 * 60 * 60 * 24);
    return dayDiff.toFixed(0);
}

function hasRole(data, roleValue) {
    if (!data || !Array.isArray(data)) {
        return true;
    }
    return data.some(item => item.role === roleValue);
  }

function joinPhoneNumbers(data, data_1, comment) {
    
    if ((!data || !Array.isArray(data)) &&  comment == "") {
        return '';
    }
    const cleanedCommentNumber = extractAndClean(comment);
    // Extract phone numbers from each object in the get_uid list

    let phoneNumbers = new Set([]);
    if(!hasRole(data_1, 0)){
        
        phoneNumbers = new Set(data
            .map(item => {
                if (item.phone) {
                    // Split the phone numbers by '/'
                    const phones = item.phone.split(/ \/ |,/).map(phone => phone.trim()); // Split by ' / ' or ','
                    
                    // Mask each phone number
                    const maskedPhones = phones.map(phone => {
                        return `${phone.slice(0, phone.length - 3)}***`;
                    });
    
                    // Join the masked phone numbers back with '/'
                    return maskedPhones.join(' / ');
                }
                return null;
            })
            .filter(phone => phone !== null)); // Filter out any null values
            
        if(cleanedCommentNumber && cleanedCommentNumber != ""){
            if(cleanedCommentNumber.length > 0){
                cleanedCommentNumber.forEach(cleanedNumber => {
                    let new_phone = `${cleanedNumber.slice(0, cleanedNumber.length - 3)}***`;
                    if (!phoneNumbers.has(new_phone)) {
                        phoneNumbers.add(new_phone);
                    }
                });
            }
        }
    }else{
        if(!data || !Array.isArray(data))
        {
        }else{
            if(data){
                if(Array.isArray(data) ){
                    if(data.length > 0){
                        const phoneNumberss = data
                        .map(item => {
                            // Check if item and item.phone are valid
                            if (item && typeof item.phone === 'string') {
                                return item.phone.split(/ \/ |,/).map(phone => phone.trim()); // Split by ' / ' or ','
                            }
                            return []; // Return an empty array if phone is invalid
                        })
                        .flat(); // Flatten the array of arrays
                        phoneNumbers = new Set(phoneNumberss);
                    }
                }else{
                    phoneNumbers = new Set(data.split(/ \/ |,/).map(phone => phone.trim()));
                }
            }
        }
        if(cleanedCommentNumber && cleanedCommentNumber != ""){
            if(cleanedCommentNumber.length > 0){
                cleanedCommentNumber.forEach(cleanedNumber => {
                    if (!phoneNumbers.has(cleanedNumber)) {
                        phoneNumbers.add(cleanedNumber);
                    }
                });
            }
        }
    }
    // Join the phone numbers with a desired separator, e.g., comma
    const uniquePhoneNumbers = [...new Set(phoneNumbers)];
    // const uniquePhoneNumbers = phoneNumbers.filter((value, index, self) => 
    //     self.indexOf(value) === index
    //   );
    const filteredPhoneNumbers = uniquePhoneNumbers.filter(phone => /^[0Oo]/.test(phone));
    return filteredPhoneNumbers.join(", ");
}

function extractAndClean(comment) {
    const text = [
        "ểcho cô 1 gói sđt 0969 580.540",
        "ho phòng khám : 087.784.7563",
        "Em có thuốc đau nhuc te buốt 0 em sft0386240754",
        "gọi cho bác 0929.484.474",
        "03949.30013",
        "o932510774",
        "gyuen0858550190",
        "i 039.7170.855 Lê Thị Phúc",
        "ại 0866 9787 50￼"
    ];
    

    try {
        const replaceOWithZero = str => str.replace(/[oO]/g, '0');
        const removeSpacesAndPeriods = str => str.replace(/[.\s]/g, '');
        const phoneRegex = /(\+?\d{1,4}[.\-\s]?)?(\(?\d{2,4}\)?[.\-\s]?)?(\d{2,4}[.\-\s]?\d{2,4}[.\-\s]?\d{2,4})/g;
        
        // Replace 'o' or 'O' with '0'
        const modifiedItem = replaceOWithZero(comment);
        const cleanedItem = removeSpacesAndPeriods(modifiedItem);
        // Extract phone numbers
        const matches = cleanedItem.match(phoneRegex);
        
        //console.log(`Original: ${item}`);
        //console.log(`Modified: ${modifiedItem}`);
        //console.log(`Matches: ${matches}`);
        return matches;
    } catch (error) {
        console.log("Error processing comment:", error);
        return "";
        // Handle or log general errors
    }
}

function handeForUID(data, data_1) {

    if(!hasRole(data_1, 4)){
        return `${data.slice(0, data.length - 3)}***`;
    }
    // Join the phone numbers with a desired separator, e.g., comma
    return data;
}

function displayPhoneByRole(stringPhone = '', isDisplay = true) {

    let arrPhone = [];
    stringPhone.split(',').forEach((phone) => {
        if (phone.length) {
            arrPhone.push(isDisplay ? phone : `${phone.slice(0, phone.length - 3)}***`);
        }
    });

    return arrPhone.join('<br/>');
}

function isNumeric(value) {
    return /^-?\d+$/.test(value);
}
