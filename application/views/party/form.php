<form enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />
            <input type="hidden" name="disc_per" value="<?= (!empty($dataRow->disc_per)) ? $dataRow->disc_per : "" ?>" />
            <input type="hidden" name="party_type" value="<?= (!empty($dataRow->party_type)) ? $dataRow->party_type : $party_type ?>" />
            <input type="hidden" name="supplied_types" value="<?= (!empty($dataRow->supplied_types)) ? $dataRow->supplied_types : "1" ?>" />

            <div class="col-md-6 form-group">
                <label for="party_name">Party Name</label>
                <input type="text" name="party_name" class="form-control text-capitalize req" value="<?= (!empty($dataRow->party_name)) ? $dataRow->party_name : ""; ?>" />
            </div>

            <div class="col-md-2 form-group hidden">
                <label for="party_category">Party Category</label>
                <select name="party_category" id="party_category" class="form-control select2 req">
                    <?php
                    foreach ($this->partyCategory as $key => $name) :
                        if ($key <= 3) :
                            $selected = (!empty($dataRow->party_category) && $dataRow->party_category == $key) ? "selected" : ((!empty($party_category) && $party_category == $key) ? "selected" : "");
                            echo '<option value="' . $key . '" ' . $selected . '>' . $name . '</option>';
                        endif;
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="party_code">Party Code</label>
                <input type="text" name="party_code" id="party_code" class="form-control" value="<?= (!empty($dataRow->party_code)) ? $dataRow->party_code : ""; ?>" />
            </div>

            <div class="col-md-3 form-group d-none">
                <label for="sales_executive">Sales Executive</label>
                <select name="sales_executive" id="sales_executive" class="form-control select2">
                    <option value="">Sales Executive</option>
                    <?php
                    foreach ($salesExecutives as $row) :
                        $selected = (!empty($dataRow->sales_executive) && $dataRow->sales_executive == $row->id) ? "selected" : "";
                        echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->emp_name . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" class="form-control text-capitalize" value="<?= (!empty($dataRow->contact_person)) ? $dataRow->contact_person : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="party_mobile">Contact No.</label>
                <input type="text" name="party_mobile" class="form-control numericOnly" value="<?= (!empty($dataRow->party_mobile)) ? $dataRow->party_mobile : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="party_email">Party Email</label>
                <input type="email" name="party_email" class="form-control" value="<?= (!empty($dataRow->party_email)) ? $dataRow->party_email : ""; ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="credit_days">Credit Days</label>
                <input type="text" name="credit_days" class="form-control numericOnly" value="<?= (!empty($dataRow->credit_days)) ? $dataRow->credit_days : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="registration_type">Registration Type</label>
                <select name="registration_type" id="registration_type" class="form-control select2">
                    <?php
                    foreach ($this->gstRegistrationTypes as $key => $value) :
                        $selected = (!empty($dataRow->registration_type) && $dataRow->registration_type == $key) ? "selected" : "";
                        echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="gstin">Party GSTIN</label>
                <input type="text" name="gstin" class="form-control text-uppercase req" value="<?= (!empty($dataRow->gstin)) ? $dataRow->gstin : ""; ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="pan_no">Party PAN</label>
                <input type="text" name="pan_no" class="form-control text-uppercase" value="<?= (!empty($dataRow->pan_no)) ? $dataRow->pan_no : "" ?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="aadhar_no">Aadhar Number</label>
                <input type="text" name="aadhar_no" class="form-control" value="<?= (!empty($dataRow->aadhar_no)) ? $dataRow->aadhar_no : "" ?>" />
            </div>
            
            <div class="col-md-3 form-group">
                <label for="currency">Currency</label>
                <select name="currency" id="currency" class="form-control select2">
                    <option value="">Select Currency</option>
                    <?php $i = 1;
                    foreach ($currencyData as $row) :
                        $selected = (!empty($dataRow->currency) && $dataRow->currency == $row->currency) ? "selected" : "";
                        if (empty($dataRow->currency) && $row->currency == "INR") {
                            $selected = "selected";
                        }
                    ?>
                        <option value="<?= $row->currency ?>" <?= $selected ?>><?= $row->currency ?> [<?= $row->code2000 ?> - <?= $row->currency_name ?>]</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="country_id">Select Country</label>
                <select name="country_id" id="country_id" class="form-control country_list select2 req" data-state_id="state_id" data-selected_state_id="<?= (!empty($dataRow->state_id)) ? $dataRow->state_id : 4030 ?>">
                    <option value="">Select Country</option>
                    <?php foreach ($countryData as $row) :
                        $selected = (!empty($dataRow->country_id) && $dataRow->country_id == $row->id) ? "selected" : ((empty($dataRow) && $row->id == 101) ? "selected" : "");

                    ?>
                        <option value="<?= $row->id ?>" <?= $selected ?>><?= $row->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="state_id">Select State</label>
                <select name="state_id" id="state_id" class="form-control state_list select2 req" data-city_id="city_id" data-selected_city_id="<?= (!empty($dataRow->city_id)) ? $dataRow->city_id : "" ?>">
                    <option value="">Select State</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="city_id">Select City</label>
                <select name="city_id" id="city_id" class="form-control select2 req">
                    <option value="">Select City</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="party_pincode">Pincode</label>
                <input type="text" name="party_pincode" class="form-control req" value="<?= (!empty($dataRow->party_pincode)) ? $dataRow->party_pincode : "" ?>" />
            </div>

            <div class="col-md-12 form-group">
                <label for="party_address">Address</label>
                <textarea name="party_address" class="form-control req" rows="3"><?= (!empty($dataRow->party_address)) ? $dataRow->party_address : "" ?></textarea>
            </div>

            <div class="col-md-3 form-group d-none">
                <label for="delivery_country_id">Delivery Country</label>
                <select name="delivery_country_id" id="delivery_country_id" class="form-control country_list select2" data-state_id="delivery_state_id" data-selected_state_id="<?= (!empty($dataRow->delivery_state_id)) ? $dataRow->delivery_state_id : "" ?>">
                    <option value="">Select Country</option>
                    <?php foreach ($countryData as $row) :
                        $selected = (!empty($dataRow->delivery_country_id) && $dataRow->delivery_country_id == $row->id) ? "selected" : "";
                    ?>
                        <option value="<?= $row->id ?>" <?= $selected ?>><?= $row->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 form-group d-none">
                <label for="delivery_state_id">Delivery State</label>
                <select name="delivery_state_id" id="delivery_state_id" class="form-control state_list select2" data-city_id="delivery_city_id" data-selected_city_id="<?= (!empty($dataRow->delivery_city_id)) ? $dataRow->delivery_city_id : "" ?>">
                    <option value="">Select State</option>
                </select>
            </div>

            <div class="col-md-3 form-group d-none">
                <label for="delivery_city_id">Select City</label>
                <select name="delivery_city_id" id="delivery_city_id" class="form-control select2">
                    <option value="">Select City</option>
                </select>
            </div>

            <div class="col-md-3 form-group d-none">
                <label for="delivery_pincode">Delivery Pincode</label>
                <input type="text" name="delivery_pincode" class="form-control" value="<?= (!empty($dataRow->delivery_pincode)) ? $dataRow->delivery_pincode : "" ?>" />
            </div>

            <div class="col-md-12 form-group d-none">
                <label for="delivery_address">Delivery Address</label>
                <textarea name="delivery_address" class="form-control" rows="3"><?= (!empty($dataRow->delivery_address)) ? $dataRow->delivery_address : "" ?></textarea>
            </div>

            <!-- <div class="col-md-4 form-group">
                <label for="delivery_contact_person">Delivery Contact Person</label>
                <input type="text" name="delivery_contact_person" id="delivery_contact_person" class="form-control text-capitalize" value="<?= (!empty($dataRow->delivery_contact_person)) ? $dataRow->delivery_contact_person : "" ?>">
            </div>  
            
            <div class="col-md-4 form-group">
                <label for="delivery_contact_person">Delivery Contact No.</label>
                <input type="text" name="delivery_contact_no" id="delivery_contact_no" class="form-control numericOnly" value="<?= (!empty($dataRow->delivery_contact_no)) ? $dataRow->delivery_contact_no : "" ?>">
            </div> -->

            <div class="col-md-3 form-group <?=(!empty($dataRow->party_category) && $dataRow->party_category != 1) ? "hidden" : ((!empty($party_category) && $party_category != 1) ? "hidden" : "")?>">
                <label for="date_of_birth">Date of birth</label>
                <input type="date" name="date_of_birth" class="form-control" value="<?= (!empty($dataRow->date_of_birth)) ? $dataRow->date_of_birth : "" ?>" />
            </div>


            <div class="col-md-3 form-group <?=(!empty($dataRow->party_category) && $dataRow->party_category != 1) ? "hidden" : ((!empty($party_category) && $party_category != 1) ? "hidden" : "")?>">
                <label for="anniversary_date">Anniversary Date</label>
                <input type="date" name="anniversary_date" class="form-control" value="<?= (!empty($dataRow->anniversary_date)) ? $dataRow->anniversary_date : "" ?>" />
            </div> 
            <div class="col-md-3 form-group">
                <label for="attechments">Attechments</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input multifiles" name="attachments[]" id="attachments" accept=".jpg, .jpeg, .png" >
                        <label class="custom-file-label" for="attachments">Choose document</label>
                    </div>
                </div>
                <div class="error attachment_error"></div>
            </div>

                <?php
                if (!empty($dataRow->attachments)) :
                    ?>
                    
            <div class="col-md-3 form-group">
                <?php
                    $attachments = explode(",", $dataRow->attachments);
                    foreach ($attachments as $file) :
                ?>
                        <img src="<?= base_url("assets/uploads/parties/" . $file) ?>" class="img-zoom" alt="IMG"><br>
                         <input type="hidden" name="attachment[]" value="<?= $file ?>">

                <?php
                    endforeach;
                    echo "</div>";
                endif;
                ?> 
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#country_id").trigger('change');
        $("#delivery_country_id").trigger('change');

        $(document).on('change', '#party_category', function() {
            var party_category = $(this).val();
            $.ajax({
                url: base_url + 'parties/getPartyCode',
                type: 'post',
                data: {
                    party_category: party_category
                },
                dataType: 'json'
            }).done(function(res) {
                $("#party_code").val(res.party_code);
            });
        });
    });
</script>