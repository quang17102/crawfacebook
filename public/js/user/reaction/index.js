var dataTable = null;
var searchParams = new Map();
var is_display_phone = $('#is_display_phone').val();
var currentUrl = window.location.href;
let reactionsData = [];

function getPageUrl(page) {
    if(formatParameters(currentUrl) == ''){
        query = "https://toolquet.com/user/reactions?today="+`${new Date().toJSON().slice(0, 10)}&page=${page}`;
    }else{
        var temp = formatParameters(currentUrl).replace(/&?page=\d+/g, '');
        query = 'https://toolquet.com/user/reactions?'+ temp + `&page=${page}`;
    }
    return  query;
}

function formatParameters(url) {
    var queryString = url.split('?')[1] ?? ''; // Get the query string part of the URL
    return queryString;
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[?&]' + name + '=([^&#]*)');
    var results = regex.exec(url);
    return results === null ? '1' : decodeURIComponent(results[1].replace(/\+/g, ' ')) || '1';
}

function foooter(query){
    $.ajax({
        type: "GET",
        url: `/api/reactions/getAllPaginationParamUser?${query}`,
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

$(document).ready(function () {
    //reload();
    if(window.location.href.includes('phoneHide')){
        //$('.showPhone').text(`Lọc theo: ${isFiltering.join(',')}`);
        $('.showPhone').prop('checked', true);
    }
    var user_id = `user_id=${$('#user_id').val()}`;
    var page = getParameterByName('page', currentUrl);
    var query = '';
    if(formatParameters(currentUrl) == ''){
        query = 'today='+`${new Date().toJSON().slice(0, 10)}&page=${page}&${user_id}`;
    }else{
        query = formatParameters(currentUrl)+ `&page=${page}&${user_id}`;
    }

    dataTable = $("#table").DataTable({
        // columnDefs: [
        //     { visible: false, targets: 1 },
        //     { visible: false, targets: 2 },
        //     { visible: false, targets: 3 },
        // ],
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
            url: `/api/reactions/getAllPaginationUser?${query}`,
            dataSrc: function(json) {
                reactionsData = json.reactions; // Save comments to the global variable
                return json.reactions;
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
                    return d.link ? d.link.uid || '' : '';
                },
            },
            {
                data: function (d) {
                    return d.link ? d.link.content || '' : '';
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
                    return `<p class="show-title tool-tip" data-type='content' data-content="${d.content || ''}" data-link_or_post_id="${d.link ? d.link.link_or_post_id || '': '' }" data-id="${d.id}">${d.link ? d.link.title : ''}
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
                    //return displayPhoneByRole(d.get_uid ? d.get_uid.phone : '', is_display_phone);
                    return joinPhoneNumbers(d.get_uid,1, '' ) || '';
                },
            },
            // {
            //     data: function (d) {
            //         return d.reaction;
            //     },
            // },
            {
                data: function (d) {
                    return d.note || '';
                },
            },
            {
                data: function (d) {
                    return `<button data-id="${d.id || ''}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
            },
        ],
        paging : false,
        info : false,
    });

    foooter(query);
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
var tempAllRecord = [];
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
    //
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

// async function reload() {
//     await $.ajax({
//         type: "GET",
//         url: `/api/reactions/getAllPaginationUser?user_id=${$('#user_id').val()}`,
//         // url: `/api/reactions/getAll?today=${new Date().toJSON().slice(0, 10)}&user_id=${$('#user_id').val()}`,
//         success: function (response) {
//             if (response.status == 0) {
//                 $('.count-reaction').text(`Cảm xúc: ${response.reactions.length}`);
//             }
//         }
//     });
//     //
//     tempAllRecord = [];
//     reloadAll();
// }

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

$(document).on("click", ".btn-copy-uid", function () {
    let uids = [];
    let number = $('#number').val();
    let ids = tempAllRecord.length > number ? tempAllRecord.slice(0, number) : tempAllRecord
    if(ids.length > 0){
        ids.forEach((e) => {
            const result =  reactionsData.find(comment => comment.id === e).uid;
            uids.push(result);
        });
    }else
    {
        let comments = reactionsData.slice(0, number);
        comments.forEach((e) => {
            uids.push(e.uid);
        });
    }
    
    navigator.clipboard.writeText(uids.join('\n'));
    closeModal('modalCopyUid');
});
