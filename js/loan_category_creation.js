$(document).ready(function () {
    $('.add_loancategory_btn, .back_to_loancategory_btn').click(function () {
        clearLoanCategoryForm();
        swapTableAndCreation();
    });

    ////////////////////////////////////////// Radio Button - Document charge , processing fee , overdue penalty START ////////////////////////////////////////////////////

    $('.interest_minmax').change(function () {
        checkMinMaxValue('#interest_rate_min', '#interest_rate_max');
    });

    $('.doc_charge_minmax').change(function () {
        checkMinMaxValue('#doc_charge_min', '#doc_charge_max');
    });

    $('.processing_minmax').change(function () {
        checkMinMaxValue('#processing_fee_min', '#processing_fee_max');
    });

    $('input[name=doc_charge_type]').click(function () {
        let Value = $(this).val();
        let type = (Value === 'percentage') ? '%' : '₹';
        $('.document-span-val').text(type);
        $('#document_charge').val(Value);
    });

    $('input[name=process_fee_type]').click(function () {
        let Value = $(this).val();
        let type = (Value === 'percentage') ? '%' : '₹';
        $('.process-span-val').text(type);
        $('#processing_type').val(Value);
    });

    $('input[name=over_due_type]').click(function () {
        let Value = $(this).val();
        let type = (Value === 'percentage') ? '%' : '₹';
        $('.overdue-span-val').text(type);
        $('#overdue_type').val(Value);
    });

    ////////////////////////////////////////// Radio Button - Document charge , processing fee , overdue penalty END ////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////// Loan Category Modal START /////////////////////////////////////////////////////////////

    $('#submit_addloan_category').click(function () {
        event.preventDefault();
        let loanCategoryName = $('#addloan_category_name').val(); let id = $('#addloan_category_id').val();
        var data = ['addloan_category_name']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (loanCategoryName != '') {
            if (isValid) {
                $.post('api/loan_category_files/submit_loan_category.php', { loanCategoryName, id }, function (response) {
                    if (response == '1') {
                        swalSuccess('Success', 'Loan Category Added Successfully!');
                    } else if (response == '0') {
                        swalSuccess('Success', 'Loan Category Updated Successfully!');
                    } else if (response == '2') {
                        swalError('Warning', 'Loan Category Already Exists!');
                    }

                    getLoanCategoryTable();
                }, 'json');
                clearLoanCategory(); //To Clear All Fields in Loan Category creation.
            }

        }
    });

    $(document).on('click', '.loancatActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/loan_category_files/get_loan_category_data.php', { id }, function (response) {
            $('#addloan_category_id').val(id);
            $('#addloan_category_name').val(response[0].loan_category);
        }, 'json');
    });

    $(document).on('click', '.loancatDeleteBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        swalConfirm('Delete', 'Do you want to Delete the Loan Category?', deleteLoanCategory, id);
        return;
    });

    ///////////////////////////////////////////////////////// Loan Category Modal END //////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////// Loan Category Creation SUBMIT START  ////////////////////////////////////////////////////////////////////////////////


    $('#submit_loan_category_creation').click(function (event) {
        event.preventDefault();

        let isValid = true;

        // Validate Loan Calculation Card
        let isLoanCalculationValid = validateLoanCalculationCard();

        // Validate main form fields
        if (!validateField($('#loan_category').val(), 'loan_category')) {
            isValid = false;
        }
        if (!validateField($('#loan_limit').val(), 'loan_limit')) {
            isValid = false;
        }

        if (isValid && (isLoanCalculationValid)) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this loan category creation?',
                function () {
                    let formData = {
                        loan_category: $('#loan_category').val(),
                        loan_limit: $('#loan_limit').val().replace(/,/g, ''),
                        profit_type: $('#profit_type_calc').val(),
                        due_method: $('#due_method').val(),
                        due_type: $('#due_type').val(),
                        benefit_method: $('#benefit_method').val(),
                        due_period: $('#due_period').val(),
                        interest_calculate: $('#interest_calculate').val(),
                        due_calculate: $('#due_calculate').val(),
                        interest_rate_min: $('#interest_rate_min').val(),
                        interest_rate_max: $('#interest_rate_max').val(),
                        document_charge: $('#document_charge').val(),
                        doc_charge_min: $('#doc_charge_min').val(),
                        doc_charge_max: $('#doc_charge_max').val(),
                        processing_fee_type: $('#processing_type').val(),
                        processing_fee_min: $('#processing_fee_min').val(),
                        processing_fee_max: $('#processing_fee_max').val(),
                        overdue_type: $('#overdue_type').val(),
                        overdue_penalty: $('#overdue_penalty').val(),
                        id: $('#loan_category_creation_id').val()
                    };

                    // Submit form via AJAX
                    $.post('api/loan_category_creation_files/submit_loan_category_creation.php', formData, function (response) {
                        if (response === '2') {
                            swalSuccess('Success', 'Loan Category Added Successfully!');
                        } else if (response === '1') {
                            swalSuccess('Success', 'Loan Category Updated Successfully!');
                        } else {
                            swalError('Error', 'Error Occurred!');
                        }
                        clearLoanCategoryForm();
                        getLoanCategoryCreationTable();
                        swapTableAndCreation(); // Change to table content
                    });
                }
            );
        }

    });

    ////////////////////////////////////////////////////// Loan Category Creation SUBMIT END  ////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////// Loan Category Creation EDIT START  ////////////////////////////////////////////////////////////////////////////////

    $(document).on('click', '.loanCatCreationActionBtn', async function () {
        var id = $(this).attr('value');
        try {
            const response = await $.post('api/loan_category_creation_files/loan_category_creation_data.php', { id }, null, 'json');

            $('#loan_category_creation_id').val(id);
            $('#loan_category2').val(response[0].loan_category);
            $('#loan_limit').val(moneyFormatIndia(response[0].loan_limit));
            $('#profit_type_calc').val(response[0].profit_type);
            $('#due_method').val(response[0].due_method);
            $('#due_type').val(response[0].due_type);
            $('#benefit_method').val(response[0].benefit_method);
            $('#due_period').val(response[0].due_period);
            $('#interest_calculate').val(response[0].interest_calculate);
            $('#due_calculate').val(response[0].due_calculate);
            $('#interest_rate_min').val(response[0].interest_rate_min);
            $('#interest_rate_max').val(response[0].interest_rate_max);
            $('#document_charge').val(response[0].document_charge);
            $('#doc_charge_min').val(response[0].doc_charge_min);
            $('#doc_charge_max').val(response[0].doc_charge_max);
            $('#processing_type').val(response[0].processing_fee_type);
            $('#processing_fee_min').val(response[0].processing_fee_min);
            $('#processing_fee_max').val(response[0].processing_fee_max);
            $('#overdue_type').val(response[0].overdue_type);
            $('#overdue_penalty').val(response[0].overdue_penalty);

            // edit document charge , processing fee , overdue penalty ( Percentage or Rupee)
            handleTypeChange($('#document_charge').val(), 'docpercentage', 'docamt', 'document-span-val');
            handleTypeChange($('#processing_type').val(), 'propercentage', 'procamt', 'process-span-val');
            handleTypeChange($('#overdue_type').val(), 'overpercentage', 'overamt', 'overdue-span-val');

            await getLoanCategoryDropdown(); // Now await this properly

            swapTableAndCreation();

        } catch (err) {
            console.error('Error loading data or dropdown:', err);
        }
    });

    /////////////////////////////////////////////////////// Loan Category Creation EDIT END  ////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////// Loan Category Creation DELETE START  ////////////////////////////////////////////////////////////////////////////////

    $(document).on('click', '.loanCatCreationDeleteBtn', function () {
        let id = $(this).attr('value'); // Get value attribute
        swalConfirm('Delete', 'Do you want to Delete the Loan Category Creation?', deleteLoanCategoryCreation, id);
        return;
    });

    /////////////////////////////////////////////////////// Loan Category Creation DELETE END  ////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////// CLEAR Screen END  ////////////////////////////////////////////////////////////////////////

    $('#clear_loan_cat_form').click(() => {
        clearLoanCategoryForm();
    })
});

/////////////////////////////////////////////////////////  Function START  //////////////////////////////////////////////////////////////////////////////////////////

function swapTableAndCreation() {
    if ($('.loan_category_table_content').is(':visible')) {
        $('.loan_category_table_content').hide();
        $('.add_loancategory_btn').hide();
        $('#loan_category_creation_content').show();
        $('.back_to_loancategory_btn').show();
    } else {
        $('.loan_category_table_content').show();
        $('.add_loancategory_btn').show();
        $('#loan_category_creation_content').hide();
        $('.back_to_loancategory_btn').hide();
        clearLoanCategoryForm();
    }
}

///////////////////////////////////////////////////////// Loan Category Function Start  //////////////////////////////////////////////////////////////////////////////////

$(function () {
    getLoanCategoryDropdown();
    getLoanCategoryCreationTable();
});

function getLoanCategoryTable() {
    $.post('api/loan_category_files/get_loan_category_list.php', function (response) {
        let loanCategoryColumn = [
            "sno",
            "loan_category",
            "action"
        ]
        appendDataToTable('#loan_category_table', response, loanCategoryColumn);
        setdtable('#loan_category_table', "Loan Category List");
    }, 'json');
}

function getLoanCategoryDropdown() {
    return new Promise((resolve, reject) => {
        $.post('api/loan_category_files/get_loan_category_list.php', function (response) {
            let appendLineNameOption = '';
            let loan_category2 = $('#loan_category2').val();
            appendLineNameOption += '<option value="">Select Loan Category</option>';

            $.each(response, function (index, val) {
                let selected = (val.id == loan_category2) ? 'selected' : '';
                appendLineNameOption += `<option value="${val.id}" ${selected}>${val.loan_category}</option>`;
            });

            $('#loan_category').empty().append(appendLineNameOption);
            clearLoanCategory();
            resolve(); // Resolve once done
        }, 'json').fail(function (err) {
            reject(err); // Reject on error
        });
    });
}

function deleteLoanCategory(id) {
    $.post('api/loan_category_files/delete_loan_category.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Loan Category Deleted Successfully.');
            getLoanCategoryTable();
        } else if (response == '0') {
            swalError('Access Denied', 'Used in Loan Category Creation');
        } else {
            swalError('Error', 'Loan Category Delete Failed.');
        }
        $('#addloan_category_name').val('');
        $('#addloan_category_id').val('');
    }, 'json');
}

function clearLoanCategory() {
    $('#addloan_category_name').val('');
    $('#addloan_category_id').val('0');
    $('#addloan_category_name').css('border', '1px solid #cecece');
}

///////////////////////////////////////////////////////// Loan Category Function End  //////////////////////////////////////////////////////////////////////////////////

function getLoanCategoryCreationTable() {
    $.post('api/loan_category_creation_files/loan_category_creation_list.php', function (response) {
        var columnMapping = [
            'sno',
            'loan_category',
            'loan_limit',
            'status',
            'action'
        ];
        appendDataToTable('#loancategory_creation_table', response, columnMapping);
        setdtable('#loancategory_creation_table', "Loan Category Creation List");
    }, 'json');
}

function deleteLoanCategoryCreation(id) {
    $.post('api/loan_category_creation_files/delete_loan_category_creation.php', { id }, function (response) {
        if (response == '0') {
            swalSuccess('Success', 'Loan Category creation Disabled Successfully');
            getLoanCategoryCreationTable();
        } else {
            swalError('Warning', 'Loan Category Creation Delete Failed!');
        }
    }, 'json');
}

function clearLoanCategoryForm() {
    $('#loan_category_creation').trigger('reset');
    $('#loan_category_creation_id').val("");
    $('#loan_category_creation input').css('border', '1px solid #cecece');
    $('#loan_category_creation select').css('border', '1px solid #cecece');
    $('select').each(function () {
        $(this).val($(this).find('option:first').val());
    });
    // Reset fee type radio buttons and span text
    resetFeeTypesToDefault();
}

function resetFeeTypesToDefault() {
    $('#document_charge').val('percentage');
    $('#processing_type').val('percentage');
    $('#overdue_type').val('percentage');

    handleTypeChange('percentage', 'docpercentage', 'docamt', 'document-span-val');
    handleTypeChange('percentage', 'propercentage', 'procamt', 'process-span-val');
    handleTypeChange('percentage', 'overpercentage', 'overamt', 'overdue-span-val');
}

function checkMinMaxValue(minSelector, maxSelector) {
    let min = parseFloat($(minSelector).val());
    let max = parseFloat($(maxSelector).val());
    // Only proceed if both values are numbers
    if (!isNaN(min) && !isNaN(max)) {
        if (min > max) {
            swalError('Warning', 'Minimum value should be less than or equal to Maximum value');
            $(minSelector).val('');
            $(maxSelector).val('');
        }
    }
}

function handleTypeChange(type, percentId, rupeeId, spanClass) {
    if (type === 'percentage') {
        $(`#${percentId}`).prop('checked', true);
        $(`#${rupeeId}`).prop('checked', false);
        $(`.${spanClass}`).text('%');
    } else if (type === 'rupee') {
        $(`#${rupeeId}`).prop('checked', true);
        $(`#${percentId}`).prop('checked', false);
        $(`.${spanClass}`).text('₹');
    }
}

function validateLoanCalculationCard() {
    let valid = true;

    valid &= validateField($('#due_method').val(), 'due_method');
    valid &= validateField($('#benefit_method').val(), 'benefit_method');
    valid &= validateField($('#due_period').val(), 'due_period');
    valid &= validateField($('#interest_calculate').val(), 'interest_calculate');
    valid &= validateField($('#due_calculate').val(), 'due_calculate');
    valid &= validateField($('#interest_rate_min').val(), 'interest_rate_min');
    valid &= validateField($('#interest_rate_max').val(), 'interest_rate_max');
    valid &= validateField($('#doc_charge_min').val(), 'doc_charge_min');
    valid &= validateField($('#doc_charge_max').val(), 'doc_charge_max');
    valid &= validateField($('#processing_fee_min').val(), 'processing_fee_min');
    valid &= validateField($('#processing_fee_max').val(), 'processing_fee_max');
    valid &= validateField($('#overdue_penalty').val(), 'overdue_penalty');
    return valid;
}