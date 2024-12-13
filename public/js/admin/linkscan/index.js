var dataTable = null;
var allRecord = [];
var tempAllRecord = [];
var linksData = [];
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
            url: `/api/userlinks/getAllLinkScan_V2?type=0`,
            dataSrc: "links",
            dataSrc: function(json) {
                //linksData = json.links; // Save comments to the global variable
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
            },
            {
                data: function (d) {
                    //let commentLink = d.comment_links ? d.comment_links[0] : '';
                    //return d.datacuoi != null ? getDateDiffInHours(new Date(d.datacuoi), new Date()) : 'Trống';
                    return `<p class="show-datacuoi tool-tip" data-id="${d.link_or_post_id}" data-link_or_post_id="${d.link_or_post_id}" data-content="${d.datacuoi}">${getDateDiffInHours(d.datacuoi ? new Date(d.datacuoi): new Date(1800, 0, 1), new Date()) ?? "Trống"}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-datacuoi-${d.link_or_post_id}">
                    </div></p>`;
                }
            },
            {
                data: function (d) {
                    return d.is_on_at;
                },
            },
            {
                data: function (d) {
                    return d.name;
                    //return getListAccountNameByUserLink(d.accounts);
                },
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
            },
            {
                data: function (d) {
                    return `<p class="" >${d.content}
                    </p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-id="${d.id}" data-type="comment" data-link_or_post_id="${d.link_or_post_id}">${d.comment}  ${getCountation(d.diff_comment)}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-comment tooltiptext-comment-${d.id}"></div></p>`;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="data" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.data}  ${getCountation(parseInt(d.diff_data))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-data tooltiptext-data-${d.id}"></div></p>`;
                },
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
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="emotion_real" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.reaction_real}  ${getCountation(parseInt(d.diff_data_reaction))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion_real tooltiptext-emotion_real-${d.id}"></div></p>`;
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
                                                                        z-index: 1;" class="tooltiptext tooltiptext-view tooltiptext-view-${d.id}"></div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.adsstatus == 1 ? `<button class="btn btn-danger btn-sm">ON</button>`
                        : (d.adsstatus == 2 ? `<button class="btn btn-success btn-sm">OFF</button>`
                            : `<button class="btn btn-warning btn-sm">NO</button>`);
                }
            },
            {
                data: function (d) {
                    return d.note;
                },
            },
            {
                data: function (d) {
                    return d.linktn == 0 ? "Chưa quét" : (d.linktn == 1 ? "K TN" : "Co TN");
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<a class="btn btn-primary btn-sm" href='/admin/linkscans/update/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" data-user_id="${d.user_id}" class="btn btn-success btn-sm btn-follow">
                                <i class="fa-solid fa-user-plus"></i>
                            </button>
                            <button data-id="${d.id}" data-user_id="${d.user_id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
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
    ["linktn", ""],
    ["adsstatus", ""],
]);

var isFiltering = [];

function getQueryUrlWithParams() {
    let query = `type=0`;
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
});

$(document).on("click", ".btn-select", async function () {
    // id userLink
    let id = $(this).data("id");
    let link_or_post_id = $(this).data("link_or_post_id");
    if ($(this).is(':checked')) {
        if (!tempAllRecord.includes(id)) {
            tempAllRecord.push(id);
        }
    } else {
        tempAllRecord = tempAllRecord.filter((e) => e != id);
    }
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
        .url("/api/userlinks/getAllLinkScan_V2?" + getQueryUrlWithParams())
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
        console.log(e);
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
        .url(`/api/userlinks/getAll?type=0`)
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

    // await $.ajax({
    //     type: "GET",
    //     url: `/api/userlinks/getAll`,
    //     success: function (response) {
    //         if (response.status == 0) {
    //             allRecord = response.links;
    //             response.links.forEach((e) => {
    //                 if (e.type == 0) {
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
    let is_scan = $(this).data("is_scan");
    let text = is_scan == 0 ? 'tắt' : 'mở';
    let user_id = $(this).data("user_id");
    if (confirm(`Bạn có muốn ${text} quét link`)) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/userlinks/updateLinkByListLinkId`,
            data: {
                ids: [id],
                is_scan,
                user_id,
                status: 1,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Cập nhật thành công");
                    dataTable.ajax.reload();
                    tempAllRecord = [];
                    reloadAll();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});

$(document).on("click", ".btn-follow", function () {
    if (confirm("Bạn có muốn theo dõi link này?")) {
        let id = $(this).data("id");
        let user_id = $(this).data("user_id");
        $.ajax({
            type: "POST",
            url: `/api/userlinks/updateLinkByListLinkId`,
            data: {
                ids: [id],
                type: 1,
                is_scan: 0,
                user_id,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Theo dõi thành công");
                    reload();
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});

$(document).on("click", ".btn-follow-multiple", function () {
    if (confirm("Bạn có muốn theo dõi các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            let user_id = $('#user_id').val();
            $.ajax({
                type: "POST",
                url: `/api/userlinks/updateLinkByListLinkId`,
                data: {
                    ids: tempAllRecord,
                    type: 1,
                    is_scan: 0,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Theo dõi thành công");
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

$(document).on("click", ".btn-scan-multiple", function () {

    let is_scan = $(this).data("is_scan");
    let text = is_scan == 0 ? 'tắt' : (is_scan == 1 ? 'mở' : 'làm mới');
    if (confirm(`Bạn có muốn ${text} quét các link đang hiển thị?`)) {
        if (tempAllRecord.length) {
            let user_id = $('#user_id').val();
            $.ajax({
                type: "POST",
                url: `/api/userlinks/updateLinkByListLinkId`,
                data: {
                    ids: tempAllRecord,
                    is_scan,
                    status: 1,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Cập nhật thành công");
                        dataTable.ajax.reload();
                        tempAllRecord = [];
                        reloadAll();
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
                    reload();
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
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
