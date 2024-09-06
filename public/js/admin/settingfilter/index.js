
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
            url: `/admin/settingfilters/getAll`,
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
                    var result = (d.data_cuoi_from == '' || d.data_cuoi_from == null) ? '' : d.data_cuoi_from;
                    return (d.data_cuoi_from == '' || d.data_cuoi_from == null) ? '' : d.data_cuoi_from;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.data_cuoi_to == '' || d.data_cuoi_to == null) ? '' : d.data_cuoi_to;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.reaction_chenh_from == '' || d.reaction_chenh_from == null) ? '' : d.reaction_chenh_from;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.reaction_chenh_to == '' || d.reaction_chenh_to == null) ? '' : d.reaction_chenh_to;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.data_reaction_chenh_from == '' || d.data_reaction_chenh_from == null) ? '' : d.data_reaction_chenh_from;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.data_reaction_chenh_to == '' || d.data_reaction_chenh_to == null) ? '' : d.data_reaction_chenh_to;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.comment_chenh_from == '' || d.comment_chenh_from == null) ? '' : d.comment_chenh_from;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.comment_chenh_to == '' || d.comment_chenh_to == null) ? '' : d.comment_chenh_to;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.data_comment_chenh_from == '' || d.data_comment_chenh_from == null) ? '' : d.data_comment_chenh_from;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.data_comment_chenh_to == '' || d.data_comment_chenh_to == null) ? '' : d.data_comment_chenh_to;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.view_chenh_from == '' || d.view_chenh_from == null) ? '' : d.view_chenh_from;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.view_chenh_from == '' || d.view_chenh_from == null) ? '' : d.view_chenh_from;
                },
                orderable: false,
            },
            {
                data: function (d) {
                    return (d.delay == '' || d.delay == null) ? '' : d.delay;
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
                    return `
                            <a class="btn btn-primary btn-sm" href='/admin/settingfilters/update/${d.id}'>
                                <i class="fas fa-edit"></i>
                            </a>
                            <button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
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
$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa?")) {
        let id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: `/api/settingfilters/delete`,
            data: { id: id },
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
