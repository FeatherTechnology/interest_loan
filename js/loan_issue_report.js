$(document).ready(function () {
    $('#from_date').change(function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        if (from_date > to_date) {
            $('#to_date').val('');
        }
        $('#to_date').attr('min', from_date);
    });

    $('#loan_issue_report_btn').click(function (event) {
        event.preventDefault();

        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();

        //  Validate both dates
        if (!from_date || !to_date) {
            swalError('Please Fill Dates!', 'Both From and To dates are required.');
            return;
        }

        let data = {
            'from_date': from_date,
            'to_date': to_date
        };

        // Proceed only if valid
        serverSideTable('#loan_issue_report_table', data, 'api/report_files/get_loan_issue_report.php', 'Loan Issue Report List');
    });
});
