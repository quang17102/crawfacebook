
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

    $.ajax({
        type: "GET",
        url: `/api/reactions/getAllPaginationParam?${query}`,
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

