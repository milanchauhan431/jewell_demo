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
                <?=$this->permission->getEmployeeMenus()?>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('reportList')?>" aria-expanded="false">
                        <i class="icon-Bar-Chart"></i><span class="hide-menu">Reports </span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>