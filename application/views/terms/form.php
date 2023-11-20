<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />

            <div class="col-md-12 form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control req" value="<?= (!empty($dataRow->title)) ? $dataRow->title : "" ?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="conditions">Conditions</label>
                <textarea name="conditions" id="conditions" class="form-control req" rows="2"><?= (!empty($dataRow->conditions)) ? $dataRow->conditions : "" ?></textarea>
            </div>

            <div class="col-md-6 form-group">
                <label for="type">Type</label>
                <select id="typeSelect" data-input_id="type" class="form-control jp_multiselect req" multiple="multiple">
                    <?php
                        foreach ($typeArray as $row) :
                            $selected = '';
                            if (!empty($dataRow->type)):
                                if(in_array($row, explode(',', $dataRow->type))):
                                    $selected = "selected";
                                endif;
                            endif;
                            echo '<option value="' . $row . '" ' . $selected . '>' . $row . '</option>';
                        endforeach;
                    ?>
                </select>
                <input type="hidden" name="type" id="type" value="<?= (!empty($dataRow->type)) ? $dataRow->type : "" ?>" />
                <div class="error type"></div>
            </div>

            <div class="col-md-6 form-group">
                <label for="is_default">Is Default ?</label>
                <select name="is_default" id="is_default" class="form-control">
                    <option value="0" <?=(!empty($dataRow->is_default) && $dataRow->is_default == 0)?"selected":""?>>No</option>
                    <option value="1" <?=(!empty($dataRow->is_default) && $dataRow->is_default == 1)?"selected":""?>>Yes</option>
                </select>
            </div>
        </div>
    </div>
</form>