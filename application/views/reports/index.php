<?php $this->load->view('includes/header'); ?>
<form id="empPermission">
    <div class="page-wrapper">
        <div class="container-fluid bg-container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4 class="card-title pageHeader">Reports</h4>
                                </div>

                            </div>
                        </div>
                        <div class="card-body reportDiv" style="min-height:75vh">
                            <div class="panel-group wrap" id="bs-collapse">
                                <?php
                                foreach ($permission as $row) :
                                    if(!empty($row->subMenuData)):
                                ?>
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#bs-collapse" href="#menu<?= $row->id ?>"><?= $row->menu_name ?></a>

                                            </h4>
                                        </div>
                                        <div id="menu<?= $row->id ?>" class="panel-collapse collapse">
                                            <div class="panel-body">
                                            
                                                <div class="row report">
                                                    <?= $row->subMenuData ?>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>


<?php $this->load->view('includes/footer'); ?>

<script>
    $(document).ready(function() {
        $('.collapse.in').prev('.panel-heading').addClass('active');
        $('#bs-collapse').on('show.bs.collapse', function(a) {
            $(a.target).prev('.panel-heading').addClass('active');
        }).on('hide.bs.collapse', function(a) {
            $(a.target).prev('.panel-heading').removeClass('active');
        });
    });
</script>