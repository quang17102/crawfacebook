var dataTable = null;
var allRecord = [];
var tempAllRecord = [];

$(document).ready(function () {
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
            url: `/api/userlinks/getAll?user_id=${$('#user_id').val()}&type=1`,
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
                    if(!hasRole(d.roles,5)){
                        return '';
                    }
                    return d.datacuoi != null ? getDateDiffInHours(new Date(d.datacuoi), new Date()) : 'Trống';
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
                    return `<p class="" >${d.content}</p>`;
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
            // {
            //     data: function (d) {
            //         return permistion_reaction == "YES" ?  `<p class="show-history tool-tip" data-type="emotion_real" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.reaction_real}  ${getCountation(parseInt(d.diff_data_reaction))}<div style="display:none;
            //                                                             width: max-content;
            //                                                             background-color: black;
            //                                                             color: #fff;
            //                                                             border-radius: 6px;
            //                                                             position: absolute;
            //                                                             z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`
            //         : '0';
            //     },
            // },
            // {
            //     data: function (d) {
            //         return permistion_view == "YES" ? `<p class="show-history tool-tip" data-type="view" data-id="${d.id}" data-link_or_post_id="${d.link_or_post_id}">${d.view}  ${getCountation(parseInt(d.diff_view))}<div style="display:none;
            //                                                             width: max-content;
            //                                                             background-color: black;
            //                                                             color: #fff;
            //                                                             border-radius: 6px;
            //                                                             position: absolute;
            //                                                             z-index: 1;" class="tooltiptext tooltiptext-emotion tooltiptext-emotion-${d.id}"></div></p>`
            //         : '0';
            //     },
            // },
            {
                data: function (d) {
                    return '0';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return '0';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    if(!hasRole(d.roles,6)){
                        return '';
                    }
                    return d.is_scan != 2 ? `<button  class="btn btn-success btn-sm">ON</button>`
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
                    return `<a class="btn btn-primary btn-sm" href='/user/linkfollows/update/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" class="btn btn-success btn-sm btn-scan">
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
