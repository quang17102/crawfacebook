var dataTable = null;
var allRecord = [];
var tempAllRecord = [];
var total_link = 0;
var total_maxlink = 0;
var is_display_count = $('#is_display_count').val();
var permistion_reaction = '';
var permistion_view = '';
var permistion_ads = '';
var permistion_datacuoi = '';
var permistion_uid = '';
var permistion_count = '';
var permistion_sdt = '';


$(document).ready(async function () {
    const json = await $.ajax({
        url: `/api/settings/getpermission?user_id=${$('#user_id').val()}`,
        method: 'GET'
    });
    permistion_reaction = json.permistion_reaction;
    permistion_view = json.permistion_view;
    permistion_ads = json.permistion_ads;
    permistion_datacuoi = json.permistion_datacuoi;
    permistion_uid = json.permistion_uid;
    permistion_count = json.permistion_count;
    
    var hiddenCountColumn = [
        { visible: permistion_uid == "YES", targets: 1 },
        { visible: permistion_datacuoi == "YES", targets: 2 },
        { visible: permistion_count == "YES", targets: 6 },
        { visible: permistion_count == "YES", targets: 7 },
        { visible: permistion_count == "YES", targets: 8 },
        { visible: permistion_reaction == "YES", targets: 9 },
        { visible: permistion_view == "YES", targets: 10 },
        { visible: permistion_ads == "YES", targets: 11 },
    ];
    //
    $('.hidden-filter').css('display', is_display_count ? '' : 'none');
    dataTable = $("#table").DataTable({
        columnDefs: !is_display_count ? hiddenCountColumn : [
            { visible: permistion_uid == "YES", targets: 1 },
            { visible: permistion_datacuoi == "YES", targets: 2 },
            { visible: permistion_count == "YES", targets: 6 },
            { visible: permistion_count == "YES", targets: 7 },
            { visible: permistion_count == "YES", targets: 8 },
            { visible: permistion_reaction == "YES", targets: 9 },
            { visible: permistion_view == "YES", targets: 10 },
            { visible: permistion_ads == "YES", targets: 11 },
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
            url: `/api/userlinks/getAll?user_id=${$('#user_id').val()}&type=0`,
            dataSrc: function(json) {
                json.links.forEach((e) => {
                    tempAllRecord.push(e.id);
                });
                total_link = json.total_link;
                total_maxlink = json.user.limit;
                permistion_reaction = json.permistion_reaction;
                permistion_view = json.permistion_view;
                reloadAll();
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
                    if(!hasRole(d.roles,5)){
                        return '';
                    }
                    return  getDateDiffInHours(d.datacuoi ? new Date(d.datacuoi): new Date(1800, 0, 1), new Date());
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.is_on_at;
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
                    return `<p class="" >${d.content} </p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return !is_display_count ?
                        `<button class="btn-sm btn btn-primary"><i class="fa-solid fa-eye-low-vision"></i></button>`
                        : `<p class="show-history tool-tip" data-type="comment" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.comment}  ${getCountation(parseInt(d.diff_comment))}<div style="display:none;
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
                    return !is_display_count ?
                        `<button class="btn-sm btn btn-primary"><i class="fa-solid fa-eye-low-vision"></i></button>`
                        : `<p class="show-history tool-tip" data-id="${d.id}" data-type="data" data-link_or_post_id="${d.link_or_post_id}">${d.data}  ${getCountation(parseInt(d.diff_data))}<div style="display:none;
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
                    return !is_display_count ?
                        `<button class="btn-sm btn btn-primary"><i class="fa-solid fa-eye-low-vision"></i></button>`
                        : `<p class="show-history tool-tip" data-id="${d.id}" data-type="emotion" data-link_or_post_id="${d.link_or_post_id}">${d.reaction}  ${getCountation(parseInt(d.diff_reaction))}<div style="display:none;
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
                    return permistion_reaction == "YES" ?  `<p class="show-history tool-tip" data-type="emotion_real" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.reaction_real}  ${getCountation(parseInt(d.diff_data_reaction))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`
                    : '0';
                },
            },
            {
                data: function (d) {
                    return permistion_view == "YES" ? `<p class="show-history tool-tip" data-type="view" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.view}  ${getCountation(parseInt(d.diff_view))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`
                    : '0';
                },
            },
            {
                data: function (d) {
                    if(!hasRole(d.roles,6)){
                        return '';
                    }
                    return d.is_scan == 0 ? `<button class="btn btn-danger btn-sm">OFF</button>`
                        : (d.is_scan == 1 ? `<button  class="btn btn-success btn-sm">ON</button>`
                            : `<button class="btn btn-warning btn-sm">ERROR</button>`);
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
                    return `<a class="btn btn-primary btn-sm" href='/user/linkscans/update/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" data-user_id="${d.user_id}" class="btn btn-success btn-sm btn-follow">
                                <i class="fa-solid fa-user-plus"></i>
                            </button>
                            <button data-id="${d.id}" data-user_id="${d.user_id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
                orderable: false,
            },
        ],
    });

    reloadAll();
});

var searchParams = new Map([
    ["time_from", ""],
    ["time_to", ""],
    ["data_from", ""],
    ["data_to", ""],
    ["last_data_from", ""],
    ["last_data_to", ""],
    ["comment_from", ""],
    ["comment_to", ""],
    ["reaction_from", ""],
    ["reaction_to", ""],
    ["from", ""],
    ["to", ""],
    ["content", ""],
    ["title", ""],
    ["link_or_post_id", ""],
    ["type", ""],
    ["is_scan", ""],
    ["data_reaction_from", ""],
    ["data_reaction_to", ""],
    ["view_from", ""],
    ["view_to", ""],
]);

var isFiltering = [];

function getQueryUrlWithParams() {
    let query = `user_id=${$('#user_id').val()}&type=0`;
    Array.from(searchParams).forEach(([key, values], index) => {
        query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
    })

    return query;
}

function reloadAll() {
    // enable or disable button
    //$('.btn-control').prop('disabled', tempAllRecord.length ? false : true);
    //$('.count-select').text(`Đã chọn: ${tempAllRecord.length}`);
    $('.count-link').text(`Số link: ${total_link}/${total_maxlink}`);

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
        .url("/api/userlinks/getAll?" + getQueryUrlWithParams())
        .load();
    //
    await $.ajax({
        type: "GET",
        url: `/api/userlinks/getAll?${getQueryUrlWithParams()}`,
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
        .url(`/api/userlinks/getAll?user_id=${$('#user_id').val()}&type=0`)
        .load();

    // reload count and record
    //reload();
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

// async function reload() {
//     let count = 0;
//     let user_id = $('#user_id').val();

//     await $.ajax({
//         type: "GET",
//         url: `/api/userlinks/getAll?user_id=${user_id}`,
//         success: function (response) {
//             if (response.status == 0) {
//                 allRecord = response.links;
//                 response.links.forEach((e) => {
//                     if (e.link.type == 0) {
//                         count++;
//                     }
//                 });
//                 $('.count-link').text(`Số link: ${count}/${response.user ? response.user.limit : 0}`);
//             }
//         }
//     });
//     //
//     tempAllRecord = [];
//     reloadAll();
// }

$(document).on("click", ".btn-scan", function () {
    let is_scan = $(this).data("is_scan");
    let user_id = $('#user_id').val();
    let text = is_scan == 0 ? 'tắt' : 'mở';
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
        let user_id = $('#user_id').val();
        $.ajax({
            type: "POST",
            url: `/api/userlinks/updateLinkByListLinkId`,
            data: {
                ids: [id],
                type: 1,
                is_scan: 0,
                user_id
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Theo dõi thành công");
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
                    user_id
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Theo dõi thành công");
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
                    user_id
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
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});
