$(document).ready(function () {

    // <----------------------------------------------------------------- Personal Info on Click start ---------------------------------------------------------------->

    $(document).on('click', '.closed-details', function (event) {
        event.preventDefault();
        $('#closed_list').hide();
        $('#closed_main_container,.back_to_closed_list').show();

        let cus_id = $(this).attr('value');

        $.post('api/collection_files/personal_info.php', { cus_id: cus_id }, function (response) {
            if (response.length > 0) {
                $('#cus_id').val(response[0].cus_id);
                $('#aadhar_number').val(response[0].aadhar_number);
                $('#first_name').val(response[0].first_name);
                $('#last_name').val(response[0].last_name);
                $('#area').val(response[0].areaname);
                $('#branch').val(response[0].branch_name);
                $('#line').val(response[0].linename);
                $('#mobile1').val(response[0].mobile1);

                let path = "uploads/customer_creation/cus_pic/";
                $("#per_pic").val(response[0].pic);
                $("#imgshow").attr("src", path + response[0].pic);

            }
        }, 'json');

        getClosedLoanList(cus_id);
    });

    // <----------------------------------------------------------------- Personal Info on Click End ---------------------------------------------------------------->

    $('#back_to_closed_list').click(function (event) {
        event.preventDefault();
        $('#closed_main_container,.back_to_closed_list').hide();
        $('#closed_list').show();
        getClosedListTable();
    });

    $(document).on('click', '.closed-move', function () {
        let cus_id = $(this).attr('value');
        let cus_sts = 12;
        moveToNext(cus_id, cus_sts);
    });

    // <----------------------------------------------------------------- Due Chart on Click start ---------------------------------------------------------------->

    $(document).on('click', '.due-chart', async function () {

        $('#due_chart_model').modal('show');

        const le_id = $(this).attr('value');
        const cus_id = $('#cus_id').val();

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

    // <----------------------------------------------------------------- Due Chart on Click End ----------------------------------------------------------------->

    // <----------------------------------------------------------------- Penalty Chart On Change Start ------------------------------------------------------------>

    $(document).on('click', '.penalty-chart', function () {
        $('#penalty_model').modal('show');
        let le_id = $(this).attr('value');
        let cus_id = $('#cus_id').val();
        $.ajax({
            //to insert penalty by on click
            url: 'api/collection_files/resetCustomerStatus.php',
            data: { 'le_id': le_id },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                penaltyChartList(le_id, cus_id); //To show Penalty List.
            }
        })
    });

    // <----------------------------------------------------------------- Penalty Chart On Change End ---------------------------------------------------------------->

    // <----------------------------------------------------------------- Fine Chart On Change Start ------------------------------------------------------------->

    $(document).on('click', '.fine-chart', function () {
        $('#fine_model').modal('show');
        var le_id = $(this).attr('value');
        fineChartList(le_id)
    });

    // <----------------------------------------------------------------- Fine Chart On Change End ---------------------------------------------------------------->

    // <------------------------------------------------------------------- Close on click start ------------------------------------------------------------------>

    $(document).on('click', '.closed-view', function () {

        let id = $(this).attr('value');
        $('#loan_entry_id').val(id)
        $('#closed_remark_model').modal('show');
    });

    // <-------------------------------------------------------------------- Close on click End ------------------------------------------------------------------->

    // <-------------------------------------------------------------------- Submit Closed Start -------------------------------------------------------------------->

    $('#submit_closed_remark').click(function (event) {
        event.preventDefault();
        if (validate()) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this closed remark?',
                function () {
                    let loan_entry_id = $('#loan_entry_id').val();
                    let sub_status = $('#sub_status').val();
                    let remark = $('#remark').val();
                    $.post('api/closed_files/closed_submit.php', { sub_status, remark, loan_entry_id }, function (response) {
                        if (response == '1') {
                            swalSuccess('Success', 'Closed Info Updated Successfully!');
                            $('#closed_remark_form input').val('');
                            $('#closed_remark_form select').val('');
                            $('#closed_remark_form textarea').val('');
                            $('#closed_remark_form input').css('border', '1px solid #cecece');
                            $('#closed_remark_form select').css('border', '1px solid #cecece');
                            $('#closed_remark_model').modal('hide');
                            let cus_id = $('#cus_id').val();
                            getClosedLoanList(cus_id);
                        } else {
                            swalError('Error', 'Failed to Closed');
                        }
                    }, 'json');
                }
            );
        }
    });

    // <-------------------------------------------------------------------- Submit Closed End -------------------------------------------------------------------->

    $('#closed_remark_model').on('hidden.bs.modal', function () {
        $('#closed_remark_form')[0].reset();
    });

});

// <----------------------------------------------------------------------  Function Start ------------------------------------------------------------------------>

$(function () {
    getClosedListTable();
});

function getClosedListTable() {
    serverSideTable('#closed_list_table', '', 'api/closed_files/close_list_table.php' , 'Closed List');
}

function getClosedLoanList(cus_id) {
    $.post('api/closed_files/closed_loan_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'loan_id',
            'loan_category',
            'loan_date',
            'closed_date',
            'loan_amount',
            'status',
            'sub_status',
            'charts',
            'action'
        ];
        appendDataToTable('#close_loan_table', response, columnMapping);
        setdtable('#close_loan_table', "Closed Loan List");
        //Dropdown in List Screen
        setDropdownScripts();
    }, 'json');
}

function moveToNext(cus_id, cus_sts) {
    $.post('api/closed_files/close_move_to_next.php', { cus_id, cus_sts }, function (response) {
        if (response == 0) {
            let alertName;
            if (cus_sts == '12') {
                alertName = 'Moved To NOC';
            }
            swalSuccess('Success', alertName);
            getClosedListTable();
        } else {
            swalError('Alert', 'Failed To Move');
        }
    }, 'json');
}

// <---------------------------------------------------------------------- Due Chart Function Start ------------------------------------------------------------------->

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
                    $('#dueChartTitle').text('Due Chart ( ' + res['cus_name'] + ' - ' + res['cus_id'] + ' - ' + res['loan_id'] + ' - ' + res['due_method'] + ' - ' + res['loan_type'] + ' )');
                    resolve();
                }, 'json').fail(reject);
            },
            error: reject
        });
    });
}

// <---------------------------------------------------------------------- Due Chart Function End -------------------------------------------------------------------->

// <----------------------------------------------------------------------- Fine Chart List Start -------------------------------------------------------------------->

function fineChartList(le_id) {
    $.ajax({
        url: 'api/collection_files/get_fine_chart_list.php',
        data: { 'le_id': le_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#fine_chart_table_div').empty()
            $('#fine_chart_table_div').html(response)
        }
    });
}

// <----------------------------------------------------------------------- Fine Chart List End -------------------------------------------------------------------->

// <----------------------------------------------------------------------- Penalty Chart List Start ---------------------------------------------------------------->

function penaltyChartList(le_id, cus_id) {
    $.ajax({
        url: 'api/collection_files/get_penalty_chart_list.php',
        data: { 'le_id': le_id, 'cus_id': cus_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#penalty_chart_table_div').empty()
            $('#penalty_chart_table_div').html(response)
        }
    });
}

// <----------------------------------------------------------------------- Penalty Chart List End -------------------------------------------------------------------->

// <-------------------------------------------------- Due Chart , Penalty Chart , Fine Chart Close Function Start ---------------------------------------------------->

function closeChartsModal() {
    $('#due_chart_model').modal('hide');
    $('#penalty_model').modal('hide');
    $('#fine_model').modal('hide');
    $('#closed_remark_model').modal('hide');
    $('#closed_remark_form input').val('');
    $('#closed_remark_form select').val('');
    $('#closed_remark_form textarea').val('');
    $('#closed_remark_form input').css('border', '1px solid #cecece');
    $('#closed_remark_form select').css('border', '1px solid #cecece');
}

// <--------------------------------------------------- Due Chart , Penalty Chart , Fine Chart Close Function End ----------------------------------------------------->

// <----------------------------------------------------------------------- Validate Function Start ------------------------------------------------------------------->

function validate() {
    let isValid = true;

    // Validate sub_status
    if (!validateField($('#sub_status').val(), 'sub_status')) {
        isValid = false;
    }

    // Validate loan_entry_id
    if (!validateField($('#loan_entry_id').val(), 'loan_entry_id')) {
        isValid = false;
    }

    return isValid;
}

// <----------------------------------------------------------------------- Validate Function End -------------------------------------------------------------------->
