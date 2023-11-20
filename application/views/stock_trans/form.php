<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="p_or_m" id="p_or_m" value="1">
            
            <div class="col-md-4 form-group">
                <label for="ref_date">Date</label>
                <input type="date" name="ref_date" id="ref_date" class="form-control" value="<?=getFyDate()?>">
            </div>

            <div class="col-md-8 form-group">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control select2 itemDetails" data-res_function="resItemDetail">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly" value="">
            </div>

            <div class="col-md-6 form-group">
                <label for="size">Packing Standard</label>
                <input type="text" name="size" id="size" class="form-control numericOnly" value="" readonly />
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control numericOnly" value="">
            </div>
        </div>
    </div>
</form>
<script>
function resItemDetail(response){
    if(response != ""){
        var itemDetail = response.data.itemDetail;
        $("#size").val(itemDetail.packing_standard);
    }else{
        $("#size").val("");
    }
}
</script>