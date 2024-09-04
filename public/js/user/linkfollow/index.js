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
