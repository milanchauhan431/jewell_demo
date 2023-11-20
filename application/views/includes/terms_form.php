<div class="modal fade" id="termModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="max-width:70%;">
        <div class="modal-content animated slideDown">
            <div class="modal-header">
                <h4 class="modal-title">Terms & Conditions</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-10">
                    <table id="terms_condition" class="table table-bordered dataTable no-footer">
                        <thead class="thead-info">
                            <tr>
                                <th style="width:10%;">#</th>
                                <th style="width:25%;">Title</th>
                                <th style="width:65%;">Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($termsList)) :
                                $termaData = $termsConditions;
                                $i = 1;$j = 0;
                                foreach ($termsList as $row) :
                                    $checked = ($row->is_default == 1 && empty($termaData))?"checked":"";
                                    $disabled = ($row->is_default != 1 && empty($termaData))?"disabled":"";
                                    
                                    if(!empty($termaData)):
                                        if(in_array($row->id, array_column($termaData, 'term_id'))) :
                                            $checked = "checked";
                                            $disabled = "";
                                            $row->conditions = $termaData[$j]->condition;
                                            $j++;
                                        else:
                                            $checked = "";
                                            $disabled = "disabled";
                                        endif;
                                    endif;
                            ?>
                                    <tr>
                                        <td style="width:10%;">
                                            <input type="checkbox" id="md_checkbox<?= $i ?>" class="filled-in chk-col-success termCheck" data-rowid="<?= $i ?>" check="<?= $checked ?>" <?= $checked ?> />
                                            <label for="md_checkbox<?= $i ?>"><?= $i ?></label>
                                        </td>
                                        <td style="width:25%;">
                                            <?= $row->title ?>
                                            <input type="hidden" name="termsData[<?= $i ?>][i_col_1]" id="term_id<?= $i ?>" value="<?= $row->id ?>" <?= $disabled ?> />
                                            <input type="hidden" name="termsData[<?= $i ?>][t_col_1]" id="term_title<?= $i ?>" value="<?= $row->title ?>" <?= $disabled ?> />
                                        </td>
                                        <td style="width:65%;">
                                            <input type="text" name="termsData[<?= $i ?>][t_col_2]" id="condition<?= $i ?>" class="form-control" value="<?= $row->conditions ?>" <?= $disabled ?> />
                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                endforeach;
                            else :
                                ?>
                                <tr>
                                    <td class="text-center" colspan="3">No data available in table</td>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn waves-effect waves-light btn-outline-success" data-dismiss="modal"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>
