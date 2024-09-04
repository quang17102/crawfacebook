var dataTable = null;
var allRecord = [];
var tempAllRecord = [];

$(document).ready(function () {
    reload();

    dataTable = $("#table").DataTable({
        columnDefs: [
            // { visible: false, targets: 0 },
            { visible: false, targets: 1 },
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
            url: `/api/userlinks/getAllLinkScan_V2?type=1`,
            //dataSrc: "links",
            dataSrc: function(json) {
                json.links.forEach((e) => {
                    tempAllRecord.push(e.id);
                });
                return json.links;
            }
        },
        columns: [
            {
                data: function (d, type, set, meta) {
                    return `<p data-id="${d.id}">${meta.row + 1}</p>`;
                },
                orderable: false
                
            },
            {
                data: function (d) {
                    return d.link_or_post_id;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    //return d.datacuoi != null ? getDateDiffInHours(new Date(d.datacuoi), new Date()) : 'Trống';
                    return `<p class="show-datacuoi tool-tip" data-id="${d.link_or_post_id}" data-link_or_post_id="${d.link_or_post_id}" data-content="${d.datacuoi}">${getDateDiffInHours(new Date(d.datacuoi), new Date()) ?? "Trống"}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-datacuoi-${d.link_or_post_id}">
                    </div></p>`;
                },
                orderable: false,
                
            },
            {
                data: function (d) {
                    //return d.created_at;
                    return d.is_on_at;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.name;
                    return getListAccountNameByUserLink(d.accounts);
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class="show-title tool-tip" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.title || d.title}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-${d.id}">
                    </div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class=""> ${d.content}</p>`;
                },
                orderable: false,
            },
            // { Để đây sau lỡ dùng lại
            //     data: function (d) {
            //         return `<p class="show-content tool-tip" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}" data-content="">
            //         <img style="width: 50px;height:50px" src="${d.image}" alt="image" />
            //         <div style="display:none;width: max-content;
            //                     background-color: black;
            //                     color: #fff;
            //                     border-radius: 6px;
            //                     padding: 5px 10px;
            //                     position: absolute;
            //                     width: 40%;
            //                     z-index: 1;" class="tooltip-content tooltip-content-${d.id}">
            //         </div></p>`;
            //     },
            // },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="comment" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.comment}  ${getCountation(parseInt(d.diff_comment))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-comment tooltiptext-comment-${d.id}"></div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-id="${d.id}" data-type="data" data-link_or_post_id="${d.link_or_post_id}">${d.data}  ${getCountation(parseInt(d.diff_data))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-data tooltiptext-data-${d.id}"></div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="emotion" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.reaction}  ${getCountation(parseInt(d.diff_reaction))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="emotion_real" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.reaction_real}  ${getCountation(parseInt(d.diff_data_reaction))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="view" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.view}  ${getCountation(parseInt(d.diff_view))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.is_scan != 2 ? `<button class="btn btn-success btn-sm">ON</button>`
                            : `<button class="btn btn-warning btn-sm">ERROR</button>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.note;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm" href='/admin/linkfollows/update/${d.id}?user_id=${d.user_id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" data-user_id="${d.user_id}" class="btn btn-success btn-sm btn-scan">
                                <i class="fa-solid fa-barcode"></i>
                            </button>
                            <button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
                orderable: false,
            },
        ],
    });
});

var searchParams = new Map([
    ["time_from", ""],
    ["time_to", ""],
    ["last_data_from", ""],
    ["last_data_to", ""],
    ["data_from", ""],
    ["data_to", ""],
    ["comment_from", ""],
    ["comment_to", ""],
    ["reaction_from", ""],
    ["reaction_to", ""],
    ["from", ""],
    ["to", ""],
    ["content", ""],
    ["title", ""],
    ["link_or_post_id", ""],
    ["user", ""],
    ["is_scan", ""],
    ["data_reaction_from", ""],
    ["data_reaction_to", ""],
    ["view_from", ""],
    ["view_to", ""],
]);

var isFiltering = [];

function getQueryUrlWithParams() {
    let query = `type=1`;
    Array.from(searchParams).forEach(([key, values], index) => {
        query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
    })

    return query;
}

function reloadAll() {
    // enable or disable button
    //$('.btn-control').prop('disabled', tempAllRecord.length ? false : true);
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
    let link_or_post_id = $(this).data("link_or_post_id");
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
        .url(`/api/userlinks/getAllLinkScan_V2?${getQueryUrlWithParams()}`)
        .load();

    //
    await $.ajax({
        type: "GET",
        url: `/api/userlinks/getAllLinkScan_V2?${getQueryUrlWithParams()}`,
        success: function (response) {
            if (response.status == 0) {
                response.links.forEach((e) => {
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
        .url(`/api/userlinks/getAll?type=1`)
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
async function reload() {
    let count = 0;
    let user_id = $('#user_id').val();

    // await $.ajax({
    //     type: "GET",
    //     url: `/api/userlinks/getAll`,
    //     success: function (response) {
    //         console.log(response.links);
    //         all = response.links.length;
    //         if (response.status == 0) {
    //             allRecord = response.links;
    //             response.links.forEach((e) => {
    //                 if (e.type == 1) {
    //                     count++;
    //                 }
    //             });
    //             $('.count-link').text(`Số link: ${count}`);
    //         }
    //     }
    // });
    //
    tempAllRecord = [];
    reloadAll();
}

$(document).on("click", ".btn-scan", function () {
    if (confirm("Bạn có muốn quét link này?")) {
        let id = $(this).data("id");
        let user_id = $(this).data("user_id");
        $.ajax({
            type: "POST",
            url: `/api/userlinks/updateLinkByListLinkId`,
            data: {
                ids: [id],
                type: 0,
                is_scan: 1,
                user_id
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Quét thành công");
                    reload();
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});



$(document).on("click", ".btn-scan-multiple", function () {
    if (confirm("Bạn có muốn quét các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/userlinks/updateLinkByListLinkId`,
                data: {
                    ids: tempAllRecord,
                    type: 0,
                    is_scan: 1,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Quét thành công");
                        reload();
                        dataTable.ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
            });
        } else {
            toastr.error('Link trống');
        }
    }
});

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/userlinks/deleteAll`,
            data: { ids: [id] },
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

$(document).on("click", ".btn-delete-multiple", function () {
    if (confirm("Bạn có muốn xóa các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/userlinks/deleteAll`,
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
            toastr.error('Link trống');
        }
    }
});

function getListAccountNameByUserLink(userLinks = []) {
    let rs = [];
    userLinks.forEach((e) => {
        if (!rs.includes(e.user.email || e.user.name)) {
            rs.push(e.user.email || e.user.name);
        }
    });

    return rs.join('|');
}
