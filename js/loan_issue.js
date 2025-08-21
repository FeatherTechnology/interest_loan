$(document).ready(function () {
    $(document).on('click', '#back_btn', function () {
        swapTableAndCreation();
    });

    $(document).on('click', '.edit-loan-issue', function () {
        let id = $(this).attr('value'); //Customer Profile id From List page.
        $('#customer_profile_id').val(id);
        let cusID = $(this).attr('data-id'); //Cus id From List Page.
        $('#cus_id').val(cusID);
        $('.cheque-div').hide();
        $('.doc_div').hide();
        $('.mortgage-div').hide();
        $('.endorsement-div').hide();
        $('.gold-div').hide();
        $('#document_type').val('')

        swapTableAndCreation();
        getDocNeedTable(id);
        getChequeInfoTable();
        getDocInfoTable();
        getMortInfoTable();
        getEndorsementInfoTable();
        getGoldInfoTable();
    });

    $(document).on('click', '.loan-issue-cancel', function () {
        let cus_sts_id = $(this).attr('value');
        let cus_sts = 8;
        $('#add_info_modal').modal('show');
        $('.modal_revoke').text('Cancel');
        $('#cus_sts_id').val(cus_sts_id);
        $('#customer_status').val(cus_sts);
        $('#remark').val('');
    });

    $(document).on('click', '.loan-issue-revoke', function () {
        let cus_sts_id = $(this).attr('value');
        let cus_sts = 9;
        $('#add_info_modal').modal('show');
        $('.modal_revoke').text('Revoke');
        $('#cus_sts_id').val(cus_sts_id);
        $('#customer_status').val(cus_sts);
        $('#remark').val('');
    });

    $(document).on('click', '#submit_remark', function () {
        event.preventDefault();
        let action = $('.modal_revoke').text().toLowerCase(); // Get action (cancel or revoke)
        let cus_sts_id = $('#cus_sts_id').val();
        let cus_sts = $('#customer_status').val();
        let remark = $('#remark').val();

        if (remark === '') {
            alert('Please enter a remark.');
            return;
        }

        submitForm(action, cus_sts_id, cus_sts, remark);
    });

    $('#document_type').change(function () {
        var documentType = $(this).val();
        // Hide all         
        $('.cheque-div').hide();
        $('.doc_div').hide();
        $('.mortgage-div').hide();
        $('.endorsement-div').hide();
        $('.gold-div').hide();

        if (documentType == '1') {
            $('.cheque-div').show();
        } else if (documentType == '2') {
            $('.doc_div').show();
        } else if (documentType == '3') {
            $('.mortgage-div').show();
        }
        else if (documentType == '4') {
            $('.endorsement-div').show();
        }
        else if (documentType == '5') {
            $('.gold-div').show();
        }

        getChequeInfoTable();
        getDocInfoTable();
        getMortInfoTable();
        getEndorsementInfoTable();
        getGoldInfoTable();
    });

    // <------------------------------------------------------- Radio Button Loan Issue click Function --------------------------------------------------->

        $('input[name=loan_issue_type]').click(function () {
            let loanIssueType = $(this).val();
            if (loanIssueType == 'loandoc') {
                $('#documentation_form').show(); $('#loan_issue_form').hide();
            } else if (loanIssueType == 'loanissue') {
                $('#documentation_form').hide(); $('#loan_issue_form').show();
                callLoanCaculationFunctions();
            }
        })

    /////////////////////////////////////////////////////////////////// Cheque info START ////////////////////////////////////////////////////////////////////////////

    $('#cq_holder_type').change(async function () {
        let holderType = $(this).val();
        emptyholderFields();

        if (holderType == '1' || holderType == '2') {
            $('.cq_fam_member').hide();
            let cus_profile_id = $('#customer_profile_id').val();
            getNameRelationship(cus_profile_id, holderType);
        } else if (holderType == '3') {
            await getFamilyMember('Select Family Member', '#cq_fam_mem');
            $('.cq_fam_member').show();
        } else {
            $('.cq_fam_member').hide();
        }
    });


    $('#cq_fam_mem').change(function () {
        let famMemId = $(this).val();
        if (famMemId != '') {
            getNameRelationship(famMemId, '3');
        }
    });

    $('#cheque_count').keyup(function () {
        $('#cheque_no').empty();
        let cnt = $(this).val();
        if (cnt != '') {
            for (let i = 1; i <= cnt; i++) {
                $('#cheque_no').append("<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12'><div class='form-group'><input type='number' class='form-control chequeno' name='chequeno[]' id='chequeno'/> </div></div>")
            }
        }
    });

    $('#submit_cheque_info').click(function (event) {
        event.preventDefault();
        let cus_id = $('#cus_id').val();
        let cq_holder_type = $('#cq_holder_type').val();
        let cq_holder_name = $("#cq_holder_name").val();
        let cq_holder_id = $("#cq_holder_name").attr('data-id');
        let cq_relationship = $('#cq_relationship').val();
        let cq_bank_name = $('#cq_bank_name').val();
        let cheque_count = $('#cheque_count').val();
        let cq_upload = $('#cq_upload')[0].files;
        let cq_upload_edit = $('#cq_upload_edit').val();
        let customer_profile_id = $('#customer_profile_id').val();
        let cheque_info_id = $('#cheque_info_id').val();

        let chequeNoArr = []; //for storing cheque no
        let i = 0;
        $('.chequeno').each(function () {//cheque numbers input box
            chequeNoArr[i] = $(this).val();//store each numbers in an array
            i++;
        });
        var data = ['cq_holder_type', 'cq_holder_name', 'cq_relationship', 'cq_bank_name', 'cheque_count']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this cheque info?',
                function () {
                    let chequeInfo = new FormData();
                    chequeInfo.append('cq_holder_type', cq_holder_type)
                    chequeInfo.append('cq_holder_name', cq_holder_name)
                    chequeInfo.append('cq_holder_id', cq_holder_id)
                    chequeInfo.append('cq_relationship', cq_relationship)
                    chequeInfo.append('cheque_count', cheque_count)
                    chequeInfo.append('cq_bank_name', cq_bank_name)
                    chequeInfo.append('cq_upload_edit', cq_upload_edit)
                    chequeInfo.append('cheque_no', chequeNoArr.join(','))
                    chequeInfo.append('cus_id', cus_id)
                    chequeInfo.append('customer_profile_id', customer_profile_id)
                    chequeInfo.append('id', cheque_info_id)

                    for (var a = 0; a < cq_upload.length; a++) {
                        chequeInfo.append('cq_upload[]', cq_upload[a])
                    }

                    $.ajax({
                        url: 'api/loan_issue_files/submit_cheque_info.php',
                        type: 'post',
                        data: chequeInfo,
                        contentType: false,
                        processData: false,
                        cache: false,
                        dataType: 'json',
                        success: function (response) {
                            if (response == '1') {
                                swalSuccess('Success', 'Cheque Info Updated Successfully')
                            } else if (response == '2') {
                                swalSuccess('Success', 'Cheque Info Added Successfully')
                            } else {
                                swalError('Alert', 'Failed')
                            }
                            getChequeCreationTable();
                            $('#clear_cheque_form').trigger('click');
                            $('#cheque_info_id').val('');

                            $('.cq_fam_member').hide();
                        }
                    });
                }
            );
        }
    });

    $(document).on('click', '.chequeActionBtn', async function () {
        let id = $(this).attr('value');

        try {
            const response = await new Promise((resolve, reject) => {
                $.post('api/loan_issue_files/cheque_info_data.php', { id }, function (data) {
                    resolve(data);
                }, 'json').fail(reject);
            });

            let res = response.result[0];
            $('#cq_holder_type').val(res.holder_type);
            $('#cq_holder_name').val(res.holder_name);
            $('#cq_holder_name').attr('data-id', res.holder_id);
            $('#cq_relationship').val(res.relationship);
            $('#cq_bank_name').val(res.bank_name);
            $('#cheque_count').val(res.cheque_cnt);
            $('#cheque_info_id').val(res.id);

            if (res.holder_type == '3') {
                $('.cq_fam_member').show();

                await getFamilyMember('Select Family Member', '#cq_fam_mem'); // Wait for family list to load

                $('#cq_fam_mem').val(res.holder_id); // Set value after load
            } else {
                $('#cq_fam_mem').val('');
                $('.cq_fam_member').hide();
            }

            if (response.upd.length > 0) {
                let uploadFiles = response.upd.map(fileObj => fileObj.uploads).filter(Boolean);
                $('#cq_upload_edit').val(uploadFiles.join(','));
            }

            $('#cheque_no').empty();
            for (let key in response.no) {
                let cheque = response.no[key];
                $('#cheque_no').append(
                    `<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12'>
                    <div class='form-group'>
                        <input type='number' class='form-control chequeno' name='chequeno[]' value='${cheque['cheque_no']}'/>
                    </div>
                </div>`
                );
            }

        } catch (err) {
            console.error('Cheque info load failed:', err);
        }
    });


    $(document).on('click', '.chequeDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this Cheque?', deleteChequeInfo, id);
    });

    $('#clear_cheque_form').click(function () {
        $('#cheque_no').empty();
        $('#cheque_info_id').val('');
        $('#cq_upload_edit').val('');
        $('#cheque_info_form input').css('border', '1px solid #cecece');
        $('#cheque_info_form select').css('border', '1px solid #cecece');
        $('.cq_fam_member').hide();
    });

    /////////////////////////////////////////////////////////////////// Cheque info END ////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////Document info START ////////////////////////////////////////////////////////////////////////////

    $('#doc_holder_name').change(function () {
        let id = $(this).val();
        if (id != '' && id != 0) {
            getRelationship(id, '#doc_relationship')
        } else if (id == 0) {
            $('#doc_relationship').val('Customer');
        }
        else {
            $('#doc_relationship').val('');
        }

    });

    $('#submit_doc_info').click(function (event) {
        event.preventDefault();
        let doc_name = $('#doc_name').val();
        let doc_type = $('#doc_type').val();
        let doc_holder_name = $('#doc_holder_name').val();
        let doc_relationship = $('#doc_relationship').val();
        let doc_upload = $('#doc_upload')[0].files[0];
        let doc_upload_edit = $('#doc_upload_edit').val();
        let doc_info_id = $('#doc_info_id').val();
        let cus_id = $('#cus_id').val();
        let customer_profile_id = $('#customer_profile_id').val();
        var data = ['doc_name', 'doc_type', 'doc_holder_name', 'doc_relationship']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this document info?',
                function () {
                    let docInfo = new FormData();
                    docInfo.append('doc_name', doc_name);
                    docInfo.append('doc_type', doc_type);
                    docInfo.append('doc_holder_name', doc_holder_name);
                    docInfo.append('doc_relationship', doc_relationship);
                    docInfo.append('doc_upload', doc_upload);
                    docInfo.append('doc_upload_edit', doc_upload_edit);
                    docInfo.append('cus_id', cus_id);
                    docInfo.append('customer_profile_id', customer_profile_id);
                    docInfo.append('id', doc_info_id);

                    $.ajax({
                        url: 'api/loan_issue_files/submit_document_info.php',
                        type: 'post',
                        data: docInfo,
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function (response) {
                            if (response == '1') {
                                swalSuccess('Success', 'Document Info Updated Successfully')
                            } else if (response == '2') {
                                swalSuccess('Success', 'Document Info Added Successfully')
                            } else {
                                swalError('Alert', 'Failed')
                            }
                            getDocCreationTable();
                            $('#clear_doc_form').trigger('click');
                            $('#doc_info_id').val('');
                        }
                    });
                }
            );
        }
    });

    $(document).on('click', '.docActionBtn', function () {
        let id = $(this).attr('value');
        $.post('api/loan_issue_files/doc_info_data.php', { id }, function (response) {
            $('#doc_name').val(response[0].doc_name);
            $('#doc_type').val(response[0].doc_type);
            $('#doc_holder_name').val(response[0].holder_name);
            $('#doc_relationship').val(response[0].relationship);
            $('#doc_upload_edit').val(response[0].upload);
            $('#doc_info_id').val(response[0].id);
        }, 'json');
    });

    $(document).on('click', '.docDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this document?', deleteDocInfo, id);
    });

    $('#clear_doc_form').click(function () {
        $('#doc_info_id').val('');
        $('#doc_upload_edit').val('');
        $('#doc_info_form input').css('border', '1px solid #cecece');
        $('#doc_info_form select').css('border', '1px solid #cecece');
    })

    ///////////////////////////////////////////////////////////////////Document info END ////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////Mortgage info START ////////////////////////////////////////////////////////////////////////////
    $('#property_holder_name').change(function () {
        let id = $(this).val();
        if (id != '' && id != 0) {
            getRelationship(id, '#mort_relationship')
        } else if (id == 0) {
            $('#mort_relationship').val('Customer');
        } else {
            $('#mort_relationship').val('');
        }

    });

    $('#submit_mortgage_info').click(function (event) {
        event.preventDefault();
        let property_holder_name = $('#property_holder_name').val();
        let mort_relationship = $('#mort_relationship').val();
        let mort_property_details = $('#mort_property_details').val();
        let mortgage_name = $('#mortgage_name').val();
        let mort_designation = $('#mort_designation').val();
        let mortgage_no = $('#mortgage_no').val();
        let reg_office = $('#reg_office').val();
        let mortgage_value = $('#mortgage_value').val();
        let mortgage_info_id = $('#mortgage_info_id').val();
        let cus_id = $('#cus_id').val();
        let customer_profile_id = $('#customer_profile_id').val();
        let mort_upload = $('#mort_upload')[0].files[0];
        let mort_upload_edit = $('#mort_upload_edit').val();
        var data = ['property_holder_name', 'mort_relationship', 'mort_property_details', 'mortgage_name', 'mort_designation', 'mortgage_no', 'reg_office', 'mortgage_value']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this mortgage info?',
                function () {
                    let mortgageInfo = new FormData();
                    mortgageInfo.append('property_holder_name', property_holder_name);
                    mortgageInfo.append('mort_relationship', mort_relationship);
                    mortgageInfo.append('mort_property_details', mort_property_details);
                    mortgageInfo.append('mortgage_name', mortgage_name);
                    mortgageInfo.append('mort_designation', mort_designation);
                    mortgageInfo.append('mortgage_no', mortgage_no);
                    mortgageInfo.append('reg_office', reg_office);
                    mortgageInfo.append('mortgage_value', mortgage_value);
                    mortgageInfo.append('mort_upload', mort_upload);
                    mortgageInfo.append('mort_upload_edit', mort_upload_edit);
                    mortgageInfo.append('cus_id', cus_id);
                    mortgageInfo.append('customer_profile_id', customer_profile_id);
                    mortgageInfo.append('id', mortgage_info_id);

                    $.ajax({
                        url: 'api/loan_issue_files/submit_mortgage_info.php',
                        type: 'post',
                        data: mortgageInfo,
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function (response) {
                            if (response == '1') {
                                swalSuccess('Success', 'Mortgage Info Updated Successfully')
                            } else if (response == '2') {
                                swalSuccess('Success', 'Mortgage Info Added Successfully')
                            } else {
                                swalError('Alert', 'Failed')
                            }
                            getMortCreationTable()
                            $('#clear_mortgage_form').trigger('click');
                            $('#mortgage_info_id').val('');
                        }
                    });
                }
            );
        }
    });

    $(document).on('click', '.mortActionBtn', function () {
        let id = $(this).attr('value');
        $.post('api/loan_issue_files/mortgage_info_data.php', { id }, function (response) {
            $('#property_holder_name').val(response[0].property_holder_name);
            $('#mort_relationship').val(response[0].relationship);
            $('#mort_property_details').val(response[0].property_details);
            $('#mortgage_name').val(response[0].mortgage_name);
            $('#mort_designation').val(response[0].designation);
            $('#mortgage_no').val(response[0].mortgage_number);
            $('#reg_office').val(response[0].reg_office);
            $('#mortgage_value').val(response[0].mortgage_value);
            $('#mort_upload_edit').val(response[0].upload);
            $('#mortgage_info_id').val(response[0].id);
        }, 'json');
    });

    $(document).on('click', '.mortDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this Mortgage?', deleteMortgageInfo, id);
    });

    $('#clear_mortgage_form').click(function () {
        $('#mortgage_info_id').val('');
        $('#mort_upload_edit').val('');
        $('#mortgage_form input').css('border', '1px solid #cecece');
        $('#mortgage_form select').css('border', '1px solid #cecece');
        $('#mortgage_form textarea').css('border', '1px solid #cecece');

    })
    ///////////////////////////////////////////////////////////////////Mortgage info END ////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////// Endorsement info START ///////////////////////////////////////////////////////////////////////

    $('#owner_name').change(function () {
        let id = $(this).val();
        if (id != '' && id != 0) {
            getRelationship(id, '#owner_relationship')
        } else if (id == 0) {
            $('#owner_relationship').val('Customer');
        } else {
            $('#owner_relationship').val('');
        }
    });

    $('#submit_endorsement').click(function (event) {
        event.preventDefault();
        let owner_name = $('#owner_name').val();
        let owner_relationship = $('#owner_relationship').val();
        let vehicle_details = $('#vehicle_details').val();
        let endorsement_name = $('#endorsement_name').val();
        let key_original = $('#key_original').val();
        let rc_original = $('#rc_original').val();
        let endorsement_upload = $('#endorsement_upload')[0].files[0];
        let endorsement_upload_edit = $('#endorsement_upload_edit').val();
        let endorsement_info_id = $('#endorsement_info_id').val();
        let cus_id = $('#cus_id').val();
        let customer_profile_id = $('#customer_profile_id').val();

        var data = ['owner_name', 'owner_relationship', 'vehicle_details', 'endorsement_name', 'key_original', 'rc_original']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this endorsement info?',
                function () {
                    let endorsementInfo = new FormData();
                    endorsementInfo.append('owner_name', owner_name);
                    endorsementInfo.append('owner_relationship', owner_relationship);
                    endorsementInfo.append('vehicle_details', vehicle_details);
                    endorsementInfo.append('endorsement_name', endorsement_name);
                    endorsementInfo.append('key_original', key_original);
                    endorsementInfo.append('rc_original', rc_original);
                    endorsementInfo.append('endorsement_upload', endorsement_upload);
                    endorsementInfo.append('endorsement_upload_edit', endorsement_upload_edit);
                    endorsementInfo.append('cus_id', cus_id);
                    endorsementInfo.append('customer_profile_id', customer_profile_id);
                    endorsementInfo.append('id', endorsement_info_id);

                    $.ajax({
                        url: 'api/loan_issue_files/submit_endorsement_info.php',
                        type: 'post',
                        data: endorsementInfo,
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function (response) {
                            if (response == '1') {
                                swalSuccess('Success', 'Endorsement Info Updated Successfully')
                            } else if (response == '2') {
                                swalSuccess('Success', 'Endorsement Info Added Successfully')
                            } else {
                                swalError('Alert', 'Failed')
                            }
                            getEndorsementCreationTable()
                            $('#clear_endorsement_form').trigger('click');
                            $('#endorsement_info_id').val('');
                        }
                    });
                }
            );
        }
    });

    $(document).on('click', '.endorseActionBtn', function () {
        let id = $(this).attr('value');
        $.post('api/loan_issue_files/endorsement_info_data.php', { id }, function (response) {
            $('#owner_name').val(response[0].owner_name);
            $('#owner_relationship').val(response[0].relationship);
            $('#vehicle_details').val(response[0].vehicle_details);
            $('#endorsement_name').val(response[0].endorsement_name);
            $('#key_original').val(response[0].key_original);
            $('#rc_original').val(response[0].rc_original);
            $('#endorsement_upload_edit').val(response[0].upload);
            $('#endorsement_info_id').val(response[0].id);
        }, 'json');
    });

    $(document).on('click', '.endorseDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this Endorsement?', deleteEndorsementInfo, id);
    });

    $('#clear_endorsement_form').click(function () {
        $('#endorsement_info_id').val('');
        $('#endorsement_upload_edit').val('');
        $('#endorsement_form input').css('border', '1px solid #cecece');
        $('#endorsement_form select').css('border', '1px solid #cecece');
        $('#endorsement_form textarea').css('border', '1px solid #cecece');
    });

    /////////////////////////////////////////////////////////////////// Endorsement info END ////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////// Gold info START //////////////////////////////////////////////////////////////////////////////

    $('#submit_gold_info').click(function (event) {
        event.preventDefault();
        let goldInfo = {
            'cus_id': $('#cus_id').val(),
            'customer_profile_id': $('#customer_profile_id').val(),
            'gold_type': $('#gold_type').val(),
            'purity': $('#gold_purity').val(),
            'weight': $('#gold_weight').val(),
            'value': $('#gold_value').val(),
            'id': $('#gold_info_id').val(),
        };
        var data = ['gold_type', 'gold_purity', 'gold_weight', 'gold_value']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this gold info?',
                function () {
                    $.post('api/loan_issue_files/submit_gold_info.php', goldInfo, function (response) {
                        if (response == '1') {
                            swalSuccess('Success', 'Gold Info Updated Successfully')
                        } else if (response == '2') {
                            swalSuccess('Success', 'Gold Info Added Successfully')
                        } else {
                            swalError('Alert', 'Failed')
                        }
                        getGoldCreationTable()
                        $('#clear_gold_form').trigger('click');
                        $('#gold_info_id').val('');
                    });
                }
            );
        }
    });

    $(document).on('click', '.goldActionBtn', function () {
        let id = $(this).attr('value');
        $.post('api/loan_issue_files/gold_info_data.php', { id }, function (response) {
            $('#gold_type').val(response[0].gold_type);
            $('#gold_purity').val(response[0].purity);
            $('#gold_weight').val(response[0].weight);
            $('#gold_value').val(response[0].value);
            $('#gold_info_id').val(response[0].id);
        }, 'json');
    });

    $(document).on('click', '.goldDeleteBtn', function () {
        let id = $(this).attr('value');
        swalConfirm('Delete', 'Are you sure you want to delete this Gold Info?', deleteGoldInfo, id);
    });

    $('#clear_gold_form').click(function () {
        $('#gold_info_id').val('');
        $('#gold_form input').css('border', '1px solid #cecece');
        $('#gold_form select').css('border', '1px solid #cecece');
    });

    /////////////////////////////////////////////////////////////////// Gold info END ////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////// Document Print START ////////////////////////////////////////////////////////////////////////

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
    })

    /////////////////////////////////////////////////////////////////// Document Print END ////////////////////////////////////////////////////////////////////////


    // <------------------------------------------------------- Loan Issue start ---------------------------------------------------------------------->

    $('#payment_type').change(function () {
        $('#payment_mode, #transaction_id, #chequeno, #cash, #chequeValue, #transaction_value, #bank_name, #chequeRemark, #transaction_remark').css('border', '1px solid #cecece');

        $('#cash').removeAttr('readonly');
        $('#chequeValue').removeAttr('readonly');
        $('#transaction_value').removeAttr('readonly');

        $('.cash_issue').hide();
        $('.checque').hide();
        $('.transaction').hide();
        $('#bank_container').hide(); // Hide bank section
        $('.balance_remark_container').hide();
        $('#balance_amount').val('');
        $('#payment_mode').val('');

    });

    // Payment Mode
    $('#payment_mode').change(function () {
        $('#payment_mode, #transaction_id, #chequeno, #cash, #chequeValue, #transaction_value, #bank_name, #chequeRemark, #transaction_remark').css('border', '1px solid #cecece');

        $('#transaction_id').val('');
        $('#chequeno').val('');
        var type = $(this).val();
        let paymentType = $('#payment_type').val();

        if (paymentType == '1') {  // Split Payment (Editable)

            $('#cash').attr('readonly', false);
            $('#chequeValue').attr('readonly', false);
            $('#transaction_value').attr('readonly', false);

            if (type == '1') {
                $('.balance_remark_container').show();
                $('#cash').val('');
                $('#chequeValue').val('');
                $('#transaction_value').val('');
                $('.transaction').hide();
                $('.checque').hide();
                $('.cash_issue').show();
                $('#bank_container').hide();
                $('#balance_amount').val('');
                $('#chequeRemark').val('');
                $('#transaction_remark').val('');

            } else if (type == '2') {
                $('#cash').val('');
                $('#chequeValue').val('');
                getBankName();
                $('#transaction_value').val('');
                $('.transaction').show();
                $('.checque').hide();
                $('.cash_issue').hide();
                $('#bank_container').show();
                $('.balance_remark_container').show();
                $('#balance_amount').val('');
                $('#chequeRemark').val('');
                $('#transaction_remark').val('');

            } else if (type == '3') {
                $('#transaction_value').val('');
                $('#cash').val('');
                $('#chequeValue').val('');
                $('#chequeRemark').val('');
                getBankName();
                $('.transaction').hide();
                $('.checque').show();
                $('.cash_issue').hide();
                $('#bank_container').show();
                $('.balance_remark_container').show();
                $('#balance_amount').val('');
                $('#transaction_remark').val('');
            }
            else {
                $('.transaction').hide();
                $('.checque').hide();
                $('.cash_issue').hide();
                $('#bank_container').hide();//hide bank id
                $('.balance_remark_container').hide();
            }
        } else if (paymentType == '2') {  // Single Payment (Read-only)

            $('#cash').attr('readonly', true);
            $('#chequeValue').attr('readonly', true);
            $('#transaction_value').attr('readonly', true);

            var netcash = $('#balance_net_cash').val();

            if (type == '1') {
                $('#cash').val(netcash);
                $('#chequeValue').val('');
                $('#transaction_value').val('');
                $('.transaction').hide();
                $('.checque').hide();
                $('.cash_issue').show();
                $('#bank_container').hide();
                $('#chequeRemark').val('');
                $('#transaction_remark').val('');

            } else if (type == '2') {
                $('#cash').val('');
                $('#chequeValue').val('');
                getBankName();
                $('#transaction_value').val(netcash);
                $('.transaction').show();
                $('.checque').hide();
                $('.cash_issue').hide();
                $('#bank_container').show();
                $('#chequeRemark').val('');
                $('#transaction_remark').val('');

            } else if (type == '3') {
                $('#transaction_value').val('');
                $('#cash').val('');
                $('#chequeValue').val(netcash);
                getBankName();
                $('.transaction').hide();
                $('.checque').show();
                $('.cash_issue').hide();
                $('#bank_container').show();
                $('#chequeRemark').val('');
                $('#transaction_remark').val('');
            }
            else {
                $('.transaction').hide();
                $('.checque').hide();
                $('.cash_issue').hide();
                $('#bank_container').hide();//hide bank id
                $('.balance_remark_container').hide();
            }
        }
    });

    $('#cash, #chequeValue, #transaction_value').on('input', function () {
        // Remove commas first, then parse to float
        let settle_balance = parseFloat($('#balance_net_cash').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        let payment_type = $('#payment_type').val();
        let cash_amount = parseFloat($('#cash').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        let che_amount = parseFloat($('#chequeValue').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        let trans_amount = parseFloat($('#transaction_value').val().replace(/,/g, '')) || 0; // Convert to float, default to 0 if empty
        if (payment_type == '1') { // Split Payment
            var totalAmount = cash_amount + che_amount + trans_amount;
            calculateBalance();
            // Compare totalAmount with settle_balance
            if (totalAmount > settle_balance) {
                swalError('Warning', 'The entered amount exceeds the Net Cash Balance.');
                $('#cash').val('');
                $('#chequeValue').val('');
                $('#transaction_value').val('');
                $('#balance_amount').val(0);
            }
        }
    });

    {
        // Get today's date
        var today = new Date().toISOString().split('T')[0];
        //Set loan date
        $('#issue_date').val(today);
    }

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

    $('#issue_person').change(function () {
        let id = $('#issue_person :selected').attr('data-val');
        if (id != '' && id != 'Customer') {
            getRelationship(id, '#issue_relationship');
        } else if (id == 'Customer') {
            $('#issue_relationship').val('Customer');
        } else {
            $('#issue_relationship').val('');
        }
    });

    $('#submit_loan_issue').click(function (event) {
        event.preventDefault();
        let loanIssue = {
            'cus_id': $('#cus_id').val(),
            'loan_entry_id': $('#loan_entry_id').val(),
            'loan_amount_calc': $('#loan_amount_calc').val(),
            'loan_date': $('#loan_date').val(),
            'due_startdate_calc': $('#due_startdate_calc').val(),
            'maturity_date_calc': $('#maturity_date_calc').val(),
            'bal_net_cash': $('#balance_net_cash').val(),
            'bal_amount': $('#balance_amount').val(),
            'payment_type': $('#payment_type').val(),
            'cash': $('#cash').val(),
            'bank_name': $('#bank_name').val(),
            'chequeValue': $('#chequeValue').val(),
            'chequeRemark': $('#chequeRemark').val(),
            'transaction_remark': $('#transaction_remark').val(),
            'transaction_value': $('#transaction_value').val(),
            'net_cash': $('#net_cash_calc').val(),
            'payment_mode': $('#payment_mode').val(),
            'transaction_id': $('#transaction_id').val(),
            'chequeno': $('#chequeno').val(),
            'issue_date': $('#issue_date').val(),
            'issue_person': $('#issue_person').val(),
            'issue_relationship': $('#issue_relationship').val(),
        }

        if (isFormDataValid(loanIssue)) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this loan issue?',
                function () {
                    $.post('api/loan_issue_files/submit_loan_issue.php', loanIssue, function (response) {
                        if (response == '1') {
                            swalSuccess('Success', 'Loan Issued Successfully');
                            swapTableAndCreation();
                            getLoanIssueTable();
                        } else {
                            swalError('Warning', 'Loan Issue Failed.');
                        }
                    });
                }
            );
        }

    })

    // <------------------------------------------------------- Loan Issue End ------------------------------------------------------------------------>

});

//On Load function 
$(function () {
    getLoanIssueTable();
});

function getLoanIssueTable() {
    serverSideTable('#loan_issue_table', '', 'api/loan_issue_files/loan_issue_list.php');
}

function swapTableAndCreation() {
    if ($('.loanissue_table_content').is(':visible')) {
        $('.loanissue_table_content').hide();
        $('#loan_issue_content').show();
        $('#back_btn').show();

    } else {
        $('.loanissue_table_content').show();
        $('#loan_issue_content').hide();
        $('#back_btn').hide();
        $('#documentation').trigger('click');
        refreshIssueInfo();
    }
}

function closeRemarkModal() {
    $('#add_info_modal').modal('hide');
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

function moveToNext(cus_sts_id, cus_sts) {
    $.post('api/common_files/move_to_next.php', { cus_sts_id, cus_sts }, function (response) {
        if (response == '0') {
            let alertName;
            if (cus_sts == '13') {
                alertName = 'Cancelled Successfully';
            }
            else if (cus_sts == '14') {
                alertName = 'Revoked Successfully';
            }
            swalSuccess('Success', alertName);
            getLoanIssueTable();
        } else {
            swalError('Alert', 'Failed To Move');
        }
    }, 'json');
}

function getDocNeedTable(cusProfileId) {
    $.post('api/loan_entry_files/loan_calculation_files/document_need_list.php', { cusProfileId }, function (response) {
        let docColumn = [
            "sno",
            "document_name"
        ]
        appendDataToTable('#doc_need_table', response, docColumn);
        setdtable('#doc_need_table');
    }, 'json');
}

// <--------------------------------------------------------------- Cheque Info Function Start ------------------------------------------------------------------>

function getChequeCreationTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/cheque_info_list.php', { cus_profile_id }, function (response) {
        let chequeColumn = [
            "sno",
            "holder_type",
            "holder_name",
            "relationship",
            "bank_name",
            "cheque_cnt",
            "cheque_no",
            "upload",
            "action"
        ]
        appendDataToTable('#cheque_creation_table', response, chequeColumn);
        setdtable('#cheque_creation_table');
    }, 'json');
}

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
            "cheque_no",
            "upload"
        ];

        appendDataToTable('#cheque_info_table', response, chequeColumn);
        setdtable('#cheque_info_table');

    }, 'json');
}

function deleteChequeInfo(id) {
    $.post('api/loan_issue_files/delete_cheque_info.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Cheque Info Deleted Successfully');
            getChequeCreationTable();
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

function emptyholderFields() {
    $('#cq_fam_mem').val('');
    $('#cq_holder_name').val('');
    $('#cq_holder_name').attr('data-id', '');
    $('#cq_relationship').val('');
}

function getFamilyMember(optn, selector) {
    return new Promise((resolve, reject) => {
        let cus_id = $('#cus_id').val();
        let holderType = $('#cq_holder_type').val();

        $.post('api/loan_issue_files/get_family_name.php', { cus_id }, function (response) {
            let appendOption = "<option value=''>" + optn + "</option>"; // Default option 

            $.each(response, function (index, val) {
                if (val.type === 'Customer' && holderType !== '3') {
                    appendOption += "<option value='0'>" + val.name + "</option>";  // Customer
                } else if (val.type === 'Family') {
                    appendOption += "<option value='" + val.id + "'>" + val.name + "</option>";  // Family Member
                }
            });

            $(selector).empty().append(appendOption);
            resolve(); // Signal that it's done
        }, 'json').fail(reject);
    });
}


function getNameRelationship(id, type) {
    $.post('api/loan_issue_files/get_cus_fam_members.php', { id, type }, function (response) {
        if (type == '1') {
            $('#cq_holder_name').val(response[0].cus_name);
            $('#cq_relationship').val('Customer');
        } else {
            $('#cq_holder_name').val(response[0].fam_name);
            $('#cq_holder_name').attr('data-id', response[0].id);
            $('#cq_relationship').val(response[0].fam_relationship);
        }
    }, 'json');
}

function refreshChequeModal() {
    $('#clear_cheque_form').trigger('click');
}

// <--------------------------------------------------------------- Cheque Info Function End --------------------------------------------------------------------->

// <--------------------------------------------------------------- Document Info Function Start ------------------------------------------------------------------>

function getDocCreationTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/doc_info_list.php', { cus_profile_id }, function (response) {
        let docInfoColumn = [
            "sno",
            "doc_name",
            "doc_type",
            "holder_name",
            "relationship",
            "upload",
            "action"
        ]
        appendDataToTable('#doc_creation_table', response, docInfoColumn);
        setdtable('#doc_creation_table')
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
        setdtable('#document_info')

    }, 'json');
}

function deleteDocInfo(id) {
    $.post('api/loan_issue_files/delete_doc_info.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Doc Info Deleted Successfully');
            getDocCreationTable();
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

function refreshDocModal() {
    $('#clear_doc_form').trigger('click');
}

function getRelationship(id, selector) {
    $.post('api/customer_creation_files/family_creation_data.php', { id }, function (response) {
        $(selector).val(response[0].fam_relationship);
    }, 'json');
}

// <--------------------------------------------------------------- Document Info Function End ------------------------------------------------------------------>

// <--------------------------------------------------------------- Mortage Info Function Start ------------------------------------------------------------------>

function getMortCreationTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/mortgage_info_list.php', { cus_profile_id }, function (response) {
        let mortInfoColumn = [
            "sno",
            "holder_name",
            "relationship",
            "property_details",
            "mortgage_name",
            "designation",
            "mortgage_number",
            "reg_office",
            "mortgage_value",
            "upload",
            "action"
        ]
        appendDataToTable('#mortgage_creation_table', response, mortInfoColumn);
        setdtable('#mortgage_creation_table')
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
        setdtable('#mortgage_info')
    }, 'json');
}

function deleteMortgageInfo(id) {
    $.post('api/loan_issue_files/delete_mortgage_info.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Mortgage Info Deleted Successfully');
            getMortCreationTable();
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

function refreshMortModal() {
    $('#clear_mortgage_form').trigger('click');
}

// <--------------------------------------------------------------- Mortage Info Function End ------------------------------------------------------------------>

// <--------------------------------------------------------------- Endorsement Info Function Start ------------------------------------------------------------->

function getEndorsementCreationTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/endorsement_info_list.php', { cus_profile_id }, function (response) {
        let endorsementInfoColumn = [
            "sno",
            "holder_name",
            "relationship",
            "vehicle_details",
            "endorsement_name",
            "key_original",
            "rc_original",
            "upload",
            "action"
        ]
        appendDataToTable('#endorsement_creation_table', response, endorsementInfoColumn);
        setdtable('#endorsement_creation_table')
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
        setdtable('#endorsement_info')

    }, 'json');
}

function deleteEndorsementInfo(id) {
    $.post('api/loan_issue_files/delete_endorsement_info.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Endorsement Info Deleted Successfully');
            getEndorsementCreationTable();
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

function refreshEndorsementModal() {
    $('#clear_endorsement_form').trigger('click');
}

// <--------------------------------------------------------------- Endorsement Info Function End ------------------------------------------------------------------>

// <---------------------------------------------------------------- Gold Info Function Start ---------------------------------------------------------------------->

function getGoldCreationTable() {
    let cus_profile_id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/gold_info_list.php', { cus_profile_id }, function (response) {
        let goldInfoColumn = [
            "sno",
            "gold_type",
            "purity",
            "weight",
            "value",
            "action"
        ]
        appendDataToTable('#gold_creation_table', response, goldInfoColumn);
        setdtable('#gold_creation_table')
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
        setdtable('#gold_info')

    }, 'json');
}

function deleteGoldInfo(id) {
    $.post('api/loan_issue_files/delete_gold_info.php', { id }, function (response) {
        if (response == '1') {
            swalSuccess('success', 'Gold Info Deleted Successfully');
            getGoldCreationTable();
        } else {
            swalError('Alert', 'Delete Failed')
        }
    }, 'json');
}

function refreshGoldModal() {
    $('#clear_gold_form').trigger('click');
}

// <--------------------------------------------------------------- Gold Info Function End ---------------------------------------------------------------------->

/////////////////////////////////////////////////////////////////////// Loan Issue Start /////////////////////////////////////////////////////////////////////////

async function callLoanCaculationFunctions() {
    await checkBalance(); // Wait for balance check to complete
    personalInfo(); // If personalInfo is synchronous, keep it as is
}

function personalInfo() {
    let id = $('#customer_profile_id').val();
    $.post('api/loan_issue_files/loan_issue_data.php', { id }, function (response) {
        $('#aadhar_number').val(response[0].aadhar_number);
        $('#cus_id').val(response[0].cus_id);
        $('#first_name').val(response[0].first_name);
        $('#last_name').val(response[0].last_name);
        $('#area').val(response[0].areaname);
        $('#mobile1').val(response[0].mobile1);
        $('#loan_id_calc').val(response[0].loan_id);
        $('#loan_category_calc').val(response[0].loan_category);
        $('#loan_amount_calc').val(response[0].loan_amount);
        $('#benefit_method').val(response[0].benefit_method);
        $('#due_method').val(response[0].due_method);
        $('#due_period').val(response[0].due_period);
        $('#interest_calculate').val(response[0].interest_calculate);
        $('#due_calculate').val(response[0].due_calculate);
        $('#interest_rate_calc').val(response[0].interest_rate_calc);
        $('#due_period_calc').val(response[0].due_period_calc);
        $('#doc_charge_calc').val(response[0].doc_charge_calc);
        $('#processing_fees_calc').val(response[0].processing_fees_calc);
        $('#loan_amnt_calc').val(response[0].loan_amnt_calc);
        $('#doc_charge_calculate').val(response[0].doc_charge_calculate);
        $('#processing_fees_calculate').val(response[0].processing_fees_calculate);
        $('#net_cash_calc').val(response[0].net_cash_calc);
        $('#interest_amnt_calc').val(response[0].interest_amnt_calc);
        $('#loan_date').val(response[0].loan_date);
        $('#due_startdate_calc').val(response[0].due_startdate_calc);
        $('#maturity_date_calc').val(response[0].maturity_date_calc);
        $('#loan_entry_id').val(response[0].id);
        getIssuePerson(response[0].cus_name);

        let path = "uploads/customer_creation/cus_pic/";
        $('#per_pic').val(response[0].pic);
        var img = $('#imgshow');
        img.attr('src', path + response[0].pic);

        if (response[0].cus_data == 'Existing') {
            $('#loan_count_div').show();
            let cus_id = $('#cus_id').val();
            getLoanCount(cus_id);
        } else {
            $('#loan_count_div').hide();
        }

        // Get today's date
        var today = new Date().toISOString().split('T')[0];

        //Set loan date
        let loan_date = $('#loan_date').val()
        // Check if loan_date is null or empty string
        if (loan_date === null || loan_date === '') {
            $('#loan_date').val(today);
        }

    }, 'json');
}

function getLoanCount(cus_id) {
    $.ajax({
        url: 'api/loan_entry_files/get_loan_count.php',
        type: 'POST',
        data: { cus_id: cus_id },
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#loan_count').val(response.loan_count);
            let formattedDate = response.first_loan_date.split('-').reverse().join('-');
            $('#first_loan_date').val(formattedDate);
        },
    });
}

function getBankName() {
    $.post('api/common_files/bank_name_list.php', function (response) {
        let appendBankOption = "<option value=''>Select Bank Name</option>";
        $.each(response, function (index, val) {
            let selected = '';
            let editGId = $('#bank_name_edit').val(); // Existing guarantor ID (if any)
            if (val.id == editGId) {
                selected = 'selected';
            }
            appendBankOption += "<option value='" + val.id + "' " + selected + ">" + val.bank_name + "</option>";
        });
        $('#bank_name').empty().append(appendBankOption);
    }, 'json');
}

function checkBalance() {
    return new Promise((resolve, reject) => {
        let cus_profile_id = $('#customer_profile_id').val();
        $.ajax({
            url: 'api/loan_issue_files/get_loan_balance.php',
            type: 'POST',
            data: { 'cus_profile_id': cus_profile_id },
            dataType: 'json',
            success: function (response) {
                if (response && response.balance_amount !== undefined) {
                    let balanceAmount = response.balance_amount;

                    if (balanceAmount === 'null' || balanceAmount === null) {
                        let netCash = $('#net_cash_calc').val() || 0; 
                        $('#balance_net_cash').val(netCash);
                        $('#due_startdate_calc').attr('readonly', false);
                    } else {
                        $('#due_startdate_calc').attr('readonly', true);
                        $('#balance_net_cash').val(balanceAmount);
                    }

                    resolve();
                } else {
                    console.error('Balance amount not found in response');
                    reject('Invalid response');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', error);
                reject(error);
            }
        });
    });
}

function calculateBalance() {
    // Get the settlement balance and remove commas, then parse it as a float
    let settlementBalance = parseFloat($('#balance_net_cash').val().replace(/,/g, '')) || 0;
    let cashVal = parseFloat($('#cash').val()) || 0;
    let cheqVal = parseFloat($('#chequeValue').val()) || 0;
    let transVal = parseFloat($('#transaction_value').val()) || 0;
    // Calculate the remaining balance
    let remainingBalance = settlementBalance - (cashVal + cheqVal + transVal);

    // Format the remaining balance using the moneyFormatIndia function
    $('#balance_amount').val((remainingBalance));
}

function getIssuePerson(cus_name) {
    let cus_id = $('#cus_id').val();
    $.post('api/customer_creation_files/get_family_name.php', { cus_id }, function (response) {
        let appendOption = '';
        appendOption += "<option value='' data-val=''>Select Issue Person</option>";
        appendOption += "<option value='" + cus_name + "' data-val='Customer'>" + cus_name + "</option>";
        $.each(response, function (index, val) {
            appendOption += "<option value='" + val.fam_name + "' data-val='" + val.id + "'>" + val.fam_name + "</option>";
        });
        $('#issue_person').empty().append(appendOption);
    }, 'json');
}

// Function to check if all values in an object are not empty
function isFormDataValid(formData) {
    let isValid = true;

    // Reset border styles for all fields
    $('#payment_mode, #transaction_id, #chequeno, #cash, #chequeValue, #transaction_value, #bank_name, #chequeRemark, #transaction_remark').css('border', '1px solid #cecece');

    // Validate required fields
    if (!validateField(formData['issue_person'], 'issue_person')) {
        isValid = false;
    }

    if (!validateField(formData['payment_type'], 'payment_type')) {
        isValid = false;
    }

    if (!validateField(formData['payment_mode'], 'payment_mode')) {
        isValid = false;
    }

    // Check if payment_type is "1" (Split Payment)
    if (formData['payment_type'] === "1") {
        // Validate payment_mode again
        if (!validateField(formData['payment_mode'], 'payment_mode')) {
            isValid = false;
        }

        // Validate specific fields based on payment_mode
        if (formData['payment_mode'] === "1") { // Cash
            if (!validateField(formData['cash'], 'cash')) {
                isValid = false;
            }
        } else if (formData['payment_mode'] === "2") { // Cheque
            if (!validateField(formData['transaction_id'], 'transaction_id')) {
                isValid = false;
            }
            if (!validateField(formData['transaction_value'], 'transaction_value')) {
                isValid = false;
            }
            if (!validateField(formData['bank_name'], 'bank_name')) {
                isValid = false;
            }
        } else if (formData['payment_mode'] === "3") { // Transaction
            if (!validateField(formData['chequeno'], 'chequeno')) {
                isValid = false;
            }
            if (!validateField(formData['chequeValue'], 'chequeValue')) {
                isValid = false;
            }
            if (!validateField(formData['bank_name'], 'bank_name')) {
                isValid = false;
            }
        }
        // Ensure that at least one payment method is filled
        let isCashFilled = formData['cash'] > 0;
        let isTransactionFilled = formData['transaction_value'] > 0;
        let isChequeFilled = formData['chequeValue'] > 0;

        if (!(isCashFilled || isChequeFilled || isTransactionFilled)) {
            isValid = false;
            $('#cash, #transaction_value , #chequeValue').css('border', '1px solid #ff0000');
        } else {
            resetFieldBorders(['cash', 'transaction_value', 'chequeValue']);
        }
    }
    else if (formData['payment_type'] == "2") { // Single Payment
        if (!validateField(formData['payment_mode'], 'payment_mode')) {
            isValid = false;
        }

        if (formData['payment_mode'] == "1") { // Cash
            if (!validateField(formData['cash'], 'cash')) {
                isValid = false;
            }
        } else if (formData['payment_mode'] == "2") { // Cheque
            if (!validateField(formData['transaction_id'], 'transaction_id')) {
                isValid = false;
            }
            if (!validateField(formData['transaction_value'], 'transaction_value')) {
                isValid = false;
            }
            if (!validateField(formData['bank_name'], 'bank_name')) {
                isValid = false;
            }
        } else if (formData['payment_mode'] == "3") { // Transaction
            if (!validateField(formData['chequeno'], 'chequeno')) {
                isValid = false;
            }
            if (!validateField(formData['chequeValue'], 'chequeValue')) {
                isValid = false;
            }
            if (!validateField(formData['bank_name'], 'bank_name')) {
                isValid = false;
            }
        }
    }
    // Check other mandatory fields not related to payment_mode
    for (let key in formData) {
        if (key !== 'payment_mode' && key !== 'bal_amount' && key !== 'payment_type' && key !== 'transaction_id' && key !== 'chequeno' && key !== 'cash' && key !== 'chequeValue' && key !== 'transaction_value' && key !== 'transaction_remark' && key !== 'chequeRemark' && key !== 'bank_name') {
            if (!validateField(formData[key], key)) {
                return false;
            }
        }
    }

    return isValid;
}

function refreshIssueInfo() {
    $('#payment_mode').val('');
    $('#payment_type').val('');
    $('#transaction_id').val('');
    $('#chequeno').val('');
    $('#issue_person').val('');
    $('#issue_relationship').val('');
    $('.transaction').hide();
    $('.checque').hide();
    $('.cash_issue').hide();
    $('.balance_remark_container').hide();
    $('#bank_container').hide();//hide bank id
    resetFieldBorders(['payment_mode', 'payment_type', 'cash', 'transaction_value', 'chequeValue', 'issue_person']);
}

function resetFieldBorders(fields) {
    fields.forEach(field => {
        document.getElementById(field).style.border = '1px solid #cecece';
    });
}

/////////////////////////////////////////////////////////////////////// Loan Issue End  ///////////////////////////////////////////////////////////////////////////