var dataTable = null;
var allRecord = [];
var tempAllRecord = [];
var tableData = [];
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
            url: "/api/links/getAllNewForUI_V2",
            dataSrc: function(json) {
                tableData = json.links;
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
                    return `<p class="show-datacuoi tool-tip" data-id="${d.link_or_post_id}" data-link_or_post_id="${d.link_or_post_id}" data-content="${d.datacuoi}">${getDateDiffInHours(new Date(d.datacuoi), new Date()) ?? "Trống"}
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
                    // let userLink = d.is_on_user_links ? d.is_on_user_links[0] : '';
                     return  d.is_on_at;
                }
            },
            {
                data: function (d) {
                    //console.log(d);
                    //return `${getListAccountNameByUserLink(d.user ? (d.user.name || d.user.email) : '', d.accounts)}`;
                    return d.user_id;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-title tool-tip" data-id="${d.link_or_post_id}" data-link_or_post_id="${d.link_or_post_id}">${d.link_or_post_id}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-${d.link_or_post_id}">
                    </div></p>`;
                    //return "Trống";
                },
            },
            {
                data: function (d) {
                    return `<p class="">${d.content}
                    </p>`;
                    //return "Trống";
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="comment" data-id="${d.link_or_post_id}" data-link_or_post_id="${d.link_or_post_id}">${d.comment}  ${getCountation(d.diff_comment)}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-comment tooltiptext-comment-${d.link_or_post_id}"></div></p>`;
                    //return "Trống";
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="data" data-id="${d.link_or_post_id}" data-link_or_post_id="${d.link_or_post_id}">${d.data}  ${getCountation(parseInt(d.diff_data))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-data tooltiptext-data-${d.link_or_post_id}"></div></p>`;
                    //return "Trống";
                },
            },
            {
                data: function (d) {
                    return `<p class="show-history tool-tip" data-type="emotion" data-id="${d.link_or_post_id}" data-link_or_post_id="${d.link_or_post_id}">${d.reaction}  ${getCountation(parseInt(d.diff_reaction))}<div style="display:none;
                                                                        width: max-content;
                                                                        background-color: black;
                                                                        color: #fff;
                                                                        border-radius: 6px;
                                                                        position: absolute;
                                                                        z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.link_or_post_id}"></div></p>`;
                    //return "Trống";
                },
            },
            // {
            //     data: function (d) {
            //         return d.is_scan == 0 ? `<button class="btn btn-danger btn-scan btn-sm" data-is_scan="1" data-id=${d.id}>OFF</button>`
            //             : (d.is_scan == 1 ? `<button data-is_scan="0" data-id=${d.id} class="btn btn-success btn-scan btn-sm">ON</button>`
            //                 : `<button class="btn btn-warning btn-sm">ERROR</button>`);
            //     }
            // },
            {
                data: function (d) {
                    return d.delay;

                }
            },
            {
                data: function (d) {
                    return d.status == 1 ? `<button class="btn btn-primary btn-sm btn-status" data-link_id="${d.link_or_post_id}" data-status="0">
                                                Running
                                            </button>`
                        : `<button class="btn btn-danger btn-sm  btn-status" data-link_id="${d.link_or_post_id}" data-status="1">
                                                Stop
                                            </button>`;
                },
            },
            {
                data: function (d) {
                    let btnDelete = d.id == $('#editing_link_id').val() ? `` :
                        `<button data-id="${d.link_or_post_id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                    return `<a class="btn btn-primary btn-sm" href='/admin/linkrunnings/update/${d.link_or_post_id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.link_or_post_id}" class="btn btn-success btn-sm btn-reset">
                                <i class="fa-solid fa-rotate-right"></i>
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
    ["type", ""],
    ["user", ""],
    ["delay_from", ""],
    ["delay_to", ""],
    ["status", ""],
]);

var isFiltering = [];

function getQueryUrlWithParams() {
    let query = 'user_id=';
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
    // id table links
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
        .url(`/api/links/getAllNewForUI_V2?${getQueryUrlWithParams()}`)
        .load();
    //
    await $.ajax({
        type: "GET",
        url: `/api/links/getAll?getAllNewForUI_V2?${getQueryUrlWithParams()}`,
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
        .url(`/api/links/getAll?is_scan[]=1`)
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
    let count = $('#number-link').val();
    let all = 0;
    let user_id = $('#user_id').val();

    // await $.ajax({
    //     type: "GET",
    //     url: "/api/links/getAll",
    //     success: function (response) {
    //         if (response.status == 0) {
    //             all = response.links.length;
    //             $('.count-link').text(`Số luồng: ${count}/${all}`);
    //         }
    //     }
    // });

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
            url: `/api/links/update`,
            data: {
                id,
                is_scan,
                user_id,
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


$(document).on("click", ".btn-delay-multiple", function () {
    tempAllRecord = [];
    tableData.forEach((e) => {
        tempAllRecord.push(e.link_or_post_id);
    });

    if (confirm("Bạn có muốn cập nhật các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            let delay = $('#delay-edit').val();
            $.ajax({
                type: "POST",
                url: `/api/links/updateDelayLink`,
                data: {
                    ids: tempAllRecord,
                    delay,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Cập nhật thành công");
                        reload();
                        dataTable.ajax.reload();
                        //
                        closeModal('modalEdit');
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

$(document).on("click", ".btn-stop-multiple", function () {
    tempAllRecord = [];
    tableData.forEach((e) => {
        tempAllRecord.push(e.link_or_post_id);
    });
    if (confirm("Bạn có muốn dừng các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/updateStatusLink`,
                data: {
                    ids: tempAllRecord,
                    status: 0,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Dừng thành công");
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

$(document).on("click", ".btn-run-multiple", function () {
    tempAllRecord = [];
    tableData.forEach((e) => {
        tempAllRecord.push(e.link_or_post_id);
    });
    if (confirm("Bạn có muốn chạy các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/updateStatusLink`,
                data: {
                    ids: tempAllRecord,
                    status: 1,
                },
                success: function (response) {
                    if (response.status == 0) {
                        toastr.success("Chạy thành công");
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

$(document).on("click", ".btn-delete-multiple", function () {
    if (confirm("Bạn có muốn xóa các link đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/links/deleteAll`,
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
            type: "DELETE",
            url: `/api/linkscans/${id}/destroy`,

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

function getListAccountNameByUserLink(name = '', userLinks = []) {
    let rs = [];
    if (name) {
        rs.push(name);
    }
    console.log(userLinks);
    userLinks.forEach((e) => {
        if (!rs.includes(e.email || e.name)) {
            rs.push(e.email || e.name);
        }
    });

    return rs.join('|');
}

function getListTitleByUserLink(title = '', userLinks = []) {
    let rs = [];
    if (title) {
        rs.push(title);
    }
    userLinks.forEach((e) => {
        if (!rs.includes(e || '')) {
            rs.push(e || '');
        }
    });

    return rs.join('|');
}

$(document).on("click", ".btn-status", function () {
    let status = $(this).data("status");
    //let user_id = $('#user_id').val();
    let text = status == 1 ? 'chạy' : 'dừng';
    if (confirm(`Bạn có muốn ${text} link này?`)) {
        let link_id = $(this).data("link_id");
        $.ajax({
            type: "POST",
            url: `/api/links/updateStatusByParentID`,
            data: {
                ids: [link_id],
                status,
                //user_id,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Cập nhật thành công");
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});

$(document).on("click", ".btn-reset", function () {
    if (confirm("Bạn có muốn làm mới link này?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/links/updateLinkByListLinkId`,
            data: {
                ids: [id],
                is_scan: 2,
            },
            success: function (response) {
                if (response.status == 0) {
                    toastr.success("Làm mới thành công");
                    reload();
                    dataTable.ajax.reload();
                } else {
                    toastr.error(response.message);
                }
            },
        });
    }
});
