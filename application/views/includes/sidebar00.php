<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
				<!-- Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('dashboard')?>" aria-expanded="false">
                        <i class="icon-Car-Wheel"></i><span class="hide-menu">Dashboards </span>
                    </a>
                </li>
				
				<!-- Confuguration -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Gear"></i><span class="hide-menu">Configration </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('terms')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Terms </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('shift')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Shift </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('masterOptions')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Master Options </span>
                            </a>
                        </li>
                    </ul>
                </li>
                
				<!-- HR -->
				<li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Address-Book"></i><span class="hide-menu">Human Resource </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/departments')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Departments </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/employees')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Employees </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/employees/empPermission')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Emp Permission </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/attendance')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Attendance </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/leaveSetting')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Leave Setting </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/leave')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Leave Request</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/leaveApprove')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Leave Approve</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('hr/payroll')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Payroll</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/hrReport')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Reports </span>
                            </a>
                        </li>
                    </ul>
                </li>
				
				<!-- Party --><!--
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-MaleFemale"></i><span class="hide-menu">Party </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('parties')?>" aria-expanded="false">
                                <i class="icon-Record"></i><span class="hide-menu">Customer </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('parties/supplier')?>" aria-expanded="false">
                                <i class="icon-Record"></i><span class="hide-menu">Supplier </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('parties/vendor')?>" aria-expanded="false">
                                <i class="icon-Record"></i><span class="hide-menu">Vendor </span>
                            </a>
                        </li>
                    </ul>
                </li>
				-->
				<!-- Products --><!--
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="sl-icon-bag"></i><span class="hide-menu">Product </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('itemCategory')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Item Category </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('items')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Row Material </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('items/consumable')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Consumable </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('products')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Finish Goods </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/productReport')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Reports </span>
                            </a>
                        </li>
                    </ul>
                </li>
				-->
				<!-- Purchase -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Shopping-Basket"></i><span class="hide-menu">Purchase </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('itemCategory')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Item Category </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('parties/supplier')?>" aria-expanded="false">
                                <i class="icon-Record"></i><span class="hide-menu">Supplier </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Services Provider </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('items/pitems')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Row Material </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('purchaseRequest')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Purchase Indent </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('purchaseEnquiry')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Purchase Enquiry </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('purchaseOrder')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Purchase Order </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Stock Leadger(RM) </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Purchase Registar </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("reports/purchaseReport")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu">  Reports  </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> setting </span>
                            </a>
                        </li>
                        <!--<li class="sidebar-item">
                            <a href="<?=base_url('purchaseInvoice')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Purchase Invoice </span>
                            </a>
                        </li>-->
                    </ul>
                </li>
				
				<!-- Store -->
				<li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Shop-2"></i><span class="hide-menu">Store </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('store')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Store Location </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("jobMaterialDispatch")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Material Issue </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('items/consumable')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Consumable </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Stock Leadger(Cons.) </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("store/items")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Stock Transfer </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('stockVerification')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Stock Verification </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('grn')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> GRN </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/storeReport')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Reports </span>
                            </a>
                        </li>
                    </ul>
                </li>
				
				<!-- Production -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Worker"></i><span class="hide-menu">Production </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('parties/vendor')?>" aria-expanded="false">
                                <i class="icon-Record"></i><span class="hide-menu">Vendor </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("jobWorkOrder")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Job Work Order </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("jobcard")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Job Card </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('process')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Process </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("processApproval")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Process Approval </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("materialRequest")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Material Request </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("jobcard/customerJobWork")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Job Work (Customer) </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("jobWork")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Job Work (Vendor) </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('rejectionComments')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Rejection Reason </span>
                            </a>
                        </li>	
                        <li class="sidebar-item">
                            <a href="<?=base_url('productionOperation')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Production Operation </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("productOption")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Product Options </span>
                            </a>
                        </li>
                        <!--<li class="sidebar-item">
                            <a href="<?=base_url("cycleTime")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Set Cycle Time </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("toolConsumption")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Tool Consumption </span>
                            </a>
                        </li>-->
                        <li class="sidebar-item">
                            <a href="<?=base_url("reports/productionReport")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu">  Reports  </span>
                            </a>
                        </li>
                    </ul>
                </li>
				
				<!-- Maintenance -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Wrench"></i><span class="hide-menu"> Maintenance </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('machines')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Machines </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('machineTicket')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Machine Ticket </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('machineActivities')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Machine Activities </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("reports/maintenanceReport")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu">  Reports  </span>
                            </a>
                        </li>
                    </ul>
                </li>
                
				<!-- Quality -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Check-2"></i><span class="hide-menu">Quality </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('grn/materialInspection')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Inward QC </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('finalInspection')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Final Inspection </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('rejectionComments')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Rejection Reason </span>
                            </a>
                        </li>
						<li class="sidebar-item">
                            <a href="<?=base_url("gauges")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Gauges </span>
                            </a>
                        </li>
						<li class="sidebar-item">
                            <a href="<?=base_url("instrument")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Instruments </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("inChallan")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> In-Challan (Customer) </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("outChallan")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Out-Challan (Vendor) </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("reports/qualityReport")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu">  Reports  </span>
                            </a>
                        </li>
                    </ul>
                </li>
				
				<!-- Dispatch -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Truck"></i><span class="hide-menu">Dispatch </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('deliveryChallan')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Delivery Challan </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Stock Ledger </span>
                            </a>
                        </li>
                    </ul>
                </li>
				
				<!-- Sales -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Receipt-3"></i><span class="hide-menu">Sales </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('parties')?>" aria-expanded="false">
                                <i class="icon-Record"></i><span class="hide-menu">Customer </span>
                            </a>
                        </li>
						<li class="sidebar-item">
                            <a href="<?=base_url('lead')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Leads </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("salesEnquiry")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Sales Enquiry </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("salesQuotation")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Sales Quotation </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("proformaInvoice")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Proforma Invoice </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url("salesOrder")?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Sales Order </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('deliveryChallan')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Delivery Challan </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('salesInvoice')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Sales Invoice </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('products')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Finish Goods </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/salesReport')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Reports </span>
                            </a>
                        </li>
                    </ul>
                </li>
				
				<!-- IATF Reports -->
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="icon-Bar-Chart"></i><span class="hide-menu">IATF Reports </span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/inwardRegister')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Inward Register </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/autoProduct')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Auto Product </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/nonAutoProduct')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Non Auto Product </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/customerAutomotive')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Automotive Customer </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/customerGenerals')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Non Automotive Cust. </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/purchaseRegister')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Purchase Register </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/stockStatement')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Stock Statement </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/stockVerification')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Stock Verification </span>
                            </a>
                        </li>
                        <!-- <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/supplierPurchase')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Supplier Purchase </span>
                            </a>
                        </li> -->
                        <li class="sidebar-item">
                            <a href="<?=base_url('reports/report/supplierService')?>" class="sidebar-link">
                                <i class="icon-Record"></i><span class="hide-menu"> Supplier Service </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>