var dataTable = null;
$(document).ready(function () {
    dataTable = $("#table").DataTable({
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: "excel",
                        text: "Xuất Excel",
                        exportOptions: {
                            columns: ":not(:last-child)",
                        },
                        title: '',
                    },
                    "colvis",
                ],
            },
        },
        ajax: {
            url: "/admin/accounts",
            dataSrc: "accounts",
        },
        columns: [
            {
                data: "id",
            },
            {
                data: "name",
            },
            {
                data: "email",
            },
            {
                data: "delay",
            },
            {
                data: "limit",
            },
            {
                data: "limit_follow",
            },
            {
                data: function (d) {
                    return getDateDiffInDays(new Date(), new Date(d.expire));
                }
            },
            {
                data: function (d) {
                    let btnDelete = `<button data-id="${d.id}" class="btn btn-danger btn-sm btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>`;
                    let btnLinkScan = `<a class="btn btn-success btn-sm" href='/admin/linkscans?user_id=${d.id}'>
                                    <i class="fas fa-solid fa-barcode"></i>
                                </a>`;
                    let btnLinkFollow = `<a class="btn btn-success btn-sm" href='/admin/linkfollows?user_id=${d.id}'>
                                    <i class="fa-solid fa-user-plus"></i>
                                </a>`;

                    return `<a class="btn btn-primary btn-sm" href='/admin/accounts/update/${d.id}'>
                            <i class="fas fa-edit"></i>
                            </a>
                            ${$("#logging_user_id").val() != d.id &&
                            $("#editing_user_id").val() != d.id
                            ? btnDelete
                            : ""}`;
                },
            },
        ],
    });
});

$(document).on("click", ".btn-delete", function () {
    if (confirm("Bạn có muốn xóa")) {
        let id = $(this).data("id");
        $.ajax({
            type: "DELETE",
            url: `/api/accounts/${id}/destroy`,

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
