$(document).ready(function () {

    //Closed Report Table
    $('#bal_report_btn').click(function () {

         //Validation
        let to_date = $('#to_date').val();

        if (!to_date) {
            swalError('Please Fill Date!', 'date are required.');
            return; // Stop execution if validation fails
        }

        $('#bal_report_table').DataTable().destroy();
        getUserAccess(function(downloadAccess) {
            let buttons = [];
    
            // Add Excel button if download access is 1
            if (downloadAccess === 1) {
                buttons.push({
                    extend: 'excel',
                    title: "Balance Report List"
                });
            }
    
            // Add column visibility button
            buttons.push({
                extend: 'colvis',
                collectionLayout: 'fixed four-column',
            });
    
            $('#bal_report_table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'api/report_files/get_bal_report.php',
                    'data': function (data) {
                        var search = $('input[type=search]').val();
                        data.search = search;
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
    
                    // Remove formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
    
                    // Array of column indices to sum
                    var columnsToSum = [13,14,16,17,18];
    
                    // Loop through each column index
                    columnsToSum.forEach(function (colIndex) {
                        // Total over all pages for the current column
                        var total = api
                            .column(colIndex)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // Update footer for the current column
                        $(api.column(colIndex).footer()).html(`<b>` + total.toLocaleString() + `</b>`);
                    });
                }
            });
        });
    });
    
});
