<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                <i class="ti-menu ti-close"></i>
            </a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="<?=base_url();?>" style="padding-top: 10px;">
                <!-- Logo icon -->
                <b class="logo-icon">
                    <img src="<?=base_url()?>assets/images/icon.png" alt="homepage" class="dark-logo" style="width:100%;max-height:43px;" />
                    <img src="<?=base_url()?>assets/images/icon.png" alt="homepage" class="light-logo" style="width:100%;max-height:43px;"  />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                   <!-- Dark Logo icon -->
                    <img src="<?=base_url()?>assets/images/logo_text.png" alt="homepage" class="dark-logo" style="width:100%;" />
                    <!-- Light Logo icon -->
                    <img src="<?=base_url()?>assets/images/logo_text.png" alt="homepage" class="light-logo" style="width:100%;"  />
                </span>
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ti-more"></i>
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar">
                        <i class="sl-icon-menu font-20"></i>
                    </a>
                </li>
				<li class="nav-item d-none d-md-block text-facebook font-20 font-bold" style="line-height:45px;"><?=(!empty($headData->pageTitle)) ? $headData->pageTitle : SITENAME?></li>
                <!-- ============================================================== -->
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
           <?php /* <ul class="navbar-nav float-right">
                <!-- Financial Years -->
                <li class="nav-item mr-10">
				Gold-24k: <strong><?php echo $compamyInfoMaster->gold_rate?></strong> |
				</li>
                <li class="nav-item mr-10">
				Silver-1kg</small>: <strong><?php echo $compamyInfoMaster->silver_rate?></strong> |
				</li>
                <li class="nav-item mr-10">
				Platinum-24k: <strong><?php echo $compamyInfoMaster->platinum_rate?></strong> |
				</li>
                <li class="nav-item mr-10 d-none">
				Palladium-24k: <strong><?php echo $compamyInfoMaster->palladium_rate?></strong>
				</li>
			</ul>	*/ ?>
			<ul class="navbar-nav float-right">
                <!-- Financial Years -->
                <li class="nav-item">
                    <select id="financialYearSelection" class="form-control mt-10">
                        <?php
                            $yearList = $this->db->get('financial_year')->result();
                            //if(in_array($this->session->userdata('role'),[-1,1])):
                                $cyKey = array_search(1,array_column($yearList,'is_active'));
                                foreach($yearList as $key=>$row):
                                    if($cyKey >= $key):
                                        $selected = ($this->session->userdata('financialYear') == $row->financial_year)?"selected":"";
                                        echo "<option value='".$row->financial_year."' ".$selected.">".$row->year."</option>";
                                    endif;
                                endforeach;
                            /*else:
                                $cyKey = array_search(1,array_column($yearList,'is_active'));
                                foreach($yearList as $key=>$row):
                                    if($this->session->userdata('financialYear') == $row->financial_year):
                                        if($cyKey >= $key):
                                            $selected = ($this->session->userdata('financialYear') == $row->financial_year)?"selected":"";
                                            echo "<option value='".$row->financial_year."' ".$selected.">".$row->year."</option>";
                                        endif;
                                    endif;
                                endforeach;
                            endif;*/
                        ?>
                    </select>
				</li>
                <!-- End Financial Years -->

                <!-- Apps Menu -->
                <!-- <li class="nav-item dropdown dropdown-apps-wrapper">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-layout-grid3 font-20" style="vertical-align: middle;"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated flipInY" style="min-width:325px;">
                        <span class="with-arrow"><span class="bg-default"></span></span>
                        <ul class="list-style-none dropdown-apps" style="padding:10px;">
                            <li>
                                <div class="row">
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="<?=base_url('items')?>">
                                                <span class="fs-24"><i class="fas fa-cubes"></i></span>
                                                <div class="ai-title">Row Material</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="<?=base_url('products')?>">
                                                <span class="fs-24"><i class="sl-icon-bag"></i></span>
                                                <div class="ai-title">Finish Goods</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="<?=base_url('jobcard')?>">
                                                <span class="fs-24"><i class="fa fa-credit-card"></i></span>
                                                <div class="ai-title">Job Card</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="<?=base_url('salesOrder')?>">
                                                <span class="fs-24"><i class="icon-Check"></i></span>
                                                <div class="ai-title">Sales Order</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="<?=base_url('purchaseInvoice')?>">
                                                <span class="fs-24"><i class="icon-Receipt-3"></i></span>
                                                <div class="ai-title">GRN</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="javascript:void(0)" class="openQrModal" data-toggle="modal" data-target="#qrCodeModal">
                                                <span class="fs-24"><i class="icon-QR-Code"></i></span>
                                                <div class="ai-title">SCAN CODE</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="<?=base_url('reports/storeReport/stockDetails')?>">
                                                <span class="fs-24"><i class="icon-Receipt-3"></i></span>
                                                <div class="ai-title">Stock Register</div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <div class="a-item">
                                            <a href="<?=base_url('reports/storeReport/billDetails')?>">
                                                <span class="fs-24"><i class="icon-Receipt-3"></i></span>
                                                <div class="ai-title">Bill Register</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!/--<li>
                                <a class="nav-link text-center m-b-5" href="javascript:void(0);"><strong>View all Apps</strong><i class="fa fa-angle-right"></i></a>
                            </li>--/>
                        </ul>
                    </div>
                </li> -->
                <!-- End Apps Menu -->
                <!-- Comment  
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-bell font-20" style="vertical-align: middle;"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <ul class="list-style-none">
                            <li>
                                <div class="drop-title bg-primary text-white">
                                    <h4 class="m-b-0 m-t-5">4 New</h4>
                                    <span class="font-light">Notifications</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center notifications"> 
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-danger btn-circle">
                                            <i class="fa fa-link"></i>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Luanch Admin</h5>
                                            <span class="mail-desc">Just see the my new admin!</span>
                                            <span class="time">9:30 AM</span>
                                        </div>
                                    </a> 
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-success btn-circle">
                                            <i class="ti-calendar"></i>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Event today</h5>
                                            <span class="mail-desc">Just a reminder that you have event</span>
                                            <span class="time">9:10 AM</span>
                                        </div>
                                    </a> 
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-info btn-circle">
                                            <i class="ti-settings"></i>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Settings</h5>
                                            <span class="mail-desc">You can customize this template as you want</span>
                                            <span class="time">9:08 AM</span>
                                        </div>
                                    </a> 
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-primary btn-circle">
                                            <i class="ti-user"></i>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Pavan kumar</h5>
                                            <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:02 AM</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center m-b-5" href="javascript:void(0);">
                                    <strong>Check all notifications</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>-->
                <!-- End Comment -->
                <!-- Messages -->
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-22 ti-email" style="vertical-align: middle;"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                        <span class="with-arrow"> <span class="bg-danger"></span> </span>
                        <ul class="list-style-none">
                            <li>
                                <div class="drop-title bg-danger text-white">
                                    <h4 class="m-b-0 m-t-5">5 New</h4>
                                    <span class="font-light">Messages</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center message-body"> -->
                                    <!-- Message -->
                                    <!-- <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img">
                                            <img src="<?=base_url()?>assets/images/users/user_default.png" alt="user" class="rounded-circle">
                                            <span class="profile-status online pull-right"></span>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Pavan kumar</h5>
                                            <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:30 AM</span>
                                        </div>
                                    </a> -->
                                    <!-- Message -->
                                    <!-- <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img">
                                            <img src="<?=base_url()?>assets/images/users/user_default.png" alt="user" class="rounded-circle">
                                            <span class="profile-status busy pull-right"></span>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Sonu Nigam</h5>
                                            <span class="mail-desc">I've sung a song! See you at</span>
                                            <span class="time">9:10 AM</span>
                                        </div>
                                    </a> -->
                                    <!-- Message -->
                                    <!-- <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img">
                                            <img src="<?=base_url()?>assets/images/users/user_default.png" alt="user" class="rounded-circle">
                                            <span class="profile-status away pull-right"></span>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Arijit Sinh</h5>
                                            <span class="mail-desc">I am a singer!</span>
                                            <span class="time">9:08 AM</span>
                                        </div>
                                    </a> -->
                                    <!-- Message -->
                                    <!-- <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img">
                                            <img src="<?=base_url()?>assets/images/users/user_default.png" alt="user" class="rounded-circle">
                                            <span class="profile-status offline pull-right"></span>
                                        </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Pavan kumar</h5>
                                            <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:02 AM</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center link" href="javascript:void(0);">
                                    <b>See all e-Mails</b>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <!-- End Messages -->
                <!-- Customizer Panel -->
                <!-- <li class="nav-item dropdown">
                    <a href="javascript:void(0)" class="nav-link dropdown-toggle waves-effect waves-dark service-panel-toggle">
                        <i class="fa fa-cog font-20" style="vertical-align: middle;"></i>
                    </a>
                </li> -->
                <!--End Customizer Panel -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?=base_url()?>assets/images/users/user_default.png" alt="user" class="rounded-circle" width="31">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <span class="with-arrow">
                            <span class="bg-primary"></span>
                        </span>
                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                            <div class="">
                                <img src="<?=base_url()?>assets/images/users/user_default.png" alt="user" class="img-circle" width="60">
                            </div>
                            <div class="m-l-10">
                                <h4 class="m-b-0"><?=$this->session->userdata('emp_name')?></h4>
                                <p class=" m-b-0"><?=$this->session->userdata('roleName')?></p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="<?=base_url("hr/employees/empProfile/".$this->session->userdata('loginId'))?>"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>

                        <?php if($this->session->userdata('loginId') == 1): ?>
                        <a class="dropdown-item addNew press-add-btn rateTrg" href="<?=base_url('purity')?>"><i class="ti-wallet m-r-5 m-l-5"></i> Change Rate</a>
                        <?php endif; ?>

                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#change-psw"><i class="ti-key m-r-5 m-l-5"></i> Change Password</a>

                        <?php if($_SERVER['HTTP_HOST'] == 'localhost'): ?>
                            <a class="dropdown-item addNew press-add-btn" href="javascript:void(0)" data-button="both" data-modal_id="modal-md" data-function="dbForm" data-controller="dbUtility" data-fnsave="syncDbQuery" data-savebtn_text="<i class='fa fa-retweet'></i> SYNC" data-form_title="SYNC DB LIVE TO LOCAL"><i class="ti-link m-r-5 m-l-5"></i> SYNC DB</a>

                            <a class="dropdown-item addNew press-add-btn" href="javascript:void(0)" data-button="both" data-modal_id="modal-md" data-function="loadQueryForm" data-controller="dbUtility" data-fnsave="executeSqlQuerys" data-savebtn_text="<i class='fa fa-check'></i> Submit" data-form_title="Execute SQL Querys In Live DB"><i class="ti-server m-r-5 m-l-5"></i> Execute SQL Querys</a>

                            <a class="dropdown-item addNew press-add-btn" href="<?=LIVE_LINK?>dbUtility/exportDBfile/TOX-<?=date("dmY")?>/<?=MASTER_DB?>" target="_blank"><i class="ti-save m-r-5 m-l-5"></i> Export Live DB</a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?=base_url('logout')?>"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                    </div>
                </li>
                <!-- User profile and search -->
            </ul>
        </div>
    </nav>
</header>