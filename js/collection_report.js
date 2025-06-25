$(document).ready(function () {

    $('#from_date').change(function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        if (from_date > to_date) {
            $('#to_date').val('');
        }
        $('#to_date').attr('min', from_date);
    });

    $('#collection_report_btn').click(function () {

        //Validate both dates first
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();

        if (!from_date || !to_date) {
            swalError('Please Fill Dates!', 'Both From and To dates are required.');
            return; // Stop execution if validation fails
        }

        // Get user access for download
        getUserAccess(function (downloadAccess) {
            // Destroy existing DataTable instance
            $('#collection_report_table').DataTable().destroy();

            // Initialize an empty array for buttons
            let buttons = [];

            // Add Excel button only if download access is granted
            if (downloadAccess === 1) {
                buttons.push({
                    extend: 'excel',
                    title: "Collection Report List"
                });
            }

            // Add column visibility button
            buttons.push({
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            });

            // Initialize DataTable with dynamic buttons
            $('#collection_report_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'api/report_files/get_collection_report.php',
                    'data': function (data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
                        data.from_date = $('#from_date').val();
                        data.to_date = $('#to_date').val();
                    }
                },
                dom: 'lBfrtip',
                buttons: buttons,  // Use the dynamically constructed buttons array
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();

                    // Convert number to Indian money format
                    function moneyFormatIndia(num) {
                        let x = num.toString();
                        let afterPoint = '';
                        if (x.indexOf('.') > 0)
                            afterPoint = x.substring(x.indexOf('.'), x.length);
                        x = Math.floor(num).toString();
                        let lastThree = x.substring(x.length - 3);
                        let otherNumbers = x.substring(0, x.length - 3);
                        if (otherNumbers != '')
                            lastThree = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + ',' + lastThree;
                        return lastThree + afterPoint;
                    }

                    var intVal = function (i) {
                        return typeof i === 'string'
                            ? i.replace(/[\₹,]/g, '') * 1
                            : typeof i === 'number'
                                ? i
                                : 0;
                    };

                    var columnsToSum = [15, 16, 17, 18, 19];

                    columnsToSum.forEach(function (colIndex) {
                        var total = api
                            .column(colIndex)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        $(api.column(colIndex).footer()).html(`<b>${moneyFormatIndia(total)}</b>`);
                    });
                }
            });
        });
    });

});

