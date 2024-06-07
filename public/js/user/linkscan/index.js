var dataTable = null;
var allRecord = [];
var tempAllRecord = [];
var is_display_count = $('#is_display_count').val();
var hiddenCountColumn = [
    { visible: false, targets: 1 },
    { visible: false, targets: 6 },
    { visible: false, targets: 7 },
    { visible: false, targets: 8 },
];

$(document).ready(function () {
    //
    $('.hidden-filter').css('display', is_display_count ? '' : 'none');
    reload();

    dataTable = $("#table").DataTable({
        columnDefs: !is_display_count ? hiddenCountColumn : [
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
            url: `/api/userlinks/getAll?user_id=${$('#user_id').val()}&type=0`,
            dataSrc: "links",
        },
        columns: [
            {
                data: function (d) {
                    return `<input class="btn-select" type="checkbox" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}" />`;
                }
            },
            {
                data: function (d) {
                    return d.link_or_post_id;
                },
            },
            {
                data: function (d) {
                    let commentLink = d.comment_links ? d.comment_links[0] : '';
                    return commentLink ? getDateDiffInHours(new Date(commentLink.created_at), new Date()) : 'Trống';
                }
            },
            {
                data: function (d) {
                    return d.created_at;
                    return d.updated_at;
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
                    return `<p class="show-content tool-tip" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}" data-content="${d.content}">
                    <img style="width: 50px;height:50px" src="${d.image}" alt="image" />
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-content tooltip-content-${d.id}">
                    </div></p>`;
                },
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
            },
            {
                data: function (d) {
                    return d.is_scan == 0 ? `<button class="btn btn-danger btn-scan btn-sm" data-is_scan="1" data-user_id="${d.user_id}" data-id=${d.id}>OFF</button>`
                        : (d.is_scan == 1 ? `<button data-is_scan="0" data-id=${d.id} data-user_id="${d.user_id}" class="btn btn-success btn-scan btn-sm">ON</button>`
                            : `<button class="btn btn-warning btn-sm">ERROR</button>`);
                }
            },
            {
                data: function (d) {
                    return d.note;
                },
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
            },
        ],
    });
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

    await $.ajax({
        type: "GET",
        url: `/api/userlinks/getAll?user_id=${user_id}`,
        success: function (response) {
            if (response.status == 0) {
                allRecord = response.links;
                response.links.forEach((e) => {
                    if (e.link.type == 0) {
                        count++;
                    }
                });
                $('.count-link').text(`Số link: ${count}/${response.user ? response.user.limit : 0}`);
            }
        }
    });
    //
    tempAllRecord = [];
    reloadAll();
}

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
                    user_id
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
