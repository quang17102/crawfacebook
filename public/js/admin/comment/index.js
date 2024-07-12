var dataTable = null;
var allRecord = [];
var tempAllRecord = [];

var currentUrl = window.location.href;

function formatParameters(url) {
    var queryString = url.split('?')[1] ?? ''; // Get the query string part of the URL
    return queryString;
}

function getPageUrl(page) {
    if(formatParameters(currentUrl) == ''){
        query = "https://toolquet.com/admin/comments?today="+`${new Date().toJSON().slice(0, 10)}&page=${page}`;
    }else{
        var temp = formatParameters(currentUrl).replace(/&?page=\d+/g, '');
        query = 'https://toolquet.com/admin/comments?'+ temp + `&page=${page}`;
    }
    return  query;
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[?&]' + name + '=([^&#]*)');
    var results = regex.exec(url);
    return results === null ? '1' : decodeURIComponent(results[1].replace(/\+/g, ' ')) || '1';
}

$(document).ready(function () {
    var page = getParameterByName('page', currentUrl);
    var query = '';
    if(formatParameters(currentUrl) == ''){
        query = 'today='+`${new Date().toJSON().slice(0, 10)}&page=${page}`;
    }else{
        query = formatParameters(currentUrl)+ `&page=${page}`;
    }
    console.log(query);
    
    dataTable = $("#table").DataTable({
        columnDefs: [
            //{ visible: false, targets: 1 },
            //{ visible: false, targets: 2 },
            { visible: false, targets: 3 },
            { visible: false, targets: 6 },
            { visible: false, targets: 8 },
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
                        //title: '',
                    },
                    "colvis",
                ],
            },
            top2Start: 'pageLength',
        },
        ajax: {
            url: `/api/comments/getAllCommentNewPagination?${query}`,
            dataSrc: "comments",
        },
        columns: [
            {
                data: function (d) {
                    return `<input class="btn-select" type="checkbox" data-id="${d.id}" />`;
                }
                ,
                orderable: false
            },
            {
                data: function (d) {
                    return d.uid ?? '';
                },
            },
            {
                data: function (d) {
                    return d.created_at;
                },
            },
            {
                data: function (d) {
                    return d.content ?? '';
                },
            },
            
            {
                data: function (d) {
                    return d.accounts;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-title-comment tool-tip" data-type='content' data-content="${d.content_link}" data-link_or_post_id="${d.link_or_post_id}" data-id="${d.id}">${d.title}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-title tooltip-title-comment-${d.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return "https://facebook.com/"+d.link_or_post_id;
                },
            },
            {
                data: function (d) {
                    return `<p class="show-uid tool-tip" data-id="${d.id}" data-value="${d.uid}" data-uid="${d.uid}">${d.name_facebook || ''}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-uid tooltip-uid-${d.id}">
                    </div></p>`;
                },
            },
            {
                data: function (d) {
                    return "https://facebook.com/"+d.uid;
                    //return displayPhoneByRole(d.get_uid ? d.get_uid.phone : '');
                },
            },
            {
                data: function (d) {
                    return d.phone || '';
                    //return displayPhoneByRole(d.get_uid ? d.get_uid.phone : '');
                },
            },
            {
                data: function (d) {
                    return d.content;
                },
            },
            {
                data: function (d) {
                    return d.note;
                },
            },
            {
                data: function (d) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-note="${d.note}"
                            data-target="#modalEditComment" data-toggle="modal" data-id=${d.id}>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
            },
        ],
        paging : false,
        info : false
    });
    reload();

    //Pagination
    $.ajax({
        type: "GET",
        url: `/api/comments/getAllCommentNewPaginationParam?${query}`,
        success: function(response) {
            console.log('fetching data:', response);
                // Assuming response.totalPages is provided by your API
            var totalPages = response.last_page; // Assuming totalPages is 22
            var currentPage = response.current_page; // Assuming currentPage is 8
            
            // Clear existing pagination links
            $('#pagination').empty();
            
            // Add 'Previous' link
            if (currentPage > 1) {
                $('#pagination').append('<li class="page-item"><a class="page-link" href="' + getPageUrl(currentPage - 1) + '">Previous</a></li>');
            }
            
            // Add first page link
            if (currentPage > 1) {
                $('#pagination').append('<li class="page-item"><a class="page-link" href="' + getPageUrl(1) + '">1</a></li>');
            }
            
            // Add ellipsis before current page
            if (currentPage > 4) {
                $('#pagination').append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
            }
            
            // Determine which page numbers to display
            var startPage = Math.max(1, currentPage - 3);
            var endPage = Math.min(totalPages, currentPage + 3);
            
            // Add page number links
            for (var i = startPage; i <= endPage; i++) {
                var activeClass = (i === currentPage) ? 'active' : '';
                $('#pagination').append('<li class="page-item ' + activeClass + '"><a class="page-link" href="' + getPageUrl(i) + '">' + i + '</a></li>');
            }
            
            // Add ellipsis after current page
            if (currentPage < totalPages - 3) {
                $('#pagination').append('<li class="page-item disabled"><a class="page-link" href="#">...</a></li>');
            }
            
            // Add last page link
            if (currentPage < totalPages) {
                $('#pagination').append('<li class="page-item"><a class="page-link" href="' + getPageUrl(totalPages) + '">' + totalPages + '</a></li>');
            }
            
            // Add 'Next' link
            if (currentPage < totalPages) {
                $('#pagination').append('<li class="page-item"><a class="page-link" href="' + getPageUrl(currentPage + 1) + '">Next</a></li>');
            }

            $('.count-comment').text(`Bình luận: ${response.total}`);
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error('Error fetching data:', error);
        }
    });
});

$(document).on('click', '.btn-edit', function () {
    let id = $(this).data('id');
    let note = $(this).data('note');
    $('#note-edit').val(note);
    $('#id-editting').val(id);
});

$(document).on('click', '.btn-save', function () {
    let id = $('#id-editting').val();
    let note = $('#note-edit').val();
    $.ajax({
        type: "POST",
        url: `/api/comments/updateById`,
        data: {
            id,
            note
        },
        success: function (response) {
            if (response.status == 0) {
                toastr.success("Cập nhật thành công");
                dataTable.ajax.reload();
                reload();
                //
                closeModal('modalEditComment');
            } else {
                toastr.error(response.message);
            }
        },
    });
});

var searchParams = new Map([
    ["from", ""],
    ["to", ""],
    ["content", ""],
    ["phone", ""],
    ["note", ""],
    ["uid", ""],
    ["name_facebook", ""],
    ["title", ""],
    ["user", ""],
    ["link_or_post_id", ""],
]);

var isFiltering = [];

function getQueryUrlWithParams() {
    let query = `user_id=`;
    Array.from(searchParams).forEach(([key, values], index) => {
        query += `&${key}=${typeof values == "array" ? values.join(",") : values}`;
    })

    return query;
}

function getListAccountNameByUserLink(accounts = []) {
    let rs = [];
    accounts.forEach((e) => {
        if (!rs.includes(e)) {
            rs.push(e);
        }
    });

    return rs.join('|');
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
    console.log(window.location.href.split('?')[0]);
    window.location.href = window.location.href.split('?')[0] +"?" + getQueryUrlWithParams() +"&page=1";
});

$(document).on("click", ".btn-refresh", function () {
    window.location.reload();
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
async function AutoFresh(){
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

    dataTable.ajax
        .url("/api/comments/getAllCommentNew?" + getQueryUrlWithParams())
        .load();

    //
    await $.ajax({
        type: "GET",
        url: `/api/comments/getAllCommentNew?${getQueryUrlWithParams()}`,
        success: function (response) {
            if (response.status == 0) {
                response.comments.forEach((e) => {
                    tempAllRecord.push(e.id);
                });
            }
        }
    });
    reloadAll();
    //
    $('.count-comment').text(`Bình luận: ${tempAllRecord.length}`);
}
$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/comments/${id}/destroy`,
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

function reload() {
    // $.ajax({
    //     type: "GET",
    //     // url: `/api/comments/getAll`,
    //     url: dataTable.ajax.url(),
    //     success: function (response) {
    //         if (response.status == 0) {
    //             $('.count-comment').text(`Bình luận: ${response.comments.length}`);
    //         } else {
    //             toastr.error(response.message);
    //         }
    //     },
    // });

    tempAllRecord = [];
    reloadAll();
}

$(document).on("click", ".btn-delete-multiple", function () {
    if (confirm("Bạn có muốn xóa các comment đang hiển thị?")) {
        if (tempAllRecord.length) {
            $.ajax({
                type: "POST",
                url: `/api/comments/deleteAll`,
                data: { ids: tempAllRecord },
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
        } else {
            toastr.error('Link trống');
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
                AutoFresh();
            }, 20000);
        }
    }
});

$(document).on("click", ".btn-copy-uid", function () {
    let number = $('#number').val();
    let ids = tempAllRecord.length > number ? tempAllRecord.slice(0, number) : tempAllRecord
    $.ajax({
        type: "GET",
        url: `/api/comments/getAllCommentNew?limit=${number}&ids=${ids.join(',')}`,
        success: function (response) {
            if (response.status == 0) {
                let uids = [];
                let comments = ids.length ? response.comments.slice(0, $('#number').val()) : response.comments;
                comments.forEach((e) => {
                    uids.push(e.uid);
                });
                navigator.clipboard.writeText(uids.join('\n'));
                closeModal('modalCopyUid');
            } else {
                toastr.error(response.message);
            }
        },
    });
});
