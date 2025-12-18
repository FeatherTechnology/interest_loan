$(document).ready(function () {

    $('#mobile1').change(function () {
        let mobileValue = $(this).val().trim();  // Retrieve and trim the value of the mobile input

        // Check if mobileValue is not empty
        if (mobileValue !== '') {
            checkMobileNo(mobileValue, $(this).attr('id'));
        }
    });

    // <-------------------------------------------------------------- Submit Search start ------------------------------------------------------------>

    $('#submit_search').click(function (event) {
        event.preventDefault();
        let cus_id = $('#cus_id').val();
        let aadhar_number = $('#aadhar_number').val().replace(/\s/g, '');
        let first_name = $('#first_name').val();
        let area = $('#area').val();
        let mobile = $('#mobile1').val();

        if (validate()) {
            $.ajax({
                url: 'api/search_files/search_customer.php',
                type: 'POST',
                data: { cus_id, aadhar_number, first_name, area, mobile },
                success: function (data) {
                    $('#custome_list').show();
                    getSearchTable(data);
                }
            });
        } else {
            $('#custome_list').hide();
        }
    });

    // <-------------------------------------------------------------- Submit Search End ---------------------------------------------------------------->

    // <-------------------------------------------------------------- View Customer start -------------------------------------------------------------->

    $(document).on('click', '.view_customer', function (event) {
        event.preventDefault();
        $('#customer_status').show();
        $('#custome_list, #search_form').hide();
        let cus_id = $(this).closest('tr').find('td:nth-child(2)').text();
        let aadhar_number = $('#aadhar_number').val().replace(/\s/g, '');
        let first_name = $('#first_name').val();
        let area = $('#area').val();
        let mobile = $('#mobile1').val();

        OnLoadFunctions(cus_id, aadhar_number, first_name, area, mobile)
    });

    // <-------------------------------------------------------------- View Customer End ---------------------------------------------------------------->

    $('#back_to_search').click(function (event) {
        event.preventDefault();
        $('#customer_status').hide();
        $('#custome_list, #search_form').show();
    });

    // <-------------------------------------------------------------- Customer Profile start ------------------------------------------------------------>

    $(document).on('click', '.customer-profile', function () {
        $('#loan_entry_content').show();
        $('#customer_status, #custome_list, #search_form').hide();
        let id = $(this).attr('value');
        $('#loan_entry_id').val(id)
        editCustmerProfile(id)
    });

    $('#back_btn').click(function () {
        $('#customer_status').show();
        $('#loan_entry_content').hide();
    });

    $('#cp_area').change(function () {
        var areaId = $(this).val();
        if (areaId) {
            getAlineName(areaId);
        }
    });

    // <--------------------------------------------------------------------- Customer Profile End --------------------------------------------------------->

    // <------------------------------------------------------------------- loan Calculation start --------------------------------------------------------->

    $(document).on('click', '.loan-calculation', function () {
        $('#loan_content').show();
        $('#customer_status, #custome_list, #search_form').hide();
        let loan_entry_id = $(this).attr('value');
        $('#loan_entry_id').val(loan_entry_id);
        loanCalculationEdit(loan_entry_id);
        callLoanCaculationFunctions();
    });

    $('#loan_back_btn').click(function () {
        $('#customer_status').show();
        $('#loan_content').hide();
    });

    $('#referred_calc').change(function () {
        let referred = $('#referred_calc').val();
        if (referred == '0') {
            $('#agent_id_calc').prop('disabled', false).val('');
            $('#agent_name_calc').val('');
            getAgentID();
        } else {

            $('#agent_id_calc').prop('disabled', true).val('');
            $('#agent_name_calc').prop('readonly', true).val('');
        }
    });

    $('#agent_id_calc').change(function () {
        let id = $(this).val();
        agentNameFunction(id); // Pass id to the function
    });

    $('#loan_category_calc').change(function () {
        let loan_category_calc = $(this).val();
        fetchLoanCategoryFieldsAsync(loan_category_calc);
        $('#interest_rate_calc').val('');
        $('#doc_charge_calc').val('');
        $('#processing_fees_calc').val('');
    });

    // <---------------------------------------------------------------- loan Calculation End -------------------------------------------------------------->

    // <--------------------------------------------------------------------- Documentation Start ---------------------------------------------------------->

    $(document).on('click', '.documentation', function () {
        $('#loan_issue_content').show();
        $('#customer_status, #custome_list, #search_form').hide();
        let id = $(this).attr('value'); //Customer Profile id From List page.
        $('#customer_profile_id').val(id);
        let cusID = $(this).attr('data-id'); //Cus id From List Page.
        $('#cus_id').val(cusID);
        $('.cheque-div').hide();
        $('.doc_div').hide();
        $('.mortgage-div').hide();
        $('.endorsement-div').hide();
        $('.gold-div').hide();
        getDocNeedTable(id);
        getChequeInfoTable();
        getDocInfoTable();
        getMortInfoTable();
        getEndorsementInfoTable();
        getGoldInfoTable();
    });

    $('#doc_back_btn').click(function () {
        $('#customer_status').show();
        $('#loan_issue_content').hide();
    });

    // <------------------------------------------------------------------------ Documentation End ------------------------------------------------------------->

    // <------------------------------------------------------------------------ Closed Remark View Start ------------------------------------------------------>

    $(document).on('click', '.closed-remark', function () {
        $('#closed_remark_model').modal('show');
        let id = $(this).attr('value');
        $('#cus_profile_id').val(id)
        $.post('api/search_files/remark_info.php', { id }, function (response) {
            if (response.length > 0) {
                $('#sub_status').val(response[0].sub_status);
                $('#remark').val(response[0].remark);
            }
        }, 'json');
    });

    // <---------------------------------------------------------------------- Closed Remark View End ------------------------------------------------------------->

    // <---------------------------------------------------------------------- Noc Summary Start ------------------------------------------------------------------->

    $(document).on('click', '.noc-summary', function (event) {
        event.preventDefault();
        $('#noc_summary').show();
        $('#customer_status, #custome_list, #search_form').hide();
        let le_id = $(this).attr('value');
        $('#le_id').val(le_id)
        callAllFunctions(le_id);
    });

    $('#back_to_cus_status').click(function (event) {
        event.preventDefault();
        $('#noc_summary').hide();
        $('#customer_status').show();
    })

    // <------------------------------------------------------------------ Noc Summary End --------------------------------------------------------------------------->

    // <----------------------------------------------------------------- Due Chart , Penalty Chart , Fine Chart Start ------------------------------------------------>

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

    $(document).on('click', '.fine-chart', function () {
        var le_id = $(this).attr('value');
        fineChartList(le_id)
    });

    $(document).on('click', '.fine-chart', function (e) {
        $('#fine_model').modal('show');
    });

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

    // <----------------------------------------------------------------- Due Chart , Penalty Chart , Fine Chart End ------------------------------------------------>

});

// <--------------------------------------------------------------------------- Function start ------------------------------------------------------------------------>

function getSearchTable(data) {
    // Assuming response is in JSON format and contains customer data
    let response = JSON.parse(data);
    var columnMapping = [
        'sno',
        'cus_id',
        'aadhar_number',
        'cus_name',
        'area',
        'branch_name',
        'linename',
        'mobile1',
        'action'
    ];
    appendDataToTable('#search_table', response, columnMapping);
    setdtable('#search_table', "Customer List");
    setDropdownScripts();
}

function validate() {
    let response = true;

    let cus_id = $('#cus_id').val().trim();
    let aadhar_number = $('#aadhar_number').val().trim();
    let first_name = $('#first_name').val().trim();
    let area = $('#area').val().trim();
    let mobile = $('#mobile1').val().trim();

    // Reset all field borders initially
    $('#cus_id, #first_name, #area, #mobile1').css('border', '1px solid #cecece');

    // Check if any one field is filled
    if (cus_id || aadhar_number || first_name || area || mobile) {
        // If any field is filled, reset the other fields' borders
        if (cus_id) {
            $('#first_name, #area, #mobile1 ,#aadhar_number').css('border', '1px solid #cecece');
        } else if (aadhar_number) {
            $('#cus_id , #first_name, #area, #mobile1').css('border', '1px solid #cecece');
        } else if (first_name) {
            $('#cus_id ,#aadhar_number , #area, #mobile1').css('border', '1px solid #cecece');
        } else if (area) {
            $('#cus_id , #aadhar_number , #first_name, #mobile1 ').css('border', '1px solid #cecece');
        } else if (mobile) {
            $('#cus_id, #aadhar_number , #first_name, #area').css('border', '1px solid #cecece');
        }
    } else {
        // If no fields are filled, show validation errors
        if (!validateField(cus_id, 'cus_id')) {
            response = false;
        }
        if (!validateField(aadhar_number, 'aadhar_number')) {
            response = false;
        }
        if (!validateField(first_name, 'first_name')) {
            response = false;
        }
        if (!validateField(area, 'area')) {
            response = false;
        }
        if (!validateField(mobile, 'mobile1')) {
            response = false;
        }
    }
    return response;
}

var customerStatusResponse = null;

function OnLoadFunctions(cus_id, aadhar_number, first_name, area, mobile) {
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
        getLoanTable(cus_id, aadhar_number, first_name, area, mobile, bal_amt, sub_status_arr)
        hideOverlay();//loader stop
    });
}//Auto Load function END

function getLoanTable(cus_id, aadhar_number, first_name, area, mobile, balAmnt, sub_status_arr) {
    $.post('api/search_files/search_loan.php', { cus_id, aadhar_number, first_name, area, mobile, balAmnt, sub_status_arr }, function (response) {
        var columnMapping = [
            'sno',
            'loan_date',
            'loan_id',
            'loan_category',
            'loan_amount',
            'status',
            'sub_status',
            'info',
            'charts'
        ];
        appendDataToTable('#status_table', response, columnMapping);
        setdtable('#status_table', "Loan List");
        //Dropdown in List Screen
        setDropdownScripts();
    }, 'json');
}

// <------------------------------------------------------------------ Customer Profile Start --------------------------------------------------------------------------->

async function editCustmerProfile(id) {
    try {
        const response = await $.post('api/loan_entry_files/customer_profile_data.php', { id }, null, 'json');

        const data = response.data[0]; // <- Correct way to access the data

        // Populate customer profile fields
        $('#loan_entry_id').val(id);
        $('#cp_aadhar_number').val(data.aadhar_number);
        $('#cp_cus_id').val(data.cus_id);
        $('#cp_first_name').val(data.first_name);
        $('#last_name').val(data.last_name);
        $('#dob').val(data.dob);
        $('#age').val(data.age);
        $('#area_edit').val(data.area);
        $('#line').val(data.line);
        $('#cp_mobile1').val(data.mobile1);
        $('#mobile2').val(data.mobile2);
        $('#whatsapp').val(data.whatsapp);
        $('#cus_limit').val(data.cus_limit);
        $('#about_cus').val(data.about_cus);

        if (data.whatsapp === data.mobile1) {
            $('#mobile1_radio').prop('checked', true);
            $('#selected_mobile_radio').val('mobile1');
        } else if (data.whatsapp === data.mobile2) {
            $('#mobile2_radio').prop('checked', true);
            $('#selected_mobile_radio').val('mobile2');
        }

        await getAreaName();
        getPropertyInfoTable();
        getBankInfoTable();
        getKycInfoTable();

        $('#cp_area').trigger('change');

        const picPath = "uploads/customer_creation/cus_pic/";
        $('#per_pic').val(data.pic);
        $('#imgshow').attr('src', picPath + data.pic);

        $('#guarantor_info tbody').empty();

        if (data.guarantor_info && data.guarantor_info.length > 0) {
            data.guarantor_info.forEach((g, index) => {
                const newRow = $(`
                    <tr data-id="${g.id}">
                        <td>${index + 1}</td>
                        <td>${g.fam_name}</td>
                        <td>${g.fam_relationship}</td>
                        <td>${g.relation_type || ''}</td>
                        <td>${g.fam_aadhar}</td>
                        <td>${g.fam_mobile}</td>
                        <td style="display:none" class="hidden-guarantor-pics" 
                            data-gur-pic="${g.gur_pic || ''}">
                        </td>
                        <td style="display:none">
                            <input type="file" class="guarantor-pic-input" name="gu_pic_hidden[]" />
                        </td>
                    </tr>
                `);

                $('#guarantor_info tbody').append(newRow);
            });
        }

    } catch (error) {
        console.error('Error in editCustmerProfile:', error);
    }
}

function getAreaName() {
    return new Promise((resolve, reject) => {
        $.post(
            "api/customer_creation_files/get_area.php",
            function (response) {
                let appendAreaOption = "<option value=''>Select Area Name</option>";
                $.each(response, function (index, val) {
                    let selected = "";
                    let editArea = $("#area_edit").val();
                    if (val.id == editArea) {
                        selected = "selected";
                    }
                    appendAreaOption +=
                        "<option value='" +
                        val.id +
                        "' " +
                        selected +
                        ">" +
                        val.areaname +
                        "</option>";
                });
                $("#cp_area").empty().append(appendAreaOption);
                resolve(); // Resolve when done
            },
            "json"
        ).fail(reject); // Reject if AJAX fails
    });
}

function getAlineName(areaId) {
    $.ajax({
        url: 'api/customer_creation_files/getAlineName.php',
        type: 'POST',
        data: { aline_id: areaId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            if (response != '') {
                $('#line').val(response[0].linename);
                $('#line').attr('data-id', response[0].line_id);
            } else {
                $('#line').val('');
                $('#line').attr('data-id', '');
            }
        },
    });
}

function getPropertyInfoTable() {
    let cus_id = $('#cp_cus_id').val();
    $.post('api/customer_creation_files/property_creation_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'property',
            'property_detail',
            'property_holder',
            'fam_relationship',
        ];
        appendDataToTable('#prop_info', response, columnMapping);
        setdtable('#prop_info', "Property Info List");
    }, 'json')
}

function getBankInfoTable() {
    let cus_id = $('#cp_cus_id').val();
    $.post('api/customer_creation_files/bank_creation_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'bank_name',
            'branch_name',
            'acc_holder_name',
            'acc_number',
            'ifsc_code',
        ];
        appendDataToTable('#bank_info', response, columnMapping);
        setdtable('#bank_info', "Bank Info List");
    }, 'json')
}

function getKycInfoTable() {
    return new Promise((resolve, reject) => {
        let cus_id = $('#cp_cus_id').val();
        $.post('api/customer_creation_files/kyc_creation_list.php', { cus_id }, function (response) {
            var columnMapping = [
                'sno',
                'proof_of',
                'name',
                'fam_relationship',
                'proof',
                'proof_detail',
                'upload',
            ];
            appendDataToTable('#kyc_info', response, columnMapping);
            setdtable('#kyc_info', "KYC Info List");
            resolve();
        },
            "json"
        ).fail(reject);
    });
}

// <------------------------------------------------------------------ Customer Profile End --------------------------------------------------------------------------->

// <----------------------------------------------------------------- Loan Calculation Start --------------------------------------------------------------------------->

async function loanCalculationEdit(id) {
    try {
        const response = await $.ajax({
            url: 'api/loan_entry_files/loan_calculation_files/loan_calculation_data.php',
            type: 'POST',
            data: { id },
            dataType: 'json'
        });

        if (response.length > 0 && response[0].loan_id !== '') {
            const data = response[0];

            // Set basic values
            $('#loan_id_calc').val(data.loan_id);
            $('#loan_category_calc').val(data.loan_category);
            $('#loan_category_calc2').val(data.loan_category);
            $('#loan_amount_calc').val(moneyFormatIndia(data.loan_amount));
            $('#benefit_method').val(data.benefit_method);
            $('#due_method').val(data.due_method);
            $('#due_period').val(data.due_period);
            $('#interest_calculate').val(data.interest_calculate);
            $('#due_calculate').val(data.due_calculate);
            $('#interest_rate_calc').val(data.interest_rate_calc);
            $('#due_period_calc').val(data.due_period_calc);
            $('#doc_charge_calc').val(data.doc_charge_calc);
            $('#processing_fees_calc').val(data.processing_fees_calc);
            $('#loan_amnt_calc').val(data.loan_amnt_calc);
            $('#doc_charge_calculate').val(data.doc_charge_calculate);
            $('#processing_fees_calculate').val(data.processing_fees_calculate);
            $('#net_cash_calc').val(data.net_cash_calc);
            $('#interest_amnt_calc').val(data.interest_amnt_calc);
            $('#due_startdate_calc').val(data.due_startdate_calc);
            $('#maturity_date_calc').val(data.maturity_date_calc);
            $('#referred_calc').val(data.referred_calc).trigger('change');

            // Handle profit type card
            const categoryId = $('#loan_category_calc').val();
            if (categoryId === '') {
                $('#profit_type_calc').hide();
                return;
            } else {
                $('#profit_type_calc').show();
            }

            // Wait for dynamic fields to load before setting dependent values
            await fetchLoanCategoryFieldsAsync(data.loan_category);
            await callRefreshCalCalculation();

            // Set agent ID and await agent name
            $('#agent_id_calc').val(data.agent_id_calc);
            await agentNameFunction(data.agent_id_calc);
        }

    } catch (error) {
        console.error(" Failed to load loan calculation data:", error);
    }
}

function callLoanCaculationFunctions() {
    getLoanCategoryName();
    let cus_profile_id = $('#loan_entry_id').val();
    getDocNeedTable(cus_profile_id);
}

function getLoanCategoryName() {
    $.post('api/common_files/get_loan_category_creation.php', function (response) {
        let appendLoanCatOption = '';
        appendLoanCatOption += '<option value="">Select Loan Category</option>';
        $.each(response, function (index, val) {
            let selected = '';
            let loan_category_calc2 = $('#loan_category_calc2').val();
            if (val.id == loan_category_calc2) {
                selected = 'selected';
            }
            appendLoanCatOption += '<option value="' + val.id + '" ' + selected + '>' + val.loan_category + '</option>';
        });
        $('#loan_category_calc').empty().append(appendLoanCatOption);
    }, 'json');
}

function getDocNeedTable(cusProfileId) {
    $.post('api/loan_entry_files/loan_calculation_files/document_need_list.php', { cusProfileId }, function (response) {
        let loanCategoryColumn = [
            "sno",
            "document_name",
        ]
        appendDataToTable('#doc_need_table', response, loanCategoryColumn);
        setdtable('#doc_need_table', "Document Need List");
    }, 'json');
}

function agentNameFunction(id) {
    return new Promise((resolve, reject) => {
        $.post('api/agent_creation_files/agent_creation_data.php', { id }, function (response) {
            if (response.length > 0) {
                $('#agent_name_calc').val(response[0].agent_code);
            } else {
                $('#agent_name_calc').val('');
            }
            resolve();
        }, 'json').fail(reject);
    });
}

function getAgentID() {
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
        $('#agent_id_calc').empty().append(appendAgentIdOption);
    }, 'json');
}

function fetchLoanCategoryFieldsAsync(loan_category_calc) {
    return new Promise((resolve, reject) => {
        $('.int-diff').text('*');
        $('.due-diff').text('*');
        $('.doc-diff').text('*');
        $('.proc-diff').text('*');
        $('.refresh_loan_calc').val('');

        if (loan_category_calc !== '') {
            $.ajax({
                url: 'api/loan_entry_files/loan_calculation_files/get_loan_category_fields.php',
                type: 'POST',
                data: { id: loan_category_calc },
                dataType: 'json',
                success: function (response) {
                    if (response.result == 1) {
                        $('#profit_type_calc').show();

                        const data = response.data;
                        const interestMap = { 1: 'Month', 2: 'Days' };

                        // Retrieve customer and loan limits
                        let cus_limit = parseInt($('#cus_limit').val().replace(/,/g, ''));
                        let loan_limit = parseInt(data.loan_limit);
                        let min_loan_limit;


                        if (!cus_limit) {
                            // If cus_limit is empty or not a valid number, use loan_limit
                            min_loan_limit = loan_limit;
                        } else if (isNaN(cus_limit) || isNaN(loan_limit)) {
                            // If both cus_limit and loan_limit are NaN, set min_loan_limit to 0
                            min_loan_limit = 0;
                        } else {
                            // Use the lesser of cus_limit and loan_limit
                            min_loan_limit = (cus_limit < loan_limit) ? cus_limit : loan_limit;
                        }
                        $('#loan_amount_calc').attr('onChange', `if( parseFloat($(this).val().replace(/,/g, '')) > '` + min_loan_limit + `' ){ alert("Enter Lesser than '${min_loan_limit}'"); $(this).val(""); }`); //To check value between range


                        $('#benefit_method').val(data.benefit_method);
                        $('#due_method').val(data.due_method);
                        $('#due_period_calc').val(data.due_period);
                        $('#interest_calculate').val(interestMap[data.interest_calculate] || '');
                        $('#due_calculate').val(data.due_calculate);

                        $('.min-max-int').text(`* (${data.interest_rate_min}% - ${data.interest_rate_max}%)`);
                        $('#interest_rate_calc').off('change').on('change', function () {
                            let val = parseFloat($(this).val());
                            if (val > parseFloat(data.interest_rate_max)) {
                                alert("Enter Lesser Value for Interest Rate");
                                $(this).val('');
                            } else if (val < parseFloat(data.interest_rate_min)) {
                                alert("Enter Higher Value for Interest Rate");
                                $(this).val('');
                            }
                        });

                        $('.min-max-doc').text(
                            data.document_charge === 'rupee' ?
                                `* (${data.doc_charge_min}₹ - ${data.doc_charge_max}₹)` :
                                `* (${data.doc_charge_min}% - ${data.doc_charge_max}%)`
                        );
                        $('#doc_charge_calc').off('change').on('change', function () {
                            let val = parseFloat($(this).val());
                            if (val > parseFloat(data.doc_charge_max)) {
                                alert("Enter Lesser Value for Document Charge");
                                $(this).val('');
                            } else if (val < parseFloat(data.doc_charge_min)) {
                                alert("Enter Higher Value for Document Charge");
                                $(this).val('');
                            }
                        });

                        $('.min-max-proc').text(
                            data.processing_fee_type === 'rupee' ?
                                `* (${data.processing_fee_min}₹ - ${data.processing_fee_max}₹)` :
                                `* (${data.processing_fee_min}% - ${data.processing_fee_max}%)`
                        );
                        $('#processing_fees_calc').off('change').on('change', function () {
                            let val = parseFloat($(this).val());
                            if (val > parseFloat(data.processing_fee_max)) {
                                alert("Enter Lesser Value for Processing Fee");
                                $(this).val('');
                            } else if (val < parseFloat(data.processing_fee_min)) {
                                alert("Enter Higher Value for Processing Fee");
                                $(this).val('');
                            }
                        });

                        resolve(); //  Finished successfully
                    } else {
                        alert("No data found for selected loan category.");
                        reject("No data");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    reject(error);
                }
            });
        } else {
            swalError('Alert', 'Kindly select Loan Category');
            $('#profit_type_calc').hide();
            reject("Empty category");
        }
    });
}

async function callRefreshCalCalculation() {
    $('.int-diff').text('*');
    $('.due-diff').text('*');
    $('.doc-diff').text('*');
    $('.proc-diff').text('*');
    $('.refresh_loan_calc').val('');

    let loan_amt = $('#loan_amount_calc').val().replace(/,/g, '');
    let int_rate = $('#interest_rate_calc').val();
    let due_period = $('#due_period_calc').val();
    let doc_charge = $('#doc_charge_calc').val();
    let proc_fee = $('#processing_fees_calc').val();

    if (loan_amt !== '' && int_rate !== '' && due_period !== '' && doc_charge !== '' && proc_fee !== '') {
        await getLoanInterest(loan_amt, int_rate, doc_charge, proc_fee);
    } else {
        swalError('Warning', 'Kindly Fill the Calculation fields.');
    }
}

async function getLoanInterest(loan_amt, int_rate, doc_charge, proc_fee) {
    return new Promise((resolve) => {
        $('#loan_amnt_calc').val(moneyFormatIndia(parseInt(loan_amt).toFixed(0)));

        let interestType = $('#interest_calculate').val();
        let int_amt;

        if (interestType === 'Month') {
            int_amt = (loan_amt * (int_rate / 100)).toFixed(0);
        } else if (interestType === 'Days') {
            int_amt = (loan_amt * (int_rate / 100) / 30).toFixed(0);
        }

        let roundedInterest = Math.ceil(int_amt / 5) * 5;
        if (roundedInterest < int_amt) roundedInterest += 5;

        $('.int-diff').text('* (Difference: +' + parseInt(roundedInterest - int_amt) + ')');
        $('#interest_amnt_calc').val(moneyFormatIndia(parseInt(roundedInterest)));

        let doc_type = $('.min-max-doc').text();
        if (doc_type.includes('₹')) {
            doc_charge = parseInt(doc_charge);
        } else if (doc_type.includes('%')) {
            doc_charge = parseInt(loan_amt) * (parseFloat(doc_charge) / 100);
        }

        let roundeddoccharge = Math.ceil(doc_charge / 5) * 5;
        if (roundeddoccharge < doc_charge) roundeddoccharge += 5;

        $('.doc-diff').text('* (Difference: +' + parseInt(roundeddoccharge - doc_charge) + ')');
        $('#doc_charge_calculate').val(moneyFormatIndia(parseInt(roundeddoccharge)));

        let proc_type = $('.min-max-proc').text();
        if (proc_type.includes('₹')) {
            proc_fee = parseInt(proc_fee);
        } else if (proc_type.includes('%')) {
            proc_fee = parseInt(loan_amt) * (parseFloat(proc_fee) / 100);
        }

        let roundeprocfee = Math.ceil(proc_fee / 5) * 5;
        if (roundeprocfee < proc_fee) roundeprocfee += 5;

        $('.proc-diff').text('* (Difference: +' + parseInt(roundeprocfee - proc_fee) + ')');
        $('#processing_fees_calculate').val(moneyFormatIndia(parseInt(roundeprocfee)));

        let net_cash = parseInt(loan_amt) - parseInt(doc_charge) - parseInt(proc_fee);
        $('#net_cash_calc').val(moneyFormatIndia(parseInt(net_cash).toFixed(0)));

        resolve(); // Resolve the promise when done
    });
}

// <----------------------------------------------------------------- Loan Calculation End --------------------------------------------------------------------------->

// <----------------------------------------------------------------- Documentation Start ---------------------------------------------------------------------------->

$('#print_doc').click(function () {
    let cus_profile_id = $('#customer_profile_id').val();
    // Open a new window or tab
    var printWindow = window.open('', '_blank');

    // Make sure the popup window is not blocked
    if (printWindow) {
        // Load the content into the popup window
        $.ajax({
            url: 'api/loan_issue_files/print_document.php',
            data: { cus_profile_id },
            cache: false,
            type: "post",
            success: function (html) {
                // Write the content to the new window
                printWindow.document.open();
                printWindow.document.write(html);
                printWindow.document.close();

                // Optionally, print the content
                printWindow.print();
            },
            error: function () {
                // Handle error
                printWindow.close();
                alert('Failed to load print content.');
            }
        });
    } else {
        alert('Popup blocked. Please allow popups for this website.');
    }
});

function getChequeInfoTable() {
    let cus_profile_id = $('#customer_profile_id').val();

    $.post('api/loan_issue_files/cheque_info_list.php', { cus_profile_id }, function (response) {
        // Check if the response length is greater than 0
        if (response && response.length > 0) {
            // Show the cheque div and populate the table if the condition is met
            $('.cheque-div').show();
        }
        let chequeColumn = [
            "sno",
            "holder_type",
            "holder_name",
            "relationship",
            "bank_name",
            "cheque_cnt",
            "upload"
        ];

        appendDataToTable('#cheque_info_table', response, chequeColumn);
        setdtable('#cheque_info_table', "Cheque Info List");

    }, 'json');
}

function getDocInfoTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/doc_info_list.php', { cus_profile_id }, function (response) {
        if (response && response.length > 0) {
            $('.doc_div').show();
        }
        let docColumn = [
            "sno",
            "doc_name",
            "doc_type",
            "holder_name",
            "relationship",
            "upload"
        ]
        appendDataToTable('#document_info', response, docColumn);
        setdtable('#document_info', "Document Info List");

    }, 'json');
}

function getMortInfoTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/mortgage_info_list.php', { cus_profile_id }, function (response) {
        if (response && response.length > 0) {
            $('.mortgage-div').show();
        }
        let mortgageColumn = [
            "sno",
            "holder_name",
            "relationship",
            "property_details",
            "mortgage_name",
            "designation",
            "mortgage_number",
            "reg_office",
            "mortgage_value",
            "upload"
        ]
        appendDataToTable('#mortgage_info', response, mortgageColumn);
        setdtable('#mortgage_info', "Mortgage Info List");
    }, 'json');
}

function getEndorsementInfoTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/endorsement_info_list.php', { cus_profile_id }, function (response) {
        if (response && response.length > 0) {
            $('.endorsement-div').show();
        }
        let endorsementColumn = [
            "sno",
            "holder_name",
            "relationship",
            "vehicle_details",
            "endorsement_name",
            "key_original",
            "rc_original",
            "upload"
        ]
        appendDataToTable('#endorsement_info', response, endorsementColumn);
        setdtable('#endorsement_info', "Endorsement Info List");

    }, 'json');
}

function getGoldInfoTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/gold_info_list.php', { cus_profile_id }, function (response) {
        if (response && response.length > 0) {
            $('.gold-div').show();
        }
        let goldColumn = [
            "sno",
            "gold_type",
            "purity",
            "weight",
            "value"
        ]
        appendDataToTable('#gold_info', response, goldColumn);
        setdtable('#gold_info', "Gold Info List");

    }, 'json');
}

// <----------------------------------------------------------------- Documentation End ---------------------------------------------------------------------------->

// <----------------------------------------------------------------- Closed Remark Start --------------------------------------------------------------------------->

function closeChartsModal() {
    $('#due_chart_model').modal('hide');
    $('#penalty_model').modal('hide');
    $('#fine_model').modal('hide');
    $('#closed_remark_model').modal('hide');
}

// <----------------------------------------------------------------- Closed Remark end --------------------------------------------------------------------------->

// <----------------------------------------------------------------- NOC Summary Start --------------------------------------------------------------------------->

async function callAllFunctions(le_id) {
    // Run all the functions concurrently
    $('.cheque-div').hide();
    $('.doc_div').hide();
    $('.mortgage-div').hide();
    $('.endorsement-div').hide();
    $('.gold-div').hide();
    await Promise.all([
        getChequeList(le_id),
        getMortgageList(le_id),
        getEndorsementList(le_id),
        getOtherDocumentList(le_id),
        getGoldList(le_id)
    ]);

    // Set the submitted disabled state
    setSubmittedDisabled();
}

function setSubmittedDisabled() {
    $('.noc_cheque_chkbx, .noc_mortgage_chkbx, .noc_endorsement_chkbx, .noc_doc_info_chkbx, .noc_gold_chkbx').each(function () {
        if ($(this).attr('data-id') == '1') {
            $(this).closest('tr').addClass('disabled-row');
            $(this).attr('checked', true).attr('disabled', true);
        }
    });

    var cheque_checkDisabled = $('.noc_cheque_chkbx:disabled').length === $('.noc_cheque_chkbx').length;
    var mort_checkDisabled = $('.noc_mortgage_chkbx:disabled').length === $('.noc_mortgage_chkbx').length;
    var endorse_checkDisabled = $('.noc_endorsement_chkbx:disabled').length === $('.noc_endorsement_chkbx').length;
    var doc_checkDisabled = $('.noc_doc_info_chkbx:disabled').length === $('.noc_doc_info_chkbx').length;
    var gold_checkDisabled = $('.noc_gold_chkbx:disabled').length === $('.noc_gold_chkbx').length;
    if (cheque_checkDisabled && mort_checkDisabled && endorse_checkDisabled && doc_checkDisabled && gold_checkDisabled) {
        $('#submit_noc').hide();
    } else {
        $('#submit_noc').show();
    }
}

function getChequeList(le_id) {
    return $.post('api/noc_files/noc_cheque_list.php', { le_id }, function (response) {
        if (response && response.length > 0) {
            $('.cheque-div').show();
        }
        let nocChequeColumns = [
            'sno', 'holder_type', 'holder_name', 'relationship', 'bank_name',
            'cheque_no', 'date_of_noc', 'noc_member', 'noc_relationship', 'action'
        ];
        appendDataToTable('#noc_cheque_list_table', response, nocChequeColumns);
        setdtable('#noc_cheque_list_table', "Cheque List");
    }, 'json');
}

function getMortgageList(le_id) {
    return $.post('api/noc_files/noc_mortgage_list.php', { le_id }, function (response) {
        if (response && response.length > 0) {
            $('.mortgage-div').show();
        }
        let nocMortgageColumns = [
            'sno', 'holder_name', 'relationship', 'property_details', 'mortgage_name',
            'designation', 'reg_office', 'date_of_noc', 'noc_member', 'noc_relationship', 'action'
        ];
        appendDataToTable('#noc_mortgage_list_table', response, nocMortgageColumns);
        setdtable('#noc_mortgage_list_table', "Mortgage List");
    }, 'json');
}

function getEndorsementList(le_id) {
    return $.post('api/noc_files/noc_endorsement_list.php', { le_id }, function (response) {
        if (response && response.length > 0) {
            $('.endorsement-div').show();
        }
        let nocEndorseColumns = [
            'sno', 'holder_name', 'relationship', 'vehicle_details', 'endorsement_name',
            'key_original', 'rc_original', 'date_of_noc', 'noc_member', 'noc_relationship', 'action'
        ];
        appendDataToTable('#noc_endorsement_list_table', response, nocEndorseColumns);
        setdtable('#noc_endorsement_list_table', "Endorsement List");
    }, 'json');
}

function getOtherDocumentList(le_id) {
    return $.post('api/noc_files/noc_document_info_list.php', { le_id }, function (response) {
        if (response && response.length > 0) {
            $('.doc_div').show();
        }
        let nocDocInfoColumns = [
            'sno', 'doc_name', 'doc_type', 'holder_name', 'upload', 'date_of_noc',
            'noc_member', 'noc_relationship', 'action'
        ];
        appendDataToTable('#noc_document_list_table', response, nocDocInfoColumns);
        setdtable('#noc_document_list_table', "Document List");
    }, 'json');
}

function getGoldList(le_id) {
    return $.post('api/noc_files/noc_gold_list.php', { le_id }, function (response) {
        if (response && response.length > 0) {
            $('.gold-div').show();
        }
        let nocGoldColumns = [
            'sno', 'gold_type', 'purity', 'weight', 'date_of_noc',
            'noc_member', 'noc_relationship', 'action'
        ];
        appendDataToTable('#noc_gold_list_table', response, nocGoldColumns);
        setdtable('#noc_gold_list_table', "Gold List");
    }, 'json');
}

// <----------------------------------------------------------------- NOC Summary End --------------------------------------------------------------------------->

// <----------------------------------------------------- Due Chart , Penalty Chart , Fine Chart Function Start -------------------------------------------------->

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

// <--------------------------------------------------------------- Due Chart , Penalty Chart , Fine Chart End -------------------------------------------------------->