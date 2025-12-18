<div class="row gutters">
    <div class="col-12">
        <div class="card" id="collection_list">
            <div class="card-header">
                <h5 class="card-title">Collection List</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table id="collection_list_table" class="table custom-table">
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
        <div class="col-12 text-right" style="margin-bottom:10px">
            <button class="btn btn-primary" id="back_to_coll_list" tabindex="10" style="display: none;"><span class="icon-arrow-left"></span> Back</button>
            <button class="btn btn-primary" id="back_to_loan_list" tabindex="27" style="display: none;"><span class="icon-cancel"></span> cancel</button>
        </div>
        <div id="coll_main_container" style="display:none">
            <!-- Row start -->
            <div class="row gutters colls-cntnr">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Personal Info</h5>
                        </div>

                        <input type="hidden" name="coll_sts" id="coll_sts" />

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
                                                <input type="text" class="form-control" id="area" name="area" tabindex="6" disabled>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="line"> Line </label>
                                                <input type="text" class="form-control" id="line" name="line" disabled placeholder="Line" tabindex="7">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="mobile1"> Mobile Number </label>
                                                <input type="number" class="form-control" id="mobile1" name="mobile1" placeholder="Enter Mobile Number 1" onKeyPress="if(this.value.length==10) return false;" tabindex="8" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label for="pic"> Photo</label><br>
                                                <img id='imgshow' class="img_show" tabindex="9" src='img\avatar.png' />
                                                <input type="hidden" class="personal_info_disble" id="per_pic">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Loan List</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <table id="loan_list_table" class=" table custom-table">
                                        <thead>
                                            <th width="50">S.No.</th>
                                            <th>Loan ID</th>
                                            <th>Loan Category</th>
                                            <th>Loan Date</th>
                                            <th>Loan Amount</th>
                                            <th>Interest Rate</th>
                                            <th>Balance Amount</th>
                                            <th>Status</th>
                                            <th>Sub Status</th>
                                            <th>Charts</th>
                                            <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ///////////////////////////////////////////////////////////////// Collection Info  START /////////////////////////////////////////////////////////////// -->

            <div class="card coll_details" style="display: none;">
                <div class="card-header">
                    <h5 class="card-title">Collection Info</h5>
                </div>

                <input type="hidden" name="loan_category_id" id="loan_category_id">
                <input type="hidden" name="le_id" id="le_id">
                <input type="hidden" name="status" id="status">
                <input type="hidden" name="sub_status" id="sub_status">

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Loan Amount</label>&nbsp;<span class="text-danger totspan">*</span>
                                        <input type="text" class="form-control" readonly id="loan_amount" name="loan_amount" value='' tabindex='1'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Paid Amount</label>&nbsp;<span class="text-danger paidspan">*</span>
                                        <input type="text" class="form-control" readonly id="paid_amount" name="paid_amount" value='' tabindex='2'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Balance Amount</label>&nbsp;<span class="text-danger balspan">*</span>
                                        <input type="text" class="form-control" readonly id="balance_amount" name="balance_amount" value='' tabindex='3'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Interest Amount</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" class="form-control" readonly id="interest_amount" name="interest_amount" value='' tabindex='4'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Pending Amount</label>&nbsp;<span class="text-danger pendingspan">*</span>
                                        <input type="text" class="form-control" readonly id="pending_amount" name="pending_amount" value='' tabindex='5'>
                                        <input type="hidden" class="form-control" readonly id="pend_amt" name="pend_amt">
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Payable Amount</label>&nbsp;<span class="text-danger payablespan">*</span>
                                        <input type="text" class="form-control" readonly id="payable_amount" name="payable_amount" value='' tabindex='6'>
                                        <input type="hidden" class="form-control" readonly id="payableAmount" name="payableAmount">
                                    </div>
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Penalty</label>&nbsp;<span class="text-danger ">*</span>
                                        <input type="text" class="form-control" readonly id="penalty" name="penalty" value='' tabindex='7'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Fine</label>&nbsp;<span class="text-danger ">*</span>
                                        <input type="text" class="form-control" readonly id="fine_charge" name="fine_charge" value='' tabindex='8'>
                                    </div>
                                </div>
                                <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1" style="margin-top: 20px;">
                                    <button type="submit" name="till_now" id="till_now" class="btn btn-primary" value="Submit" tabindex='9'> &nbsp;  Till Now &nbsp; </button>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 till_now_pay" style="display: none;margin-left: -20px;">
                                    <div class="form-group">
                                        <label for="till_now_payable">Till Now Payable <span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="text" class="form-control" readonly id="till_now_payable" name="till_now_payable" tabindex="10" />
                                            <div class="form-check mt-1">
                                                <input type="checkbox" class="form-check-input" id="till_now_pay_checkbox" style=" width: 20px;height: 20px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- //////////////////////////////////////////////////////////////// Collection Info END /////////////////////////////////////////////////////// -->

            <!-- //////////////////////////////////////////////////////////////// Collection Track START ///////////////////////////////////////////////////// -->

            <div class="card coll_details" style="display: none;">
                <div class="card-header">
                    <div class="card-title">Collection Track</div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <!--Fields -->
                        <div class="col-md-12 ">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Interest Amount</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" class="form-control clearFields" id="interest_amount_track" name="interest_amount_track" value='' placeholder='Enter Interest Amount' tabindex='11'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Penalty</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" class="form-control clearFields" id="penalty_track" name="penalty_track" value='' placeholder='Enter Penalty Amount' tabindex='12'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Fine</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" class="form-control clearFields" id="fine_charge_track" name="fine_charge_track" value='' placeholder='Enter Fine' tabindex='13'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 ">
                                    <div class="form-group">
                                        <label for="disabledInput">Principal Amount</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="number" class="form-control clearFields" id="principal_amount_track" name="principal_amount_track" value='' placeholder='Enter Principal Amount' tabindex='14'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Total Paid</label>
                                        <input type="text" readonly class="form-control clearFields" id="total_paid_track" name="total_paid_track" value='' tabindex='15'>
                                    </div>
                                </div>
                            </div>

                            <!-- Waiver Access if the user have collection access. -->

                            <div class="row collection_access_div">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Interest Waiver</label>
                                        <input type="text" class="form-control clearFields" id="interest_waiver" name="interest_waiver" value='' placeholder='Enter Interest Waiver' tabindex='16'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Penalty Waiver</label>
                                        <input type="text" class="form-control clearFields" id="penalty_waiver" name="penalty_waiver" value='' placeholder='Enter Penalty Waiver' tabindex='17'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Fine Waiver</label>
                                        <input type="text" class="form-control clearFields" id="fine_charge_waiver" name="fine_charge_waiver" value='' placeholder='Enter Fine Waiver' tabindex='18'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Principle Waiver</label>
                                        <input type="text" class="form-control clearFields" id="principal_waiver" name="principal_waiver" value='' placeholder='Enter Principal Waiver' tabindex='19'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Total Waiver</label>
                                        <input type="text" readonly class="form-control clearFields" id="total_waiver" name="total_waiver" value='' tabindex='20'>
                                    </div>
                                </div>
                            </div>

                            <!-- Waiver Access if the user have collection access. -->

                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Collection Date</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" readonly class="form-control" id="collection_date" name="collection_date" tabindex='21'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Collection ID</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" readonly class="form-control" id="collection_id" name="collection_id" value='' tabindex='22'>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="disabledInput">Collection Mode</label>&nbsp;<span class="text-danger">*</span>
                                        <select class='form-control clearFields' id='collection_mode' name='collection_mode' tabindex='23'>
                                            <option value=''>Select Collection Mode</option>
                                            <option value='1'>Cash</option>
                                            <option value='2'>Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Bank Name</label>&nbsp;<span class="text-danger">*</span>
                                        <select class='form-control' id='bank_id' name='bank_id' tabindex='24'>
                                            <option value=''>Select Bank Name</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 transaction" style="display:none">
                                    <div class="form-group">
                                        <label for="disabledInput">Transaction ID</label>&nbsp;<span class="text-danger">*</span>
                                        <input type="text" class="form-control" id="trans_id" name="trans_id" value='' placeholder="Enter Transaction ID" tabindex='25'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /////////////////////////////////////////////////// Collection Track END ///////////////////////////////////////// -->

            <!-- Submit Button Start -->
            <div class="col-md-12 coll_details" style="display: none;">
                <div class="text-right">
                    <button type="submit" name="submit_collection" id="submit_collection" class="btn btn-primary" value="Submit" tabindex='26'><span class="icon-check"></span>&nbsp;Submit</button>
                </div>
            </div>
            <!-- Submit Button End -->
        </div>
    </div>
</div>

<div id="printcollection" style="display: none"></div>

<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal Start ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade bd-example-modal-lg" id="due_chart_model" tabindex="1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Due Chart Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Penalty Chart Modal Start ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade" id="penalty_model" tabindex="1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Penalty Chart Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Fine Chart Modal Start ////////////////////////////////////////////////////////////////////// -->

<div class="modal fade" id="fine_model" tabindex="1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <button class="btn btn-secondary" data-dismiss="modal" onclick="closeChartsModal()" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Fine Chart Modal END ////////////////////////////////////////////////////////////////////// -->

<!-- /////////////////////////////////////////////////////////////////// Fine Add Modal START ////////////////////////////////////////////////////////////// -->

<div class="modal fade" id="fine_form_modal" tabindex="1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Add Fine</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeFineChartModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="coll_date "> Date </label> <span class="required">&nbsp;*</span>
                            <input type="hidden" class="form-control" id="fine_le_id" name="fine_le_id">
                            <input type="text" class="form-control" id="fine_date" name="fine_date" readonly tabindex='1'>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="coll_purpose"> Purpose </label> <span class="required">&nbsp;*</span>
                            <input type="text" class="form-control" id="fine_purpose" name="fine_purpose" placeholder="Enter Purpose" onkeydown="return /[a-z ]/i.test(event.key)" tabindex='1'>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="form-group">
                            <label for="coll_amnt"> Amount </label> <span class="required">&nbsp;*</span>
                            <input type="number" class="form-control" id="fine_Amnt" name="fine_Amnt" placeholder="Enter Amount" tabindex='1'>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-4 col-12">
                        <button type="button" tabindex="1" name="fine_form_submit" id="fine_form_submit" class="btn btn-primary" style="margin-top: 19px;">Submit</button>
                    </div>
                </div>
                </br>
                <div>
                    <table id="fine_form_table" class="table custom-table">
                        <thead>
                            <tr>
                                <th width="15%"> S.No </th>
                                <th> Date </th>
                                <th> Purpose </th>
                                <th> Amount </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" tabindex="1" data-dismiss="modal" onclick="closeFineChartModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- /////////////////////////////////////////////////////////////////// Fine Add Modal END ////////////////////////////////////////////////////////////////////// -->