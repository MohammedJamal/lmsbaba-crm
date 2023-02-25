<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Download extends CI_Controller {	

	function __construct()
	{
		parent :: __construct();		   
		$this->load->model(array("Opportunity_model","quotation_model","Client_model","Setting_model"));
		$this->load->library('common_functions');
	}

	function quotation($tmp_id)
    {	
    	if($tmp_id)
    	{ 
    		$tmp_str=base64_decode($tmp_id);
    		$tmp_arr=explode("#", $tmp_str);
    		$opportunity_id=$tmp_arr[1];
    		$quotation_id=$tmp_arr[2];
    		$client_id=$tmp_arr[3];    		
    		$client_info=$this->Client_model->get_details($client_id);
    		//echo $opportunity_id.'-'.$quotation_id.'-'.$client_id; die();
    		$this->generate_pdf($opportunity_id,$quotation_id,$client_info);		   
    	}
    	
    }

    public function generate_pdf($opportunity_id,$quotation_id,$client_info=array())
	{
		$data=array();
		$data['client_info']=$client_info;   
		$data['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
		//print_r($data['quotation_data']); die();
		$data['quotation']=$data['quotation_data']['quotation'];
		$data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
		$data['company']=$data['quotation_data']['company_log'];
		$data['customer']=$data['quotation_data']['customer_log'];
		$data['terms']=$data['quotation_data']['terms_log_only_display_in_quotation'];

		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;		

		// ===================================================
		// check is external quotation pdf uploaded
		// print_r($data); die();
		$is_extermal_quote=$data['quotation']['is_extermal_quote'];
		if($is_extermal_quote=='Y')
		{	
			$file_name=$data['quotation']['file_name'];
			$file_path="assets/uploads/clients/".$client_info->client_id."/quotation/".$file_name;
			
			$this->load->helper('download');				
			$tmp_path    =   file_get_contents(base_url().$file_path);
			$tmp_name    =   $file_name;
			//force_download($file_name, $file_path);
			force_download($tmp_name, $tmp_path);
			return true;
			
		}
		// check is external quotation pdf uploaded
		// ===================================================

		// -----------------------------
	    // Generate PDF Script Start  
	    $pdfFileName = $data['quotation']['quote_no']."-QUOTE.pdf"; 
	    $pdfFilePath = "assets/uploads/clients/".$client_info->client_id."/quotation/".$pdfFileName;		
		
        $curr_date_time=date('Y-m-d H:i:s');
        $data['curr_datetime']=$curr_date_time;     
        $data['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
        $data['curr_company']=$this->Setting_model->GetCompanyData($client_info);
		$pdf_html = $this->load->view('admin/lead/quotation_pdf_view',$data,TRUE);
		$this->load->library('m_pdf'); //load mPDF library
		$mpdf = new mPDF();
		$mpdf->AddPage('P', // L - landscape, P - portrait 
                        '', '', '', '',
                        4, // margin_left
                        4, // margin right
                        4, // margin top
                        0, // margin bottom
                        0, // margin header
                        4	// margin footer
                       ); 
		// Footer Start					
		$footer = '<div style="bottom: 10px; text-align: right; width: 100%;font-size: 11px;">Page {PAGENO} of {nb}</div>';
		// Footer End
		// $mpdf->SetHTMLFooter($footer,'E');
		// $mpdf->SetHTMLFooter($footer,'O');
		$mpdf->SetTitle("Quote");
        $mpdf->SetAuthor($data['company']['name']);
        $mpdf->SetWatermarkText($data['company']['name']);
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
		//$stylesheet = file_get_contents(base_url().'styles/quotation_pdf.css'); // external css			
		//$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
        $mpdf->defaultfooterfontstyle='';
        $mpdf->defaultfooterfontsize=8;
        $mpdf->defaultfooterline=0;
        //$mpdf->setFooter('Page {PAGENO} of {nb}');
        $mpdf->WriteHTML($pdf_html);
        return $mpdf->Output($pdfFileName, "D");
	}
}