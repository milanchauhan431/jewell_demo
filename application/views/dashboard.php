<?php $this->load->view('includes/header'); ?>
<link href="<?= base_url() ?>assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/js/pages/chartist/chartist-init.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/extra-libs/c3/c3.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/libs/fullcalendar/dist/fullcalendar.min.css">

<div class="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-header">
                <div class="dashboard-header-overlay"></div>
                <div class="dashboard-header-data">
                    <div class="row">
                        <div class="col-lg-2">
                            <img src="<?= base_url() ?>assets/images/icon.png" alt="homepage" class="dark-logo" />
                        </div>
                        <div class="col-lg-10">
                            <h1> <?= (!empty(SITENAME)) ? SITENAME : "" ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <?php //echo $this->permission->getDashboardShortcut(); ?>
        <div class="row">
            <?php if($this->loginId != 1): ?>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-b-0">Accept Material</h4>
                    </div>
                    <div class="card-body">
                        <div class="table table-responsive">
                            <table id="acceptMaterial" class="table table-bordered">
                                <thead class="thead-info">
                                    <tr>
                                        <th>Action</th>
                                        <th>Issue No.</th>
                                        <th>Issue Date</th>
                                        <th>Collected By</th>
                                        <th>Issue Qty.</th>
                                    </tr>
                                </thead>
                                <tbody id="acceptIssueMaterial"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col-md-8">

                <?php if ($charge_data > 0 && $this->loginId == 1) { ?>
                <div class="alert alert-info">
                    <a href="<?php echo base_url() ?>makingCharge" class=""> <strong>Making Charge! </strong> Fill out your <?php echo count($charge_data); ?> pending Making Charge items. </a>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="m-b-0">Current Rate Update (Last update <?php echo  date("d-m-Y h:i:s A",strtotime($today_rate[0]->updated_at)); ?>)</h4>
                            </div>
                            <div class="col-md-6">
                                <a href="<?=base_url('purity')?>" class="btn waves-effect waves-light btn-outline-primary float-right">Update Rate</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="" class="table table-bordered">
                            <thead class="thead-info">
                                <tr>
                                    <th>Purity</th>
                                    <th>Gold 10grm</th>
                                    <th>Silver 10grm</th>
                                    <th>Platinum 10grm</th>
                                    <th>Palladium 10grm</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                <?php foreach($today_rate as $rate){ ?>
                                <tr>
                                    <td><?php echo $rate->purity ?></td>
                                    <td><?php echo floatVal($rate->gold_rate); ?></td>
                                    <td><?php echo floatVal($rate->silver_rate); ?></td>
                                    <td><?php echo floatVal($rate->platinum_rate); ?></td>
                                    <td><?php echo floatVal($rate->palladium_rate); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card earning-widget">
                            <div class="card-body">
                                <h4 class="m-b-0">Accounting</h4>
                            </div>
                            <div class="border-top p-20">
                                <ul class="dashboard-btn">
                                    <li>
                                        <a href="<?php echo base_url() ?>inwardReceipt" class=""><i class="icon-Gear"></i><span class="hide-menu">Inward Receipt</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>salesInvoice" class=""><i class="icon-Gear"></i><span class="hide-menu">Sales Invoice</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>purchaseOrders" class=""><i class="icon-Gear"></i><span class="hide-menu">Purchase Invoice</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>deliveryChallan" class=""><i class="icon-Gear"></i><span class="hide-menu">Delivery Challan</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>proformaInvoice" class=""><i class="icon-Gear"></i><span class="hide-menu">Proforma Invoice</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>salesOrders" class=""><i class="icon-Gear"></i><span class="hide-menu">Sales Order</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>salesQuotation" class=""><i class="icon-Gear"></i><span class="hide-menu">Sales Quotation</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>salesEnquiry" class=""><i class="icon-Gear"></i><span class="hide-menu">Sales Enquiry</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card earning-widget">
                            <div class="card-body">
                                <h4 class="m-b-0">Account</h4>
                            </div>
                            <div class="border-top p-20">
                                <ul class="dashboard-btn">
                                    <li>
                                        <a href="<?php echo base_url() ?>makingCharge" class=""><i class="icon-Gear"></i><span class="hide-menu">Making Charge</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>parties/list/customer" class=""><i class="icon-Gear"></i><span class="hide-menu">Customer</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>parties/list/supplier" class=""><i class="icon-Gear"></i><span class="hide-menu">Supplier</span></a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url() ?>parties/list/vendor" class=""><i class="icon-Gear"></i><span class="hide-menu">Vendor</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card earning-widget">
                            <div class="card-body">
                                <h4 class="m-b-0">TO DO LIST</h4>
                            </div>
                            <div class="border-top p-20">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<script src="<?= base_url() ?>assets/js/pages/c3-chart/bar-pie/c3-stacked-column.js"></script>
<script src="<?= base_url() ?>assets/js/pages/dashboards/dashboard3.js"></script>
<script src="<?= base_url() ?>assets/libs/fullcalendar/dist/fullcalendar.min.js"></script>

<script>
<?php if (empty($today_rate) && $this->loginId == 1) { ?>
setTimeout(function() {
    $(".rateTrg").trigger("click");
}, 2000);
<?php } ?>

var acceptMaterialTableOption = {
    responsive: true,
    "autoWidth": false,
    order: [],
    "columnDefs": [{
            type: 'natural',
            targets: 0
        },
        {
            orderable: false,
            targets: "_all"
        },
        {
            className: "text-center",
            "targets": "_all"
        }
    ],
    pageLength: 25,
    language: {
        search: ""
    },
    lengthMenu: [
        [10, 25, 50, 100, -1],
        ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
    ],
    dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" + "<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: ['pageLength', {
        text: 'Refresh',
        action: function() {
            loadIssueData();
        }
    }]
};
reportTable("acceptMaterial", acceptMaterialTableOption);

loadIssueData();

function loadIssueData() {
    var issueTrans = {
        'postData': {},
        'table_id': "acceptMaterial",
        'tbody_id': 'acceptIssueMaterial',
        'tfoot_id': '',
        'fnget': 'loadIssueData',
        'res_function': 'initIssueTrans',
        'controller': 'materialIssue'
    };
    getTransHtml(issueTrans);
}

function initIssueTrans(res) {
    $('#acceptMaterial').DataTable().clear().destroy();
    $("#acceptMaterial #acceptIssueMaterial").html('');
    $("#acceptMaterial #acceptIssueMaterial").html(res.tbodyData);
    reportTable("acceptMaterial", acceptMaterialTableOption);
}

function resAcceptMatrial(response, formId) {
    if (response.status == 1) {
        $('#' + formId)[0].reset();
        closeModal(formId);
        loadIssueData();

        toastr.success(response.message, 'Success', {
            "showMethod": "slideDown",
            "hideMethod": "slideUp",
            "closeButton": true,
            positionClass: 'toastr toast-bottom-center',
            containerId: 'toast-bottom-center',
            "progressBar": true
        });
    } else {
        if (typeof response.message === "object") {
            $(".error").html("");
            $.each(response.message, function(key, value) {
                $("." + key).html(value);
            });
        } else {
            toastr.error(response.message, 'Error', {
                "showMethod": "slideDown",
                "hideMethod": "slideUp",
                "closeButton": true,
                positionClass: 'toastr toast-bottom-center',
                containerId: 'toast-bottom-center',
                "progressBar": true
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        events: base_url + controller + '/getTodoList',

        selectable: true,
        select: async function(start, end, allDay) {
            const {
                value: formValues
            } = await Swal.fire({
                title: 'Add Event',
                html: '<input id="title" class="swal2-input" placeholder="Enter title">' + '<textarea id="description" class="swal2-input" placeholder="Enter description"></textarea>',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('title').value,
                        document.getElementById('description').value
                    ]
                }
            });

            if (formValues) {
                // Add event
                fetch(base_url + controller + '/saveTodo', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id: '',
                        start: start.startStr,
                        end: start.endStr,
                        title: formValues[0],
                        description: formValues[1]
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == 1) {
                        Swal.fire('Event added successfully!', '', 'success');
                    } else {
                        Swal.fire(data.message, '', 'error');
                    }

                    // Refetch events from all sources and rerender
                    calendar.refetchEvents();
                }).catch(console.error);
            }
        },

        eventClick: function(info) {
            info.jsEvent.preventDefault();

            // change the border color
            info.el.style.borderColor = 'red';

            Swal.fire({
                title: info.event.title,
                //icon: 'info',
                imageUrl: base_url + 'assets/images/favicon.png',
                html: '<p>' + info.event.extendedProps.description + '</p>',
                showCloseButton: true,
                showCancelButton: true,
                showDenyButton: true,
                cancelButtonText: 'Close',
                confirmButtonText: 'Delete',
                denyButtonText: 'Edit',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete event
                    fetch(base_url + controller + '/deleteTodo', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id: info.event.id
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == 1) {
                            Swal.fire('Event deleted successfully!', '', 'success');
                        } else {
                            Swal.fire(data.message, '', 'error');
                        }

                        // Refetch events from all sources and rerender
                        calendar.refetchEvents();
                    }).catch(console.error);
                } else if (result.isDenied) {
                    // Edit and update event
                    Swal.fire({
                        title: 'Edit Event',
                        html: '<input id="swalEvtTitle_edit" class="swal2-input" placeholder="Enter title" value="' + info.event.title + '">' + '<textarea id="swalEvtDesc_edit" class="swal2-input" placeholder="Enter description">' + info.event.extendedProps.description + '</textarea>',
                        focusConfirm: false,
                        confirmButtonText: 'Submit',
                        preConfirm: () => {
                            return [
                                document.getElementById('swalEvtTitle_edit').value,
                                document.getElementById('swalEvtDesc_edit').value
                            ];
                        }
                    }).then((result) => {
                        if (result.value) {
                            // Event update request
                            fetch(base_url + controller + '/saveTodo', {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    id: info.event.id,
                                    title: result.value[0],
                                    description: result.value[1]
                                }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status == 1) {
                                    Swal.fire('Event updated successfully!','', 'success');
                                } else {
                                    Swal.fire(data.error, '', 'error');
                                }

                                // Refetch events from all sources and rerender
                                calendar.refetchEvents();
                            }).catch(console.error);
                        }
                    });
                } else {
                    Swal.close();
                }
            });
        }
    });

    calendar.render();
});
</script>