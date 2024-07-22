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

    const cleanedCommentNumber = extractAndClean(comment);
    if ((!data || !Array.isArray(data)) &&  comment == "") {
        return '';
    }
    // Extract phone numbers from each object in the get_uid list
    let existingPhones = [];
    if(data && Array.isArray(data)){
        existingPhones = new Set(data.map(item => item.phone).flat().split(' / '));
    }

    let phoneNumbers;
    if(!hasRole(data_1, 0)){
        phoneNumbers = data
        .map(item => {
            if (item.phone) {
                // Split the phone numbers by '/'
                const phones = item.phone.split(' / ');
                
                // Mask each phone number
                const maskedPhones = phones.map(phone => {
                    return `${phone.slice(0, phone.length - 3)}***`;
                });

                // Join the masked phone numbers back with '/'
                return maskedPhones.join(' / ');
            }
            return null;
        })
        .filter(phone => phone !== null); // Filter out any null values
    }else{
        if(!data || !Array.isArray(data))
        {
            phoneNumbers = [];
        }else{
            phoneNumbers = data.map(item => item.phone);
        }
    }
    if(cleanedCommentNumber != ""){
        if (existingPhones && !existingPhones.has(cleanedCommentNumber)) {
            phoneNumbers.push(cleanedCommentNumber);
        }
    }
    // Join the phone numbers with a desired separator, e.g., comma
    return phoneNumbers.join(", ");
}

function extractAndClean(comment) {
    // List of regex patterns to match various phone number and identifier formats
    const patterns = [
        /\b\d{10}\b/,                   // Matches 10-digit numbers (e.g., 0842220050, 0386240754)
        /\b\d{5}\.\d{5}\b/,             // Matches numbers with 5 digits followed by a dot and another 5 digits (e.g., 03949.30013)
        /\b\d{4}[\s.]\d{3}[\s.]\d{3}\b/, // Matches numbers with 4 digits, a space or dot, 3 digits, a space or dot, and 3 digits (e.g., 0969 580.540)
        /\b\d{3}\.\d{3}\.\d{4}\b/,      // Matches numbers with 3 digits, a dot, 3 digits, a dot, and 4 digits (e.g., 087.784.7563)
        /\b\d{4}\.\d{3}\.\d{3}\b/,      // Matches numbers with 4 digits, a dot, 3 digits, and a dot, and 3 digits (e.g., 0929.484.474)
        /\b\d{4}\s\d{3}\s\d{3}\b/,      // Matches numbers with 4 digits, a space, 3 digits, a space, and 3 digits (e.g., 0929 484 474)
        /\babn\d{4}[\s.]\d{3}[\s.]\d{3}[a-zA-Z]*\b/, // Matches "abn" followed by 4 digits, a space or dot, 3 digits, a space or dot, 3 digits, and optional letters (e.g., abn0928.228.382fc)
        /\babn\d{4}[\s.]\d{2}[\s.]\d{1}[\s.]\d{3}[a-zA-Z]*\b/, // Matches "abn" followed by 4 digits, a space or dot, 2 digits, a space or dot, 1 digit, a space or dot, 3 digits, and optional letters (e.g., abn0928 22 8 382fc)
        /\bo\d{3}[\s.]\d{3}[\s.]\d{3}\b/ // Matches "o" followed by 3 digits, a space or dot, 3 digits, a space or dot, and 3 digits (e.g., o928 228 382)
    ];

    for (let pattern of patterns) {
        let matches = comment.match(pattern);
        if (matches) {
            for (let match of matches) {
                // Remove dots, spaces, commas, "abn", and "o" from the matched value
                return match.replace(/[.\s,]/g, "").replace(/^(abn|o)/i, "");
            }
        }
    }
    return "";
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
