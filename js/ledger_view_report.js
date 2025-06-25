$(document).ready(function () {

    //Closed Report Table
    $('#ledger_view_report_btn').click(function () {
        let toDate = $('#to_date').val();

        //Validation
        let to_date = $('#to_date').val();

        if (!to_date) {
            swalError('Please Fill Date!', 'Date is required.');
            return; // Stop execution if validation fails
        }

        url = 'api/report_files/get_ledger_view_report.php';

        $.ajax({
            type: "POST",
            url: url,
            data: { toDate: toDate },
            success: function (data) {
                $('.reportDiv').empty().html(data);
            }
        });
    });

    // <------------------------------------------------------------------------ Due Chart Start --------------------------------------------------------------->

    $(document).on('click', '.due-chart', async function () {

        var cus_id = $(this).data('cusid');
        var le_id = $(this).data('loanid');

        // Wait for chart to be rendered
        await dueChartList(le_id, cus_id);

        // Bind print click event after content is loaded
        $('.print_due_coll').off('click').on('click', async function () {
            const collection_id = $(this).attr('value');
            const result = await Swal.fire({
                title: 'Print',
                text: 'Do you want to print this collection?',
                imageUrl: 'img/printer.png',
                imageWidth: 300,
                imageHeight: 210,
                imageAlt: 'Custom image',
                showCancelButton: true,
                confirmButtonColor: '#009688',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes'
            });

            if (result.isConfirmed) {
                try {
                    const html = await new Promise((resolve, reject) => {
                        $.ajax({
                            url: 'api/collection_files/print_collection.php',
                            data: { 'collection_id': collection_id },
                            type: 'POST',
                            cache: false,
                            success: resolve,
                            error: reject
                        });
                    });

                    $('#printcollection').html(html);
                    const content = $("#printcollection").html(); // Optional usage
                } catch (err) {
                    console.error('Print load failed:', err);
                }
            }
        });
    });


    $(document).on('click', '.due-chart', function () {
        $('#due_chart_model').modal('show');
    });

});

// <------------------------------------------------------------------------ Due Chart Function Start --------------------------------------------------------------->

function dueChartList(le_id, cus_id) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: 'api/collection_files/get_due_chart_list.php',
            data: { 'le_id': le_id, 'cus_id': cus_id },
            type: 'POST',
            cache: false,
            success: function (response) {
                $('#due_chart_table_div').empty().html(response);

                // Now get the method name
                $.post('api/collection_files/get_due_method_name.php', { le_id }, function (res) {
                    $('#dueChartTitle').text('Due Chart ( ' + res['due_method'] + ' - ' + res['loan_type'] + ' )');
                    resolve();
                }, 'json').fail(reject);
            },
            error: reject
        });
    });
}

function closeChartsModal() {
    $('#due_chart_model').modal('hide');
}