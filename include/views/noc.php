<div class="row gutters">
    <div class="col-12">

        <!-------------------------------------------------------------------------- CARD START NOC TABLE------------------------------------------>

        <div class="card wow" id="noc_list">
            <div class="card-header">
                <h5 class="card-title">NOC List</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table id="noc_list_table" class=" table custom-table">
                            <thead>
                                <tr>
                                    <th width="50">S.No.</th>
                                    <th>Customer ID</th>
                                    <th>Aadhar Number</th>
                                    <th>Customer Name</th>
                                    <th>Area</th>
                                    <th>Line</th>
                                    <th>Branch</th>
                                    <th>Mobile No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--------------------------------------------------- CARD END NOC TABLE--------------------------------------------------------------------->

        <div class="col-12 text-right back_to_noc_list" style="margin-bottom:10px">
            <button class="btn btn-primary back_to_noc_list" id="back_to_noc_list" style="display: none;"><span class="icon-arrow-left"></span> Back</button>
        </div>
        <div id="noc_main_container" style="display:none">
            <!-- Row start -->
            <div class="row gutters" id="personal_info">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Personal Info</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="aadhar_number">Aadhar No</label><span class="text-danger">*</span>
                                                <input type="text" class="form-control" name="aadhar_number" id="aadhar_number" tabindex="1" maxlength="14" data-type="adhaar-number" placeholder="Enter Aadhar Number" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="cus_id"> Customer ID</label>
                                                <input type="text" class="form-control" id="cus_id" name="cus_id" placeholder="Enter Customer ID" tabindex="2" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" class="form-control " id="first_name" name="first_name" placeholder="Enter First name" tabindex="3" readonly>
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
                                                <label for="branch">Branch</label>
                                                <input type="text" class="form-control" id="branch" name="branch" tabindex="5" disabled>
                                                <input type="hidden" id="branch_id" name="branch_id">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="area">Area</label>
                                                <input type="hidden" id="area_edit">
                                                <input type="text" class="form-control" id="area" name="area" tabindex="7" disabled>
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
                                                <label for="mobile1"> Mobile Number </label>
                                                <input type="number" class="form-control" id="mobile1" name="mobile1" placeholder="Enter Mobile Number 1" onKeyPress="if(this.value.length==10) return false;" tabindex="9" readonly>
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
                </div>
            </div>
            <div class="card" id="loan_list">
                <div class="card-header">
                    <h5 class="card-title">Loan List</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="noc_loan_list_table" class=" table custom-table">
                                <thead>
                                    <th width="50">S.No.</th>
                                    <th>Loan ID</th>
                                    <th>Loan Category</th>
                                    <th>Loan Date</th>
                                    <th>Closed Date</th>
                                    <th>Loan Amount</th>
                                    <th>Status</th>
                                    <th>Sub Status</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right back_to_loan_list" style="margin-bottom:10px">
                <button class="btn btn-primary back_to_loan_list" id="back_to_loan_list" style="display: none;"><span class="icon-arrow-left"></span> Back</button>
            </div>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="date_of_noc">Date of NOC</label><span class="required">*</span>
                                        <input type="date" class="form-control" id="date_of_noc" name="date_of_noc" tabindex="1" readonly>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="noc_member">Member</label><span class="required">*</span>
                                        <select name="noc_member" id="noc_member" class="form-control" tabindex="2">
                                            <option value="">Select Member Name</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="noc_relation">Relationship</label><span class="required">*</span>
                                        <input type="text" class="form-control" id="noc_relation" name="noc_relation" tabindex="3" readonly>
                                    </div>
                                </div>
                                <div class="col-12 mt-3 text-right">
                                    <button name="submit_noc" id="submit_noc" class="btn btn-primary" tabindex="4"><span class="icon-check"></span>&nbsp;Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Closed Remark Modal Start ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade" id="closed_remark_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Closed Remark</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="1" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-2 col-md-2 col-lg-2"></div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="sub_status">Sub Status</label>
                                <input class="form-control" name="sub_status" id="sub_status" tabindex="2" disabled>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="remark">Remark</label>
                                <textarea class="form-control" name="remark" id="remark" tabindex="3" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" tabindex="4">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Closed Remark Modal END ////////////////////////////////////////////////////////////////////// -->