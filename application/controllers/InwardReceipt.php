<?php

require 'vendor/autoload.php';

class InwardReceipt extends MY_Controller
{
    private $indexPage = "inward_receipt/index";
    private $form = "inward_receipt/form";

    public function __construct()
    {
        parent::__construct();
        $this->data['headData']->pageTitle = "Inward Receipt";
        $this->data['headData']->controller = "inwardReceipt";
        $this->data['headData']->pageUrl = "inwardReceipt";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller' => 'inwardReceipt']);
    }

    public function index()
    {
        $this->data['tableHeader'] = getPurchaseDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage, $this->data);
    }

    public function print_barcode(){
        // Create a new TSCPDF instance
        $this->load->view("inward_receipt/print_barcode");
    }

    public function printBarcode(){
        $data = $this->input->post();
        $logo = base_url('assets/images/logo.png');
        $boxData = '';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [50, 12]]); // Landscap
        $pdfFileName = 'pack' . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetProtection(array('print'));

        $dcode = $this->inwardReceipt->getInwardReceipt($data)->design_no;
        $codes = $this->inwardReceipt->getItemSerialNo($data);
        $stock_category  = 0;
        $stock_category_d  = 0;
        foreach ($codes as $code):
            if (in_array($code->stock_category, array("Gold", "Gold Items"))):
                $stock_category = 1;
            endif;

            if (in_array($code->stock_category, array("Silver", "Silver Items"))):
                $stock_category = 1;
            endif;

            if (in_array($code->stock_category, array("Platinum Items"))) :
                $stock_category = 1;
            endif;

            if (in_array($code->stock_category, array("Palladium"))):
                $stock_category = 1;
            endif;

            if (in_array($code->stock_category, array("Lab Grown Diamond", "Loos Diamond", "Gold + Diamond Items", "Platinum + Diamond Items"))):
                $stock_category = 2;
            endif;

            if (in_array($code->stock_category, array("Lab Grown Diamond", "Loos Diamond"))):
                $stock_category_d = 3;
            endif;

            $boxData = '';
            $boxData .= '<div style="text-align:center;padding:0mm; ">
                <table class="" border="0" cellspacing="0" cellpadding="1">  				
                    <tr>
                        <td rowspan="2" class="text-center" style="vertical-align: middle;">
                            <div  class="barcode" >
                            
                            <barcode code="' . $code->unique_id . '" type="C128C" size="1.6"/>
                            </div>
                            <b class="fs-19">' . $code->unique_id . '<br>' . $code->purity . 'K</b>';
                            if ($stock_category_d == 3) {
                                $boxData .=  '/<b>' . $dcode . '</b>';
                            }else{
                                $boxData .=  '/<b>' . $code->making_per . '%</b>';
                            }
                        $boxData .=  '</td>
                        <td  class="text-left fw-700 fs-19" style="padding-left:70px;padding-top:0px" >
                        <b> ' . $code->gross_weight . '<br>' . $code->net_weight . '
                        </td>
                        <td  class="text-left fw-700 fs-19" >';
                        if ($stock_category != 2) {
                            $boxData .= '  <b>OC:' . $code->otc_amount  . '
                            <br>VC:' . $code->vrc_amount   . '</b>';
                        } else {
                            if ($stock_category_d == 3) {
                                $boxData .= '<b>'. $code->item_code.'</b>';
                            }else{
                                $boxData .= '<b>'. $dcode . '<br>' . $code->item_code.'</b>';
                            }
                          
                        }
                        $boxData .= ' </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left:70px;" class="fw-700 fs-19"><b>';
                            if ($stock_category == 2):
                                $boxData .= $code->diamond_carat . '/' . (int)$code->diamond_pcs . 'pc &nbsp;  ' . $code->clarity. ' '.$code->color .'<br>';
                            else:
                                $boxData .=  $dcode . '-'.$code->item_code.'<br>';
                            endif;
                            $boxData .= 'PID:' . $code->party_code . '-' . $code->unique_id . '
                            </b><br>
                        </td> 
                    </tr>
                </table>
            </div>';
            
            $mpdf->AddPage('P', '', '', '', '', 0, 0, 1, 1, 1, 1);
            $mpdf->WriteHTML($boxData);
        endforeach;

        $mpdf->Output($pdfFileName, 'I');
    }

    public function printMultiBarcode($party_id=0,$form=0,$to=0){
        $queryData = array();
        $queryData['tableName'] = "inward_receipt";
        $queryData['select'] = "id,design_no,party_id";
        
        if(!empty($party_id))
            $queryData['where']['party_id'] = $party_id;

        $queryData['where']['approved_by > '] = 0;
        $queryData['start'] = $form;
        $queryData['length'] = $to;
        $result =  $this->masterModel->rows($queryData);

        $logo = base_url('assets/images/logo.png');
        $boxData = '';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [50, 12]]); // Landscap
        $pdfFileName = 'pack' . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetProtection(array('print'));

        foreach($result as $row):
            $dcode = $row->design_no;
            $codes = $this->inwardReceipt->getItemSerialNo(['id'=>$row->id]);
            $stock_category_d  = 0;
            foreach ($codes as $code):
                if (in_array($code->stock_category, array("Gold", "Gold Items"))):
                    $stock_category = 1;
                endif;

                if (in_array($code->stock_category, array("Silver", "Silver Items"))):
                    $stock_category = 1;
                endif;

                if (in_array($code->stock_category, array("Platinum Items"))) :
                    $stock_category = 1;
                endif;

                if (in_array($code->stock_category, array("Palladium"))):
                    $stock_category = 1;
                endif;

                if (in_array($code->stock_category, array("Lab Grown Diamond", "Loos Diamond", "Gold + Diamond Items", "Platinum + Diamond Items"))):
                    $stock_category = 2;
                endif;

                if (in_array($code->stock_category, array("Lab Grown Diamond", "Loos Diamond"))):
                    $stock_category_d = 3;
                endif;
    
                $boxData = '';
                $boxData .= '<div style="text-align:center;padding:0mm; ">
                    <table class="" border="0" cellspacing="0" cellpadding="1">  				
                        <tr>
                            <td rowspan="2" class="text-center" style="vertical-align: middle;">
                                <div  class="barcode" >
                                
                                <barcode code="' . $code->unique_id . '" type="C128C" size="1.6"/>
                                </div>
                                <b class="fs-19">' . $code->unique_id . '<br>' . $code->purity . 'K</b>';
                                if ($stock_category_d == 3) {
                                    $boxData .=  '/<b>' . $dcode . '</b>';
                                }else{
                                    $boxData .=  '/<b>' . $code->making_per . '%</b>';
                                }
                            $boxData .=  '</td>
                            <td  class="text-left fw-700 fs-19" style="padding-left:70px;padding-top:0px" >
                            <b> ' . $code->gross_weight . '<br>' . $code->net_weight . '
                            </td>
                            <td  class="text-left fw-700 fs-19" >';
                            if ($stock_category != 2) {
                                $boxData .= '  <b>OC:' . $code->otc_amount  . '
                                <br>VC:' . $code->vrc_amount   . '</b>';
                            } else {
                                if ($stock_category_d == 3) {
                                    $boxData .= '<b>'. $code->item_code.'</b>';
                                }else{
                                    $boxData .= '<b>'. $dcode . '<br>' . $code->item_code.'</b>';
                                }
                              
                            }
                            $boxData .= ' </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-left:70px;" class="fw-700 fs-19"><b>';
                                if ($stock_category == 2):
                                    $boxData .= $code->diamond_carat . '/' . (int)$code->diamond_pcs . 'pc &nbsp;  ' . $code->clarity. ' '.$code->color .'<br>';
                                else:
                                    $boxData .=  $dcode . '-'.$code->item_code.'<br>';
                                endif;
                                $boxData .= 'PID:' . $code->party_code . '-' . $code->unique_id . '
                                </b><br>
                            </td> 
                        </tr>
                    </table>
                </div>';
                
                $mpdf->AddPage('P', '', '', '', '', 0, 0, 1, 1, 1, 1);
                $mpdf->WriteHTML($boxData);
            endforeach;
        endforeach;

        $mpdf->Output($pdfFileName, 'I');
    }

    public function loadBarcode()
    {
        $data['pageTitle'] = "Print Barcode";
        $data = $this->input->post();
        $data['dcode'] = $this->inwardReceipt->getInwardReceipt($data)->design_no;

        $data['dataRow']  = $this->inwardReceipt->getItemSerialNo($data);
        $this->load->view('inward_receipt/print_barcode', $data);
    }
    public function reversalApproval()
    {
        $data['pageTitle'] = "Approval reversal";
        $post = $this->input->post();
        $data['dataRow'] = $this->inwardReceipt->getInwardReceipt($post);
        $this->load->view('inward_receipt/reversal', $data);
    }

    public function saveReversalApproval()
    {
        $post = $this->input->post();
        $this->printJson($this->inwardReceipt->reversalApproval($post));
    }

    public function getDTRows($status = 0)
    {
        $data = $this->input->post();
        $data['status'] = $status;
        $result = $this->inwardReceipt->getDTRows($data);
        $sendData = array();
        $i = ($data['start'] + 1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getInwardReceiptData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addInward()
    {
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->transMainModel->getNextTransNo(['table_name' => "inward_receipt"]);
        $this->data['trans_number'] = $this->data['trans_prefix'] . $this->data['trans_no'];

        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2,3"]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['purityList'] = $this->purity->getPurityList();
        $this->data['diamondQualityList'] = $this->diamondQuality->getList();
        $this->data['fineList'] = $this->fine->getFineList();
        $this->data['polishList'] = $this->polish->getPolishList();
        $this->data['colorList'] = $this->color->getColorList();
        $this->data['clarityList'] = $this->clarity->getClarityList();

        $this->load->view($this->form, $this->data);
    }

    public function save()
    {
        $data = $this->input->post();
        $errorMessage = array();

        if (empty($data['trans_number']))
            $errorMessage['trans_number'] = "Inward No. is required.";
        if (empty($data['trans_date']))
            $errorMessage['trans_date'] = "Inward Date is required.";
        if (empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if (empty($data['item_id']))
            $errorMessage['item_id'] = "Product Name is required.";
        if (empty($data['inward_type']))
            $errorMessage['inward_type'] = "Inward Type is required.";
        if (empty($data['order_type']))
            $errorMessage['order_type'] = "Challan Type is required.";
        if (empty($data['qty']))
            $errorMessage['qty'] = "Qty is required.";
        if (empty($data['gross_weight']))
            $errorMessage['gross_weight'] = "Gross Weight is required.";
        if (empty($data['net_weight']))
            $errorMessage['net_weight'] = "Net Weight is required.";

        /* if (empty($data['purchase_price']) && $data['order_type'] == "Regular")
            $errorMessage['purchase_price'] = "Purchase Price is required."; */

        if (!empty($data['approved_by']) && empty($data['location_id']))
            $errorMessage['location_id'] = "Location is required.";

        /* if (in_array($data['inward_type'], ["Gold + Diamond Items", "Loos Diamond", "Diamond Items", "Lab Grown Diamond", "Platinum + Diamond Items"])) :
            if (empty($data['sales_price']) && $data['order_type'] == "Regular")
                $errorMessage['sales_price'] = "Sales Price is required.";
        endif; */

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $attachments = array();
            if (!empty($data['id']) && !empty($data['attachment'])) :
                $attachments = $data['attachment'];
                unset($data['attachment']);
            endif;

            if (!empty($_FILES['attachments']['name'][0])) :
                $this->load->library('upload');
                $this->load->library('image_lib');

                $errorMessage['attachment_error'] = "";

                foreach ($_FILES['attachments']['name'] as $key => $fileName) :
                    $_FILES['userfile']['name']     = $fileName;
                    $_FILES['userfile']['type']     = $_FILES['attachments']['type'][$key];
                    $_FILES['userfile']['tmp_name'] = $_FILES['attachments']['tmp_name'][$key];
                    $_FILES['userfile']['error']    = $_FILES['attachments']['error'][$key];
                    $_FILES['userfile']['size']     = $_FILES['attachments']['size'][$key];

                    $imagePath = realpath(APPPATH . '../assets/uploads/inventory_img/');

                    $fileName = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($fileName));
                    $config = ['file_name' => time() . "_IR_" . $fileName, 'allowed_types' => 'jpg|jpeg|png|gif|JPG|JPEG|PNG', 'max_size' => 10240, 'overwrite' => FALSE, 'upload_path' => $imagePath];

                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) :
                        $errorMessage['attachment_error'] .= $fileName . " => " . $this->upload->display_errors();
                    else :
                        $uploadData = $this->upload->data();
                        $attachments[] = $uploadData['file_name'];

                        $imgConfig['image_library'] = 'gd2';
                        $imgConfig['source_image'] = $uploadData['full_path'];
                        $imgConfig['maintain_ratio'] = TRUE;
                        $imgConfig['width'] = 640;
                        $imgConfig['height'] = 480;
                        $imgConfig['quality'] = "50%";

                        $this->image_lib->clear();
                        $this->image_lib->initialize($imgConfig);

                        if (!$this->image_lib->resize()) :
                            $errorMessage['attachment_error'] .= $fileName . " => " . $this->image_lib->display_errors();
                        endif;
                    endif;
                endforeach;

                if (!empty($errorMessage['attachment_error'])) :
                    foreach ($attachments as $file) :
                        if (file_exists($imagePath . '/' . $file)) : unlink($imagePath . '/' . $file);
                        endif;
                    endforeach;
                    $this->printJson(['status' => 0, 'message' => $errorMessage]);
                endif;
            endif;

            if (!empty($attachments)) :
                $data['attachments'] = implode(",", $attachments);
            else :
                $data['attachments'] = "";
            endif;
            $data['gross_weight'] = (!empty($data['gross_weight']))?$data['gross_weight']:0; 
            $data['net_weight'] = (!empty($data['net_weight']))?$data['net_weight']:0; 
            $data['kundan_weight'] = (!empty($data['kundan_weight']))?$data['kundan_weight']:0; 
            $data['stone_weight'] = (!empty($data['stone_weight']))?$data['stone_weight']:0; 
            $data['moti_weight'] = (!empty($data['moti_weight']))?$data['moti_weight']:0; 
            $data['diamond_weight']  = (!empty($data['diamond_weight']))?$data['diamond_weight']:0; 

			$data['gold_weight'] = round(($data['gross_weight'] - $data['net_weight'] - $data['kundan_weight'] 
			- $data['stone_weight'] - $data['moti_weight']-  $data['diamond_weight']),3);
            $this->printJson($this->inwardReceipt->save($data));
        endif;
    }

    public function edit()
    {
        $data = $this->input->post();
        if (!empty($data['is_approve'])) :
            $this->data['approved_by'] = $this->loginId;
            $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['final_location' => 1]);
        endif;

        $this->data['dataRow'] = $this->inwardReceipt->getInwardReceipt($data);

        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2,3"]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['purityList'] = $this->purity->getPurityList();
        $this->data['fineList'] = $this->fine->getFineList();
        $this->data['polishList'] = $this->polish->getPolishList();
        $this->data['colorList'] = $this->color->getColorList();
        $this->data['clarityList'] = $this->clarity->getClarityList();

        $this->load->view($this->form, $this->data);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->inwardReceipt->delete($id));
        endif;
    }

    public function getPartyInwards()
    {
        $data = $this->input->post();
        $this->data['orderItems'] = $this->inwardReceipt->getPendingInwardItems($data);
        $this->load->view('purchase_invoice/create_invoice', $this->data);
    }
}
