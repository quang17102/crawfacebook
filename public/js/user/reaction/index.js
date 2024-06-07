var dataTable = null;
var searchParams = new Map();
var is_display_phone = $('#is_display_phone').val();

$(document).ready(function () {
    reload();

    dataTable = $("#table").DataTable({
        columnDefs: [
            { visible: false, targets: 1 },
            { visible: false, targets: 2 },
            { visible: false, targets: 3 },
        ],
        lengthMenu: [
            [100, 250, 500],
            [100, 250, 500]
        ],
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: "excel",
                        text: "Xuất Excel",
                        exportOptions: {
                            columns: ":not(:last-child)",
                        },
                    },
                    "colvis",
                ],
            },
            top2Start: 'pageLength',
        },
        ajax: {
            url: `/api/reactions/getAll?today=${new Date().toJSON().slice(0, 10)}&user_id=${$('#user_id').val()}`,
            dataSrc: "reactions",
        },
        columns: [
            {
                data: function (d) {
                    return `<input class="btn-select" type="checkbox" data-id="${d.id}" />`;
                }
            },
            {
                data: function (d) {
                    return d.link.link_or_post_id;
                },
            },
            {
                data: function (d) {
                    return d.link.content;
                },
            },
            {
                data: function (d) {
                    return d.uid;
                },
            },
            {
                data: function (d) {
                    return d.created_at;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-title tool-tip" data-type='content' data-content="${d.content}" data-link_or_post_id="${d.link.link_or_post_id}" data-id="${d.id}">${d.link ? d.link.title : ''}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-${d.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-name_facebook tool-tip" data-id="${d.id}" data-value="${d.uid}" data-uid="${d.uid}">${d.reaction ? (d.name_facebook || '') : ''}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-name_facebook tooltip-name_facebook-${d.id}">
                    </div></p>`;
                },
            },
            // {
            //     data: function (d) {
            //         return `<p class="show-uid tool-tip" data-id="${d.id}" data-value="${d.uid}" data-uid="${d.uid}">${d.uid}
            //         <div style="display:none;width: max-content;
            //                     background-color: black;
            //                     color: #fff;
            //                     border-radius: 6px;
            //                     padding: 5px 10px;
            //                     position: absolute;
            //                     z-index: 1;" class="tooltip-uid tooltip-uid-${d.id}">
            //         </div></p>`;
            //     },
            // },
            {
                data: function (d) {
                    return displayPhoneByRole(d.get_uid ? d.get_uid.phone : '', is_display_phone);
                },
            },
            {
                data: function (d) {
                    return d.reaction;
                },
            },
            {
                data: function (d) {
                    return d.note;
                },
            },
            {
                data: function (d) {
                    return `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
            },
        ],
    });
});

var searchParams = new Map([
    ["from", ""],
    ["to", ""],
    ["reaction", ""],
    ["phone", ""],
    ["note", ""],
    ["uid", ""],
    ["name_facebook", ""],
    ["title", ""],
    ["link_or_post_id", ""],
]);

var isFiltering = [];

function getQueryUrlWithParams() {
    let query = `user_id=${$('#user_id').val()}`;
    Array.from(searchParams).forEach(([key, values], index) => {
        query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
    })

    return query;
}

function reloadAll() {
    // enable or disable button
    $('.btn-control').prop('disabled', tempAllRecord.length ? false : true);
    $('.count-select').text(`Đã chọn: ${tempAllRecord.length}`);
}

$(document).on("click", ".btn-select-all", function () {
    tempAllRecord = [];
    if ($(this).is(':checked')) {
        $('.btn-select').each(function () {
            if ($(this).is(':checked')) {
                $(this).prop('checked', false);
            } else {
                $(this).prop('checked', true);
                tempAllRecord.push($(this).data('id'));
            }
        });
    } else {
        $('.btn-select').each(function () {
            $(this).prop('checked', false);
        });
    }
    reloadAll();
    console.log(tempAllRecord);
});

$(document).on("click", ".btn-select", async function () {
    let id = $(this).data("id");
    if ($(this).is(':checked')) {
        if (!tempAllRecord.includes(id)) {
            tempAllRecord.push(id);
        }
    } else {
        tempAllRecord = tempAllRecord.filter((e) => e != id);
    }
    console.log(tempAllRecord);
    reloadAll();
});

$(document).on("click", ".btn-filter", async function () {
    isFiltering = [];
    tempAllRecord = [];
    Array.from(searchParams).forEach(([key, values], index) => {
        searchParams.set(key, String($('#' + key).val()).length ? $('#' + key).val() : '');
        if ($('#' + key).val() && $('#' + key).attr('data-name')) {
            isFiltering.push($('#' + key).attr('data-name'));
        }
    });
    // display filtering
    displayFiltering();

    // reload
    // dataTable.clear().rows.add(tempAllRecord).draw();
    dataTable.ajax
        .url("/api/reactions/getAll?" + getQueryUrlWithParams())
        .load();

    //
    await $.ajax({
        type: "GET",
        url: `/api/reactions/getAll?${getQueryUrlWithParams()}`,
        success: function (response) {
            if (response.status == 0) {
                response.reactions.forEach((e) => {
                    tempAllRecord.push(e.id);
                });
            }
        }
    });
    // auto selected
    tempAllRecord.forEach((e) => {
        $(`.btn-select[data-id="${e}"]`).prop('checked', true);
    });
    $('.btn-select-all').prop('checked', true);
    // reload all
    reloadAll();
    $('.count-reaction').text(`Cảm xúc: ${tempAllRecord.length}`);
});

$(document).on("click", ".btn-refresh", function () {
    Array.from(searchParams).forEach(([key, values], index) => {
        $('#' + key).val('');
    });

    // display filtering
    isFiltering = [];
    displayFiltering();

    // reload table
    dataTable.ajax
        .url(`/api/reactions/getAll?user_id=${$('#user_id').val()}&type=1`)
        .load();

    // reload count and record
    reload();
    // reload all
    reloadAll();
});

function displayFiltering() {
    isFiltering = isFiltering.filter(function (item, pos, self) {
        return self.indexOf(item) == pos;
    });
    // isFiltering.forEach((e) => {
    //     console.log(e);
    //     html += `<button class="btn btn-warning">${e}</button>`;
    // });
    $('.filtering').text(`Lọc theo: ${isFiltering.join(',')}`);

}

function getListAccountNameByUserLink(userLinks = []) {
    let rs = [];
    userLinks.forEach((e) => {
        if (!rs.includes(e.user.email || e.user.name)) {
            rs.push(e.user.email || e.user.name);
        }
    });

    return rs.join('|');
}

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/reactions/${id}/destroy`,
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Xóa thành công");
                    dataTable.ajax.reload();
                    reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});

async function reload() {
    await $.ajax({
        type: "GET",
        url: `/api/reactions/getAll?user_id=${$('#user_id').val()}`,
        // url: `/api/reactions/getAll?today=${new Date().toJSON().slice(0, 10)}&user_id=${$('#user_id').val()}`,
        success: function (response) {
            if (response.status == 0) {
                $('.count-reaction').text(`Cảm xúc: ${response.reactions.length}`);
            }
        }
    });
    //
    tempAllRecord = [];
    reloadAll();
}

$(document).on("click", ".btn-delete-multiple", function () {
    if (confirm("Bạn có muốn xóa các cảm xúc đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/reactions/deleteAll`,
                data: { ids: tempAllRecord },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Xóa thành công");
                        reload();
                        dataTable.ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        } else {
            toastr.error('Cảm xúc trống');
        }
    }
});

var idIntervalRefresh = null;

$(document).on("click", ".btn-auto-refresh", function () {
    if (confirm(`Bạn có muốn ${idIntervalRefresh ? 'tắt' : 'bật'} auto refresh?`)) {
        if (idIntervalRefresh) {
            clearInterval(idIntervalRefresh);
            idIntervalRefresh = null;
            $(this).text('Auto Refresh: OFF');
            $(this).addClass('btn-danger');
            $(this).removeClass('btn-success');
        } else {
            $(this).text('Auto Refresh: ON');
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-success');
            idIntervalRefresh = setInterval(() => {
                $('.btn-filter').click();
            }, 20000);
        }
    }
});

$(document).on("click", ".btn-copy-uid", function () {
    let number = $('#number').val();
    let ids = tempAllRecord.length > number ? tempAllRecord.slice(0, number) : tempAllRecord
    $.ajax({
        type: "GET",
        url: `/api/reactions/getAll?limit=${number}&ids=${ids.join(',')}`,
        success: function (response) {
            if (response.status == 0) {
                let uids = [];
                let reactions = ids.length ? response.reactions.slice(0, $('#number').val()) : response.reactions;
                reactions.forEach((e) => {
                    uids.push(e.uid);
                });
                navigator.clipboard.writeText(uids.join(','));
                closeModal('modalCopyUid');
            } else {
                toastr.error(response.message);
            }
        },
    });
});
