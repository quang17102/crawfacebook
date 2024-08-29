
$(document).ready(function () {

    dataTable = $("#table").DataTable({
        dom: 'Bfrtip',
        // columnDefs: [
        //     //{ visible: false, targets: 1 },
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
            url: `/api/settingfilters/getAll`,
            dataSrc: "settings",
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
                    return d.data_cuoi_from || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.data_cuoi_to || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.reaction_from || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.reaction_from || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.delay || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return d.status || '';
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>`;
                },
                orderable: false,
            },
        ],
        paging : false,
        info : false
    });
});

