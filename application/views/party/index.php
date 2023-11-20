<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title"><?=$this->partyCategory[$party_category]?></h4>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="<?=($party_category !=4)?"modal-xl":"modal-md"?>" data-function="addParty" data-form_title="Add <?=$this->partyCategory[$party_category]?>" data-postdata='{"party_category" : <?=$party_category?> }' ><i class="fa fa-plus"></i> Add <?=$this->partyCategory[$party_category]?></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='partyTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows/<?=$party_category?>'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
function resSavePartyGstDetail(data,formId){
    if(data.status==1){
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

        $('#'+formId)[0].reset();

        var gstTrans = {'postData':{'party_id':$("#gstDetail #party_id").val()},'table_id':"gstDetail",'tbody_id':'gstDetailBody','tfoot_id':'','fnget':'getPartyGSTDetailHtml'};
        getTransHtml(gstTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resTrashPartyGstDetail(data){
    if(data.status==1){
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

        var gstTrans = {'postData':{'party_id':$("#gstDetail #party_id").val()},'table_id':"gstDetail",'tbody_id':'gstDetailBody','tfoot_id':'','fnget':'getPartyGSTDetailHtml'};
        getTransHtml(gstTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resSavePartyContactDetail(data,formId){
    if(data.status==1){
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

        $('#'+formId)[0].reset();

        var contactTrans = {'postData':{'party_id':$("#contactDetail #party_id").val()},'table_id':"contactDetail",'tbody_id':'contactDetailBody','tfoot_id':'','fnget':'getPartyContactDetailHtml'};
        getTransHtml(contactTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}

function resTrashPartyContactDetail(data){
    if(data.status==1){
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

        var contactTrans = {'postData':{'party_id':$("#contactDetail #party_id").val()},'table_id':"contactDetail",'tbody_id':'contactDetailBody','tfoot_id':'','fnget':'getPartyContactDetailHtml'};
        getTransHtml(contactTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}
</script>