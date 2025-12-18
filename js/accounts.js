$(document).ready(function () {

    // <--------------------------------------------------------------- Radio Button Hide and Show -------------------------------------------------------------->

    $('input[name=accounts_type]').click(function () {
        let accountsType = $(this).val();
        if (accountsType == '1') { //Collection List
            $('#coll_card').show(); $('#loan_issued_card').hide(); $('#expenses_card').hide(); $('#other_transaction_card').hide();
            getBankName('#coll_bank_name');
        }
        else if (accountsType == '2') { //Loan Issued
            $('#coll_card').hide(); $('#loan_issued_card').show(); $('#expenses_card').hide(); $('#other_transaction_card').hide();
            getBankName('#issue_bank_name');
        }
        else if (accountsType == '3') { //Expenses
            $('#coll_card').hide(); $('#loan_issued_card').hide(); $('#expenses_card').show(); $('#other_transaction_card').hide();
            expensesTable('#accounts_expenses_table');
        }
        else if (accountsType == '4') { //Other Transaction
            $('#coll_card').hide(); $('#loan_issued_card').hide(); $('#expenses_card').hide(); $('#other_transaction_card').show();
            otherTransTable('#accounts_other_trans_table');
        }
    });

    // <--------------------------------------------------------------- Accounts Collection List -------------------------------------------------------------->

    $("input[name='coll_cash_type']").click(function () {
        let collCashType = $(this).val();

        if (collCashType == '2') {
            $('#coll_bank_name').val('').attr('disabled', false);
            $('#accounts_collection_table').DataTable().destroy();
            $('#accounts_collection_table tbody').empty()
        } else {
            $('#coll_bank_name').val('').attr('disabled', true);
            getCollectionList();
        }
    });

    $('#coll_bank_name').change(function () {
        getCollectionList();
    });

    $(document).on('click', '.collect-money', function (event) {
        event.preventDefault();
        let collectTableRowVal = {
            'username': $(this).closest('tr').find('td:nth-child(2)').text(),
            'id': $(this).attr('value'),
            'line': $(this).closest('tr').find('td:nth-child(3)').text(),
            'branch': $(this).closest('tr').find('td:nth-child(4)').text(),
            'no_of_bills': $(this).closest('tr').find('td:nth-child(5)').text(),
            'collected_amnt': $(this).closest('tr').find('td:nth-child(6)').text(),
            'cash_type': $("input[name='coll_cash_type']:checked").val(),
            'bank_id': $('#coll_bank_name :selected').val()
        };
        swalConfirm('Collect', `Do you want to collect Money from ${collectTableRowVal.username}?`, submitCollect, collectTableRowVal);
    });

    // <--------------------------------------------------------------- Accounts Loan Issue List -------------------------------------------------------------->

    $("input[name='issue_cash_type']").click(function () {
        let collCashType = $(this).val();

        if (collCashType == '2') {
            $('#issue_bank_name').val('').attr('disabled', false);
            $('#accounts_loanissue_table').DataTable().destroy();
            $('#accounts_loanissue_table tbody').empty()
        } else {
            $('#issue_bank_name').val('').attr('disabled', true);
            getLoanIssueList();
        }
    });

    $('#issue_bank_name').change(function () {
        getLoanIssueList();
    });

    // <--------------------------------------------------------------------- Accounts Expense List ---------------------------------------------------------------->

    $('#expenses_add').click(function () {
        getBankName('#expenses_bank_name');
        getInvoiceNo();
        getBranchList();
        expensesTable('#expenses_creation_table');
    });

    $("input[name='expenses_cash_type']").click(function () {
        let expCashType = $(this).val();
        $('#expenses_trans_id').val('');

        if (expCashType == '2') {
            $('#expenses_bank_name').val('').attr('disabled', false);
            $('.exp_trans_div').show();
        } else {
            $('.exp_trans_div').hide();
            $('#expenses_bank_name').val('').attr('disabled', true);
        }
    });

    $('#expenses_category').change(function () {
        let expCat = $(this).val();
        if (expCat == '14') {
            $('.agentDiv').show();
            getAgentName();
        } else {
            $('.agentDiv').hide();
        }
    });

    $('#submit_expenses_creation').click(function (event) {
        event.preventDefault();
        let expensesData = {
            'collection_mode': $("input[name='expenses_cash_type']:checked").val(),
            'bank_id': $('#expenses_bank_name :selected').val(),
            'invoice_id': $('#invoice_id').val(),
            'branch_name': $('#branch_name :selected').val(),
            'expenses_category': $('#expenses_category :selected').val(),
            'agent_name': $('#agent_name :selected').val(),
            'expenses_total_issued': $('#expenses_total_issued').val(),
            'expenses_total_amnt': $('#expenses_total_amnt').val(),
            'description': $('#description').val(),
            'expenses_amnt': $('#expenses_amnt').val(),
            'expenses_trans_id': $('#expenses_trans_id').val(),
        }
        // Fetch closing balance and validate the expense amount before submitting
        getClosingBal(function (hand_cash_balance, bank_cash_balance) {
            let expensesAmount = parseFloat(expensesData.expenses_amnt);
            let collMode = expensesData.collection_mode;

            // Check if cash mode is 1 (Hand Cash) and expenses amount is greater than hand cash balance
            if (collMode == '1' && expensesAmount > hand_cash_balance) {
                swalError('Warning', 'Insufficient Hand cash balance');
                return;
            }

            // Check if cash mode is 2 (Bank Transaction) and expenses amount is greater than bank cash balance
            if (collMode == '2' && expensesAmount > bank_cash_balance) {
                swalError('Warning', 'Insufficient Bank cash balance.');
                return;
            }

            // Proceed if the balance check passes
            if (expensesFormValid(expensesData)) {
                $.post('api/accounts_files/accounts/submit_expenses.php', expensesData, function (response) {
                    if (response == '1') {
                        swalSuccess('Success', 'Expenses added successfully.');
                        expensesTable('#expenses_creation_table');
                        getInvoiceNo();
                        getClosingBal(); // Update the closing balance after submission
                    } else {
                        swalError('Error', 'Failed to add expenses.');
                    }
                }, 'json');
            } else {
                swalError('Warning', 'Kindly Fill Mandatory Fields.');
            }
        });
    });


    $(document).on('click', '.expDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this Expenses?', deleteExp, id);
    });

    $(document).on('click', '.exp-clse', function () {
        expensesTable('#accounts_expenses_table');
    });

    $('#agent_name').change(function () {
        let agentId = $(this).val();
        let collection_mode = $("input[name='expenses_cash_type']:checked").val();

        if (collection_mode == '' || collection_mode == undefined) {
            swalError('Warning', 'Kindly select the cash mode.')
            $('#expenses_total_issued').val('');
            $('#expenses_total_amnt').val('');
        } else {
            getAgentDetails(agentId, collection_mode);
        }
    });

    // <---------------------------------------------------------------- Accounts Other Transaction List ------------------------------------------------------------->

    $('#other_trans_add').click(function () {
        otherTransTable('#other_transaction_table');
    });

    $("input[name='othertransaction_cash_type']").click(function () {
        let otherCashType = $(this).val();
        $('#other_trans_id').val('');

        if (otherCashType == '2') {
            $('#othertransaction_bank_name').val('').attr('disabled', false);
            $('.other_trans_div').show();
            getBankName('#othertransaction_bank_name');
        } else {
            $('.other_trans_div').hide();
            $('#othertransaction_bank_name').val('').attr('disabled', true);
        }
    });

    $('#trans_category').change(function () {
        let category = $(this).val();
        if (category != '') {
            $('#trans_cat').val($(this).find(':selected').text());
            $('#trans_cat').attr('data-id', $(this).val());
            $('#name_modal_btn')
                .attr('data-toggle', 'modal')
                .attr('data-target', '#add_name_modal');
        } else {
            $('#name_modal_btn')
                .removeAttr('data-toggle')
                .removeAttr('data-target');
        }

        nameDropDown(); //To show name based on transaction category.

        let catTypeOptn = '';
        catTypeOptn += "<option value=''>Select Type</option>";
        if (category == '1' || category == '2' || category == '3' || category == '4' || category == '9') { //credit / debit
            catTypeOptn += "<option value='1'>Credit</option>";
            catTypeOptn += "<option value='2'>Debit</option>";

        } else if (category == '5') { //debit || category == '7'
            catTypeOptn += "<option value='2'>Debit</option>";

        } else if (category == '6' || category == '8') { //credit
            catTypeOptn += "<option value='1'>Credit</option>";
        }

        $('#cat_type').empty().append(catTypeOptn); //To show Type based on transaction category.

        getRefId(category);
    });

    $('#name_modal_btn').click(function () {
        if ($(this).attr('data-target')) {
            $('#add_other_transaction_modal').hide();
            getOtherTransNameTable();
        } else {
            swalError('Warning', 'Kindly select Transaction Category.');
        }
    });

    $('.name_close').click(function () {
        $('#add_other_transaction_modal').show();
        nameDropDown();
        $('#other_name').val('');
    });

    $('.clse-trans').click(function () {
        otherTransTable('#accounts_other_trans_table');
    });

    $('#submit_name').click(function (event) {
        event.preventDefault();
        let transCat = $('#trans_cat').attr('data-id');
        let name = $('#other_name').val();
        if (transCat == '' || name == '') {
            swalError('Warning', 'Kindly fill all the fields.');
            return false;
        }
        $.post('api/accounts_files/accounts/submit_other_trans_name.php', { transCat, name }, function (response) {
            if (response == '1') {
                swalSuccess('Success', 'Transaction Name Added Successfully.');
                getOtherTransNameTable();
                $('#other_name').val('');
            } else {
                swalError('Error', 'Transaction Name Not Added. Try Again Later.');
            }
        }, 'json');
    });

    $('#submit_other_transaction').click(function (event) {
        event.preventDefault();
        let otherTransData = {
            'collection_mode': $("input[name='othertransaction_cash_type']:checked").val(),
            'bank_id': $('#othertransaction_bank_name :selected').val(),
            'trans_category': $('#trans_category :selected').val(),
            'other_trans_name': $('#other_trans_name :selected').val(),
            'cat_type': $('#cat_type :selected').val(),
            'other_ref_id': $('#other_ref_id').val(),
            'other_trans_id': $('#other_trans_id').val(),
            'other_amnt': $('#other_amnt').val(),
            'other_remark': $('#other_remark').val()
        }
        let otherAmount = otherTransData.other_amnt;
        let collMode = otherTransData.collection_mode;
        let catType = otherTransData.cat_type; // 1 = Credit, 2 = Debit
        let transCategory = parseInt(otherTransData.trans_category);
        // Fetch user's total credit and debit amounts
        $.post('api/accounts_files/accounts/get_user_transactions.php', {
            'other_trans_name': otherTransData.other_trans_name
        }, function (response) {
            let totalCredit = parseFloat(response.total_type_1_amount || 0); // Total Credit
            let totalDebit = parseFloat(response.total_type_2_amount || 0);  // Total Debit

            let balance;
            if (catType == '2') { // Debit Transaction
                balance = totalCredit - totalDebit; // Calculate balance
            } else if (catType == '1') { // Credit Transaction
                balance = totalDebit - totalCredit; // Calculate balance  
            }

            // Validate Debit Transactions
            if (transCategory >= 3 && transCategory <= 9) {
                if (catType == '2') { // Debit Transaction
                    if (balance > 0) {
                        // Allow debit if balance is zero or negative, as long as debit amount does not exceed the absolute value of the balance
                        if (otherAmount > Math.abs(balance)) {
                            const formattedBalance = moneyFormatIndia(Math.abs(balance));
                            swalError('Warning', 'You may only debit up to: ' + formattedBalance);
                            return;
                        }
                    }
                } else if (catType == '1') { // Credit Transaction
                    // Allow credit if balance is negative or zero
                    if (balance > 0) {
                        if (otherAmount > Math.abs(balance)) {
                            const formattedBalance = moneyFormatIndia(Math.abs(balance));
                            swalError('Warning', 'You may only credit up to: ' + formattedBalance);
                            return;
                        }
                    }
                }
            } else if (transCategory <= 2) {
                if (catType == '2' && totalCredit < totalDebit + otherAmount) {
                    const formattedBalance = moneyFormatIndia(Math.abs(balance));
                    swalError('Warning', 'You may only debit up to: ' + formattedBalance);
                    return;
                }
            }

            // Fetch hand cash and bank cash balances for validation
            getClosingBal(function (hand_cash_balance, bank_cash_balance) {
                if (catType == '2') { // Debit Transaction
                    if (collMode == '1' && otherAmount > hand_cash_balance) {
                        swalError('Warning', 'Insufficient hand cash balance.');
                        return;
                    }
                    if (collMode == '2' && otherAmount > bank_cash_balance) {
                        swalError('Warning', 'Insufficient bank cash balance.');
                        return;
                    }
                }
                //    Proceed if all validations pass
                if (otherTransFormValid(otherTransData)) {
                    $.post('api/accounts_files/accounts/submit_other_transaction.php', otherTransData, function (response) {
                        if (response == '1') {
                            swalSuccess('Success', 'Other Transaction added successfully.');
                            otherTransTable('#other_transaction_table');
                            getClosingBal(); // Update closing balance after submission
                            $('#name_id_cont').show();
                            $('#name_modl_btn').show();
                        } else {
                            swalError('Error', 'Failed to add transaction.');
                        }
                    }, 'json');
                }
                else {
                    swalError('Warning', 'Please fill all required fields.');
                }
            });
        }, 'json');
    })

    $(document).on('click', '.transDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this Other Transaction?', deleteTrans, id);
    });

    // <--------------------------------------------------------- Accounts Other Transaction Balance Sheet List -------------------------------------------------------->

    $('#IDE_type').change(function () {
        $('#blncSheetDiv').empty();
        $('.IDE_nameDiv').hide();
        $('#IDE_view_type').val(''); $('#IDE_name_list').val('');
    });

    $('#IDE_view_type').change(function () {
        $('#blncSheetDiv').empty()

        var view_type = $(this).val();//overall/Individual
        var type = $('#IDE_type').val(); //investment/Deposit/EL

        if (view_type == 1 && type != '') {
            $('#IDE_name_list').val(''); //reset name value when using overall
            $('.IDE_nameDiv').hide() // hide name list div
            getIDEBalanceSheet();
        } else if (view_type == 2 && type != '') {
            balNameDropDown();
            $('.IDE_nameDiv').show()
        } else {
            $('.IDE_nameDiv').hide()
        }
    });

    $('#IDE_name_list').change(function () {
        var name_id = $(this).val();
        if (name_id != '') {
            getIDEBalanceSheet();
        }
    });

});

// <--------------------------------------------------------------------- Function Start ----------------------------------------------------------------------------------->

$(function () {
    getOpeningBal();
});

function getOpeningBal() {
    $.post('api/accounts_files/accounts/opening_balance.php', function (response) {
        if (response.length > 0) {
            $('.opening_val').text(moneyFormatIndia(response[0]['opening_balance']));
            $('.op_hand_cash_val').text(moneyFormatIndia(response[0]['hand_cash']));
            $('.op_bank_cash_val').text(moneyFormatIndia(response[0]['bank_cash']));
        }
    }, 'json').then(function () {
        getClosingBal();
    });
}

function getClosingBal(callback) {
    $.post('api/accounts_files/accounts/closing_balance.php', function (response) {
        if (response.length > 0) {
            let close = parseInt($('.opening_val').text().replace(/,/g, '')) + parseInt(response[0]['closing_balance']);
            let hand = parseInt($('.op_hand_cash_val').text().replace(/,/g, '')) + parseInt(response[0]['hand_cash']);
            let bank = parseInt($('.op_bank_cash_val').text().replace(/,/g, '')) + parseInt(response[0]['bank_cash']);

            $('.closing_val').text(moneyFormatIndia(close));
            $('.clse_hand_cash_val').text(moneyFormatIndia(hand));
            $('.clse_bank_cash_val').text(moneyFormatIndia(bank));

            // Call the callback function if defined
            if (typeof callback === "function") {
                callback(hand, bank, close);
            }
        }
    }, 'json');
}

// <--------------------------------------------------------------- Accounts Collection List Function --------------------------------------------------------------->

function getBankName(dropdowndId) {
    $.post('api/common_files/bank_name_list.php', function (response) {
        var bankName = '<option value="">Select Bank Name</option>';
        $.each(response, function (index, value) {
            bankName += '<option value="' + value.id + '" data-id="' + value.account_number + '">' + value.bank_name + '</option>';
        });
        $(dropdowndId).empty().html(bankName);
    }, 'json');
}

function getCollectionList() {
    let cash_type = $("input[name='coll_cash_type']:checked").val();
    let bank_id = $('#coll_bank_name :selected').val();
    $.post('api/accounts_files/accounts/accounts_collection_list.php', { cash_type, bank_id }, function (response) {
        let columnMapping = [
            'sno',
            'name',
            'linename',
            'branch_name',
            'no_of_bills',
            'total_amount',
            'action'
        ];
        appendDataToTable('#accounts_collection_table', response, columnMapping);
        setdtable('#accounts_collection_table',"Collection List");
    }, 'json');
}

function submitCollect(values) {
    $.post('api/accounts_files/accounts/submit_collect.php', values, function (response) {
        if (response == '1') {
            swalSuccess('Success', `Successfully collected ₹${moneyFormatIndia(values.collected_amnt)} for ${values.no_of_bills} bills from ${values.username}.`);
            getCollectionList();
            getClosingBal();
        } else {
            swalError('Error', 'Something went wrong.');
        }
    }, 'json');
}

// <--------------------------------------------------------------- Accounts Loan Issue List Function --------------------------------------------------------------->

function getLoanIssueList() {
    let cash_type = $("input[name='issue_cash_type']:checked").val();
    let bank_id = $('#issue_bank_name :selected').val();
    $.post('api/accounts_files/accounts/accounts_loan_issue_list.php', { cash_type, bank_id }, function (response) {
        let columnMapping = [
            'sno',
            'name',
            'linename',
            'no_of_loans',
            'issueAmnt'
        ];
        appendDataToTable('#accounts_loanissue_table', response, columnMapping);
        setdtable('#accounts_loanissue_table',"Loan Issue List");
    }, 'json');
}

// <--------------------------------------------------------------- Accounts Expense List Function ------------------------------------------------------------------>

function getInvoiceNo() {
    $.post('api/accounts_files/accounts/get_invoice_no.php', {}, function (response) {
        $('#invoice_id').val(response);
    }, 'json');
}

function getBranchList() {
    $.post('api/accounts_files/accounts/user_mapped_branches.php', function (response) {
        let branchOption;
        branchOption += '<option value="">Select Branch Name</option>';
        $.each(response, function (index, value) {
            branchOption += '<option value="' + value.id + '">' + value.branch_name + '</option>';
        });
        $('#branch_name').empty().html(branchOption);
    }, 'json');
}

function getAgentName() {
    $.post('api/agent_creation_files/agent_creation_list.php', function (response) {
        let appendAgentIdOption = '';
        appendAgentIdOption += '<option value="">Select Agent Name</option>';
        $.each(response, function (index, val) {
            let selected = '';
            let agent_id_edit_it = '';
            if (val.id == agent_id_edit_it) {
                selected = 'selected';
            }
            appendAgentIdOption += '<option value="' + val.id + '" ' + selected + '>' + val.agent_name + '</option>';
        });
        $('#agent_name').empty().append(appendAgentIdOption);
    }, 'json');
}

function expensesFormValid(expensesData) {
    for (key in expensesData) {
        if (key != 'agent_name' && key != 'expenses_total_issued' && key != 'expenses_total_amnt' && key != 'bank_id' && key != 'expenses_trans_id') {
            if (expensesData[key] == '' || expensesData[key] == null || expensesData[key] == undefined) {
                return false;
            }
        }
    }

    if (expensesData['collection_mode'] == '2') {
        if (expensesData['bank_id'] == '' || expensesData['bank_id'] == null || expensesData['bank_id'] == undefined || expensesData['expenses_trans_id'] == '' || expensesData['expenses_trans_id'] == null || expensesData['expenses_trans_id'] == undefined) {
            return false;
        }
    }

    if (expensesData['expenses_category'] == '14') {
        if (expensesData['agent_name'] == '' || expensesData['agent_name'] == null || expensesData['agent_name'] == undefined || expensesData['expenses_total_issued'] == '' || expensesData['expenses_total_issued'] == null || expensesData['expenses_total_issued'] == undefined || expensesData['expenses_total_amnt'] == '' || expensesData['expenses_total_amnt'] == null || expensesData['expenses_total_amnt'] == undefined) {
            return false;
        }
    }

    return true;
}

function expensesTable(tableId) {
    $.post('api/accounts_files/accounts/get_expenses_list.php', function (response) {
        let expensesColumn = [
            'sno',
            'collection_mode',
            'bank_id',
            'invoice_id',
            'branch',
            'expenses_category',
            'agent_id',
            'total_issued',
            'total_amount',
            'description',
            'amount',
            'trans_id',
            'action'
        ];

        appendDataToTable(tableId, response, expensesColumn);
        setdtable(tableId,"Expenses List");
        clearExpForm();
    }, 'json');
}

function clearExpForm() {
    $('#expenses_total_issued').val('');
    $('#expenses_total_amnt').val('');
    $('#expenses_amnt').val('');
    $('#expenses_trans_id').val('');
    $('#expenses_form select').val('');
    $('#expenses_form textarea').val('');
    $('.agentDiv').hide();
}

function deleteExp(id) {
    $.post('api/accounts_files/accounts/delete_expenses.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Expenses Deleted Successfully');
            expensesTable('#expenses_creation_table');
            expensesTable('#accounts_expenses_table');
            getInvoiceNo();
            getClosingBal();
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

function getAgentDetails(id, collection_mode) {
    $.post('api/accounts_files/accounts/get_agent_cash_details.php', { id, collection_mode }, function (response) {
        $('#expenses_total_issued').val(response[0].total_issued);
        let issuedAmount = (response[0].total_issued == '0') ? '0' : response[0].total_amount;
        $('#expenses_total_amnt').val(issuedAmount);
    }, 'json');
}

// <----------------------------------------------------------- Accounts Other Transaction List Function -------------------------------------------------------------->

function otherTransTable(tableId) {
    $.post('api/accounts_files/accounts/get_other_trans_list.php', function (response) {
        let expensesColumn = [
            'sno',
            'collection_mode',
            'bank_namecash',
            'trans_cat',
            'name',
            'type',
            'ref_id',
            'trans_id',
            'amount',
            'remark',
            'action'
        ];

        appendDataToTable(tableId, response, expensesColumn);
        setdtable(tableId,"Other Transaction List");
        clearTransForm();
    }, 'json');
}

function clearTransForm() {
    $('#other_ref_id').val('');
    $('#other_trans_id').val('');
    $('#other_amnt').val('');
    $('#other_transaction_form select').val('');
    $('#other_transaction_form textarea').val('');
}

function getOtherTransNameTable() {
    let transCat = $('#trans_category :selected').val();
    $.post('api/accounts_files/accounts/get_other_trans_name_table.php', { transCat }, function (response) {
        let nameColumns = [
            'sno',
            'trans_cat',
            'name'
        ];
        appendDataToTable('#other_trans_name_table', response, nameColumns);
        setdtable('#other_trans_name_table',"Other Transaction Name List");
    }, 'json');
}

function nameDropDown() {
    let transCat = $('#trans_category :selected').val();
    $.post('api/accounts_files/accounts/get_other_trans_name_table.php', { transCat }, function (response) {
        let nameOptn = '';
        nameOptn += "<option value=''>Select Name</option>";
        $.each(response, function (index, val) {
            nameOptn += "<option value='" + val.id + "'>" + val.name + "</option>";
        });
        $('#other_trans_name').empty().append(nameOptn);
    }, 'json');
}

function otherTransFormValid(data) {
    for (key in data) {
        if (key != 'expenses_total_amnt' && key != 'bank_id' && key != 'other_trans_id') {
            if (data[key] == '' || data[key] == null || data[key] == undefined) {
                return false;
            }
        }
    }

    if (data['collection_mode'] == '2') {
        if (data['bank_id'] == '' || data['bank_id'] == null || data['bank_id'] == undefined || data['other_trans_id'] == '' || data['other_trans_id'] == null || data['other_trans_id'] == undefined) {
            return false;
        }
    }

    return true;
}

function deleteTrans(id) {
    $.post('api/accounts_files/accounts/delete_other_transaction.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Other Transaction Deleted Successfully');
            otherTransTable('#other_transaction_table');
            otherTransTable('#accounts_other_trans_table');
            getClosingBal();
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

function getRefId(trans_cat) {
    $.post('api/accounts_files/accounts/get_ref_id.php', { trans_cat }, function (response) {
        $('#other_ref_id').val(response)
    }, 'json');
}

// <------------------------------------------------------- Accounts Other Transaction - balance Sheet Function ---------------------------------------------------->

function balNameDropDown() {
    let transCat = $('#IDE_type :selected').val();
    $.post('api/accounts_files/accounts/get_other_trans_name_table.php', { transCat }, function (response) {
        let nameOptn = '';
        nameOptn += "<option value=''>Select Name</option>";
        $.each(response, function (index, val) {
            nameOptn += "<option value='" + val.id + "'>" + val.name + "</option>";
        });
        $('#IDE_name_list').empty().append(nameOptn);
    }, 'json');
}

function getIDEBalanceSheet() {
    var type = $('#IDE_type').val(); //investment/Deposit/EL
    var view_type = $('#IDE_view_type').val();//overall/Individual
    var IDE_name_id = $('#IDE_name_list').val();//show by name wise

    $.ajax({
        url: 'api/accounts_files/accounts/dep_bal_sheet.php',
        data: { 'IDEview_type': view_type, 'IDEtype': type, 'IDE_name_id': IDE_name_id },
        type: 'post',
        cache: false,
        success: function (response) {
            $('#blncSheetDiv').empty()
            $('#blncSheetDiv').html(response)
        }
    })
}

function resetBlncSheet() {
    $('#IDE_type').val('');
    $('#IDE_view_type').val('');
    $('#IDE_name_list').val('');
}