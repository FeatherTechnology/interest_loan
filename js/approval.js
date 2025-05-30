$(document).ready(function () {
    // Loan Entry Tab Change Radio buttons
    $(document).on('click', '#back_btn', function () {
        swapTableAndCreation();
    });

    ////////////////////////////////////////////////////////////////////// Kyc Modal Start ////////////////////////////////////////////////////////////////////////////////

    $('#submit_kyc').click(function (event) {
        event.preventDefault();
        //Validation

        let cus_id = $('#cus_id').val();
        let upload = $('#upload')[0].files[0];
        let kyc_upload = $('#kyc_upload').val();
        let proof_of = $('#proof_of').val();
        let fam_mem = $("#fam_mem").val();
        let proof = $('#proof').val();
        let proof_detail = $('#proof_detail').val();
        let kyc_id = $('#kyc_id').val();

        var data = ['proof_of', 'kyc_relationship', 'proof', 'proof_detail']
        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
            let kycDetail = new FormData();

            kycDetail.append('proof_of', proof_of)
            kycDetail.append('fam_mem', fam_mem);
            kycDetail.append('cus_id', cus_id)
            kycDetail.append('proof', proof)
            kycDetail.append('proof_detail', proof_detail)
            kycDetail.append('upload', upload)
            kycDetail.append('kyc_upload', kyc_upload)
            kycDetail.append('kyc_id', kyc_id)

            $.ajax({
                url: 'api/customer_creation_files/submit_kyc.php',
                type: 'post',
                data: kycDetail,
                contentType: false,
                processData: false,
                cache: false,
                success: function (response) {

                    if (response === '2') {
                        swalSuccess('Success', 'KYC Added Successfully!');
                        $('.kyc_name_div').hide();
                        $('.fam_mem_div').hide();
                    } else if (response === '1') {
                        swalSuccess('Success', 'KYC Updated Successfully!')
                        $('.kyc_name_div').hide();
                        $('.fam_mem_div').hide();
                    }
                    else {
                        swalError('Error', 'Error Occurred!');
                    }

                    getKycTable();
                }
            });
        }
    });

    $(document).on('click', '.kycActionBtn', async function () {
        const id = $(this).attr('value');

        try {
            const response = await $.post('api/customer_creation_files/kyc_creation_data.php', { id }, null, 'json');

            if (response && response.length > 0) {
                $('#kyc_id').val(id);
                $('#proof_of').val(response[0].proof_of);

                if (response[0].proof_of == 1) {
                    $('.kyc_name_div').show();
                    const first_name = $("#first_name").val().trim();
                    const last_name = $("#last_name").val().trim();
                    const cus_name = first_name + (last_name ? " " + last_name : "");
                    $('#kyc_name').val(cus_name);
                    $('.fam_mem_div').hide();
                    $('#fam_mem').val('');
                } else {
                    $('.kyc_name_div').hide();
                    $('#kyc_name').val('');
                    await getFamilyMember(); // Wait for dropdown to populate
                    $('#fam_mem').val(response[0].fam_mem);
                    $('.fam_mem_div').show();
                }

                if (response[0].proof_of == 1) {
                    $('#kyc_relationship').val('NIL');
                } else {
                    $('#kyc_relationship').val(response[0].fam_relationship);
                }

                $('#proof').val(response[0].proof);
                $('#proof_detail').val(response[0].proof_detail);
                $('#kyc_upload').val(response[0].upload);
            } else {
                alert('No data found for the selected KYC ID.');
            }
        } catch (error) {
            console.error("Error fetching KYC data:", error);
            alert('Something went wrong while fetching KYC data.');
        }
    });

    $(document).on('click', '.kycDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the KYC Details?', getKycDelete, id);
        return;
    });

    $('#clear_kyc_form').on('click', function () {
        $('.fam_mem_div').hide();
        $('#fam_mem').val('');
    });

    $('.kycmodal_close').on('click', function () {
        $('.fam_mem_div').hide();
        $('#fam_mem').val('');
    });

    $('#proof_of').on('change', function () {
        if ($(this).val() == "2") {
            $('.fam_mem_div').show();
            $('.kyc_name_div').hide();
        } else {
            $('.kyc_name_div').show();
            $('.fam_mem_div').hide();
        }
    });

    $('#proof_of').change(function () {
        var proofOf = $(this).val();
        if (proofOf == "2") { // Family Member selected
            $('.fam_mem_div').show();
            $('.kyc_name_div').hide();
            $('#kyc_relationship').val('');
            getFamilyMember();
        } else { // Customer or any other selection
            let first_name = $("#first_name").val().trim();
            let last_name = $("#last_name").val().trim();
            let cus_name = first_name + (last_name ? " " + last_name : "");
            $('#kyc_name').val(cus_name);
            $('.kyc_name_div').show();
            $('.fam_mem_div').hide();
            $('#kyc_relationship').val('NIL');
        }
    });

    $('#fam_mem').change(function () {
        var familyMemberId = $(this).val();
        if (familyMemberId) {
            getKycRelationshipName(familyMemberId);
        } else {
            $('#kyc_relationship').val('');
        }
    });

    ////////////////////////////////////////////////////////////////////// KyC Modal End /////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////// KyC Proof Modal Start //////////////////////////////////////////////////////////////////////////////

    $('#submit_proof').click(function () {
        event.preventDefault();
        //Validation
        let addProof_name = $('#addProof_name').val();
        let proof_id = $('#proof_id').val();
        var data = ['addProof_name']
        var isValid = true;

        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $.post('api/customer_creation_files/submit_proof.php', { addProof_name, proof_id }, function (response) {

                if (response === '2') {
                    swalSuccess('Success', 'Proof Added Successfully!');
                } else if (response === '1') {
                    swalSuccess('Success', 'Proof Updated Successfully!');
                } else {
                    swalError('Error', 'Error Occurred!');
                }

                $('#proof_id').val('')
                $('#add_proof_info_modal').modal('hide');
                getProofTable();
                fetchProofList();
            });
        }

    });

    $(document).on('click', '.proofActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/customer_creation_files/proof_creation_data.php', { id: id }, function (response) {
            $('#proof_id').val(id);
            $('#addProof_name').val(response[0].addProof_name);
        }, 'json');
    });

    $(document).on('click', '.proofDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Proof Details?', getProofDelete, id);
        return;
    });

    $('#proof_modal_btn').click(function () {
        if ($('#add_kyc_info_modal').is(':visible')) {
            $('#add_kyc_info_modal').hide();
        }
    });

    $('.kyc_proof_close').click(function () {
        if ($('#add_kyc_info_modal').is(':hidden')) {
            $('#add_kyc_info_modal').show();
        }
    });

    ////////////////////////////////////////////////////////////////// KyC Proof Modal End //////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////// submit Bank Info Model Start //////////////////////////////////////////////////////////////////////////

    $('#submit_bank').click(function () {
        event.preventDefault();
        //Validation

        let cus_id = $('#cus_id').val();
        let bank_name = $('#bank_name').val();
        let branch_name = $('#branch_name').val();
        let acc_holder_name = $('#acc_holder_name').val();
        let acc_number = $('#acc_number').val();
        let ifsc_code = $('#ifsc_code').val();
        let bank_id = $('#bank_id').val();

        var data = ['bank_name', 'branch_name', 'acc_holder_name', 'acc_number', 'ifsc_code']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $.post('api/customer_creation_files/submit_bank.php', { cus_id, bank_name, branch_name, acc_holder_name, acc_number, ifsc_code, bank_id }, function (response) {

                if (response === '2') {
                    swalSuccess('Success', 'Bank Added Successfully!');
                } else if (response === '1') {
                    swalSuccess('Success', 'Bank Updated Successfully!');
                } else {
                    swalError('Error', 'Error Occurred!');
                }

                getBankTable();
            });
        }
    })

    $(document).on('click', '.bankActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/customer_creation_files/bank_creation_data.php', { id: id }, function (response) {
            $('#bank_id').val(id);
            $('#bank_name').val(response[0].bank_name);
            $('#branch_name').val(response[0].branch_name);
            $('#acc_holder_name').val(response[0].acc_holder_name);
            $('#acc_number').val(response[0].acc_number);
            $('#ifsc_code').val(response[0].ifsc_code);
        }, 'json');
    });

    $(document).on('click', '.bankDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Bank Details?', getBankDelete, id);
        return;
    });

    /////////////////////////////////////////////////////////// submit Bank Info Model End ////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////// submit property info Start //////////////////////////////////////////////////////////////////////////////

    $('#submit_property').click(function () {
        event.preventDefault();
        //Validation
        let cus_id = $('#cus_id').val();

        let property = $('#property').val();
        let property_detail = $('#property_detail').val();
        let property_holder = $('#property_holder').val();
        let property_id = $('#property_id').val();

        var data = ['property', 'property_detail', 'property_holder', 'prop_relationship']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            $.post('api/customer_creation_files/submit_property.php', { cus_id, property, property_detail, property_holder, property_id }, function (response) {

                if (response === '2') {
                    swalSuccess('Success', 'Property Added Successfully!');
                } else if (response === '1') {
                    swalSuccess('Success', 'Property Updated Successfully!');
                } else {
                    swalError('Error', 'Error Occurred!');
                }

                getPropertyTable();
            });
        }
    });

    $(document).on('click', '.propertyActionBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/customer_creation_files/property_creation_data.php', { id: id }, function (response) {
            $('#property_id').val(id);
            $('#property').val(response[0].property);
            $('#property_detail').val(response[0].property_detail);
            $('#property_holder').val(response[0].property_holder);
            if (response[0].fam_relationship == null) {
                $('#prop_relationship').val('Customer');
            } else {
                $('#prop_relationship').val(response[0].fam_relationship);
            }

        }, 'json');
    });

    $(document).on('click', '.propertyDeleteBtn', function () {
        var id = $(this).attr('value');
        swalConfirm('Delete', 'Do you want to Delete the Property Details?', getPropertyDelete, id);
        return;
    });

    $('#property_holder').change(function () {
        var propertyHolderId = $(this).val();
        if (propertyHolderId != '' && propertyHolderId != 0) {
            getRelationshipName(propertyHolderId);
        } else if (propertyHolderId == 0) {
            $('#prop_relationship').val('Customer');
        } else {
            $('#prop_relationship').val('');
        }
    });

    /////////////////////////////////////////////////////////// submit property info end ///////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////// Submit Guarantor Info Start  ///////////////////////////////////////////////////////////////////////////////

    $('#add_guarantor_info').click(function (event) {
        event.preventDefault();

        let guarantor_name = $('#guarantor_name').val();
        let gu_pic = $('#gu_pic')[0].files[0]; // This is your File object
        let gur_pic = $('#gur_pic').val();

        if (!guarantor_name) {
            swalError('Alert', 'Please select Guarantor Name');
            return;
        }

        // Max limit check
        if ($('#guarantor_info tbody tr').length >= 5) {
            swalError('Limit Reached', 'You can only map up to 5 guarantors.');
            return;
        }

        let guarantorExists = false;
        $('#guarantor_info tbody tr').each(function () {
            if ($(this).attr('data-id') == guarantor_name) {
                guarantorExists = true;
                return false;
            }
        });

        if (guarantorExists) {
            swalError('Warning', 'Guarantor is already mapped.');
            $('#guarantor_name').val('');
            $('#relationship').val('');
            return;
        }

        let formData = new FormData();
        formData.append('guarantor_name', guarantor_name);

        $.ajax({
            url: 'api/customer_creation_files/add_guarantor_info.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.result === 1) {
                    let g = response.customer_data;

                    // Create new row with hidden file input (empty for now)
                    let newRow = $(`
                    <tr data-id="${g.id}">
                        <td>${$('#guarantor_info tbody tr').length + 1}</td>
                        <td>${g.fam_name}</td>
                        <td>${g.fam_relationship}</td>
                        <td>${g.relation_type || ''}</td>
                        <td>${g.fam_aadhar}</td>
                        <td>${g.fam_mobile}</td>
                        <td style="display:none" class="hidden-guarantor-pics" 
                            data-gur-pic="${gur_pic}">
                        </td>
                        <td style="display:none">
                            <input type="file" class="guarantor-pic-input" name="gu_pic_hidden[]" />
                        </td>
                        <td>
                            <span class="icon-trash-2 guaMapDeleteBtn" style="cursor:pointer;"></span>
                        </td>
                    </tr>
                `);

                    $('#guarantor_info tbody').append(newRow);

                    // Copy file to hidden input
                    if (gu_pic) {
                        let lastRow = $('#guarantor_info tbody tr').last();
                        let hiddenFileInput = lastRow.find('.guarantor-pic-input')[0];
                        let dt = new DataTransfer();
                        dt.items.add(gu_pic);
                        hiddenFileInput.files = dt.files;
                    }

                    // Clear input values
                    $('#guarantor_name').val('');
                    $('#relationship').val('');
                    $('#gu_pic').val('');
                    $('#gur_pic').val('');
                    $('#gur_imgshow').attr('src', 'img/avatar.png');
                } else {
                    swalError('Warning', response.message || 'Something went wrong.');
                }
            }
        });
    });

    //////////////////////////////////////////////////////// Submit Guarantor Info End  ///////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////// Guarantor Delete Start //////////////////////////////////////////////////////////////////////////

    // Event listener for delete button click
    $(document).on('click', '.guaMapDeleteBtn', function () {
        let btn = $(this);
        let row = btn.closest('tr');
        let family_info_id = row.attr('data-id'); // this is the `guarantor_id`
        let loan_entry_id = $('#customer_profile_id').val(); // get current loan_entry_id

        let TableRowVal = {
            row: row,
            family_info_id: family_info_id,
            loan_entry_id: loan_entry_id
        };

        swalConfirm('Delete', 'Do you want to remove this customer mapping?', removeGuaMap, TableRowVal, '');
    });

    //////////////////////////////////////////////////////////////// Guarantor Delete End //////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////// Customer Profile Submit Start /////////////////////////////////////////////////////////////////////

    $('#submit_customer_profile').click(function (event) {
        event.preventDefault();

        let cus_id = $('#cus_id').val();
        let aadhar_number = $('#aadhar_number').val().replace(/\s/g, '');
        let customer_profile_id = $('#customer_profile_id').val();
        let cus_limit = $('#cus_limit').val().replace(/,/g, '');
        let about_cus = $('#about_cus').val();

        let isValid = true;

        if (!aadhar_number) {
            validateField(aadhar_number, 'aadhar_number');
            isValid = false;
        }

        if (!cus_id) {
            validateField(cus_id, 'cus_id');
            isValid = false;
        }

        // Create FormData object
        let personalDetail = new FormData();
        personalDetail.append('cus_id', cus_id);
        personalDetail.append('aadhar_number', aadhar_number);
        personalDetail.append('customer_profile_id', customer_profile_id);
        personalDetail.append('cus_limit', cus_limit);
        personalDetail.append('about_cus', about_cus);

        // Gather guarantor data
        var guarantorMappingData = [];
        $('#guarantor_info tbody tr').each(function (index) {
            var $row = $(this);
            var guar_id = $row.attr('data-id');
            var gur_pic = $row.find('.hidden-guarantor-pics').data('gur-pic');
            var fileInput = $row.find('.guarantor-pic-input')[0];

            // Append file with dynamic key like gu_pic_0, gu_pic_1, etc.
            if (fileInput && fileInput.files.length > 0) {
                personalDetail.append('gu_pic_' + index, fileInput.files[0]);
            }

            guarantorMappingData.push({
                guar_id: guar_id,
                gur_pic: gur_pic // only the stored path, not the file
                // gu_pic file is added separately above
            });
        });

        // Add mapping data
        personalDetail.append('guarantorMappingData', JSON.stringify(guarantorMappingData));
        if (isValid) {
            // Submit via AJAX
            $.ajax({
                url: 'api/loan_entry_files/submit_customer_profile_info.php',
                type: 'POST',
                data: personalDetail,
                contentType: false,
                processData: false,
                cache: false,
                dataType: 'json',
                success: function (response) {
                    if (response.result == 1) {
                        swalSuccess('Success', 'Customer Profile Updated Successfully!');
                    } else if (response.result == 2) {
                        swalSuccess('Success', 'Customer Profile Added Successfully!');
                    }

                    $('#loan_calculation').trigger('click')
                    $('html, body').animate({
                        scrollTop: $('.page-content').offset().top
                    }, 3000);

                    $('#customer_profile_id').val(response.loan_entry_id);
                }
            });
        }
    });

    //////////////////////////////////////////////////////////// Customer Profile Submit End /////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////// Customer Profile Edit start /////////////////////////////////////////////////////////////////////

    $(document).on('click', '.approval-edit', function () {
        let id = $(this).attr('value');
        $('#customer_profile_id').val(id);
        swapTableAndCreation();
        editCustmerProfile(id);
    });

    $(document).on('click', '.approval-approve', function () {
        let loan_entry_id = $(this).attr('data-id');
        let cus_sts_id = $(this).attr('value');
        let cus_sts = 4;

        // Call check Customer Limit and only proceed when response is valid
        checkCustomerLimit(loan_entry_id, cus_sts_id, cus_sts);
    });


    $(document).on('click', '.approval-cancel', function () {
        let cus_sts_id = $(this).attr('value');
        let cus_sts = 5;
        $('#add_info_modal').modal('show');
        $('#exampleModalLongTitle').text('Cancel');
        $('.modal_revoke').text('Cancel');
        $('#customer_profile_id').val(cus_sts_id);
        $('#customer_status').val(cus_sts);
        $('#remark').val('');
    });

    $(document).on('click', '.approval-revoke', function () {
        let cus_sts_id = $(this).attr('value');
        let cus_sts = 6;
        $('#add_info_modal').modal('show');
        $('#exampleModalLongTitle').text('Revoke');
        $('.modal_revoke').text('Revoke');
        $('#customer_profile_id').val(cus_sts_id);
        $('#customer_status').val(cus_sts);
        $('#remark').val('');
    });

    $(document).on('click', '#submit_remark', function () {
        event.preventDefault();
        let action = $('#exampleModalLongTitle').text().toLowerCase(); // Get action (cancel or revoke)
        let cus_sts_id = $('#customer_profile_id').val();
        let cus_sts = $('#customer_status').val();
        let remark = $('#remark').val();

        if (remark === '') {
            alert('Please enter a remark.');
            return;
        }

        submitForm(action, cus_sts_id, cus_sts, remark);
    });

    //////////////////////////////////////////////////////////// Customer Profile Edit End ///////////////////////////////////////////////////////////////////////


    // <-------------------------------------------- Customer Profile and Loan Calculation On Click Radio Button --------------------------------------------------->

    $('input[name=loan_entry_type]').click(function () {
        let loanEntryType = $(this).val();
        if (loanEntryType == 'cus_profile') {
            $('#loan_entry_customer_profile').show(); $('#loan_entry_loan_calculation').hide();

        } else if (loanEntryType == 'loan_calc') {
            $('#loan_entry_customer_profile').hide(); $('#loan_entry_loan_calculation').show();
            callLoanCaculationFunctions();
        }
    })

    //<---------------------- Family info relationship - other on Change  ----------------------------------->

    $('#fam_relationship').on('change', function () {
        if ($(this).val() === 'Other') {
            $('.other').show();
        } else {
            $('.other').hide();
            $('#relation_type').val(''); // Clear input when hidden
        }
    });

    //<------------------------------------ aadhar number Change  ------------------------------------------------->

    $("#aadhar_number").on("blur", function () {
        let aadhar_number = $("#aadhar_number").val().trim().replace(/\s/g, "");
        existingCustmerProfile(aadhar_number);
    });

    $('input[data-type="adhaar-number"]').change(function () {
        let len = $(this).val().length;
        if (len < 14) {
            $(this).val('');
            swalError('Warning', 'Kindly Enter Valid Aadhaar Number');
        }
    });

    // Function to format Aadhaar number input
    $('input[data-type="adhaar-number"]').keyup(function () {
        var value = $(this).val();
        value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join(" ");
        $(this).val(value);
    });

    //<---------------------- area and line on chnage function --------------------------------------------------->

    $('#area').change(function () {
        var areaId = $(this).val();
        if (areaId) {
            getAlineName(areaId);
        }
    });

    //<-------------------------- Guranator on chnage function --------------------------------------------------->

    $('#guarantor_name').change(function () {
        var family_info_id = $(this).val();
        if (family_info_id) {
            getFamilyRelationship(family_info_id);
        } else {
            $('#relationship').val('');
        }
    })

    // <-------------------------------------- Guarantor pic On Change Function ----------------------------------------->

    $('#gu_pic').change(function () {
        let pic = $('#gu_pic')[0];
        let img = $('#gur_imgshow');
        img.attr('src', URL.createObjectURL(pic.files[0]));
        checkInputFileSize(this, 200, img)
    })

    //////////////////////////////////////////////////////////// Loan Category On Change Function Start /////////////////////////////////////////////////////////

    $('#loan_category_calc').change(function () {
        let loan_category_calc = $(this).val();
        fetchLoanCategoryFieldsAsync(loan_category_calc);
        $('#interest_rate_calc').val('');
        $('#doc_charge_calc').val('');
        $('#processing_fees_calc').val('');
    });

    //////////////////////////////////////////////////////////// Loan Category On Change Function END /////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////// Calculate Button Function Start /////////////////////////////////////////////////////////

    $('#refresh_cal').click(function () {
        callRefreshCalCalculation()
    });

    //////////////////////////////////////////////////////////// Calculate Button Function END /////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////// Loan Date Function Start /////////////////////////////////////////////////////////////////

    {
        // Get today's date
        var today = new Date().toISOString().split('T')[0];
        //Set loan date
        $('#loan_date_calc').val(today);
        //Due start date -- set min date = current date.
        $('#due_startdate_calc').attr('min', today);
    }

    //////////////////////////////////////////////////////////// Loan Date END ////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////// Due Start Date On Change Start ///////////////////////////////////////////////////////////////////////

    $('#due_startdate_calc').change(function () {
        var due_start_from = $('#due_startdate_calc').val(); // get start date to calculate maturity date
        var due_period = parseInt($('#due_period_calc').val()); //get due period to calculate maturity date
        var due_method = $('#due_method').val()

        if (due_period == '' || isNaN(due_period)) {
            swalError('Warning', 'Kindly Fill the Due Period field.');
            $(this).val('');
        } else {
            if (due_method == 'Monthly' || due_method == '1') { // if due method is monthly or 1(for scheme) then calculate maturity by month

                var maturityDate = moment(due_start_from, 'YYYY-MM-DD').add(due_period, 'months').subtract(1, 'month').format('YYYY-MM-DD');//subract one month because by default its showing extra one month
                $('#maturity_date_calc').val(maturityDate);
            }
        }
    });

    //////////////////////////////////////////////////////////// Due Start Date On Change Start ///////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////// Agent On Change Start //////////////////////////////////////////////////////////////////////////////

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

    ///////////////////////////////////////////////////////////// Agent On Change end //////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////// Submit Document On Change Start //////////////////////////////////////////////////////////////////////////////

    $('#submit_doc_need').click(function () {
        event.preventDefault();
        let docName = $('#doc_need_calc').val();
        let cusProfileId = $('#customer_profile_id').val();
        let cusID = $('#cus_id').val();

        if (docName != '') {

            $.post('api/loan_entry_files/loan_calculation_files/submit_document_need.php', { docName, cusProfileId, cusID }, function (response) {
                getDocNeedTable(cusProfileId);
            }, 'json');

            $('#doc_need_calc').val('');
        }

    });

    $(document).on('click', '.docNeedDeleteBtn', function () {
        var id = $(this).attr('value'); // Get value attribute
        $.post('api/loan_entry_files/loan_calculation_files/delete_doc_need.php', { id }, function (response) {
            if (response == '0') {
                // swalSuccess('Success', 'Document Need Deleted Successfully.');
                let cus_profile_id = $('#customer_profile_id').val();
                getDocNeedTable(cus_profile_id);
            } else {
                // swalError('Error', 'Document Need Delete Failed.');
            }
        }, 'json');
    });

    ///////////////////////////////////////////////////////////// Submit Document On Change end //////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////// Submit Loan Calculation Start //////////////////////////////////////////////////////////////////////////////

    $('#submit_loan_calculation').click(function (event) {
        event.preventDefault();

        $('#refresh_cal').trigger('click'); //For calculate once again if user missed to refresh calculation
        let formData = {
            'loan_id_calc': $('#loan_id_calc').val(),
            'loan_category_calc': $('#loan_category_calc').val(),
            'loan_amount_calc': $('#loan_amount_calc').val().replace(/,/g, ''),
            'benefit_method': $('#benefit_method').val(),
            'due_method': $('#due_method').val(),
            'due_period': $('#due_period').val(),
            'interest_calculate': $('#interest_calculate').val(),
            'due_calculate': $('#due_calculate').val(),
            'interest_rate_calc': $('#interest_rate_calc').val(),
            'due_period_calc': $('#due_period_calc').val(),
            'doc_charge_calc': $('#doc_charge_calc').val(),
            'processing_fees_calc': $('#processing_fees_calc').val(),
            'loan_amnt_calc': $('#loan_amnt_calc').val().replace(/,/g, ''),
            'doc_charge_calculate': $('#doc_charge_calculate').val().replace(/,/g, ''),
            'processing_fees_calculate': $('#processing_fees_calculate').val().replace(/,/g, ''),
            'net_cash_calc': $('#net_cash_calc').val().replace(/,/g, ''),
            'interest_amnt_calc': $('#interest_amnt_calc').val().replace(/,/g, ''),
            'loan_date_calc': $('#loan_date_calc').val(),
            'due_startdate_calc': $('#due_startdate_calc').val(),
            'maturity_date_calc': $('#maturity_date_calc').val(),
            'referred_calc': $('#referred_calc').val(),
            'agent_id_calc': $('#agent_id_calc').val(),
            'agent_name_calc': $('#agent_name_calc').val(),
            'id': $('#customer_profile_id').val(),
            'cus_status': '2'
        }
        if (isFormDataValid(formData)) {
            $.post('api/loan_entry_files/loan_calculation_files/submit_loan_calculation.php', formData, function (response) {
                if (response.status == '1') {
                    swalSuccess('Success', 'Loan Calculation Added Successfully!');
                    if ($('.page-content').length) {
                        $('html, body').animate({
                            scrollTop: $('.page-content').offset().top
                        }, 3000);
                    }
                } else if (response.status == '2') {
                    swalSuccess('Success', 'Loan Calculation Updated Successfully!')
                    if ($('.page-content').length) {
                        $('html, body').animate({
                            scrollTop: $('.page-content').offset().top
                        }, 3000);
                    }
                } else {
                    swalError('Error', 'Error Occurs!')
                }

            }, 'json');
        }
    });

    ///////////////////////////////////////////////////////////// Submit Loan Calculation End /////////////////////////////////////////////////////////////////

    $('.clear_loan_entry').click(function () {
        event.preventDefault();
        clearLoanEntryForm();
    });

}); //////////////////////////////////////////////////// DOCUMENT END /////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////// Function start /////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////// CUSTOMER PROFILE START ////////////////////////////////////////////////////////////////////////////

//On Load function 
$(function () {
    getApprovalTable();
});

function getApprovalTable() {
    serverSideTable('#approval_table', '', 'api/approval_files/approval_list.php');
}

function swapTableAndCreation() {
    if ($('.loan_table_content').is(':visible')) {
        $('.loan_table_content').hide();
        $('#loan_entry_content').show();
        $('#back_btn').show();

    } else {
        $('.loan_table_content').show();
        $('#loan_entry_content').hide();
        $('#back_btn').hide();
        getApprovalTable();
        clearLoanEntryForm();
        $('#customer_profile').trigger('click')
    }
}

function moveToNext(cus_sts_id, cus_sts) {
    $.post('api/common_files/move_to_next.php', { cus_sts_id, cus_sts }, function (response) {
        if (response == '0') {
            let alertName;
            if (cus_sts == '4') {
                alertName = 'Approved Successfully';
            }
            else if (cus_sts == '5') {
                alertName = 'Cancelled Successfully';
            }
            else if (cus_sts == '6') {
                alertName = 'Revoked Successfully';
            }
            swalSuccess('Success', alertName);
            getApprovalTable();
        } else {
            swalError('Alert', 'Failed To Move');
        }
    }, 'json');
}

function submitForm(action, cus_sts_id, cus_sts, remark) {
    $.post('api/common_files/update_status.php', { cus_sts_id, remark, cus_sts }, function (response) {
        if (response == '0') {
            $('#add_info_modal').modal('hide');
            moveToNext(cus_sts_id, cus_sts);
        } else {
            swalError('Alert', 'Failed to ' + action);
        }
    }, 'json');
}

function closeRemarkModal() {
    $('#add_info_modal').modal('hide');
}

function checkCustomerLimit(loan_entry_id, cus_sts_id, cus_sts) {
    $.post('api/common_files/check_customer_limit.php', { loan_entry_id }, function (response) {
        if (response == '1') {
            swalError('Warning', 'Kindly Enter The Customer Limit');
        } else if (response == '2') {
            swalError('Warning', 'Customer limit is less than the loan amount. Please update either the customer limit or the loan amount.');
        } else if (response == '3') {
            moveToNext(cus_sts_id, cus_sts);
        } else {
            swalError('Alert', 'Failed To Approved');
        }
    }, 'json');
}

///////////////////////////////////////////////////////////// Customer Profile edit Start //////////////////////////////////////////////////////////////////////////////////

async function editCustmerProfile(id) {
    try {
        const response = await $.post('api/loan_entry_files/customer_profile_data.php', { id }, null, 'json');

        const data = response.data[0]; // <- Correct way to access the data

        // Populate customer profile fields
        $('#customer_profile_id').val(id);
        $('#aadhar_number').val(data.aadhar_number);
        $('#cus_id').val(data.cus_id);
        $('#first_name').val(data.first_name);
        $('#last_name').val(data.last_name);
        $('#dob').val(data.dob);
        $('#age').val(data.age);
        $('#area_edit').val(data.area);
        $('#line').val(data.line);
        $('#mobile1').val(data.mobile1);
        $('#mobile2').val(data.mobile2);
        $('#whatsapp').val(data.whatsapp);
        $('#cus_limit').val(moneyFormatIndia(data.cus_limit));
        $('#about_cus').val(data.about_cus);

        if (data.whatsapp === data.mobile1) {
            $('#mobile1_radio').prop('checked', true);
            $('#selected_mobile_radio').val('mobile1');
        } else if (data.whatsapp === data.mobile2) {
            $('#mobile2_radio').prop('checked', true);
            $('#selected_mobile_radio').val('mobile2');
        }

        await getGuarantorName();
        await getAreaName();
        getPropertyInfoTable();
        getBankInfoTable();
        getKycInfoTable();

        $('#area').trigger('change');

        const picPath = "uploads/customer_creation/cus_pic/";
        $('#per_pic').val(data.pic);
        $('#imgshow').attr('src', picPath + data.pic);

        const guPath = "uploads/loan_entry/gu_pic/";
        if (data.gu_pic) {
            $('#gur_pic').val(data.gu_pic);
            $('#gur_imgshow').attr('src', guPath + data.gu_pic);
        } else {
            $('#gur_imgshow').attr('src', 'img/avatar.png');
        }

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
                        <td>
                            <span class="icon-trash-2 guaMapDeleteBtn" style="cursor:pointer;"></span>
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

///////////////////////////////////////////////////////////// Customer Profile edit End //////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////// existing Customer creation data start ///////////////////////////////////////////////////////////////////////////

async function existingCustmerProfile(aadhar_number) {
    try {
        const response = await $.post(
            "api/customer_creation_files/customer_profile_existing.php",
            { aadhar_number },
            null,
            "json"
        );

        $("#customer_profile_id").val("");

        if (response == "New") {
            $("#area_edit").val("");
            $("#cus_id").val("");
            $("#first_name").val("");
            $("#last_name").val("");
            $("#dob").val("");
            $("#age").val("");
            $("#area").val("");
            $("#line").val("");
            $("#mobile1").val("");
            $("#mobile2").val("");
            $("#whatsapp").val("");
            $("#address").val("");
            $("#native_address").val("");
            $('#cus_limit').val("");
            $('#about_cus').val("");
            $("#occupation").val("");
            $("#occ_detail").val("");
            $("#relationship").val("");
            $('#guarantor_info tbody').empty();

            $("#per_pic").val("");
            $("#imgshow").attr("src", "img/avatar.png");
            $('#gur_pic').val('');
            var img = $('#gur_imgshow');
            img.attr('src', 'img/avatar.png');

        } else {
            $("#area_edit").val(response[0].area);
            $("#cus_id").val(response[0].cus_id);
            $("#first_name").val(response[0].first_name);
            $("#last_name").val(response[0].last_name);
            $("#dob").val(response[0].dob);
            $("#age").val(response[0].age);
            $("#line").val(response[0].line);
            $("#mobile1").val(response[0].mobile1);
            $("#mobile2").val(response[0].mobile2);
            $("#whatsapp").val(response[0].whatsapp);
            $("#address").val(response[0].address);
            $("#native_address").val(response[0].native_address);
            $('#cus_limit').val(response[0].cus_limit);
            $('#about_cus').val(response[0].about_cus);
            $("#occupation").val(response[0].occupation);
            $("#occ_detail").val(response[0].occ_detail);

            if (response[0].whatsapp === response[0].mobile1) {
                $("#mobile1_radio").prop("checked", true);
                $("#selected_mobile_radio").val("mobile1");
            } else if (response[0].whatsapp === response[0].mobile2) {
                $("#mobile2_radio").prop("checked", true);
                $("#selected_mobile_radio").val("mobile2");
            }

            $("#area").trigger("change");

            let path = "uploads/customer_creation/cus_pic/";
            $("#per_pic").val(response[0].pic);
            $("#imgshow").attr("src", path + response[0].pic);
        }

        await getAreaName();
        await getKycInfoTable()
        getBankInfoTable();
        getPropertyInfoTable();
        await getGuarantorName();

    } catch (error) {
        console.error("Error in existingCustmerProfile:", error);
    }
}

/////////////////////////////////////////////////////////// Existing Customer creation data Emd ////////////////////////////////////////////////////////////////////////

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
                $("#area").empty().append(appendAreaOption);
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

/////////////////////////////////////////////////////////////////// Guarantor info function Start ///////////////////////////////////////////////////////////////////

function getGuarantorName() {
    let cus_id = $('#cus_id').val();

    return new Promise((resolve, reject) => {
        $.post('api/customer_creation_files/get_family_name.php', { cus_id }, function (response) {
            let appendGuarantorOption = "<option value=''>Select Guarantor Name</option>";
            let editGId = $('#guarantor_name_edit').val();

            $.each(response, function (index, val) {
                let selected = (val.id == editGId) ? 'selected' : '';
                appendGuarantorOption += `<option value='${val.id}' ${selected}>${val.fam_name}</option>`;
            });

            $('#guarantor_name').empty().append(appendGuarantorOption);
            resolve(); // Everything is done
        }, 'json').fail(reject); // Handle any error
    });
}

function getFamilyRelationship(family_info_id) {
    $.ajax({
        url: 'api/customer_creation_files/getFamilyRelationship.php',
        type: 'POST',
        data: { family_info_id: family_info_id },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#relationship').val(response.relationship);
        },
        error: function (xhr, status, error) {
            console.error('AJAX error: ' + status, error);
            // Optionally handle errors here, such as displaying an error message to the user
        }
    });
}

function removeGuaMap(TableRowVal) {
    let { row, family_info_id, loan_entry_id } = TableRowVal;

    // Remove row from table immediately
    row.remove();
    swalSuccess('Success', 'Guarantor mapping removed successfully.');

    // Reindex remaining rows
    $('#guarantor_info tbody tr').each(function (index) {
        $(this).find('td:first').text(index + 1);
    });

    // Make delete request to backend
    $.post('api/loan_entry_files/delete_gua_mapping.php', {
        family_info_id: family_info_id,
        loan_entry_id: loan_entry_id
    }, function (response) {
        // Do nothing if not found (no need to show error)
        if (response == 1) {
            console.log('Mapping deleted from DB.');
        }
    }, 'json').fail(function () {
        swalError('Error', 'Server error occurred while deleting.');
    });
}

/////////////////////////////////////////////////////////////////// Guarantor info function End ///////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////// KYC info function Start ///////////////////////////////////////////////////////////////////////////

function getKycTable() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/kyc_creation_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'proof_of',
            'name',
            'fam_relationship',
            'proof',
            'proof_detail',
            'upload',
            'action'
        ];
        appendDataToTable('#kyc_creation_table', response, columnMapping);
        setdtable('#kyc_creation_table');
        $('#kyc_form input').val('');
        $('#kyc_form input').css('border', '1px solid #cecece');
        $('#kyc_form select').css('border', '1px solid #cecece');
        $('#Kyc_form .kyc_name_div').hide();
        $('#Kyc_form .fam_mem_div').hide();
        $('#kyc_form select').each(function () {
            $(this).val($(this).find('option:first').val());
        });
    }, 'json')
}

function getKycInfoTable() {
    return new Promise((resolve, reject) => {
        let cus_id = $('#cus_id').val();
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
            setdtable('#kyc_info');
            resolve();
        },
            "json"
        ).fail(reject);
    });
}

function getKycDelete(id) {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/delete_kyc_creation.php', { id, cus_id }, function (response) {
        if (response == '0') {
            swalError('Warning', 'Have to maintain atleast one Kyc Info');
        } else if (response == '1') {
            swalSuccess('Success', 'Kyc Info Deleted Successfully!');
            getKycTable();
        } else {
            swalError('Error', 'Failed to Delete Kyc');
        }
    }, 'json');
}

function getFamilyMember() {
    return new Promise((resolve, reject) => {
        let cus_id = $('#cus_id').val();
        $.post(
            'api/customer_creation_files/get_family_name.php',
            { cus_id },
            function (response) {
                let appendHolderOption = "<option value=''>Select Family Member</option>";
                $.each(response, function (index, val) {
                    appendHolderOption += "<option value='" + val.id + "'>" + val.fam_name + "</option>";
                });
                $('#fam_mem').empty().append(appendHolderOption);
                resolve(); // Notify that the task is complete
            },
            'json'
        ).fail(reject); // Handle error scenario
    });
}

function getKycRelationshipName(familyMemberId) {
    $.ajax({
        url: 'api/customer_creation_files/getKycRelationshipName.php',
        type: 'POST',
        data: { family_member_id: familyMemberId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#kyc_relationship').val(response.kyc_relationship);
        },
    });
}

function fetchProofList() {
    $.ajax({
        url: 'api/customer_creation_files/get_proof_list.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            $('#proof').empty().append('<option value="">Select proof</option>');
            $.each(response, function (index, proof) {
                $('#proof').append('<option value="' + proof.id + '">' + proof.addProof_name + '</option>');
            });
            $('#proof_form input').val('');
            $('#proof_form input').css('border', '1px solid #cecece');

        }
    });
}

function getProofTable() {
    $.post('api/customer_creation_files/proof_creation_list.php', function (response) {
        var columnMapping = [
            'sno',
            'addProof_name',
            'action'
        ];
        appendDataToTable('#proof_creation_table', response, columnMapping);
        setdtable('#proof_creation_table');
    }, 'json')
}

function getProofDelete(id) {
    $.post('api/customer_creation_files/delete_proof_creation.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'proof Info Deleted Successfully!');
            getProofTable();
        } else if (response == '0') {
            swalError('Access Denied', 'proof Info Already Used');
        } else {
            swalError('Warning', 'Error occur While Delete Proof Info.');
        }
    }, 'json')
}

/////////////////////////////////////////////////////////////////// KYC info function End ///////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////// Bank info function Start ///////////////////////////////////////////////////////////////////////////

function getBankTable() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/bank_creation_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'bank_name',
            'branch_name',
            'acc_holder_name',
            'acc_number',
            'ifsc_code',
            'action'
        ];
        appendDataToTable('#bank_creation_table', response, columnMapping);
        setdtable('#bank_creation_table');
        $('#bank_form input').val('');
        $('#bank_form input').css('border', '1px solid #cecece');

    }, 'json')
}

function getBankInfoTable() {
    let cus_id = $('#cus_id').val();
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
        setdtable('#bank_info');
    }, 'json')
}

function getBankDelete(id) {
    $.post('api/customer_creation_files/delete_bank_creation.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Bank Info Deleted Successfully!');
            getBankTable();
        } else {
            swalError('Error', 'Failed to Delete Bank: ' + response);
        }
    }, 'json');
}

/////////////////////////////////////////////////////////////////// Bank info function End ///////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////// Property info function Start //////////////////////////////////////////////////////////////////////

function getPropertyTable() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/property_creation_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'property',
            'property_detail',
            'property_holder',
            'fam_relationship',
            'action'
        ];
        appendDataToTable('#property_creation_table', response, columnMapping);
        setdtable('#property_creation_table');
        $('#property_form input').val('');
        $('#property_form input').css('border', '1px solid #cecece');
        $('#property_form select').css('border', '1px solid #cecece');
        $('textarea').css('border', '1px solid #cecece');
        $('#property_holder').val('');
        $('#property_detail').val('');
    }, 'json')
}

function getPropertyInfoTable() {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/property_creation_list.php', { cus_id }, function (response) {
        var columnMapping = [
            'sno',
            'property',
            'property_detail',
            'property_holder',
            'fam_relationship',
        ];
        appendDataToTable('#prop_info', response, columnMapping);
        setdtable('#prop_info');
    }, 'json')
}

function getPropertyHolder() {
    let cus_id = $('#cus_id').val();
    let first_name = $("#first_name").val().trim();
    let last_name = $("#last_name").val().trim();
    let cus_name = first_name + (last_name ? " " + last_name : "");
    $.post('api/customer_creation_files/get_family_name.php', { cus_id }, function (response) {
        let appendHolderOption = '';
        appendHolderOption += "<option value=''>Select Property Holder</option>";
        appendHolderOption += "<option value='" + 0 + "'>" + cus_name + "</option>";
        $.each(response, function (index, val) {
            appendHolderOption += "<option value='" + val.id + "'>" + val.fam_name + "</option>";
        });
        $('#property_holder').empty().append(appendHolderOption);
    }, 'json');
}

function getPropertyDelete(id) {
    $.post('api/customer_creation_files/delete_property_creation.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('Success', 'Property Info Deleted Successfully!');
            getPropertyTable();
        } else {
            swalError('Error', 'Failed to Delete Property: ' + response);
        }
    }, 'json');
}

function getRelationshipName(propertyHolderId) {
    $.ajax({
        url: 'api/customer_creation_files/getRelationshipName.php',
        type: 'POST',
        data: { property_holder_id: propertyHolderId },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#prop_relationship').val(response.prop_relationship);
        },
    });
}

/////////////////////////////////////////////////////////////////// Property info function End //////////////////////////////////////////////////////////////////////

// Function to check if all values in an object are not empty
function isFormDataValid(formData) {
    let isValid = true;
    const excludedFields = [
        'referred_calc', 'agent_id_calc', 'agent_name_calc', 'doc_need_calc'
    ];

    // Validate all fields except the excluded ones
    for (let key in formData) {
        if (!excludedFields.includes(key)) {
            if (!validateField(formData[key], key)) {
                isValid = false;
            }
        }
    }

    return isValid;
}

/////////////////////////////////////////////////////////////////// CUSTOMER PROFILE END ////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////// Loan Calculation Start ////////////////////////////////////////////////////////////////////////////

function callLoanCaculationFunctions() {
    getLoanCategoryName();
    getAutoGenLoanId();
    let cus_profile_id = $('#customer_profile_id').val();
    getDocNeedTable(cus_profile_id);
    let loanCalcId = $('#customer_profile_id').val();
    loanCalculationEdit(loanCalcId);
}

function getAutoGenLoanId() {
    $.post('api/loan_entry_files/loan_calculation_files/get_autoGen_loan_id.php', function (response) {
        $('#loan_id_calc').val(response);
    }, 'json');
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

function getDocNeedTable(cusProfileId) {
    $.post('api/loan_entry_files/loan_calculation_files/document_need_list.php', { cusProfileId }, function (response) {
        let loanCategoryColumn = [
            "sno",
            "document_name",
            "action"
        ]
        appendDataToTable('#doc_need_table', response, loanCategoryColumn);
        setdtable('#doc_need_table');
    }, 'json');
}

/////////////////////////////////////////////////////////////////// Loan Calculation Edit Start ////////////////////////////////////////////////////////////////

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
            $('#loan_date_calc').val(data.loan_date_calc);
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

/////////////////////////////////////////////////////////////////// Loan Calculation Edit End ////////////////////////////////////////////////////////////////////////////

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
/////////////////////////////////////////////////////////////////// Loan Calculation End ////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////// Function ClearLoanEntry Start ///////////////////////////////////////////////////////////////////

function clearLoanEntryForm() {
    $("#customer_profile_id").val("");
    $('#loan_entry_customer_profile').trigger('reset');

    // Reset border styles for inputs and selects in both forms
    $('#loan_entry_customer_profile input, #loan_entry_customer_profile select, #loan_entry_loan_calculation input, #loan_entry_loan_calculation select')
        .css('border', '1px solid #cecece');

    // Reset image previews
    $("#per_pic").val("");
    $("#imgshow").attr("src", "img/avatar.png");
    $('#gur_pic').val('');
    $('#gur_imgshow').attr("src", "img/avatar.png");

    // Reset guarantor fields
    $('#guarantor_info tbody').empty();
    $('#guarantor_name').empty().append('<option value="">Select Guarantor Name</option>');

    // Show "No data available" for each table using DataTables
    resetTableWithNoData('#prop_info', ['sno', 'property', 'property_detail', 'property_holder', 'fam_relationship']);
    resetTableWithNoData('#kyc_info', ['sno', 'proof_of', 'name', 'relationship', 'proof', 'proof_number', 'upload']);
    resetTableWithNoData('#bank_info', ['sno', 'bank_name', 'branch_name', 'account_holder', 'account_number', 'ifsc_code']);

    // Reset all selects in both forms to their first option
    $('#loan_entry_customer_profile select, #loan_entry_loan_calculation select').each(function () {
        $(this).val($(this).find('option:first').val());
    });

    $('#loan_entry_loan_calculation').find('input').each(function () {
        var id = $(this).attr('id');
        if (id !== 'loan_date_calc' && id != 'due_period' && id != 'submit_doc_need' && id != 'refresh_cal') {
            $(this).val('');
        }
    });

}

// Utility function to reset a DataTable and show "No data available"
function resetTableWithNoData(selector, columnMapping) {
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().clear().destroy();
    }

    const columns = columnMapping.map(key => ({ data: key, title: key.replace(/_/g, ' ').toLowerCase() }));

    $(selector).DataTable({
        data: [],
        columns: columns,
        language: {
            emptyTable: "No data available"
        },
        searching: false,
        paging: false,
        info: false
    });
}

/////////////////////////////////////////////////////////////////// Function ClearLoanEntry End /////////////////////////////////////////////////////////////////



