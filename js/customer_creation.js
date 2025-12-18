$(document).ready(function () {
    //Move Loan Entry
    $(document).on("click", "#add_customer, #back_btn", async function () {
        $("#submit_cus_creation").prop("disabled", false);
        $("#cus_id").val("");

        await swapTableAndCreation(); // Ensure swap is complete first
        await getAutoGenCusId("");
        await getAreaName(); // Optional to await if it's async
        await getFamilyInfoTable();
        await getKycInfoTable();
        getBankInfoTable();
        getPropertyInfoTable();

        $("#imgshow").attr("src", "img/avatar.png");
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

    /////////////////////////////////////////////////////////// submit customer creation start //////////////////////////////////////////////////////////////////////////

    $("#submit_cus_creation").click(function (event) {
        event.preventDefault();

        // Validation
        let kycInfoRowCount = $('#kyc_info').DataTable().rows().count();
        let familyInfoRowCount = $('#fam_info_table').DataTable().rows().count();

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
                'Do you want to submit this customer creation?',
                function () {

                    if (kycInfoRowCount === 0) {
                        swalError('Warning', 'Please Fill out KYC Info!');
                        return false;
                    }

                    if (familyInfoRowCount === 0) {
                        swalError('Warning', 'Please Fill out Family Info!');
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

                            if (response === 2) {
                                swalSuccess('Success', 'Customer Creation Added Successfully!');
                            } else if (response === 1) {
                                swalSuccess('Success', 'Customer Creation Updated Successfully!');
                            } else {
                                swalError('Error', 'Error Occurred!');
                            }

                            $("#customer_profile_id").val(response.last_id);
                            $("#customer_creation").trigger("reset");
                            getCustomerEntryTable();
                            swapTableAndCreation();
                        },
                    });
                }
            );
        }
    });

    $(document).on("click", ".customerActionBtn", function () {
        let id = $(this).attr("value");
        $("#customer_profile_id").val(id);
        swapTableAndCreation();
        editCustomerCreation(id);
    });

    $(document).on("click", ".customerDeleteBtn", function () {
        var id = $(this).attr("value");
        swalConfirm("Delete", "Do you want to Delete the Customer Details?", getCustomerDelete, id);
        return;
    });

    let isAadharValid = true; // global flag

    $("#aadhar_number").on("blur", function () {
        let aadhar_number = $(this).val().trim().replace(/\s/g, "");

        if (aadhar_number !== "") {
            $.post(
                "api/customer_creation_files/existing_aadhar_number.php",
                { aadhar_number },
                function (response) {
                    if (response.exists) {
                        swalError("Warning", "This Aadhar number is already created.");
                        $("#aadhar_number").val("");
                        isAadharValid = false;
                    } else {
                        isAadharValid = true;
                    }
                },
                "json"
            );
        }
    });

    //////////////////////////////////////////////////////////////////// submit customer creation end /////////////////////////////////////////////////////////////////////


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

    //<----------------------  reset back button function --------------------------------------------------->
    $('button[type="reset"],#back_btn').click(function (event) {
        // event.preventDefault();
        $("input").each(function () {
            var id = $(this).attr("id");
            if (id !== "cus_id" && id !== "mobile1_radio" && id !== "mobile2_radio") {
                $(this).val("");
            }
        });
        // Clear all textarea fields within the specific form
        $("#customer_creation").find("textarea").val("");

        //clear all upload inputs within the form.
        $("#customer_creation").find('input[type="file"]').val("");

        // Reset all select fields within the specific form
        $("#customer_creation")
            .find("select")
            .each(function () {
                $(this).val($(this).find("option:first").val());
            });
        $("#customer_creation").find('input[type="radio"]').prop("checked", false);
        //Reset all  images within the form
        $("#imgshow").attr("src", "img/avatar.png");
        $("#customer_creation input").css("border", "1px solid #cecece");
        $("#customer_creation select").css("border", "1px solid #cecece");
        $("#customer_creation textarea").css("border", "1px solid #cecece");
    });

}); ///////////////////////////////////////////////////// document end /////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////// Function Start /////////////////////////////////////////////////////////////////////////////////////

$(function () {
    getCustomerEntryTable();
});

function getCustomerEntryTable() {
    serverSideTable("#customer_create", "", "api/customer_creation_files/customer_creation_list.php" , "Customer Creation List");
}

async function swapTableAndCreation() {
    if ($(".customer_table_content").is(":visible")) {
        $(".customer_table_content").hide();
        $("#add_customer").hide();
        $("#customer_creation_content").show();
        $("#back_btn").show();
    } else {
        $(".customer_table_content").show();
        $("#add_customer").show();
        $("#customer_creation_content").hide();
        $("#back_btn").hide();
    }
}

function getAutoGenCusId(id) {
    return new Promise((resolve, reject) => {
        $.post(
            "api/customer_creation_files/get_autoGen_cus_id.php", { id },
            function (response) {
                $("#cus_id").val(response);
                resolve();
            },
            "json"
        ).fail(reject);
    });
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
            $("#mobile1_radio").prop("checked", true).trigger("change");
        } else if (response[0].whatsapp === response[0].mobile2) {
            $("#mobile2_radio").prop("checked", true).trigger("change");
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
        if (response[0].pic == "") {
            $("#imgshow").attr("src", "img/avatar.png");
        }

    } catch (error) {
        console.error("Error editing customer:", error);
    }
}

///////////////////////////////////////////////////////////// Customer creation edit end ///////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////// Customer creation delete start ///////////////////////////////////////////////////////////////////////////

function getCustomerDelete(id) {
    $.post(
        "api/customer_creation_files/delete_customer_creation.php",
        { id },
        function (response) {
            if (response === 0) {
                swalError("Access Denied", "Used in Loan Entry Screen");
            } else if (response === 1) {
                swalSuccess("Success", "Customer Creation Deleted Successfully");
                getCustomerEntryTable();
            } else {
                swalError("Error", "Failed to delete customer: " + response);
            }
        },
        "json"
    );
}

///////////////////////////////////////////////////////////// Customer creation delete end ///////////////////////////////////////////////////////////////////////////

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
        setdtable('#bank_info', "Bank Info List");
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
        setdtable('#prop_info', "Property Info List");
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