<div class="row gutters">
    <div class="col-12">
        <!------------------------------------------------------------------ CARD START SEARCH FORM --------------------------------------------------------->
        <div>
            <form id="search_form" name="search_form" method="post" enctype="multipart/form-data">
                <!-- Row start -->
                <div class="row gutters">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Search Customer</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Fields -->
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="aadhar_number">Aadhar No</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="aadhar_number" id="aadhar_number" tabindex="1" maxlength="14" data-type="adhaar-number" placeholder="Enter Aadhar Number">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="cus_id"> Customer ID</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="cus_id" name="cus_id" placeholder="Enter Customer ID" tabindex="2">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="first_name">Customer Name</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First name" tabindex="3">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="area">Area</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="area" name="area" placeholder="Enter Area" tabindex="4">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label for="mobile1"> Mobile Number</label><span class="text-danger">*</span>
                                            <input type="number" class="form-control" id="mobile1" name="mobile1" placeholder="Enter Mobile Number" onKeyPress="if(this.value.length==10) return false;" tabindex="5">
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3 text-right">
                                        <button name="submit_search" id="submit_search" class="btn btn-primary" tabindex="5"><span class="icon-check"></span>&nbsp;Search</button>
                                        <button type="reset" class="btn btn-outline-secondary" tabindex="6">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!----------------------------- CARD END SEARCH FORM------------------------------>

            <div class="card" id="custome_list" style="display:none">
                <div class="card-header">
                    <h5 class="card-title">Customer List</h5>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table id="search_table" class="table custom-table">
                            <thead>
                                <th width="20">S No.</th>
                                <th>Customer ID</th>
                                <th>Aadhar Number</th>
                                <th>Customer Name</th>
                                <th>Area</th>
                                <th>Branch</th>
                                <th>Line</th>
                                <th>Mobile Number</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-3" id="customer_status" style="display:none">
                <div class="card-header">
                    <h5 class="card-title">Customer Status
                        <button type="button" id="back_to_search" style="float:right" class="btn btn-primary">
                            <span class="icon-arrow-left"></span>&nbsp;Back
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table id="status_table" class="table custom-table">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" rowspan="2">S No.</th>
                                    <th class="text-center align-middle" rowspan="2">Date</th>
                                    <th class="text-center align-middle" rowspan="2">Loan ID</th>
                                    <th class="text-center align-middle" rowspan="2">Loan Category</th>
                                    <th class="text-center align-middle" rowspan="2">Loan Amount</th>
                                    <th colspan="2" style=" text-align: center; border-bottom: none;">Loan Status</th>
                                    <th colspan="2" style=" text-align: center; border-bottom: none; border-left:1px solid #d9dee3;">Details</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Sub Status</th>
                                    <th class="text-center">Info</th>
                                    <th class="text-center">Charts</th>
                                </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row gutters" id="noc_summary" style="display:none">
                <input type="hidden" id="cp_id">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">NOC Summary&nbsp;<button type="button" id="back_to_cus_status" style="float:right" class="btn btn-primary "><span class="icon-arrow-left"></span>&nbsp;Back</button></h5>
                        </div>
                        <div class="card-body">
                            <div class="card cheque-div" style="display:none">
                                <div class="card-header">
                                    <h5 class="card-title">Cheque List</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table custom-table" id="noc_cheque_list_table">
                                        <thead>
                                            <th>S No.</th>
                                            <th>Holder Type</th>
                                            <th>Holder Name</th>
                                            <th>Relationship</th>
                                            <th>Bank Name</th>
                                            <th>Cheque No.</th>
                                            <th>Date of NOC</th>
                                            <th>Handover Person</th>
                                            <th>Relationship</th>
                                            <th>Checklist</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card mortgage-div" style="display:none">
                                <div class="card-header">
                                    <h5 class="card-title">Mortgage List</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table custom-table" id="noc_mortgage_list_table">
                                        <thead>
                                            <th>S No.</th>
                                            <th>Property Holder Name</th>
                                            <th>Relationship</th>
                                            <th>Property Details</th>
                                            <th>Mortgage Name</th>
                                            <th>Desigantion</th>
                                            <th>Reg Office</th>
                                            <th>Date of NOC</th>
                                            <th>Handover Person</th>
                                            <th>Relationship</th>
                                            <th>Checklist</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card endorsement-div" style="display:none">
                                <div class="card-header">
                                    <h5 class="card-title">Endorsement List</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table custom-table" id="noc_endorsement_list_table">
                                        <thead>
                                            <th>S No.</th>
                                            <th>Owner Name</th>
                                            <th>Relationship</th>
                                            <th>Vehicle Details</th>
                                            <th>Endorsement Name</th>
                                            <th>RC</th>
                                            <th>KEY</th>
                                            <th>Date of NOC</th>
                                            <th>Handover Person</th>
                                            <th>Relationship</th>
                                            <th>Checklist</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card doc_div" style="display:none">
                                <div class="card-header">
                                    <h5 class="card-title">Other Document List</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table custom-table" id="noc_document_list_table">
                                        <thead>
                                            <th>S No.</th>
                                            <th>Document Name</th>
                                            <th>Document Type</th>
                                            <th>Document Holder</th>
                                            <th>Document</th>
                                            <th>Date of NOC</th>
                                            <th>Handover Person</th>
                                            <th>Relationship</th>
                                            <th>Checklist</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card gold-div" style="display:none">
                                <div class="card-header">
                                    <h5 class="card-title">Gold List</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table custom-table" id="noc_gold_list_table">
                                        <thead>
                                            <th>S No.</th>
                                            <th>Gold Type</th>
                                            <th>Purity</th>
                                            <th>Weight</th>
                                            <th>Date of NOC</th>
                                            <th>Handover Person</th>
                                            <th>Relationship</th>
                                            <th>Checklist</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--------------------------------------------------------------------- Customer Profile Start ----------------------------------------------------------------------->

<div id="loan_entry_content" style="display:none;">
    <div class="text-right">
        <button type="button" class="btn btn-primary" id="back_btn"><span class="icon-arrow-left"></span>&nbsp; Back </button>
        <br><br>
    </div>
    <form id="loan_entry_customer_profile" name="loan_entry_customer_profile">
        <input type="hidden" id="loan_entry_id">
        <div class="row gutters">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Personal Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cp_aadhar_number">Aadhar No</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="cp_aadhar_number" id="cp_aadhar_number" tabindex="1" maxlength="14" data-type="adhaar-number" placeholder="Enter Aadhar Number" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cp_cus_id"> Customer ID</label>
                                            <input type="text" class="form-control" id="cp_cus_id" name="cp_cus_id" placeholder="Enter Customer ID" tabindex="2" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cp_first_name">First Name</label>
                                            <input type="text" class="form-control " id="cp_first_name" name="cp_first_name" placeholder="Enter First name" tabindex="3" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last name" tabindex="4" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="dob"> DOB</label>
                                            <input type="date" class="form-control  personal_info_disble" id="dob" name="dob" placeholder="Enter Date Of Birth" tabindex="5" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="age"> Age</label>
                                            <input type="number" class="form-control  personal_info_disble" id="age" name="age" readonly placeholder="Age" tabindex="6">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="area">Area</label>
                                            <input type="hidden" id="area_edit">
                                            <select type="text" class="form-control" id="cp_area" name="area" tabindex="7" disabled>
                                                <option value="">Select Area</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="line"> Line </label>
                                            <input type="text" class="form-control" id="line" name="line" disabled placeholder="Line" tabindex="8">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="cp_mobile1"> Mobile Number 1</label>
                                            <input type="number" class="form-control" id="cp_mobile1" name="cp_mobile1" placeholder="Enter Mobile Number 1" onKeyPress="if(this.value.length==10) return false;" tabindex="9" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="mobile2"> Mobile Number 2</label>
                                            <input type="number" class="form-control" id="mobile2" name="mobile2" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter Mobile Number 2" tabindex="10" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Choose Mobile Number for WhatsApp:</label><br>
                                            <label>
                                                <input type="radio" name="mobile_whatsapp" value="mobile1" id="mobile1_radio" tabindex="11" disabled>
                                                Mobile Number 1
                                            </label><br>
                                            <label>
                                                <input type="radio" name="mobile_whatsapp" value="mobile2" id="mobile2_radio" tabindex="12" disabled>
                                                Mobile Number 2
                                            </label>
                                            <input type="hidden" id="selected_mobile_radio">
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="whatsapp"> WhatsApp Number </label>
                                            <input type="number" class="form-control" id="whatsapp" name="whatsapp" onKeyPress="if(this.value.length==10) return false;" placeholder="Enter WhatsApp Number" tabindex="13" readonly>
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
                                            <input type="hidden" class="personal_info_disble" id="per_pic">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-------------------------------------------------------------------- Guarantor Info Start --------------------------------------------------------------->

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Guarantor Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="guarantor_name"> Guarantor Name</label><span class="text-danger">*</span>
                                            <input type="hidden" id="guarantor_name_edit">
                                            <select type="text" class="form-control" id="guarantor_name" name="guarantor_name" tabindex="14" disabled>
                                                <option value="">Select Guarantor Name</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="relationship"> Relationship</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="relationship" name="relationship" pattern="[a-zA-Z\s]+" disabled placeholder="Enter Relationship" tabindex="15" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label for="pic"> Photo</label><br>
                                            <img id='gur_imgshow' class="img_show" src='img\avatar.png' />
                                            <input type="hidden" id="gur_pic">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="guarantor_info" class="table custom-table">
                                        <thead>
                                            <tr>
                                                <th width="20">S.NO</th>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Remark</th>
                                                <th>Aadhar</th>
                                                <th>Mobile</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-------------------------------------------------------------------- Guarantor Info end --------------------------------------------------------------->

                <!-------------------------------------------------------------------- KYC Info start -------------------------------------------------------------------->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">KYC Info</div>
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
                        <div class="card-title">Bank Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="bank_info" class="table custom-table">
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
                        <div class="card-title">Property Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <table id="prop_info" class="table custom-table">
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
                                    <label for="cus_limit"> Customer Limit</label>
                                    <input type="number" class="form-control" id="cus_limit" name="cus_limit" placeholder="Enter Customer Limit" tabindex="21" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="about_cus"> About Customer </label>
                                    <textarea class="form-control" name="about_cus" id="about_cus" placeholder="Enter About Customer" tabindex="22" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!------------------------------------------------------------------------ Customer Profile End ------------------------------------------------------------------------------>

<!-------------------------------------------------------------------- Loan Calculation Start --------------------------------------------------------------------------------->

<div id="loan_content" style="display:none;">
    <div class="text-right">
        <button type="button" class="btn btn-primary" id="loan_back_btn"><span class="icon-arrow-left"></span>&nbsp; Back </button>
        <br><br>
    </div>
    <form id="loan_entry_loan_calculation" name="loan_entry_loan_calculation">
        <div class="row gutters">
            <div class="col-12">
                <!--- -------------------------------------- Loan Info ------------------------------- -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Loan Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="loan_id_calc"> Loan ID</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" id="loan_id_calc" name="loan_id_calc" tabindex="1" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="loan_category_calc"> Loan Category</label><span class="text-danger">*</span>
                                    <input type="hidden" id="loan_category_calc2">
                                    <select class="form-control" id="loan_category_calc" name="loan_category_calc" tabindex="2" disabled>
                                        <option value="">Select Loan Category</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="loan_amount_calc"> Loan Amount</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control clearLoanInfo" id="loan_amount_calc" name="loan_amount_calc" tabindex="3" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="benefit_method">Benefit Method</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control clearLoanInfo" id="benefit_method" name="benefit_method" value="" tabindex="4" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="due_method">Due Method</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control clearLoanInfo" id="due_method" name="due_method" value="" tabindex="5" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="due_period">Due Period</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control clearLoanInfo" id="due_period" name="due_period" value="Month" tabindex="6" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="interest_calculate">Interest Calculate</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control clearLoanInfo" id="interest_calculate" name="interest_calculate" tabindex="7" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="due_calculate">Due Calculate</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control clearLoanInfo" id="due_calculate" name="due_calculate" value="" tabindex="8" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- -------------------------------------------------------------------- Loan Info END ---------------------------------------------------------->

                <!------------------------------------------------------------- Calculation START -------------------------------------------------------------------->

                <div class="card" id="profit_type_calc" style="display: none;">
                    <div class="card-header">
                        <div class="card-title calc_title">Calculation</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="interest_rate_calc">Interest Rate</label><span class="text-danger min-max-int">*</span><!-- Min and max intrest rate-->
                                    <input type="number" class="form-control to_clear" id="interest_rate_calc" name="interest_rate_calc" tabindex="9" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="due_period_calc">Due Period</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control to_clear" id="due_period_calc" name="due_period_calc" tabindex="10" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_charge_calc">Document Charges</label><span class="text-danger min-max-doc">*</span><!-- Min and max Document charges-->
                                    <input type="number" class="form-control to_clear" id="doc_charge_calc" name="doc_charge_calc" tabindex="11" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="processing_fees_calc">Processing Fees</label><span class="text-danger min-max-proc">*</span><!-- Min and max Processing fee-->
                                    <input type="number" class="form-control to_clear" id="processing_fees_calc" name="processing_fees_calc" tabindex="12" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--------------------------------------------------------------------------- Calculation END --------------------------------------------------------->

                <!--- -------------------------------------- Loan Calculate START ------------------------------- -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="loan_amnt_calc">Loan Amount</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control refresh_loan_calc" id="loan_amnt_calc" name="loan_amnt_calc" tabindex="14" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="doc_charge_calculate">Document Charges</label><span class="text-danger doc-diff">*</span>
                                    <input type="text" class="form-control refresh_loan_calc" id="doc_charge_calculate" name="doc_charge_calculate" tabindex="15" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="processing_fees_calculate">Processing Fees</label><span class="text-danger proc-diff">*</span>
                                    <input type="text" class="form-control refresh_loan_calc" id="processing_fees_calculate" name="processing_fees_calculate" tabindex="16" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="net_cash_calc">Net Cash</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control refresh_loan_calc" id="net_cash_calc" name="net_cash_calc" tabindex="17" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="interest_amnt_calc">Interest Amount</label><span class="text-danger int-diff">*</span>
                                    <input type="text" class="form-control refresh_loan_calc" id="interest_amnt_calc" name="interest_amnt_calc" tabindex="18" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- -------------------------------------- Loan Calculate END ------------------------------- -->

                <!--- -------------------------------------- Collection Info START ------------------------------- -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Collection Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="due_startdate_calc">Due Start Date</label><span class="text-danger">*</span>
                                    <input type="date" class="form-control" id="due_startdate_calc" name="due_startdate_calc" tabindex="20" disabled>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="maturity_date_calc">Maturity Date</label><span class="text-danger">*</span>
                                    <input type="date" class="form-control" id="maturity_date_calc" name="maturity_date_calc" tabindex="21" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- -------------------------------------- Collection Info END ------------------------------- -->

                <!--- -------------------------------------- Agent Info START ------------------------------- -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Agent Info</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="referred_calc">Referred</label>
                                    <select class="form-control" id="referred_calc" name="referred_calc" tabindex="22" disabled>
                                        <option value="">Select Referred</option>
                                        <option value="0">Yes</option>
                                        <option value="1">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="agent_id_calc">Agent Name</label>
                                    <select class="form-control" id="agent_id_calc" name="agent_id_calc" tabindex="23" readonly>
                                        <option value="">Select Agent Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="agent_name_calc">Agent ID</label>
                                    <input type="text" class="form-control" id="agent_name_calc" name="agent_name_calc" tabindex="24" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- -------------------------------------- Agent Info END ------------------------------- -->

                <!--- -------------------------------------- Documents START ------------------------------- -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Documents</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table id="doc_need_table" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Document Name</th>
                                        </tr>
                                    </thead>
                                    <tbody> </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--- -------------------------------------- Documents END ------------------------------- -->
            </div>
        </div>
    </form>
</div>

<!-------------------------------------------------------------------- Loan Calculation End --------------------------------------------------------------------------------->

<!--------------------------------------------------------------------- Documentation start --------------------------------------------------------------------------------->

<div id="loan_issue_content" style="display:none;">
    <div class="text-right">
        <button type="button" class="btn btn-primary" id="doc_back_btn"><span class="icon-arrow-left"></span>&nbsp; Back </button>
        <br><br>
    </div>
    <form id="documentation_form" name="documentation_form">
        <input type="hidden" id="customer_profile_id">
        <div class="text-right">
            <button type="button" class="btn btn-primary" id="print_doc"><span class="icon-print"></span>&nbsp; Print </button>
            <br><br>
        </div>
        <div class="row gutters">
            <div class="col-12">
                <!--- -------------------------------------- Document Need START ------------------------------- -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Document Need</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table id="doc_need_table" class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th width="500">S.No</th>
                                            <th>Document Name</th>
                                        </tr>
                                    </thead>
                                    <tbody> </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!--- -------------------------------------- Document Need END ------------------------------- -->
                <div class="card">
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

                <div class="card cheque-div" style="display: none;">
                    <div class="card-header">
                        <div class="card-title">Cheque Info</div>
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

                <div class="card doc_div" style="display: none;">
                    <div class="card-header">
                        <div class="card-title">Document Info</div>
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

                <div class="card mortgage-div" style="display: none;">
                    <div class="card-header">
                        <div class="card-title">Mortgage Info</div>
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

                <div class="card endorsement-div" style="display: none;">
                    <div class="card-header">
                        <div class="card-title">Endorsement Info</div>
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

                <div class="card gold-div" style="display: none;">
                    <div class="card-header">
                        <div class="card-title">Gold Info</div>
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
</div>

<!--------------------------------------------------------------------- Documentation End --------------------------------------------------------------------------------->

<!----------------------------------------------------------------------- Closed Remark Modal Start ----------------------------------------------------------------------->

<div class="modal fade" id="closed_remark_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Closed Remark</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="closed_remark_form" method="post">
                        <input type="hidden" id="loan_entry_id">
                        <div class="col-12 row">
                            <div class="col-sm-2 col-md-2 col-lg-2"></div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="sub_status">Sub Status</label><span class="required">*</span>
                                    <input type="text" name="sub_status" id="sub_status" class="form-control" tabindex="2" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea class="form-control" name="remark" id="remark" tabindex="3" placeholder="Remarks" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button name="submit_closed_remark" id="submit_closed_remark" class="btn btn-primary" tabindex="4"><span class="icon-check"></span>&nbsp;Submit</button>
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="6">Close</button>
            </div>
        </div>
    </div>
</div>

<!----------------------------------------------------------------------- Closed Remark Modal End ----------------------------------------------------------------------->

<!-------------------------------------------------------------------------- NOC Summary Start -------------------------------------------------------------------------->

<div class="row gutters" id="noc_summary" style="display:none">
    <input type="hidden" id="le_id">
    <div class="col-12">

        <div class="card cheque-div" style="display:none">
            <div class="card-header">
                <h5 class="card-title">Cheque List</h5>
            </div>
            <div class="card-body">
                <table class="table custom-table" id="noc_cheque_list_table">
                    <thead>
                        <th>S No.</th>
                        <th>Holder Type</th>
                        <th>Holder Name</th>
                        <th>Relationship</th>
                        <th>Bank Name</th>
                        <th>Cheque No.</th>
                        <th>Date of NOC</th>
                        <th>Handover Person</th>
                        <th>Relationship</th>
                        <th>Checklist</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card mortgage-div" style="display:none">
            <div class="card-header">
                <h5 class="card-title">Mortgage List</h5>
            </div>
            <div class="card-body">
                <table class="table custom-table" id="noc_mortgage_list_table">
                    <thead>
                        <th>S No.</th>
                        <th>Property Holder Name</th>
                        <th>Relationship</th>
                        <th>Property Details</th>
                        <th>Mortgage Name</th>
                        <th>Desigantion</th>
                        <th>Reg Office</th>
                        <th>Date of NOC</th>
                        <th>Handover Person</th>
                        <th>Relationship</th>
                        <th>Checklist</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card endorsement-div" style="display:none">
            <div class="card-header">
                <h5 class="card-title">Endorsement List</h5>
            </div>
            <div class="card-body">
                <table class="table custom-table" id="noc_endorsement_list_table">
                    <thead>
                        <th>S No.</th>
                        <th>Owner Name</th>
                        <th>Relationship</th>
                        <th>Vehicle Details</th>
                        <th>Endorsement Name</th>
                        <th>RC</th>
                        <th>KEY</th>
                        <th>Date of NOC</th>
                        <th>Handover Person</th>
                        <th>Relationship</th>
                        <th>Checklist</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card doc_div" style="display:none">
            <div class="card-header">
                <h5 class="card-title">Other Document List</h5>
            </div>
            <div class="card-body">
                <table class="table custom-table" id="noc_document_list_table">
                    <thead>
                        <th>S No.</th>
                        <th>Document Name</th>
                        <th>Document Type</th>
                        <th>Document Holder</th>
                        <th>Document</th>
                        <th>Date of NOC</th>
                        <th>Handover Person</th>
                        <th>Relationship</th>
                        <th>Checklist</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card gold-div" style="display:none">
            <div class="card-header">
                <h5 class="card-title">Gold List</h5>
            </div>
            <div class="card-body">
                <table class="table custom-table" id="noc_gold_list_table">
                    <thead>
                        <th>S No.</th>
                        <th>Gold Type</th>
                        <th>Purity</th>
                        <th>Weight</th>
                        <th>Date of NOC</th>
                        <th>Handover Person</th>
                        <th>Relationship</th>
                        <th>Checklist</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-------------------------------------------------------------------------- NOC Summary End -------------------------------------------------------------------------->

<!----------------------------------------------------------------------- Due Chart Modal Start ----------------------------------------------------------------------->

<div class="modal fade bd-example-modal-lg" id="due_chart_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document" style="max-width: 70% !important">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="dueChartTitle">Due Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid" id="due_chart_table_div">
                    <table class="table custom-table">
                        <thead>
                            <th>Due No.</th>
                            <th>Due Month</th>
                            <th>Month</th>
                            <th>Due Amount</th>
                            <th>Pending</th>
                            <th>Payable</th>
                            <th>Collection Date</th>
                            <th>Collection Amount</th>
                            <th>Balance Amount</th>
                            <th>Pre Closure</th>
                            <th>Role</th>
                            <th>User ID</th>
                            <th>Collection Method</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>

<!-------------------------------------------------------------------------- Due Chart Modal END -------------------------------------------------------------------------->

<!----------------------------------------------------------------------- Penalty Chart Modal Start ------------------------------------------------------------------------>

<div class="modal fade" id="penalty_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Penalty Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="penalty_chart_table_div">
                        <table class="table custom-table">
                            <thead>
                                <th>S No.</th>
                                <th>Penalty Date</th>
                                <th>Penalty</th>
                                <th>Paid Date</th>
                                <th>Paid Amount</th>
                                <th>Balance Amount</th>
                                <th>Waiver Amount</th>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>

<!--------------------------------------------------------------------- Penalty Chart Modal END -------------------------------------------------------------------------->

<!----------------------------------------------------------------------- Fine Chart Modal Start ------------------------------------------------------------------------->

<div class="modal fade" id="fine_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Fine Chart</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close" onclick="closeChartsModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body overflow-x-cls" id="fine_chart_table_div">
                <table class="table custom-table">
                    <thead>
                        <th>S No.</th>
                        <th>Date</th>
                        <th>Fine</th>
                        <th>Purpose</th>
                        <th>Paid Date</th>
                        <th>Paid Amount</th>
                        <th>Balance Amount</th>
                        <th>Waiver Amount</th>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>

<!------------------------------------------------------------------------ Fine Chart Modal END -------------------------------------------------------------------------->