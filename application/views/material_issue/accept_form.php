<form>
    <div class="col-md-12">
        <input type="hidden" name="id" id="id" value="<?=$id?>" />
        <div class="row">
            <div class="table table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>Item Name</th>
                            <th>Barcode No.</th>
                            <th>Gross Weight</th>
                            <th>Stone Weight</th>
                            <th>Net Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 0;$totalGw = $totalNw = 0;
                            foreach($issueItemList as $row):
                                $i++;
                                echo '<tr>
                                    <td>'.$row->item_name.'  <input type="hidden" name="itemData['.$id.'][id]" value="'.$row->id.'"></td>
                                    <td>'.$row->item_desc.'</td>
                                    <td>'.$row->gross_weight.'</td>
                                    <td>'.($row->gross_weight - $row->net_weight).'</td>
                                    <td>'.$row->net_weight.'</td>
                                </tr>';
                                $totalGw += $row->gross_weight;
                                $totalNw += $row->net_weight;                                
                            endforeach;
                        ?>
                    </tbody>
                    <tfoot class="thead-info">
                        <tr>
                            <th>Total</th>
                            <th><?=$i?></th>
                            <th><?=sprintf('%.3f',round($totalGw,3))?></th>
                            <th><?=sprintf('%.3f',round(($totalGw - $totalNw),3))?></th>
                            <th><?=sprintf('%.3f',round($totalNw,3))?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</form>