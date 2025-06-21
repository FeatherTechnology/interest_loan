<br>
<br>
<div id="customer_data_content">
    <div class="radio-container">
        <div class="selector">
            <div class="selector-item">
                <input type="radio" id="new_list" name="customer_data" class="selector-item_radio" value="new_list" checked>
                <label for="new_list" class="selector-item_label">New Promotion</label>
            </div>
            <div class="selector-item">
                <input type="radio" id="existing_list" name="customer_data" class="selector-item_radio" value="existing_list">
                <label for="existing_list" class="selector-item_label">Existing</label>
            </div>
            <div class="selector-item">
                <input type="radio" id="repromotion_list" name="customer_data" class="selector-item_radio" value="repromotion_list">
                <label for="repromotion_list" class="selector-item_label">Repromotion</label>
            </div>
        </div>
    </div>
    <br>
    <br>

    <!---------------------------------------------------------- New Promotion List Start ------------------------------------------------------------------>

    <div class="card new_table_content">
        <div class="card-header">
            <div class="card-title">New Promotion List
                <button type="button" class="btn btn-primary" id="add_new" name="add_new" data-toggle="modal" data-target="#add_new_list_modal" style="padding: 5px 35px; float: right;"><span class="icon-add"></span></button>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12">
                <table id="new_list_table" class="table custom-table">
                    <thead>
                        <tr>
                            <th>S.NO</th>
                            <th>Customer Name</th>
                            <th>Area</th>
                            <th>Mobile</th>
                            <th>Loan Category</th>
                            <th>Loan Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-------------------------------------------------------- New Promotion List End --------------------------------------------------------------------------->

    <!----------------------------------------------------------- Existing List Start ---------------------------------------------------------------------------->

    <div class="card existing_table_content" style="display: none;">
        <div class="card-header">
            <div class="card-title">Existing List </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4" style="display: flex;">
                    <div>
                        <input type="hidden" id="existing_id">
                        <select class="form-control" id="existing_details" name="existing_details" tabindex="1" multiple>
                            <option value="">Select Existing Details</option>
                            <option value="needed">Needed</option>
                            <option value="later">Later</option>
                            <option value="tofollow">To Follow</option>
                        </select>
                    </div>
                    <div style="margin-top:3px ; margin-left:40px;">
                        <button type="button" class="btn btn-primary" id="existing_detail_btn">Proceed</button>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12">
                <table id="existing_list_table" class="table custom-table">
                    <thead>
                        <tr>
                            <th width="20">S.NO</th>
                            <th>Customer ID</th>
                            <th>Aadhar Number</th>
                            <th>Customer Name</th>
                            <th>Mobile</th>
                            <th>Area</th>
                            <th>Line</th>
                            <th>Branch</th>
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

    <!------------------------------------------------------------------ Existing List End -------------------------------------------------------------------->

    <!------------------------------------------------------------------- Repromotion List Start -------------------------------------------------------------->

    <div class="card repromotion_table_content" style="display: none;">
        <div class="card-header">
            <div class="card-title">Repromotion List </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4" style="display: flex;">
                    <div>
                        <input type="hidden" id="existing_id">
                        <select class="form-control" id="repromotion_details" name="repromotion_details" tabindex="1" multiple>
                            <option value="">Select Existing Details</option>
                            <option value="needed">Needed</option>
                            <option value="later">Later</option>
                            <option value="tofollow">To Follow</option>
                        </select>
                    </div>
                    <div style="margin-top:3px ; margin-left:40px;">
                        <button type="button" class="btn btn-primary" id="repromotion_detail_btn">Proceed</button>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-12">
                <table id="repromotion_list_table" class="table custom-table">
                    <thead>
                        <tr>
                            <th width="20">S.NO</th>
                            <th>Customer ID</th>
                            <th>Aadhar Number</th>
                            <th>Customer Name</th>
                            <th>Mobile</th>
                            <th>Area</th>
                            <th>Line</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!------------------------------------------------------------------------ Repromotion List End ----------------------------------------------------------------->
</div>

<!---------------------------------------------------------------- New Promotion Modal Start --------------------------------------------------------------------->

<div class="modal fade" id="add_new_list_modal" tabindex="1" role="dialog" aria-labelledby="exampleModalCenterTitle">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">New Promotion</h5>
                <button type="button" class="close" data-dismiss="modal" tabindex="2" onclick="getNewPromotionTable()" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="new_form">
                        <div class="row">
                            <input type="hidden" name="new_promotion_id" id='new_promotion_id'>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="cus_name">Customer Name</label><span class="text-danger">*</span>
                                    <input tye="text" class="form-control" name="cus_name" id="cus_name" tabindex="3" placeholder="Enter Customer Name">
                                    <input type="hidden" id="addcus_name_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="area">Area</label><span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="area" id="area" tabindex="4" placeholder="Enter Area Name">
                                    <input type="hidden" id="addarea_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="mobile">Mobile</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="mobile" id="mobile" onKeyPress="if(this.value.length==10) return false;" tabindex="5" placeholder="Enter Mobile Number">
                                    <input type="hidden" id="addmobile_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="loan_category">Loan Category</label><span class="text-danger">*</span>
                                    <input class="form-control" name="loan_category" id="loan_category" tabindex="6" placeholder=" Enter Loan category">
                                    <input type="hidden" id="addloan_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="loan_amount">Loan Amount</label><span class="text-danger">*</span>
                                    <input type="number" class="form-control" name="loan_amount" id="loan_amount" tabindex="7" placeholder="Enter Loan amount">
                                    <input type="hidden" id="addamt_id" value='0'>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <button name="submit_new" id="submit_new" class="btn btn-primary" tabindex="8" style="margin-top: 18px;"><span class="icon-check"></span>&nbsp;Submit</button>
                                    <button type="reset" id="clear_new_form" class="btn btn-outline-secondary" style="margin-top: 18px;" tabindex="9">Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" onclick="getNewPromotionTable()" tabindex="10">Close</button>
            </div>
        </div>
    </div>
</div>

<!-------------------------------------------------------------------- New Promotion Modal End --------------------------------------------------------------------------->