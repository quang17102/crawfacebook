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
    let rs = dayDiff.toFixed(0) + 'h';
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
