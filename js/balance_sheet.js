$(document).ready(function () {

    $('#from_date').change(function () {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        if (from_date > to_date) {
            $('#to_date').val('');
        }
        $('#to_date').attr('min', from_date);
    });

    const toggleButtons = $(".toggle-button");
    toggleButtons.removeClass('active'); //initially make all buttons unchecked
    toggleButtons.on("click", function () {
        // Reset active class for all buttons
        toggleButtons.removeClass("active");
        // Add active class to the clicked button
        $(this).addClass("active");

        let chosenOpt = $(this).val();
        if (chosenOpt == 'Today') {
            clearAllContents();
            getOpeningBal('today', '', '', '')
            getBalSheetDetails('today', '', '', '');
            getNetProfitDetails('today', '', '', '');
            currentOutstandingDetails('today', '', '', '');
            preOutstandingDetails('today', '', '', '');
            outstandingDetails('today', '', '', '');
        }
    });

    $('#submitDaywise').click(function () {
        let from_date = $('#from_date').val(); let to_date = $('#to_date').val();
        if (from_date != '' && to_date != '') {
            clearAllContents();
            getOpeningBal('day', from_date, to_date, '')
            getBalSheetDetails('day', from_date, to_date, '');
            getNetProfitDetails('day', from_date, to_date, '');
            currentOutstandingDetails('day', from_date, to_date, '');
            preOutstandingDetails('day', from_date, to_date, '');
            outstandingDetails('day', from_date, to_date, '');

            $('.close').trigger('click');//it will close modal
        } else {
            swalError('Please Fill Dates!', 'error');
            event.preventDefault();
        }
    })

    $('#submitMonthwise').click(function () {
        let for_month = $('#for_month').val()
        if (for_month != '') {
            clearAllContents();
            getOpeningBal('month', '', '', for_month)
            getBalSheetDetails('month', '', '', for_month);
            getNetProfitDetails('month', '', '', for_month);
            currentOutstandingDetails('month', '', '', for_month);
            preOutstandingDetails('month', '', '', for_month);
            outstandingDetails('month', '', '', for_month);

            $('.close').trigger('click');//it will close modal
        } else {
            swalError('Please Choose Month!', 'error');
            event.preventDefault();
        }
    });

}); ////// Document END.

$(function () {
    getUserNames();
});

function getUserNames() {
    $.post('api/accounts_files/balance_sheet_files/user_list.php', function (response) {
        $('#by_user').empty()
        $('#by_user').append("<option value=''>Select User</option>")
        $.each(response, function (index, val) {
            $('#by_user').append("<option value='" + val['id'] + "'>" + val['name'] + "</option> ");
        })
    }, 'json')
}

// <!------------------------------------------------------------------- Opening Balance For Balance Sheet -------------------------------------------------------->

function getOpeningBal(type, from_date, to_date, month) {
    var user_id = $('#by_user').val();
    if (type == 'today') {
        var args = { 'type': 'today', 'user_id': user_id };
    } else if (type == 'day') {
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id };
    } else if (type == 'month') {
        var args = { 'type': 'month', 'month': month, 'user_id': user_id };
    }
    $.post('api/accounts_files/balance_sheet_files/bs_opening_bal.php', args, function (response) {
        if (response.length > 0) {
            $('#balance_sheet_table tbody tr:first td:nth-child(2)').text(moneyFormatIndia(response[0]['opening_balance']));
        }
    }, 'json');
}

// <!------------------------------------------------------------------- Balance Sheet details Function -------------------------------------------------------->

function getBalSheetDetails(type, from_date, to_date, month) {
    var user_id = $('#by_user').val();
    if (type == 'today') {
        var args = { 'type': 'today', 'user_id': user_id };
    } else if (type == 'day') {
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id };
    } else if (type == 'month') {
        var args = { 'type': 'month', 'month': month, 'user_id': user_id };
    }

    $.post('api/accounts_files/balance_sheet_files/balance_sheet_details.php', args, function (response) {
        $('#balance_sheet_table tbody tr:nth-child(2) td:nth-child(2)').text(moneyFormatIndia(response[0]['princ']));
        $('#balance_sheet_table tbody tr:nth-child(3) td:nth-child(2)').text(moneyFormatIndia(response[0]['intrst']));
        $('#balance_sheet_table tbody tr:nth-child(4) td:nth-child(2)').text(moneyFormatIndia(response[0]['doc_charges']));
        $('#balance_sheet_table tbody tr:nth-child(5) td:nth-child(2)').text(moneyFormatIndia(response[0]['proc_charges']));
        $('#balance_sheet_table tbody tr:nth-child(6) td:nth-child(2)').text(moneyFormatIndia(response[0]['penalty']));
        $('#balance_sheet_table tbody tr:nth-child(7) td:nth-child(2)').text(moneyFormatIndia(response[0]['fine']));

        $('#balance_sheet_table tbody tr:nth-child(8) td:nth-child(2)').text(moneyFormatIndia(response[0]['invcr']));
        $('#balance_sheet_table tbody tr:nth-child(9) td:nth-child(2)').text(moneyFormatIndia(response[0]['depcr']));
        $('#balance_sheet_table tbody tr:nth-child(10) td:nth-child(2)').text(moneyFormatIndia(response[0]['elcr']));
        $('#balance_sheet_table tbody tr:nth-child(11) td:nth-child(2)').text(moneyFormatIndia(response[0]['exccr']));
        $('#balance_sheet_table tbody tr:nth-child(12) td:nth-child(2)').text(moneyFormatIndia(response[0]['contracr']));
        $('#balance_sheet_table tbody tr:nth-child(13) td:nth-child(2)').text(moneyFormatIndia(response[0]['oicr']));

        $('#balance_sheet_table tbody tr:nth-child(8) td:nth-child(3)').text(moneyFormatIndia(response[0]['invdr']));
        $('#balance_sheet_table tbody tr:nth-child(9) td:nth-child(3)').text(moneyFormatIndia(response[0]['depdr']));
        $('#balance_sheet_table tbody tr:nth-child(10) td:nth-child(3)').text(moneyFormatIndia(response[0]['eldr']));
        $('#balance_sheet_table tbody tr:nth-child(11) td:nth-child(3)').text(moneyFormatIndia(response[0]['excdr']));
        $('#balance_sheet_table tbody tr:nth-child(12) td:nth-child(3)').text(moneyFormatIndia(response[0]['contradr']));

        $('#balance_sheet_table tbody tr:nth-child(14) td:nth-child(3)').text(moneyFormatIndia(response[0]['advdr']));
        $('#balance_sheet_table tbody tr:nth-child(15) td:nth-child(3)').text(moneyFormatIndia(response[0]['expdr']));

    }, 'json').then(function () {
        getBalSheetTotal();
    });
}

// <!---------------------------------------------------------- Balance Sheet Total Credit and Debit Function ----------------------------------------------------->

function getBalSheetTotal() {
    var credit_total = 0;
    var debit_total = 0;
    $('#balance_sheet_table tbody tr').not('tr:nth-child(17)').each(function () {
        var credit = $(this).find('td:nth-child(2)').text().replace(/,/g, ''); // credit amount
        var debit = $(this).find('td:nth-child(3)').text().replace(/,/g, ''); // debit amount
        credit_total += parseInt(credit) || 0;
        debit_total += parseInt(debit) || 0;
    });

    let close = credit_total - debit_total;
    debit_total = debit_total + close;
    credit_total = moneyFormatIndia(credit_total);
    debit_total = moneyFormatIndia(debit_total);

    $('#balance_sheet_table tbody tr:nth-child(16) td:nth-child(3)').text(moneyFormatIndia(close));
    $('#balance_sheet_table tbody tr:nth-child(17) td:nth-child(2)').text(credit_total).css('font-weight', 'bold');
    $('#balance_sheet_table tbody tr:nth-child(17) td:nth-child(3)').text(debit_total).css('font-weight', 'bold');
}

// <!------------------------------------------------------------------- Net Profit details Function ----------------------------------------------------------->

function getNetProfitDetails(type, from_date, to_date, month) {
    var user_id = $('#by_user').val();
    if (type == 'today') {
        var args = { 'type': 'today', 'user_id': user_id };
    } else if (type == 'day') {
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id };
    } else if (type == 'month') {
        var args = { 'type': 'month', 'month': month, 'user_id': user_id };
    }
    $.post('api/accounts_files/balance_sheet_files/net_profit_details.php', args, function (response) {
        $('#net_benefit_table tbody tr:nth-child(1) td:nth-child(2)').text(moneyFormatIndia(response[0]['interest']));
        $('#net_benefit_table tbody tr:nth-child(2) td:nth-child(2)').text(moneyFormatIndia(response[0]['doc_charges']));
        $('#net_benefit_table tbody tr:nth-child(3) td:nth-child(2)').text(moneyFormatIndia(response[0]['proc_charges']));
        $('#net_benefit_table tbody tr:nth-child(4) td:nth-child(2)').text(moneyFormatIndia(response[0]['penalty']));
        $('#net_benefit_table tbody tr:nth-child(5) td:nth-child(2)').text(moneyFormatIndia(response[0]['fine']));
        $('#net_benefit_table tbody tr:nth-child(6) td:nth-child(2)').text(moneyFormatIndia(response[0]['oicr']));

        $('#net_benefit_table tbody tr:nth-child(7) td:nth-child(3)').text(moneyFormatIndia(response[0]['expdr']));

    }, 'json').then(function () {
        getNetProfitTotal();
    });
}

// <!------------------------------------------------------------- Net Profit Total Credit and Debit Function ---------------------------------------------------->

function getNetProfitTotal() {
    let credit_total = 0;
    let debit_total = 0;
    $('#net_benefit_table tbody tr').each(function () {
        let credit = $(this).find('td:nth-child(2)').text().replace(/,/g, ''); // credit amount
        let debit = $(this).find('td:nth-child(3)').text().replace(/,/g, ''); // debit amount
        credit_total += parseInt(credit) || 0;
        debit_total += parseInt(debit) || 0;
    });

    let benefit = credit_total - debit_total;
    credit_total = moneyFormatIndia(credit_total);
    debit_total = moneyFormatIndia(debit_total);
    benefit_total = moneyFormatIndia(benefit);

    $('#net_benefit_table tbody tr:nth-child(8) td:nth-child(2)').text(credit_total).css('font-weight', 'bold');
    $('#net_benefit_table tbody tr:nth-child(8) td:nth-child(3)').text(debit_total).css('font-weight', 'bold');
    $('#net_benefit_table tbody tr:nth-child(9) td:nth-child(2)').text(benefit_total).css('font-weight', 'bold');
}

// <!------------------------------------------------------------------- Pre Outstanding details Function ----------------------------------------------------------->

function preOutstandingDetails(type, from_date, to_date, month) {
    var user_id = $('#by_user').val();
    if (type == 'today') {
        var args = { 'type': 'today', 'user_id': user_id };
    } else if (type == 'day') {
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id };
    } else if (type == 'month') {
        var args = { 'type': 'month', 'month': month, 'user_id': user_id };
    }
    $.post('api/accounts_files/balance_sheet_files/pre_outstanding_details.php', args, function (response) {
        $('#outstanding_table tbody tr:first td:nth-child(2)').text(moneyFormatIndia(response[0]['pre_outstanding_cr']));
        $('#outstanding_table tbody tr:nth-child(2) td:nth-child(2)').text(moneyFormatIndia(response[0]['pre_acc_c_cr']));
    }, 'json')
}

// <!------------------------------------------------------------------- Outstanding details Function ----------------------------------------------------------->

function outstandingDetails(type, from_date, to_date, month) {
    var user_id = $('#by_user').val();
    if (type == 'today') {
        var args = { 'type': 'today', 'user_id': user_id };
    } else if (type == 'day') {
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id };
    } else if (type == 'month') {
        var args = { 'type': 'month', 'month': month, 'user_id': user_id };
    }
    $.post('api/accounts_files/balance_sheet_files/balance_sheet_details.php', args, function (response) {
        $('#outstanding_table tbody tr:nth-child(3) td:nth-child(2)').text(moneyFormatIndia(response[0]['invcr']));
        $('#outstanding_table tbody tr:nth-child(4) td:nth-child(2)').text(moneyFormatIndia(response[0]['depcr']));
        $('#outstanding_table tbody tr:nth-child(5) td:nth-child(2)').text(moneyFormatIndia(response[0]['elcr']));
        $('#outstanding_table tbody tr:nth-child(6) td:nth-child(2)').text(moneyFormatIndia(response[0]['exccr']));

        $('#outstanding_table tbody tr:nth-child(3) td:nth-child(3)').text(moneyFormatIndia(response[0]['invdr']));
        $('#outstanding_table tbody tr:nth-child(4) td:nth-child(3)').text(moneyFormatIndia(response[0]['depdr']));
        $('#outstanding_table tbody tr:nth-child(5) td:nth-child(3)').text(moneyFormatIndia(response[0]['eldr']));
        $('#outstanding_table tbody tr:nth-child(6) td:nth-child(3)').text(moneyFormatIndia(response[0]['excdr']));

    }, 'json').then(function () {
        getOutstandingTotal();
    });
}

// <!------------------------------------------------------------------- Current Outstanding details Function ----------------------------------------------------------->

function currentOutstandingDetails(type, from_date, to_date, month) {
    var user_id = $('#by_user').val();
    if (type == 'today') {
        var args = { 'type': 'today', 'user_id': user_id };
    } else if (type == 'day') {
        var args = { 'type': 'day', 'from_date': from_date, 'to_date': to_date, 'user_id': user_id };
    } else if (type == 'month') {
        var args = { 'type': 'month', 'month': month, 'user_id': user_id };
    }
    $.post('api/accounts_files/balance_sheet_files/current_outstanding_details.php', args, function (response) {
        $('#outstanding_table tbody tr:nth-child(7) td:nth-child(3)').text(moneyFormatIndia(response[0]['pre_outstanding_db']));
        $('#outstanding_table tbody tr:nth-child(8) td:nth-child(3)').text(moneyFormatIndia(response[0]['pre_acc_c_db']));
    }, 'json')
}

// <!------------------------------------------------------------- Outstanding Total Credit and Debit Function ---------------------------------------------------->

function getOutstandingTotal() {
    let credit_total = 0;
    let debit_total = 0;
    $('#outstanding_table tbody tr').each(function () {
        let credit = $(this).find('td:nth-child(2)').text().replace(/,/g, ''); // credit amount
        let debit = $(this).find('td:nth-child(3)').text().replace(/,/g, ''); // debit amount
        credit_total += parseInt(credit) || 0;
        debit_total += parseInt(debit) || 0;
    });

    let benefit = credit_total - debit_total;
    credit_total = moneyFormatIndia(credit_total);
    debit_total = moneyFormatIndia(debit_total);
    benefit_total = moneyFormatIndia(benefit);

    $('#outstanding_table tbody tr:nth-child(9) td:nth-child(2)').text(credit_total).css('font-weight', 'bold');
    $('#outstanding_table tbody tr:nth-child(9) td:nth-child(3)').text(debit_total).css('font-weight', 'bold');
    $('#outstanding_table tbody tr:nth-child(10) td:nth-child(2)').text(benefit_total).css('font-weight', 'bold');
}

// <------------------------------------------------------------------------ To clear all contents ------------------------------------------------------------>

function clearAllContents() {
    $('#balance_sheet_table').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    });
    $('#net_benefit_table').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    });
    $('#outstanding_table').find('tbody tr').each(function () {
        $(this).find('td:nth-child(2)').text('')
        $(this).find('td:nth-child(3)').text('')
    });
}