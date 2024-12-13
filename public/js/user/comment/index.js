var currentUrl = window.location.href;
let commentsData = [];
var permistion_sdt = '';

function formatParameters(url) {
    var queryString = url.split('?')[1] ?? ''; // Get the query string part of the URL
    return queryString;
}
function foooter(query){
    $.ajax({
        type: "GET",
        url: `/api/comments/getAllCommentNewPaginationParamByUser?${query}`,
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
}
function getPageUrl(page) {
    if(formatParameters(currentUrl) == ''){
        query = "https://toolquet.com/user/comments?today="+`${new Date().toJSON().slice(0, 10)}&page=${page}`;
    }else{
        var temp = formatParameters(currentUrl).replace(/&?page=\d+/g, '');
        query = 'https://toolquet.com/user/comments?'+ temp + `&page=${page}`;
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

var dataTable = null;
var allRecord = [];
var tempAllRecord = [];
var is_display_phone = $('#is_display_phone').val();

$(document).ready(async function () {
    const json = await $.ajax({
        url: `/api/settings/getpermission?user_id=${$('#user_id').val()}`,
        method: 'GET'
    });
    permistion_sdt = json.permistion_sdt;

    var user_id = `user_id=${$('#user_id').val()}`;
    var page = getParameterByName('page', currentUrl);
    var query = '';
    if(formatParameters(currentUrl) == ''){
        query = 'today='+`${new Date().toJSON().slice(0, 10)}&page=${page}&${user_id}`;
    }else{
        query = formatParameters(currentUrl)+ `&page=${page}&${user_id}`;
    }

    if(window.location.href.includes('phoneHide')){
        //$('.showPhone').text(`Lọc theo: ${isFiltering.join(',')}`);
        $('.showPhone').prop('checked', true);
    }

    dataTable = $("#table").DataTable({
        columnDefs: [
            //{ visible: false, targets: 1 },
            { visible: false, targets: 4 },
            { visible: permistion_sdt == "YES", targets: 6 },
            { visible: false, targets: 7 },
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
                            columns: ":not(:first-child):not(:last-child)",
                        },
                        title: '',
                    },
                    "colvis",
                ],
            },
            top2Start: 'pageLength',
        },
        ajax: {
            url: `/api/comments/getAllCommentNewPaginationByUser?${query}`,
            dataSrc: function(json) {
                commentsData = json.comments; // Save comments to the global variable
                return json.comments;
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
                    return d.created_at || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return handeForUID(d.uid, d.roles) || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class="show-title-comment tool-tip" data-type='content' data-content="${d.link.content || ''}" data-link_or_post_id="${d.link_or_post_id }" data-id="${d.id}" style="margin: 0;">${d.title}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                width: 40%;
                                margin: 0;
                                z-index: 1;" class="tooltip-title tooltip-title-comment-${d.id}">
                    </div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return 'https://facebook.com/'+ d.link_or_post_id;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<p class="show-name_facebook tool-tip" data-id="${d.id}" data-uid="${handeForUID(d.uid, d.roles)}" style="margin: 0;">${d.name_facebook || ''}
                    <div style="display:none;width: max-content;
                                background-color: black;
                                color: #fff;
                                border-radius: 6px;
                                padding: 5px 10px;
                                position: absolute;
                                z-index: 1;" class="tooltip-name_facebook tooltip-name_facebook-${d.id}">
                    </div></p>`;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return joinPhoneNumbers(d.get_uid, d.roles, d.content) || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return 'https://facebook.com/'+ d.uid;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.link.content || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    //return '';
                    return d.content || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.note|| '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-note="${d.note}"
                            data-target="#modalEditComment" data-toggle="modal" data-id=${d.id}>
                                <i class="fas fa-edit"></i>
                            </button>`
                            ;
                },
                orderable: false,
            },
        ],
        paging : false,
        info : false,
        // initComplete: function() {
        //     var api = this.api();
    
        //     // Custom filtering function
        //     $.fn.dataTable.ext.search.push(
        //         function(settings, data, dataIndex) {
        //             var rowData = api.row(dataIndex).data();
        //             var showOnlyWithPhone = $('#showPhone').is(':checked');
        //             var hideWithPhone = $('#hidePhone').is(':checked');
        //             var showAllWithPhone = $('#showAll').is(':checked');
        //             var phoneNumber = joinPhoneNumbers(rowData.get_uid, 1, rowData.content);
        //             if(showAllWithPhone) return true;
                    
        //             if (showOnlyWithPhone) {
        //                 return phoneNumber ? true : false;
        //             }
        //             if (hideWithPhone) {
        //                 return phoneNumber ? false : true;
        //             }
        //             return true;
        //         }
        //     );
    
        //     // Checkbox change event
        //     $('#showPhone').on('change', function() {
        //         api.draw();
        //     });
        //     // Checkbox change event
        //     $('#hidePhone').on('change', function() {
        //         api.draw();
        //     });
        //     // Checkbox change event
        //     $('#showAll').on('change', function() {
        //         api.draw();
        //     });
        // }
    });
    reload();
    //Pagination
    foooter(query);
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
    ["link_or_post_id", ""],
]);

var isFiltering = [];

$(document).on('click', '.showPhone', function () {
    var showOnlyWithPhone = $('#showPhone').is(':checked');
    var isPhone = '';
    if(showOnlyWithPhone){
        isPhone = 'DisplayPhone';
    }
    var url = window.location.href;
    if(url.includes('?')){
        url = url + `&phoneHide=${isPhone}`;
    }else
    {
        url = url + `?phoneHide=${isPhone}&page=1&today=${new Date().toJSON().slice(0, 10)}`;
    }
    window.location.href = url;
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
    //$('.count-select').text(`Đã chọn: ${tempAllRecord.length}`);
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
    var user_id = `user_id=${$('#user_id').val()}`;
    window.location.href = window.location.href.split('?')[0] +"?" + getQueryUrlWithParams() +"&page=1&"+user_id;
});
async function AutoFresh()
{
    var page = getParameterByName('page', currentUrl);
    var query = '';
    if(formatParameters(currentUrl) == ''){
        query = 'today='+`${new Date().toJSON().slice(0, 10)}&page=${page}`;
    }else{
        query = formatParameters(currentUrl)+ `&page=${page}`;
    }
    var user_id = `user_id=${$('#user_id').val()}`;
    query =query + "&"+ user_id;
    // reload
    // dataTable.clear().rows.add(tempAllRecord).draw();
    dataTable.ajax
        .url("/api/comments/getAllCommentNewPaginationByUser?" + query)
        .load();

    foooter(query);
    
}
$(document).on("click", ".btn-refresh", function () {
    Array.from(searchParams).forEach(([key, values], index) => {
        $('#' + key).val('');
    });

    // display filtering
    isFiltering = [];
    displayFiltering();

    // reload table
    dataTable.ajax
        .url(`/api/comments/getAll?user_id=${$('#user_id').val()}&type=1`)
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

async function reload() {
    //console.log(dataTable.ajax.url());
    // $.ajax({
    //     type: "GET",
    //     url: dataTable.ajax.url(),
    //     data: { ids: tempAllRecord },
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
    let uids = [];
    let number = $('#number').val();
    let ids = tempAllRecord.length > number ? tempAllRecord.slice(0, number) : tempAllRecord
    if(ids.length > 0){
        ids.forEach((e) => {
            const result =  commentsData.find(comment => comment.id === e).uid;
            uids.push(result);
        });
    }else
    {
        let comments = commentsData.slice(0, number);
        comments.forEach((e) => {
            uids.push(e.uid);
        });
    }
    
    navigator.clipboard.writeText(uids.join('\n'));
    closeModal('modalCopyUid');
});
