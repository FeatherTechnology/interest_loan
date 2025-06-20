<!----------------------------------------------------------------- Loan Entry List Start --------------------------------------------------------------------------->

<div class="card update_table_content">
    <div class="card-body">
        <div class="col-12">
            <table id="cus_update_table" class="table custom-table">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Customer ID</th>
                        <th>Aadhar Number</th>
                        <th>Customer Name</th>
                        <th>Area</th>
                        <th>Line</th>
                        <th>Branch</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!----------------------------------------------------------------- Loan Entry List End --------------------------------------------------------------------------->

<div id="update_cus_content" style="display:none;">
    <div class="text-right">
        <button type="button" class="btn btn-primary" id="back_btn"><span class="icon-arrow-left"></span>&nbsp; Back </button>
    </div>
    <br>
    <div class="radio-container">
        <div class="selector">
            <div class="selector-item">
                <input type="radio" id="customer_profile" name="update_type" class="selector-item_radio" value="cus_profile" checked>
                <label for="customer_profile" class="selector-item_label">Customer Profile</label>
            </div>
            <div class="selector-item">
                <input type="radio" id="documentation" name="update_type" class="selector-item_radio" value="loan_doc">
                <label for="documentation" class="selector-item_label">Documentation</label>
            </div>
        </div>
    </div>
    <br>

    <form id="update_customer_profile" name="update_customer_profile">
        <input type="hidden" id="customer_profile_id">
        <input type="hidden" id="doc_cus_id">
        <div class="row gutters">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Customer Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cus_id"> Customer ID</label>
                                            <input type="text" class="form-control" id="cus_id" name="cus_id" placeholder="Enter Customer ID" tabindex="1" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="aadhar_number">Aadhar No</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="aadhar_number" id="aadhar_number" tabindex="2" maxlength="14" data-type="adhaar-number" placeholder="Enter Aadhar Number">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control " id="first_name" name="first_name" placeholder="Enter First name" tabindex="5">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label><span class="text-danger">*</span>
                                            <input type="last_name" class="form-control" id="last_name" name="last_name" placeholder="Enter Last name" tabindex="6">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="dob"> DOB</label>
                                            <input type="date" class="form-control" id="dob" name="dob" placeholder="Enter Date Of Birth" tabindex="7">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="age"> Age</label>
                                            <input type="text" class="form-control" id="age" name="age" readonly placeholder="Age" tabindex="8">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="area">Area</label><span class="text-danger">*</span>
                                            <input type="hidden" id="area_edit">
                                            <select type="text" class="form-control" id="area" name="area" tabindex="9">
                                                <option value="">Select Area</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="line"> Line </label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="line" name="line" disabled placeholder="Line" tabindex="30">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mobile1"> Mobile Number 1</label><span class="text-danger">*</span>
                                            <input type="number" class="form-control" id="mobile1" name="mobile1" placeholder="Enter Mobile Number 1" onKeyPress="if(this.value.length==10) return false;" tabindex="10">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mobile2"> Mobile Number 2</label>
                                            <input type="number" class="form-control" id="mobile2" name="mobile2" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number 2" tabindex="11">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Choose Mobile Number for WhatsApp:</label><br>
                                            <label>
                                                <input type="radio" name="mobile_whatsapp" value="mobile1" id="mobile1_radio" tabindex="12">
                                                Mobile Number 1
                                            </label><br>
                                            <label>
                                                <input type="radio" name="mobile_whatsapp" value="mobile2" id="mobile2_radio" tabindex="13">
                                                Mobile Number 2
                                            </label>
                                            <input type="hidden" id="selected_mobile_radio">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="whatsapp"> WhatsApp Number </label>
                                            <input type="number" class="form-control" id="whatsapp" name="whatsapp" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter WhatsApp Number" tabindex="14">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="occupation">Occupation</label>
                                            <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter Occupation" tabindex="15">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="occ_detail">Occupation Detail</label>
                                            <input type="text" class="form-control" id="occ_detail" name="occ_detail" placeholder="Enter Occupation Detail" tabindex="16">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="address"> Address </label>
                                            <textarea class="form-control" name="address" id="address" placeholder="Enter Address" tabindex="17"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="native_address"> Native Address </label>
                                            <textarea class="form-control" name="native_address" id="native_address" placeholder="Enter Native Address" tabindex="18"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="pic"> Photo</label><br>
                                            <img id='imgshow' class="img_show" src='img\avatar.png' />
                                            <input type="file" class="form-control  personal_info_disble" id="pic" name="pic" tabindex="20">
                                            <input type="hidden" class="personal_info_disble" id="per_pic">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-------------------------------------------------------------------- Family Info start -------------------------------------------------------------------->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Family Info
                            <button type="button" class="btn btn-primary" id="add_group" name="add_group" data-toggle="modal" data-target="#add_fam_info_modal" onclick="getFamilyTable()" style="padding: 5px 35px; float: right;" tabindex='21'><span class="icon-add"></span></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="fam_info_table" class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="20">S.NO</th>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Age</th>
                                                <th>Occupation</th>
                                                <th>Aadhar No</th>
                                                <th>Mobile No</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-------------------------------------------------------------------- Family Info end ------------------------------------------------------------------->

                <!-------------------------------------------------------------------- KYC Info start -------------------------------------------------------------------->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">KYC Info <span class="text-danger">*</span>
                            <button type="button" class="btn btn-primary" id="add_kyc" name="add_kyc" data-toggle="modal" data-target="#add_kyc_info_modal" onclick="getKycTable();fetchProofList()" style="padding: 5px 35px; float: right;" tabindex='33'><span class="icon-add"></span></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="kyc_info" class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="20">S.NO</th>
                                                <th>Proof Of</th>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Proof</th>
                                                <th>Proof Number</th>
                                                <th>Upload</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-------------------------------------------------------------------- KYC Info end -------------------------------------------------------------------->

                <!-------------------------------------------------------------------- Bank Info start ----------------------------------------------------------------->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Bank Info
                            <button type="button" class="btn btn-primary" id="add_bank" name="add_bank" data-toggle="modal" data-target="#add_bank_info_modal" onclick="getBankTable()" style="padding: 5px 35px; float: right;" tabindex='32'><span class="icon-add"></span></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="bank_info" class="custom-table">
                                        <thead>
                                            <tr>
                                                <th width="20">S.No.</th>
                                                <th>Bank Name</th>
                                                <th>Branch Name</th>
                                                <th>Account Holder Name</th>
                                                <th>Account Number</th>
                                                <th>IFSC Code</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-------------------------------------------------------------------- Bank Info end ----------------------------------------------------------------->

                <!-------------------------------------------------------------------- Property Info start ------------------------------------------------------------->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Property Info
                            <button type="button" class="btn btn-primary" id="add_property" name="add_property" data-toggle="modal" data-target="#add_prop_info_modal" onclick="getPropertyTable();getPropertyHolder()" style="padding: 5px 35px; float: right;" tabindex='31'><span class="icon-add"></span></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="prop_info" class="custom-table">
                                        <thead>
                                            <tr>
                                                <th width="20">S.NO</th>
                                                <th>Property</th>
                                                <th>Property Detail</th>
                                                <th>Property Holder</th>
                                                <th>Relationship</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-------------------------------------------------------------------- Property Info end ------------------------------------------------------------->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Customer Summary</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cus_limit"> Customer Limit</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" id="cus_limit" name="cus_limit" placeholder="Enter Customer Limit" tabindex="36">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="about_cus"> About Customer </label><span class="text-danger">*</span>
                                    <textarea class="form-control" name="about_cus" id="about_cus" placeholder="Enter About Customer" tabindex="37"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-12 ">
                <div class="text-right">

                    <button type="submit" name="submit_cus_creation" id="submit_cus_creation" class="btn btn-primary" value="Submit" tabindex="22"><span class="icon-check"></span>&nbsp;Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" tabindex="23">Clear</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!------------------------------------------------------------------ Family Info Modal start  ----------------------------------------------------------------------------->

<div class="modal fade" id="add_fam_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Family Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="getFamilyInfoTable();" tabindex="1">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="family_form">
                        <div class="row">
                            <input type="hidden" name="family_id" id='family_id'>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_name">Name</label><span class="text-danger">*</span>
                                    <input class="form-control" name="fam_name" id="fam_name" tabindex="1" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_relationship">Relationship</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="fam_relationship" name="fam_relationship" tabindex="2">
                                        <option value=""> Select Relationship </option>
                                        <option value="Father"> Father </option>
                                        <option value="Mother"> Mother </option>
                                        <option value="Spouse"> Spouse </option>
                                        <option value="Son"> Son </option>
                                        <option value="Daughter"> Daughter </option>
                                        <option value="Brother"> Brother </option>
                                        <option value="Sister"> Sister </option>
                                        <option value="Other"> Other </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 other" style="display: none;">
                                <div class="form-group">
                                    <label for="relation_type">Relation Type</label>
                                    <input class="form-control" name="relation_type" id="relation_type" tabindex="4" placeholder="Enter Relation Type">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_age">Age</label>
                                    <input type="number" class="form-control" name="fam_age" id="fam_age" tabindex="3" placeholder="Enter Age">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_occupation">Occupation</label>
                                    <input class="form-control" name="fam_occupation" id="fam_occupation" tabindex="4" placeholder="Enter Occupation">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_aadhar">Aadhar No</label>
                                    <input type="text" class="form-control" name="fam_aadhar" id="fam_aadhar" tabindex="5" maxlength="14" data-type="adhaar-number" placeholder="Enter Aadhar Number">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="fam_mobile">Mobile No</label>
                                    <input type="number" class="form-control" name="fam_mobile" id="fam_mobile" onKeyPress="if(this.value.length==10) return false;" tabindex="6" placeholder="Enter Mobile Number">
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="" style="visibility:hidden"></label><br>
                                    <button name="submit_family" id="submit_family" class="btn btn-primary" tabindex="7"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_fam_form" class="btn btn-outline-secondary" tabindex="8">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="family_creation_table" class="custom-table">
                            <thead>
                                <tr>
                                    <th width="10">S.No.</th>
                                    <th>Name</th>
                                    <th>Relationship</th>
                                    <th>Age</th>
                                    <th>Occupation</th>
                                    <th>Aadhar No</th>
                                    <th>Mobile No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="1" onclick="getFamilyInfoTable()">Close</button>
            </div>
        </div>
    </div>
</div>

<!----------------------------------------------------------------- Family Modal End ----------------------------------------------------------------------------->

<!------------------------------------------------------------------ KYC Info Modal Start -------------------------------------------------------------------------->

<div class="modal fade" id="add_kyc_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add KYC Info</h5>
                <button type="button" class="close kycmodal_close" data-dismiss="modal" tabindex="1" onclick="getKycInfoTable()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="kyc_form">
                        <div class="row">
                            <input type="hidden" name="kyc_id" id='kyc_id'>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="proof_of">Proof Of</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="proof_of" name="proof_of" tabindex="1">
                                        <option value="">Select Proof Of</option>
                                        <option value="1">Customer</option>
                                        <option value="2">Family Member</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 kyc_name_div" style="display:none">
                                <div class="form-group">
                                    <label for="kyc_name">Name</label><span class="text-danger">*</span>
                                    <input class="form-control" name="kyc_name" id="kyc_name" tabindex="1" disabled placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 fam_mem_div" style="display:none">
                                <div class="form-group">
                                    <label for="fam_mem"> Family Member </label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="fam_mem" name="fam_mem">
                                        <option value=""> Select Family Member </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="kyc_relationship">Relationship</label><span class="text-danger">*</span>
                                    <input class="form-control" name="kyc_relationship" id="kyc_relationship" tabindex="1" disabled placeholder="Enter Relationship">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                <div class="form-group">
                                    <label for="proof">Proof</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="proof" name="proof" tabindex="1">
                                        <option value="">Select proof</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-12" style="margin-top: 18px; padding-left: 0px !important">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary modalBtnCss" id="proof_modal_btn" data-toggle="modal" data-target="#add_proof_info_modal" onclick="getProofTable()" tabindex="1">+</button>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="proof_detail">Proof Number</label><span class="text-danger">*</span>
                                    <input class="form-control" name="proof_detail" id="proof_detail" tabindex="1" placeholder="Enter Proof Number">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="upload"> Upload</label>
                                    <input type="file" class="form-control" id="upload" name="upload" tabindex="1">
                                    <input type="hidden" id="kyc_upload">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_kyc" id="submit_kyc" class="btn btn-primary" tabindex="1" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_kyc_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="9">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div style="overflow-x: auto; width: 100%;">
                            <table id="kyc_creation_table" class="custom-table" style="min-width: 1000px;">
                                <thead>
                                    <tr>
                                        <th width="20">S.No.</th>
                                        <th>Proof Of</th>
                                        <th>Name</th>
                                        <th>Relationship</th>
                                        <th>Proof</th>
                                        <th>Proof Number</th>
                                        <th>Upload</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary kycmodal_close" data-dismiss="modal" onclick="getKycInfoTable()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------------------- KYC Info Modal End -------------------------------------------------------------------------------->

<!------------------------------------------------------------- KYC Proof Modal Start ------------------------------------------------------------------------------>

<div class="modal fade" id="add_proof_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Proof</h5>
                <button type="button" class="close kyc_proof_close" data-dismiss="modal" onclick="fetchProofList()" tabindex="1">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="proof_form">
                        <div class="row">
                            <input type="hidden" name="proof_id" id='proof_id'>
                            <div class="col-sm-3 col-md-3 col-lg-3"></div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="addProof_name">Proof</label><span class="text-danger">*</span>
                                    <input class="form-control" name="addProof_name" id="addProof_name" tabindex="1" placeholder="Enter Proof">
                                    <input type="hidden" id="addline_name_id" value='0'>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <button name="submit_proof" id="submit_proof" class="btn btn-primary" tabindex="1" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_proof_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="1">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="proof_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Proof </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary kyc_proof_close" data-dismiss="modal" onclick="fetchProofList()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>

<!----------------------------------------------------------------- KYC Proof Modal End ----------------------------------------------------------------------------->

<!------------------------------------------------------------------- Bank Info Modal Start ---------------------------------------------------------------------------->

<div class="modal fade" id="add_bank_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Bank Info</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" onclick="getBankInfoTable()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="bank_form">
                        <div class="row">
                            <input type="hidden" name="bank_id" id='bank_id'>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label><span class="text-danger">*</span>
                                    <input class="form-control" name="bank_name" id="bank_name" tabindex="1" placeholder="Enter Bank Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="branch_name">Branch Name</label><span class="text-danger">*</span>
                                    <input class="form-control" name="branch_name" id="branch_name" tabindex="1" placeholder="Enter Branch Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="acc_holder_name">Account Holder Name</label><span class="text-danger">*</span>
                                    <input class="form-control" name="acc_holder_name" id="acc_holder_name" tabindex="1" placeholder="Enter Account Holder Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="acc_number">Account Number</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="acc_number" id="acc_number" tabindex="1" placeholder="Enter Account Number">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="ifsc_code">IFSC Code</label><span class="text-danger">*</span>
                                    <input class="form-control" name="ifsc_code" id="ifsc_code" tabindex="1" placeholder="Enter IFSC Code">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_bank" id="submit_bank" class="btn btn-primary" tabindex="1" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_bank_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="8">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="bank_creation_table" class="custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Bank Name</th>
                                    <th>Branch Name</th>
                                    <th>Account Holder Name</th>
                                    <th>Account Number</th>
                                    <th>IFSC Code</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick=" getBankInfoTable()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>

<!------------------------------------------------------------------- Bank Info Modal End -------------------------------------------------------------------------->

<!--------------------------------------------------------------- Property Info Modal Start --------------------------------------------------------------------------->

<div class="modal fade" id="add_prop_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Property Info</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" onclick="getPropertyInfoTable()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="property_form">
                        <div class="row">
                            <input type="hidden" name="property_id" id='property_id'>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="property">Property</label><span class="text-danger">*</span>
                                    <input class="form-control" name="property" id="property" tabindex="1" placeholder="Enter Property">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="property_detail">Property Detail</label><span class="text-danger">*</span>
                                    <textarea class="form-control" name="property_detail" id="property_detail" tabindex="1"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="property_holder">Property Holder</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="property_holder" name="property_holder" tabindex="1">
                                        <option value="">Select Property Holder</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="prop_relationship">Relationship</label><span class="text-danger">*</span>
                                    <input class="form-control" name="prop_relationship" id="prop_relationship" disabled tabindex="1" placeholder="Enter Relationship">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_property" id="submit_property" class="btn btn-primary" tabindex="6" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_prop_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="1">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table id="property_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Property</th>
                                    <th>Property Detail</th>
                                    <th>Property Holder</th>
                                    <th>Relationship</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="getPropertyInfoTable()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>

<!---------------------------------------------------------------------- Proerty Info Modal End --------------------------------------------------------------------------->

<!---------------------------------------------------------------------- Update Documentation Start --------------------------------------------------------------------------->

<form id="update_documentation" name="update_documentation" style="display: none;">
    <input type="hidden" id="loan_entry_id">
    <div class="row gutters">
        <div class="col-12">
            <!--- -------------------------------------- Loan Info ------------------------------- -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Loan List</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="loan_list_table" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th width="20">S.NO</th>
                                        <th>Loan ID</th>
                                        <th>Loan Category</th>
                                        <th>Loan Date</th>
                                        <th>Loan Amount</th>
                                        <th>Closed Date</th>
                                        <th>Status</th>
                                        <th>Sub Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <!--Loan List End--->

            <div class="card" id="document_type_div" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Document Info</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="document_type">Document Type</label>
                                <select class="form-control" id="document_type" name="document_type" tabindex="2">
                                    <option value="">Select Document Type</option>
                                    <option value="1">Cheque Info</option>
                                    <option value="2">Document Info</option>
                                    <option value="3">Mortgage Info</option>
                                    <option value="4">Endorsement Info</option>
                                    <option value="5">Gold Info</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-------------------------------------------------------------- Cheque Info START ----------------------------------------------------->

            <div class="card cheque-div" id="cheque_info_card" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Cheque Info
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_cheque_info_modal" style="padding: 5px 35px; float: right;" tabindex='9' onclick="getChequeCreationTable();"><span class="icon-add"></span></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <table id="cheque_info_table" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th width="20">S.NO</th>
                                            <th>Holder Type</th>
                                            <th>Holder Name</th>
                                            <th>Relationship</th>
                                            <th>Bank Name</th>
                                            <th>Cheque Count</th>
                                            <th>Upload</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------- Cheque Info END ------------------------------------>

            <!------------------------------------------------------------ Document Info START -------------------------------------------------->

            <div class="card doc_div" id="document_info_card" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Document Info
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_doc_info_modal" onclick="getFamilyMember('Select Holder Name', '#doc_holder_name'); getDocCreationTable();" style="padding: 5px 35px; float: right;" tabindex='29'><span class="icon-add"></span></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <table id="document_info" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th width="20">S.NO</th>
                                            <th>Document Name</th>
                                            <th>Document Type</th>
                                            <th>Holder Name</th>
                                            <th>Relationship</th>
                                            <th>Upload</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------- Document Info END ---------------------------------------------------------------->

            <!------------------------------------------------------------- Mortgage Info START --------------------------------------------------->

            <div class="card mortgage-div" id="mortgage_info_card" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Mortgage Info
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_mortgage_info_modal" onclick="getFamilyMember('Select Property Holder Name', '#property_holder_name');getMortCreationTable()" style="padding: 5px 35px; float: right;" tabindex='30'><span class="icon-add"></span></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <table id="mortgage_info" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th width="20">S.No</th>
                                            <th>Property Holder Name</th>
                                            <th>Relationship</th>
                                            <th>Property Detail</th>
                                            <th>Mortgage Name</th>
                                            <th>Designation</th>
                                            <th>Mortgage Number</th>
                                            <th>Reg Office</th>
                                            <th>Mortgage Value</th>
                                            <th>Upload</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----------------------------------------------- Mortgage Info END ----------------------------------------------------->

            <!------------------------------------------- Endorsement Info START -------------------------------------------------------------->

            <div class="card endorsement-div" id="endorsement_info_card" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Endorsement Info
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_endorsement_info_modal" onclick="getFamilyMember('Select Proof Of', '#owner_name');getEndorsementCreationTable();" style="padding: 5px 35px; float: right;" tabindex='31'><span class="icon-add"></span></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <table id="endorsement_info" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th width="20">S.NO</th>
                                            <th>Owner Name</th>
                                            <th>Relationship</th>
                                            <th>Vehicle Details</th>
                                            <th>Endorsement Name</th>
                                            <th>Key Original</th>
                                            <th>RC Original</th>
                                            <th>Upload</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----------------------------------------------------- Endorsement Info END --------------------------------------------------------->

            <!----------------------------------------------------------------- Gold Info START ------------------------------------------------>

            <div class="card gold-div" id="gold_info_card" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Gold Info
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_gold_info_modal" style="padding: 5px 35px; float: right;" tabindex='31' onclick="getGoldCreationTable()"><span class="icon-add"></span></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <table id="gold_info" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th width="20">S.NO</th>
                                            <th>Gold Type</th>
                                            <th>Purity</th>
                                            <th>Weight</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!----------------------------------------------------------------- Gold Info END ---------------------------------------------------->

        </div>
    </div>
</form>

<!----------------------------------------------------------------- Cheque Info Modal START --------------------------------------------------------------->

<div class="modal fade" id="add_cheque_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Cheque Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" tabindex="1" onclick="getChequeInfoTable(); refreshChequeModal();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="cheque_info_form">
                        <input type="hidden" name="cheque_info_id" id='cheque_info_id'>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cq_holder_type">Holder Type</label><span class="text-danger">*</span>
                                    <select class="form-control" name="cq_holder_type" id="cq_holder_type" tabindex="2">
                                        <option value="">Select Holder Type</option>
                                        <option value="1">Customer</option>
                                        <option value="2">Guarantor</option>
                                        <option value="3">Family Member</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 cq_fam_member" style="display:none">
                                <div class="form-group">
                                    <label for="cq_fam_mem"> Family Member </label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="cq_fam_mem" name="cq_fam_mem" tabindex="3">
                                        <option value=""> Select Family Member </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cq_holder_name">Holder Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="cq_holder_name" name="cq_holder_name" tabindex="4" placeholder="Holder Name" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cq_relationship">Relationship</label>
                                    <input type="text" class="form-control" name="cq_relationship" id="cq_relationship" tabindex="5" placeholder="Relationship" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cq_bank_name">Bank Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="cq_bank_name" name="cq_bank_name" tabindex="6" placeholder="Enter Bank Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cheque_count">Cheque Count</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="cheque_count" id="cheque_count" tabindex="7" placeholder="Enter Cheque Count">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cq_upload">Upload</label><span class="text-danger">*</span>
                                    <input type="file" class="form-control cq_upload" name="cq_upload[]" id="cq_upload" tabindex="8" multiple>
                                    <input type="hidden" id="cq_upload_edit">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="cheque_no"></div>

                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="" style="visibility:hidden"></label><br>
                                    <button name="submit_cheque_info" id="submit_cheque_info" class="btn btn-primary" tabindex="9"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_cheque_form" class="btn btn-outline-secondary" tabindex="10">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="cheque_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.NO</th>
                                    <th>Holder Type</th>
                                    <th>Holder Name</th>
                                    <th>Relationship</th>
                                    <th>Bank Name</th>
                                    <th>Cheque Count</th>
                                    <th>Upload</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="11" onclick="getChequeInfoTable();refreshChequeModal();">Close</button>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------------------------------- Cheque Info Modal END -------------------------------------------------------------------->


<!--------------------------------------------------------------- Document Info Modal START ----------------------------------------------------------------->

<div class="modal fade" id="add_doc_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Document Info</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="getDocInfoTable();refreshDocModal();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="doc_info_form">
                        <input type="hidden" name="doc_info_id" id='doc_info_id'>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_name">Document Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="doc_name" id="doc_name" tabindex="1" placeholder="Enter Document Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_type">Document Type</label><span class="text-danger">*</span>
                                    <select class="form-control" name="doc_type" id="doc_type" tabindex="2">
                                        <option value="">Select Document Type</option>
                                        <option value="1">Original</option>
                                        <option value="2">Xerox</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_holder_name">Holder Name</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="doc_holder_name" name="doc_holder_name" tabindex="3">
                                        <option value="">Select Holder Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_relationship">Relationship</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="doc_relationship" id="doc_relationship" tabindex="4" placeholder="Relationship" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_upload">Upload</label><span class="text-danger">*</span>
                                    <input type="file" class="form-control" name="doc_upload" id="doc_upload" tabindex="5">
                                    <input type="hidden" name="doc_upload_edit" id="doc_upload_edit">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_doc_info" id="submit_doc_info" class="btn btn-primary" tabindex="6" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_doc_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="7">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="doc_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Document Name</th>
                                    <th>Document Type</th>
                                    <th>Holder Name</th>
                                    <th>Relationship</th>
                                    <th>Upload</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="getDocInfoTable();refreshDocModal()" tabindex="8">Close</button>
            </div>
        </div>
    </div>
</div>

<!--------------------------------------------------------------- Document Info Modal END -------------------------------------------------------------------->

<!--------------------------------------------------------------- Mortgage Info Modal START ------------------------------------------------------------------->

<div class="modal fade" id="add_mortgage_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Mortgage Info</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="getMortInfoTable();refreshMortModal();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="mortgage_form">
                        <input type="hidden" name="mortgage_info_id" id='mortgage_info_id'>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="property_holder_name">Property Holder Name</label><span class="text-danger">*</span>
                                    <select class="form-control" name="property_holder_name" id="property_holder_name" tabindex="1">
                                        <option value="">Select Property Holder Name </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mort_relationship">Relationship</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="mort_relationship" id="mort_relationship" tabindex="2" placeholder="Relationship" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mort_property_details">Property Details</label><span class="text-danger">*</span>
                                    <textarea class="form-control" name="mort_property_details" id="mort_property_details" tabindex="3"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mortgage_name">Mortgage Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="mortgage_name" id="mortgage_name" tabindex="4" placeholder="Enter Mortgage Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mort_designation">Designation</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="mort_designation" id="mort_designation" tabindex="5" placeholder="Enter Designation">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mortgage_no">Mortgage Number</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="mortgage_no" id="mortgage_no" tabindex="6" placeholder="Mortgage Number">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="reg_office">Reg Office</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="reg_office" id="reg_office" tabindex="7" placeholder="Reg Office">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mortgage_value">Mortgage Value</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="mortgage_value" id="mortgage_value" tabindex="8" placeholder="Mortgage value">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mort_upload">Upload</label><span class="text-danger">*</span>
                                    <input type="file" class="form-control" name="mort_upload" id="mort_upload" tabindex="9">
                                    <input type="hidden" name="mort_upload_edit" id="mort_upload_edit">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_mortgage_info" id="submit_mortgage_info" class="btn btn-primary" tabindex="10" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_mortgage_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="11">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="mortgage_creation_table" class="table-responsive custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Property Holder Name</th>
                                    <th>Relationship</th>
                                    <th>Property Details</th>
                                    <th>Mortgage Name</th>
                                    <th>Designation</th>
                                    <th>Mortgage Number</th>
                                    <th>Reg Office</th>
                                    <th>Mortgage Value</th>
                                    <th>Upload</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="12" onclick="getMortInfoTable();refreshMortModal();">Close</button>
            </div>
        </div>
    </div>
</div>

<!--------------------------------------------------------------- Mortgage Info Modal END ----------------------------------------------------------------------->

<!--------------------------------------------------------------- Endorsement Info Modal START ------------------------------------------------------------------>

<div class="modal fade" id="add_endorsement_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Endorsement Info</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="getEndorsementInfoTable();refreshEndorsementModal();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="endorsement_form">
                        <input type="hidden" name="endorsement_info_id" id='endorsement_info_id'>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="owner_name">Owner</label><span class="text-danger">*</span>
                                    <select type="text" class="form-control" id="owner_name" name="owner_name" tabindex="2">
                                        <option value="">Select Proof Of</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="owner_relationship">Relationship</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="owner_relationship" id="owner_relationship" tabindex="3" placeholder="Relationship" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="vehicle_details">Vehicle Details</label><span class="text-danger">*</span>
                                    <textarea class="form-control" id="vehicle_details" name="vehicle_details" tabindex="4"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="endorsement_name">Endorsement Name</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="endorsement_name" id="endorsement_name" tabindex="5" placeholder="Enter Endorsement Name">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="key_original">Key Original</label><span class="text-danger">*</span>
                                    <select class="form-control" name="key_original" id="key_original" tabindex="6">
                                        <option value="">Select Key Original</option>
                                        <option value="YES">YES</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="rc_original">RC Original</label><span class="text-danger">*</span>
                                    <select class="form-control" name="rc_original" id="rc_original" tabindex="7">
                                        <option value="">Select RC Original</option>
                                        <option value="YES">YES</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="endorsement_upload"> Upload</label><span class="text-danger">*</span>
                                    <input type="file" class="form-control" id="endorsement_upload" name="endorsement_upload" tabindex="8">
                                    <input type="hidden" id="endorsement_upload_edit">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_endorsement" id="submit_endorsement" class="btn btn-primary" tabindex="9" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_endorsement_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="10">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 overflow-x-cls">
                        <table id="endorsement_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.No.</th>
                                    <th>Owner Name</th>
                                    <th>Relationship</th>
                                    <th>Vehicle Details</th>
                                    <th>Endorsement Name</th>
                                    <th>Key Original</th>
                                    <th>RC Original</th>
                                    <th>Upload</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="11" onclick="getEndorsementInfoTable();refreshEndorsementModal();">Close</button>
            </div>
        </div>
    </div>
</div>

<!--------------------------------------------------------------- Endorsement Info Modal END --------------------------------------------------------------------->

<!--------------------------------------------------------------- Gold Info Modal END ---------------------------------------------------------------------------->

<div class="modal fade" id="add_gold_info_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Gold</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" onclick="getGoldInfoTable();refreshGoldModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="gold_form">
                        <input type="hidden" name="gold_info_id" id='gold_info_id'>
                        <div class="row">
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="gold_type">Gold Type</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="gold_type" id="gold_type" tabindex="1" placeholder="Enter Gold Type">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="gold_purity">Purity</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="gold_purity" id="gold_purity" tabindex="2" placeholder="Enter Purity">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="gold_weight">Weight</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="gold_weight" id="gold_weight" tabindex="3" placeholder="Enter Weight">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="gold_value">Value</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="gold_value" id="gold_value" tabindex="4" placeholder="Enter Value">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <button name="submit_gold_info" id="submit_gold_info" class="btn btn-primary" tabindex="5" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_gold_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="6">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="gold_creation_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th width="20">S.NO</th>
                                    <th>Gold Type</th>
                                    <th>Purity</th>
                                    <th>Weight</th>
                                    <th>Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="7" onclick="getGoldInfoTable();refreshGoldModal();">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- ------------------------------------------------------------ Gold Info Modal END --------------------------------------------------------------- -->