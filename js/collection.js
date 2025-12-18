$(document).ready(function () {

    // <-------------------------------------------------------------- Back Button Click -------------------------------------------------------------->
    $('#back_to_coll_list').click(function () {
        swapTableAndCreation();
        let coll_sts = $('#coll_sts').val();
        if (coll_sts != '') {
            $("#due_nill_btn").click();
        } else {
            getCollectionListTable('');
        }
    });

    // <-------------------------------------------------------------- View Button Click -------------------------------------------------------------->
    $(document).on('click', '.collection-details', function () {
        let cusId = $(this).attr('value');
        let sts = $(this).attr('sts');
        getPersonalInfo(cusId, sts);
        OnLoadFunctions(cusId)
        $('#collection_list').hide();
        $('#back_to_coll_list').show();
        $('#coll_main_container').show();
    });

    // <-------------------------------------------------------------- Pay Due Button Click -------------------------------------------------------------->
    $(document).on('click', '.pay-due', function () {
        let le_id = $(this).attr('value');
        $('.colls-cntnr').hide();
        $('.till_now_pay').hide();
        $('#back_to_coll_list').hide();
        $('.coll_details').show();
        $('#back_to_loan_list').show();

        {
            // Get today's date
            var today = new Date();

            // Extract day, month, and year
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed, so add 1
            var year = today.getFullYear();

            // Construct the date in dd-mm-yyyy format
            var formattedDate = day + '-' + month + '-' + year;

            // Set loan date
            $('#collection_date').val(formattedDate);
        }

        //To get the loan category ID to store when collection form submitted
        $.ajax({
            url: 'api/collection_files/collection_details.php',
            data: { "le_id": le_id },
            dataType: 'json',
            type: 'post',
            cache: false,
            success: function (response) {
                $('#loan_category_id').val(response['loan_category']);

                if (response['collection_access'] == '2') {
                    $('.collection_access_div').hide();

                } else {
                    $('.collection_access_div').show();
                }
            }
        })
        var status = $(this).closest('#loan_list_table tbody tr').find('td:nth-child(7)').text()
        var sub_status = $(this).closest('#loan_list_table tbody tr').find('td:nth-child(8)').text()

        $('#le_id').val(le_id)
        $('#status').val(status)
        $('#sub_status').val(sub_status)

        //To get Collection Code
        getCollectionCode();

        // <-------------------------------------------- Second Time No Need To Call ResetCustomerStatus.php--------------------------------------------------->

        if (!customerStatusResponse) {
            alert('Customer data not loaded. Please reload the page.');
            return;
        }

        let index = customerStatusResponse['le_id']?.map(String).indexOf(String(le_id));


        if (index !== -1 && index !== undefined) {
            let res = customerStatusResponse;

            $('#loan_amount').val(moneyFormatIndia(res['loanAmount'][index]));
            $('#paid_amount').val(moneyFormatIndia(res['Paid_Amount'][index]));
            $('#balance_amount').val(moneyFormatIndia(res['balAmnt'][index]));
            $('#interest_amount').val(moneyFormatIndia(res['Interest_Amount'][index]))
            $('#pending_amount').val(moneyFormatIndia(res['pending_as_req'][index]));
            $('#payable_amount').val(moneyFormatIndia(res['payable_as_req'][index]));
            $('#penalty').val(moneyFormatIndia(res['Penalty'][index]))
            $('#fine_charge').val(moneyFormatIndia(res['Fine_Charge'][index]));
            $('#till_now_payable').val(moneyFormatIndia(res['till_Date_Int'][index]));
        } else {
            console.warn("Loan entry ID not found in customerStatusResponse.");
        }

        $('#loan_amount').prev().prev().text('Loan Amount')
        $('#interest_amount').prev().prev().text('Interest Amount')

        // Show all in span class
        $('.totspan').text('*')
        $('.paidspan').text('*')
        $('.balspan').text('*')
        $('.pendingspan').text('*')
        $('.payablespan').text('*')

        let validateInterest = true; // default is ON

        // Toggle validation when checkbox is clicked
        $('#till_now_pay_checkbox').on('change', function () {
            validateInterest = !$(this).is(':checked');

            // If checkbox is UNCHECKED, clear the input fields
            if (!$(this).is(':checked')) {
                $('#interest_amount_track').val("");
                $('#total_paid_track').val("");
            }
        });

        // Interest Amount Track Validation
        $('#interest_amount_track').on('blur', function () {
            const enteredValue = parseFloat($(this).val());
            const leId = String($('#le_id').val());
            const index = customerStatusResponse['le_id']?.map(String).indexOf(leId);
            // Use till_Date_Int if checkbox is checked, else use payable_as_req
            let payable = validateInterest
                ? parseFloat(customerStatusResponse['payable_as_req'][index])
                : parseFloat(customerStatusResponse['till_Date_Int'][index]);

            if (enteredValue > payable) {
                alert("Enter a Lesser Value");
                $(this).val("");
                $('#total_paid_track').val("");
            }
        });

        // Principal Amount Track Validation
        $('#principal_amount_track').on('blur', function () {
            let index = customerStatusResponse['le_id']?.map(String).indexOf(String($('#le_id').val()));
            let balance_amount = parseFloat(customerStatusResponse['balAmnt'][index]);

            if (parseFloat($(this).val()) > balance_amount) {
                alert("Enter a Lesser Value");
                $(this).val("");
                $('#principal_amount_track').val("");
            }
        });

        // Penalty Track Validation
        $('#penalty_track').on('blur', function () {
            let index = customerStatusResponse['le_id']?.map(String).indexOf(String($('#le_id').val()));
            let penaltyValue = parseFloat($(this).val());
            let penaltyLimit = parseFloat(customerStatusResponse['Penalty'][index]);

            if (isNaN(penaltyValue)) return;

            if (penaltyValue > penaltyLimit) {
                alert("Enter a Lesser Value");
                $(this).val("");
                $('#total_paid_track').val("");
            }
        });

        // Fine Charge Track Validation
        $('#fine_charge_track').on('blur', function () {
            let index = customerStatusResponse['le_id']?.map(String).indexOf(String($('#le_id').val()));
            let chargeEntered = parseFloat($(this).val());
            let fineLimit = parseFloat(customerStatusResponse['Fine_Charge'][index]);

            if (chargeEntered > fineLimit) {
                alert("Enter a Lesser Value");
                $(this).val("");
                $('#total_paid_track').val("");
            }
        });

        // Penalty Waiver Validation
        $('#penalty_waiver').on('blur', function () {
            let index = customerStatusResponse['le_id']?.map(String).indexOf(String($('#le_id').val()));
            let penalty_track = parseFloat($('#penalty_track').val()) || 0;
            let penaltyLimit = parseFloat(customerStatusResponse['Penalty'][index]);

            if (parseFloat($(this).val()) > (penaltyLimit - penalty_track)) {
                alert("Enter a Lesser Value");
                $(this).val("");
                $('#total_waiver').val("");
            }
        });

        // Fine Charge Waiver Validation
        $('#fine_charge_waiver').on('blur', function () {
            let index = customerStatusResponse['le_id']?.map(String).indexOf(String($('#le_id').val()));
            let fine_charge_track = parseFloat($('#fine_charge_track').val()) || 0;
            let fineLimit = parseFloat(customerStatusResponse['Fine_Charge'][index]);

            if (parseFloat($(this).val()) > (fineLimit - fine_charge_track)) {
                alert("Enter a Lesser Value");
                $(this).val("");
                $('#total_waiver').val("");
            }
        });

    });

    $(document).on('click', '#back_to_loan_list', function () {
        let cusid = $('#cus_id').val();
        OnLoadFunctions(cusid);
        $('.clearFields').val('');
        $('.colls-cntnr').show();
        $('#back_to_coll_list').show();
        $('.coll_details').hide();
        $('#back_to_loan_list').hide();
        $('#collection_mode').trigger('change');
        $('#coll_main_container input').css('border', '1px solid #cecece');
        $('#coll_main_container select').css('border', '1px solid #cecece');
    });

    $('#collection_mode').change(function () {
        var collection_mode = $(this).val();
        if (collection_mode != '') {
            getBankNames();
        }
        //Clear All Value initially
        $('#trans_id').val('')
        if (collection_mode == '2') { //Cheque
            $('.transaction').show();

        } else if (collection_mode == '1') { //Cash
            $('.transaction').hide();

        } else {//If nothing chosen
            $('.transaction').hide();
        }

        resetValidation()
    });

    // <------------------------------------------------------------------ Fine On Change Start ------------------------------------------------------------------->

    $(document).on('click', '.fine-form', function (e) {
        let le_id = $(this).attr('value');
        $('#fine_le_id').val(le_id);
        $('#fine_form_modal').modal('show');
        setCurrentDate('#fine_date');
        getFineFormTable(le_id);
    });


    //Fine Submit
    $('#fine_form_submit').click(function (event) {
        event.preventDefault();
        let fine_le_id = $('#fine_le_id').val();
        let cus_id = $('#cus_id').val();
        let fine_date = $('#fine_date').val();
        let fine_purpose = $('#fine_purpose').val();
        let fine_Amnt = $('#fine_Amnt').val();
        var data = ['fine_le_id', 'cus_id', 'fine_date', 'fine_purpose', 'fine_Amnt']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $.post('api/collection_files/submit_fine_form.php', { fine_le_id, cus_id, fine_date, fine_purpose, fine_Amnt }, function (response) {
                if (response == '1') {
                    swalSuccess('Success', 'Fine Added Successfully.');
                    getFineFormTable(fine_le_id);
                } else {
                    swalError('Error', 'Failed to Add Fine');
                }
            }, 'json');
        }
    })

    // <----------------------------------------------------------------- Fine On Change End --------------------------------------------------------------------->

    // <----------------------------------------------------------------- Due Chart on Click start ---------------------------------------------------------------->

    $(document).on('click', '.due-chart', async function () {
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


    $(document).on('click', '.due-chart', function () {
        $('#due_chart_model').modal('show');
    });

    // <----------------------------------------------------------------- Due Chart on Click End ----------------------------------------------------------------->

    // <----------------------------------------------------------------- Fine Chart On Change Start ------------------------------------------------------------->

    $(document).on('click', '.fine-chart', function () {
        var le_id = $(this).attr('value');
        fineChartList(le_id)
    });

    $(document).on('click', '.fine-chart', function (e) {
        $('#fine_model').modal('show');
    });

    // <----------------------------------------------------------------- Fine Chart On Change  End ---------------------------------------------------------------->

    // <----------------------------------------------------------------- Penalty Chart On Change Start ------------------------------------------------------------>

    $(document).on('click', '.penalty-chart', function () {
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

    $(document).on('click', '.penalty-chart', function () {
        $('#penalty_model').modal('show');
    });

    // <----------------------------------------------------------------- Penalty Chart On Change End ---------------------------------------------------------------->

    // <--------------------------------------------------------------- Till Now Payable Button Click Start ---------------------------------------------------------->

    $('#till_now').on('click', function (e) {
        e.preventDefault(); // prevent form submission if button is inside a form

        if ($('.till_now_pay').is(':visible')) {
            $('.till_now_pay').hide(); // Hide if already visible
        } else {
            $('.till_now_pay').show(); // Show if hidden
            $('#till_now_pay_checkbox').prop('checked', false); // Uncheck the checkbox
        }
    });

    // <----------------------------------------------------------------- Till Now Payable Button Click End ----------------------------------------------------------->

    // <---------------------------------------------------------- Collection Track Total Paid Blur change Start ------------------------------------------------------>

    $('#interest_amount_track, #penalty_track , #fine_charge_track , #principal_amount_track ').blur(function () {

        var interest_amount_track = ($('#interest_amount_track').val() != '') ? $('#interest_amount_track').val().replace(/,/g, '') : 0;
        var penalty_track = ($('#penalty_track').val() != '') ? $('#penalty_track').val().replace(/,/g, '') : 0;
        var fine_charge_track = ($('#fine_charge_track').val() != '') ? $('#fine_charge_track').val().replace(/,/g, '') : 0;
        var principal_amount_track = ($('#principal_amount_track').val() != '') ? $('#principal_amount_track').val().replace(/,/g, '') : 0;

        var total_paid_track = + parseInt(interest_amount_track) + parseInt(penalty_track) + parseInt(fine_charge_track) + parseInt(principal_amount_track);
        $('#total_paid_track').val(moneyFormatIndia(total_paid_track));
    });

    // <------------------------------------------------------------ Collection Track Total Paid Blur change End ------------------------------------------------------>

    // <----------------------------------------------------------- Collection Track Total Waiver Blur change Start ---------------------------------------------------->

    $('#interest_waiver , #penalty_waiver , #fine_charge_waiver , #principal_waiver').blur(function () {

        var interest_waiver = ($('#interest_waiver').val() != '') ? $('#interest_waiver').val().replace(/,/g, '') : 0;
        var penalty_waiver = ($('#penalty_waiver').val() != '') ? $('#penalty_waiver').val().replace(/,/g, '') : 0;
        var fine_charge_waiver = ($('#fine_charge_waiver').val() != '') ? $('#fine_charge_waiver').val().replace(/,/g, '') : 0;
        var principal_waiver = ($('#principal_waiver').val() != '') ? $('#principal_waiver').val().replace(/,/g, '') : 0;

        var total_waiver = parseInt(interest_waiver) + parseInt(penalty_waiver) + parseInt(fine_charge_waiver) + parseInt(principal_waiver);
        $('#total_waiver').val(moneyFormatIndia(total_waiver));
    });

    // <----------------------------------------------------------- Collection Track Total WaiverBlur change Start ----------------------------------------------------->

    // <--------------------------------------------------------------------- Submit Collection Start ------------------------------------------------------------------>

    $('#submit_collection').click(async function (event) {
        event.preventDefault();
        $(this).attr('disabled', true);

        let LoanEntryId = $('#le_id').val();

        let collData = {
            'le_id': LoanEntryId,
            'cus_id': $('#cus_id').val(),
            'status': $('#status').val(),
            'sub_status': $('#sub_status').val().trim(),
            'loan_amount': $('#loan_amount').val().replace(/,/g, ''),
            'paid_amount': $('#paid_amount').val().replace(/,/g, ''),
            'balance_amount': $('#balance_amount').val().replace(/,/g, ''),
            'interest_amount': $('#interest_amount').val().replace(/,/g, ''),
            'pending_amount': $('#pending_amount').val().replace(/,/g, ''),
            'payable_amount': $('#payable_amount').val().replace(/,/g, ''),
            'penalty': $('#penalty').val().replace(/,/g, ''),
            'fine_charge': $('#fine_charge').val().replace(/,/g, ''),
            'till_now_payable': $('#till_now_payable').val().replace(/,/g, ''),
            'interest_amount_track': $('#interest_amount_track').val().replace(/,/g, ''),
            'penalty_track': $('#penalty_track').val().replace(/,/g, ''),
            'fine_charge_track': $('#fine_charge_track').val().replace(/,/g, ''),
            'principal_amount_track': $('#principal_amount_track').val().replace(/,/g, ''),
            'total_paid_track': $('#total_paid_track').val().replace(/,/g, ''),
            'interest_waiver': $('#interest_waiver').val().replace(/,/g, ''),
            'penalty_waiver': $('#penalty_waiver').val().replace(/,/g, ''),
            'fine_charge_waiver': $('#fine_charge_waiver').val().replace(/,/g, ''),
            'principal_waiver': $('#principal_waiver').val().replace(/,/g, ''),
            'total_waiver': $('#total_waiver').val().replace(/,/g, ''),
            'collection_date': $('#collection_date').val(),
            'collection_id': $('#collection_id').val(),
            'collection_mode': $('#collection_mode').val(),
            'bank_id': $('#bank_id').val(),
            'trans_id': $('#trans_id').val()
        };

        swalConfirm(
            'Are you sure?',
            'Do you want to submit this collection?',
            async function () {

                try {
                    // Check if form data is valid before proceeding
                    if (!isFormDataValid(collData)) {
                        $('#submit_collection').attr('disabled', false);
                        return; // Exit if invalid
                    }

                    const response = await new Promise((resolve, reject) => {
                        $.post('api/collection_files/submit_collection.php', collData, function (res) {
                            resolve(res);
                        }, 'json').fail(reject);
                    });

                    $('#submit_collection').attr('disabled', false);

                    if (response.result == '1') {
                        swalSuccess('Success', 'Collection Added Successfully.');
                    } else if (response.result == '2') {
                        swalError('Error', 'Failed to Insert Collection');
                        return;
                    } else if (response.result == '3') {
                        swalSuccess('Success', 'Moved to Closed Successfully.');
                    }

                    $('#back_to_loan_list').trigger('click');

                    if (response.collection_id) {
                        await printCollection(response.collection_id);
                    }

                    await getSubStatus(LoanEntryId);

                } catch (error) {
                    console.error("Collection submit failed:", error);
                    $('#submit_collection').attr('disabled', false);
                }
            }
        );
    });

    // <------------------------------------------------------------------ Submit Collection End ---------------------------------------------------------------->

});

$(function () {
    getCollectionListTable('');
});

function getCollectionListTable(collection_status) {
    let params = { 'collection_status': collection_status };
    serverSideTable('#collection_list_table', params, 'api/collection_files/collection_list.php', "Collection List");
}

function swapTableAndCreation() {
    if ($('#collection_list').is(':visible')) {
        $('#collection_list').hide();
        $('#coll_main_container').show();
        $('#back_to_coll_list').show();

    } else {
        $('#collection_list').show();
        $('#coll_main_container').hide();
        $('#back_to_coll_list').hide();
    }
}

function getPersonalInfo(cusId, sts) {
    $.post('api/collection_files/personal_info.php', { cus_id: cusId }, function (response) {
        if (response.length > 0) {
            $('#coll_sts').val(sts);
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
}

var customerStatusResponse = null;

function OnLoadFunctions(cus_id) {
    //To get loan sub Status
    var balAmnt = [];
    var sub_status_arr = [];
    $.ajax({
        url: 'api/collection_files/resetCustomerStatus.php',
        data: { 'cus_id': cus_id },
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            customerStatusResponse = response; // Save it for reuse
            if (response.length != 0) {
                let pendingCount = (response['pending_customer']) ? response['pending_customer'].length : 0;
                for (var i = 0; i < pendingCount; i++) {
                    balAmnt[i] = response['balAmnt'][i]
                    sub_status_arr[i] = response['sub_status_customer']?.[i] ?? 'Null';
                }
                balAmnt = balAmnt.join(',');
            }
        }
    }).then(function () {
        showOverlay();//loader start
        var bal_amt = balAmnt;
        $.ajax({
            //in this file, details gonna fetch by customer ID, Not by req id (Because we need all loans from customer)
            url: 'api/collection_files/collection_loan_list.php',
            data: { 'cus_id': cus_id, 'bal_amt': bal_amt, 'sub_status': sub_status_arr },
            type: 'post',
            dataType: 'json',
            cache: false,
            success: function (response) {
                $('.overlay').remove();
                var columnMapping = [
                    'sno',
                    'loan_id',
                    'loan_category',
                    'issue_date',
                    'loan_amount',
                    'interest_rate_calc',
                    'bal_amount',
                    'status',
                    'sub_status',
                    'charts',
                    'action'
                ];
                appendDataToTable('#loan_list_table', response, columnMapping);
                setdtable('#loan_list_table', "Loan List");
                //Dropdown in List Screen
                setDropdownScripts();
            }
        });
        hideOverlay();//loader stop
    });
}//Auto Load function END

// <----------------------------------------------------------------------- Bank Name Function Start ---------------------------------------------------------------->

function getBankNames() {
    $.ajax({
        url: 'api/common_files/bank_name_list.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#bank_id').empty();
            $('#bank_id').append('<option value="">Select Bank Name</option>');
            $.each(response, function (ind, val) {
                $('#bank_id').append('<option value="' + val['id'] + '">' + val['bank_name'] + '</option>');
            })

        }
    })
}

// <----------------------------------------------------------------------- Bank Name Function End ------------------------------------------------------------------->

// <---------------------------------------------------------------------- Fine Function Start ----------------------------------------------------------------------->

function getFineFormTable(le_id) {
    $.post('api/collection_files/fine_form_list.php', { le_id }, function (response) {
        let fineColumn = [
            'sno',
            'fine_date',
            'fine_purpose',
            'fine_charge'
        ];
        appendDataToTable('#fine_form_table', response, fineColumn);
        setdtable('#fine_form_table', "Fine List");

        $('#fine_purpose').val('');
        $('#fine_Amnt').val('');
        $('#fine_purpose').css('border', '1px solid #cecece');
        $('#fine_Amnt').css('border', '1px solid #cecece');
    }, 'json');
}

function closeFineChartModal() {
    $('#fine_form_modal').modal('hide');
    let cus_id = $('#cus_id').val();
    OnLoadFunctions(cus_id);
}

// <---------------------------------------------------------------------- Fine Function End ------------------------------------------------------------------------->

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

function closeChartsModal() {
    $('#due_chart_model').modal('hide');
    $('#penalty_model').modal('hide');
    $('#fine_model').modal('hide');
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

// <----------------------------------------------------------------------- Collection Id Function Start --------------------------------------------------------------->

function getCollectionCode() {
    $.ajax({
        url: 'api/collection_files/collection_code.php',
        data: {},
        dataType: 'json',
        type: 'post',
        cache: false,
        success: function (response) {
            $('#collection_id').val(response)
        }
    });
}

// <----------------------------------------------------------------------- Collection Id Function  End -------------------------------------------------------------->

// <----------------------------------------------------------------------- Get Sub Status Function Start  ----------------------------------------------------------->

async function getSubStatus(le_id) {
    let sub_status = '';

    $('#loan_list_table tbody tr').each(function () {
        const row_le_id = $(this).find('.pay-due').attr('value');
        if (row_le_id == le_id) {
            // Get Sub Status from 8th column (adjust index if needed)
            sub_status = $(this).find('td:nth-child(8)').text().trim();
        }
    });

    try {
        const response = await new Promise((resolve, reject) => {
            $.ajax({
                url: 'api/collection_files/get_sub_status.php',
                data: { le_id, sub_status },
                dataType: 'json',
                type: 'POST',
                cache: false,
                success: resolve,
                error: reject
            });
        });

        console.log("Response received:", response);
        return response;

    } catch (error) {
        console.error("Failed to fetch sub status:", error);
        throw error;
    }
}

// <----------------------------------------------------------------------- Get Sub Status Function End ------------------------------------------------------------->

// <--------------------------------------------------------------------- Print Collection Function Start ------------------------------------------------------------>

async function printCollection(collection_id) {
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
            const content = $("#printcollection").html();
            // If you have logic to trigger print, do it here
        } catch (error) {
            console.error('AJAX Error:', error);
        }
    }
}

// <--------------------------------------------------------------------- Print Collection Function End ------------------------------------------------------------->

// <----------------------------------------------------------------------- Validation Function Start ---------------------------------------------------------------->

function isFormDataValid(collData) {
    let isValid = true;

    // Check if all three fields are empty
    const allFourFieldsEmpty = !collData['interest_amount_track'] && !collData['penalty_track'] && !collData['fine_charge_track'] && !collData['principal_amount_track'];

    if (allFourFieldsEmpty) {
        if (!validateField(collData['interest_amount_track'], 'interest_amount_track')) {
            isValid = false;
        }
        if (!validateField(collData['penalty_track'], 'penalty_track')) {
            isValid = false;
        }
        if (!validateField(collData['fine_charge_track'], 'fine_charge_track')) {
            isValid = false;
        }
        if (!validateField(collData['principal_amount_track'], 'principal_amount_track')) {
            isValid = false;
        }
    } else {
        // Reset border color for the fields if any one of them is filled
        $('#interest_amount_track').css('border', '1px solid #cecece');
        $('#penalty_track').css('border', '1px solid #cecece');
        $('#fine_charge_track').css('border', '1px solid #cecece');
        $('#principal_amount_track').css('border', '1px solid #cecece');
    }

    // Validate collection_mode
    if (!validateField(collData['collection_mode'], 'collection_mode')) {
        isValid = false;
    } else {
        if (collData['collection_mode'] == '2') { // cheque
            let validations = [
                validateField(collData['bank_id'], 'bank_id'),
                validateField(collData['trans_id'], 'trans_id'),
            ];
            if (!validations.every(result => result)) {
                isValid = false;
            }
        }
    }

    return isValid;
}

function resetValidation() {
    const fieldsToReset = [
        'bank_id', 'trans_id']
    fieldsToReset.forEach(fieldId => {
        $('#' + fieldId).css('border', '1px solid #cecece');
    });
}

// <----------------------------------------------------------------------- Validation Function End ---------------------------------------------------------------->
