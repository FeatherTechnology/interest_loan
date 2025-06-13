<div class="text-right">
    <button type="button" class="btn btn-primary " id="add_customer"><span class="fa fa-plus"></span>&nbsp; Add Customer</button>
    <button type="button" class="btn btn-primary" id="back_btn" style="display:none;"><span class="icon-arrow-left"></span>&nbsp; Back </button>
</div>
<br>
<div class="card customer_table_content">
    <div class="card-body">
        <div class="col-12">

            <table id="customer_create" class="table custom-table">
                <thead>
                    <tr>
                        <th>S.NO</th>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Aadhar Number</th>
                        <th>Mobile No</th>
                        <th>Area</th>
                        <th>Line</th>
                        <th>Customer Data</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="customer_creation_content" style="display:none;">
    <form id="customer_creation" name="customer_creation">
        <input type="hidden" id="customer_profile_id">
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