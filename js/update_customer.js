$(document).ready(function () {

    $(document).on('click', '#back_btn', function () {
        swapTableAndCreation();
        getcusUpdateTable();
        $('#document_type_div').hide();
        $('#cheque_info_card').hide();
        $('#document_info_card').hide();
        $('#mortgage_info_card').hide();
        $('#endorsement_info_card').hide();
        $('#gold_info_card').hide();
    });

    // <--------------------------------------------------  Edit Button  ------------------------------------------------------------->

    $(document).on('click', '.edit-cus-update', function () {
        let id = $(this).attr('value');
        $('#customer_profile_id').val(id);
        swapTableAndCreation();
        editCustomerCreation(id)
    });

    // <--------------------------------------------------  Radio Button Customer Profile & Documentation ------------------------------------------------------------->

    $('input[name=update_type]').click(function () {
        let updateType = $(this).val();
        if (updateType == 'cus_profile') {
            $('#update_customer_profile').show(); $('#update_documentation').hide();
        } else if (updateType == 'loan_doc') {
            $('#update_customer_profile').hide(); $('#update_documentation').show();
        }
    })

    $('.selector-item_label').click(function () {
        var radioId = $(this).attr('for');
        $('#' + radioId).prop('checked', true);
    });

    /////////////////////////////////////////////////// family Modal Start //////////////////////////////////////////////////////////////////////////////////

    $("#submit_family").click(function (event) {
        event.preventDefault();
        // Validation
        let cus_id = $("#cus_id").val(); // Remove spaces from cus_id
        let fam_name = $("#fam_name").val();
        let fam_relationship = $("#fam_relationship").val();
        let relation_type = $("#relation_type").val();
        let fam_age = $("#fam_age").val();
        let fam_occupation = $("#fam_occupation").val();
        let fam_aadhar = $("#fam_aadhar").val().replace(/\s/g, "");
        let fam_mobile = $("#fam_mobile").val();
        let family_id = $("#family_id").val();

        var data = ["fam_name", "fam_relationship"];

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($("#" + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this family info?',
                function () {
                    $.post(
                        "api/customer_creation_files/submit_family_info.php",
                        {
                            cus_id,
                            fam_name,
                            fam_relationship,
                            relation_type,
                            fam_age,
                            fam_occupation,
                            fam_aadhar,
                            fam_mobile,
                            family_id,
                        },
                        function (response) {

                            if (response === '2') {
                                swalSuccess('Success', 'Family Added Successfully!');
                            } else if (response === '1') {
                                swalSuccess('Success', 'Family Updated Successfully!');
                            } else {
                                swalError('Error', 'Error Occurred!');
                            }

                            // Refresh the family table
                            getFamilyTable();
                        }
                    );
                }
            );
        }
    });

    $(document).on("click", ".familyActionBtn", function () {
        var id = $(this).attr("value"); // Get value attribute
        $.post(
            "api/customer_creation_files/family_creation_data.php",
            { id: id },
            function (response) {
                $("#family_id").val(id);
                $("#fam_name").val(response[0].fam_name);
                $("#fam_relationship").val(response[0].fam_relationship);
                $("#relation_type").val(response[0].relation_type);
                $("#fam_age").val(response[0].fam_age);
                $("#fam_occupation").val(response[0].fam_occupation);
                $("#fam_aadhar").val(response[0].fam_aadhar);
                $("#fam_mobile").val(response[0].fam_mobile);

                // Check if relation type is 'Other'
                if (response[0].fam_relationship === 'Other') {
                    $('.other').show();
                } else {
                    $('.other').hide();
                    $('.other input').val(''); // Clear the "other" field input
                }
            },
            "json"
        );
    });


    $(document).on("click", ".familyDeleteBtn", function () {
        var id = $(this).attr("value");
        swalConfirm(
            "Delete",
            "Do you want to Delete the Family Details?",
            getFamilyDelete,
            id
        );
        return;
    });

    $("#clear_fam_form").click(function (event) {
        event.preventDefault();
        $("#family_id").val("");
        $("#family_form select").each(function () {
            $(this).val($(this).find("option:first").val());
        });
        $("#family_form textarea").val("");
        $("#family_form input").val("");
    });

    /////////////////////////////////////////////////////////////////////////// Family Modal end ////////////////////////////////////////////////////////////

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

        var data = ['proof_of', 'kyc_relationship', 'proof']
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
                'Do you want to submit this KYC info?',
                function () {
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
            );
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
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this proof info?',
                function () {
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
            );
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
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this bank info?',
                function () {
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
            );
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
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this property info?',
                function () {
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
            );
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

    // <-------------------------------------------------------------- Submit Customer Profile Start ---------------------------------------------------------------->

    $("#submit_cus_creation").click(function (event) {
        event.preventDefault();

        // Validation
        let kycInfoRowCount = $('#kyc_info').DataTable().rows().count();

        let cus_id = $("#cus_id").val();
        let aadhar_number = $("#aadhar_number").val().replace(/\s/g, "");
        let first_name = $("#first_name").val();
        let last_name = $("#last_name").val();
        let dob = $("#dob").val();
        let age = $("#age").val();
        let area = $("#area").val();
        let line = $('#line').attr('data-id');
        let mobile1 = $("#mobile1").val();
        let mobile2 = $("#mobile2").val();
        let whatsapp = $("#whatsapp").val();
        let occ_detail = $("#occ_detail").val();
        let occupation = $("#occupation").val();
        let address = $("#address").val();
        let native_address = $("#native_address").val();
        let cus_limit = $('#cus_limit').val().replace(/,/g, '');
        let about_cus = $('#about_cus').val();
        let pic = $("#pic")[0].files[0];
        let per_pic = $("#per_pic").val();
        let customer_profile_id = $("#customer_profile_id").val();

        let cusDetail = new FormData();
        cusDetail.append("cus_id", cus_id);
        cusDetail.append("aadhar_number", aadhar_number);
        cusDetail.append("first_name", first_name);
        cusDetail.append("last_name", last_name);
        cusDetail.append("dob", dob);
        cusDetail.append("age", age);
        cusDetail.append("area", area);
        cusDetail.append("line", line);
        cusDetail.append("mobile1", mobile1);
        cusDetail.append("mobile2", mobile2);
        cusDetail.append("whatsapp", whatsapp);
        cusDetail.append("occupation", occupation);
        cusDetail.append("occ_detail", occ_detail);
        cusDetail.append("address", address);
        cusDetail.append("native_address", native_address);
        cusDetail.append('cus_limit', cus_limit);
        cusDetail.append('about_cus', about_cus);
        cusDetail.append("pic", pic);
        cusDetail.append("per_pic", per_pic);
        cusDetail.append("customer_profile_id", customer_profile_id);

        var data = [
            "cus_id",
            "first_name",
            "last_name",
            "aadhar_number",
            "area",
            "line",
            "mobile1",
            "cus_limit",
            "about_cus",
        ];

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($("#" + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (isValid) {
            swalConfirm(
                'Are you sure?',
                'Do you want to submit this customer profile?',
                function () {

                    if (kycInfoRowCount === 0) {
                        swalError('Warning', 'Please Fill out KYC Info!');
                        return false;
                    }

                    $("#submit_cus_creation").prop("disabled", true);
                    $.ajax({
                        url: "api/customer_creation_files/submit_customer.php",
                        type: "post",
                        data: cusDetail,
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function (response) {
                            $("#submit_cus_creation").prop("disabled", false);
                            response = JSON.parse(response);

                            if (response === 1) {
                                swalSuccess('Success', 'Customer Creation Updated Successfully!');
                                if ($('.page-content').length) {
                                    $('html, body').animate({
                                        scrollTop: $('.page-content').offset().top
                                    }, 3000);
                                }
                            } else {
                                swalError('Error', 'Error Occurred!');
                            }

                            $("#customer_creation").trigger("reset");

                        },
                    });
                }
            );
        }
    });

    // <-------------------------------------------------------------- Submit Customer Profile End ---------------------------------------------------------------->

    // <---------------------- Date of Birth On Change ------------------------------------------------- >
    $("#dob").on("change", function () {
        var dob = new Date($(this).val());
        var today = new Date();
        var age = today.getFullYear() - dob.getFullYear();
        var m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        $("#age").val(age);
    });

    //<---------------------- Radio button on Change  ------------------------------------------------------->
    $("input[name=mobile_whatsapp]").click(function () {
        let selectedValue = $(this).val();
        let mobileNumber;

        if (selectedValue === "mobile1") {
            mobileNumber = $("#mobile1").val();
        } else if (selectedValue === "mobile2") {
            mobileNumber = $("#mobile2").val();
        }

        $("#whatsapp").val(mobileNumber);
    });

    //<---------------------- mobile , whatsapp , family mobile on chnage ----------------------------------->
    $("#mobile1, #mobile2, #whatsapp, #fam_mobile").change(function () {
        checkMobileNo($(this).val(), $(this).attr("id"));
    });

    //<---------------------- Family info relationship - other on Change  ----------------------------------->
    $('#fam_relationship').on('change', function () {
        if ($(this).val() === 'Other') {
            $('.other').show();
        } else {
            $('.other').hide();
            $('#relation_type').val(''); // Clear input when hidden
        }
    });

    //<---------------------- photo on Change  --------------------------------------------------------------->
    $("#pic").change(function () {
        let pic = $("#pic")[0];
        let img = $("#imgshow");
        img.attr("src", URL.createObjectURL(pic.files[0]));
        checkInputFileSize(this, 200, img);
    });

    //<----------------------  aadhar number keyup function --------------------------------------------------->
    $('input[data-type="adhaar-number"]').keyup(function () {
        var value = $(this).val();
        value = value
            .replace(/\D/g, "")
            .split(/(?:([\d]{4}))/g)
            .filter((s) => s.length > 0)
            .join(" ");
        $(this).val(value);
    });

    $('input[data-type="adhaar-number"]').change(function () {
        let len = $(this).val().length;
        if (len < 14) {
            $(this).val("");
            swalError("Warning", "Kindly Enter Valid Aadhaar Number");
        }
    });

    //<---------------------- area and line on chnage function --------------------------------------------------->
    $('#area').change(function () {
        var areaId = $(this).val();
        if (areaId) {
            getAlineName(areaId);
        }
    });

    //<----------------------------------------------------------- Update - Documnenation on click function --------------------------------------------------------------->

    $(document).on('click', '#documentation', function (event) {
        event.preventDefault();
        let cus_id = $('#cus_id').val();
        OnLoadFunctions(cus_id)
    })

    $(document).on('click', '.doc-update', function () {
        let id = $(this).attr('value'); //Customer Profile id From List page.
        $('#loan_entry_id').val(id);
        $('#document_type_div').show();
        $('#document_type').val('');
        $('#cheque_info_card').hide();
        $('#document_info_card').hide();
        $('#mortgage_info_card').hide();
        $('#endorsement_info_card').hide();
        $('#gold_info_card').hide();
        getChequeInfoTable();
        getDocInfoTable();
        getMortInfoTable();
        getEndorsementInfoTable();
        getGoldInfoTable();
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

    /////////////////////////////////////////////////////////////////// Cheque info START ////////////////////////////////////////////////////////////////////////////

    $('#cq_holder_type').change(async function () {
        let holderType = $(this).val();
        emptyholderFields();

        if (holderType == '1' || holderType == '2') {
            $('.cq_fam_member').hide();
            let cus_profile_id = $('#loan_entry_id').val();
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
        let customer_profile_id = $('#loan_entry_id').val();
        let cheque_info_id = $('#cheque_info_id').val();

        let chequeNoArr = []; //for storing cheque no
        let i = 0;
        $('.chequeno').each(function () {//cheque numbers input box
            chequeNoArr[i] = $(this).val();//store each numbers in an array
            i++;
        });
        var data = ['cq_holder_type', 'cq_holder_name', 'cq_relationship', 'cq_bank_name', 'cheque_count', 'cq_upload']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
            let chequeInfo = new FormData();
            chequeInfo.append('cq_holder_type', cq_holder_type)
            chequeInfo.append('cq_holder_name', cq_holder_name)
            chequeInfo.append('cq_holder_id', cq_holder_id)
            chequeInfo.append('cq_relationship', cq_relationship)
            chequeInfo.append('cheque_count', cheque_count)
            chequeInfo.append('cq_bank_name', cq_bank_name)
            chequeInfo.append('cq_upload_edit', cq_upload_edit)
            chequeInfo.append('cheque_no', chequeNoArr)
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
        let customer_profile_id = $('#loan_entry_id').val();
        var data = ['doc_name', 'doc_type', 'doc_holder_name', 'doc_relationship', 'doc_upload']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
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
        let customer_profile_id = $('#loan_entry_id').val();
        let mort_upload = $('#mort_upload')[0].files[0];
        let mort_upload_edit = $('#mort_upload_edit').val();
        var data = ['property_holder_name', 'mort_relationship', 'mort_property_details', 'mortgage_name', 'mort_designation', 'mortgage_no', 'reg_office', 'mortgage_value', 'mort_upload']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
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
        let customer_profile_id = $('#loan_entry_id').val();

        var data = ['owner_name', 'owner_relationship', 'vehicle_details', 'endorsement_name', 'key_original', 'rc_original', 'endorsement_upload']

        var isValid = true;
        data.forEach(function (entry) {
            var fieldIsValid = validateField($('#' + entry).val(), entry);
            if (!fieldIsValid) {
                isValid = false;
            }
        });
        if (isValid) {
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
            'customer_profile_id': $('#loan_entry_id').val(),
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

    ///////////////////////////////////////////////////////////////////Document Print START ////////////////////////////////////////////////////////////////////////////

    $(document).on('click', '.doc-print', function () {
        let cus_profile_id = $('#loan_entry_id').val();
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

    ///////////////////////////////////////////////////////////////////Document Print END ////////////////////////////////////////////////////////////////////////////

});

// <----------------------------------------------------------------- Update - Customer Profile Function Start ------------------------------------------------------------>

$(function () {
    getcusUpdateTable();
});

function getcusUpdateTable() {
    serverSideTable('#cus_update_table', '', 'api/update_customer_files/update_customer_list.php',"Update Customer List");
};

function swapTableAndCreation() {
    if ($('.update_table_content').is(':visible')) {
        $('.update_table_content').hide();
        $('#add_loan').hide();
        $('#update_cus_content').show();
        $('#back_btn').show();

    } else {
        $('.update_table_content').show();
        $('#add_loan').show();
        $('#update_cus_content').hide();
        $('#back_btn').hide();
        $('#customer_profile').trigger('click')
    }
}

/////////////////////////////////////////////////////////////////// Customer creation edit start ////////////////////////////////////////////////////////////////////////

async function editCustomerCreation(id) {
    try {
        const response = await $.post("api/customer_creation_files/customer_creation_data.php", { id }, null, "json");

        $("#customer_profile_id").val(id);
        $("#area_edit").val(response[0].area);
        $("#cus_id").val(response[0].cus_id);
        $("#first_name").val(response[0].first_name);
        $("#last_name").val(response[0].last_name);
        $("#dob").val(response[0].dob);
        $("#age").val(response[0].age);
        $("#mobile2").val(response[0].mobile2);
        $("#line").val(response[0].line);
        $("#whatsapp").val(response[0].whatsapp);
        $("#aadhar_number").val(response[0].aadhar_number);
        $("#mobile1").val(response[0].mobile1);
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

        await getAreaName();
        $("#area").trigger("change");
        await getFamilyInfoTable();
        await getKycInfoTable();
        getBankInfoTable();
        getPropertyInfoTable();

        let path = "uploads/customer_creation/cus_pic/";
        $("#per_pic").val(response[0].pic);
        $("#imgshow").attr("src", path + response[0].pic);

    } catch (error) {
        console.error("Error editing customer:", error);
    }
}

///////////////////////////////////////////////////////////// Customer creation edit end ///////////////////////////////////////////////////////////////////////////

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

/////////////////////////////////////////////////////////////////// Family info function start ////////////////////////////////////////////////////////////////

function getFamilyInfoTable() {
    return new Promise((resolve, reject) => {
        let cus_id = $("#cus_id").val();
        $.post(
            "api/customer_creation_files/family_creation_list.php",
            { cus_id },
            function (response) {
                var columnMapping = [
                    "sno",
                    "fam_name",
                    "fam_relationship",
                    "fam_age",
                    "fam_occupation",
                    "fam_aadhar",
                    "fam_mobile",
                ];
                appendDataToTable("#fam_info_table", response, columnMapping);
                setdtable("#fam_info_table", "Family Info List");
                resolve();
            },
            "json"
        ).fail(reject);
    });
}

function getFamilyTable() {
    let cus_id = $("#cus_id").val();
    $.post(
        "api/customer_creation_files/family_creation_list.php",
        { cus_id: cus_id },
        function (response) {
            var columnMapping = [
                "sno",
                "fam_name",
                "fam_relationship",
                "fam_age",
                "fam_occupation",
                "fam_aadhar",
                "fam_mobile",
                "action",
            ];
            appendDataToTable("#family_creation_table", response, columnMapping);
            setdtable("#family_creation_table", "Family Creation List");
            $("#family_form input").val("");
            $("#family_form input").css("border", "1px solid #cecece");
            $("#family_form select").css("border", "1px solid #cecece");
            $("#fam_relationship").val("").change();
        },
        "json"
    );
}

function getFamilyDelete(id) {
    $.post(
        "api/customer_creation_files/delete_family_creation.php",
        { id },
        function (response) {
            if (response == "1") {
                swalSuccess("Success", "Family Info Deleted Successfully!");
                getFamilyTable();
            } else if (response == "3") {
                swalError("Warning", "Used in Loan Entry");
            } else {
                swalError("Warning", "Error occur While Delete Family Info.");
            }
        },
        "json"
    );
}

/////////////////////////////////////////////////////////////////// Family info function End ///////////////////////////////////////////////////////////////////////////

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
        setdtable('#kyc_creation_table', "KYC Creation List");
        $('#kyc_form input').val('');
        $('#kyc_form input').css('border', '1px solid #cecece');
        $('#kyc_form select').css('border', '1px solid #cecece');
        $('#Kyc_form.kyc_name_div').hide();
        $('#Kyc_form.fam_mem_div').hide();
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
            setdtable('#kyc_info', "KYC Info List");
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
        setdtable('#proof_creation_table', "Proof Creation List");
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
        setdtable('#bank_creation_table', "Bank Creation List");
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
        setdtable('#bank_info', "Bank Creation List");
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
        setdtable('#property_creation_table', "Property Creation List");
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
        setdtable('#prop_info', "Property Creation List");
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

//<-------------------------------------------------------------- Update - Customer Profile Function End -----------------------------------------------------------------> 

//<--------------------------------------------------------------- Update - Documentation Function start -----------------------------------------------------------------> 

function getLoanListTable(cus_id, bal_amt, sub_status_arr) {
    $.post('api/update_customer_files/update_document_list.php', { cus_id, bal_amt, sub_status_arr }, function (response) {
        var columnMapping = [
            'sno',
            'loan_id',
            'loan_category',
            'loan_date',
            'loan_amount',
            'closed_date',
            'c_sts',
            'sub_status',
            'action'
        ];
        appendDataToTable('#loan_list_table', response, columnMapping);
        setdtable('#loan_list_table', "Loan List");
        //Dropdown in List Screen
        setDropdownScripts();
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
        getLoanListTable(cus_id, bal_amt, sub_status_arr)
        hideOverlay();//loader stop
    });
}//Auto Load function END

// <--------------------------------------------------------------- Cheque Info Function Start ------------------------------------------------------------------>

function getChequeCreationTable() {
    let cus_profile_id = $('#loan_entry_id').val();
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
        setdtable('#cheque_creation_table', "Cheque Creation List");
    }, 'json');
}

function getChequeInfoTable() {
    let cus_profile_id = $('#loan_entry_id').val();

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
        setdtable('#cheque_info_table', "Cheque Info List");

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
    let cus_profile_id = $('#loan_entry_id').val();
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
        setdtable('#doc_creation_table', "Doc Creation List");
    }, 'json');
}

function getDocInfoTable() {
    let cus_profile_id = $('#loan_entry_id').val();
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
        setdtable('#document_info', "Doc Info List");

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
    let cus_profile_id = $('#loan_entry_id').val();
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
        setdtable('#mortgage_creation_table', "Mortgage Creation List");
    }, 'json');
}

function getMortInfoTable() {
    let cus_profile_id = $('#loan_entry_id').val();
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
    let cus_profile_id = $('#loan_entry_id').val();
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
        setdtable('#endorsement_creation_table', "Endorsement Creation List");
    }, 'json');
}

function getEndorsementInfoTable() {
    let cus_profile_id = $('#loan_entry_id').val();
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
    let cus_profile_id = $('#loan_entry_id').val();
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
        setdtable('#gold_creation_table', "Gold Creation List");
    }, 'json');
}

function getGoldInfoTable() {
    let cus_profile_id = $('#loan_entry_id').val();
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
