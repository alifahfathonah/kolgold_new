<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  class Master extends CI_Controller{
    public function __construct(){
      parent::__construct();
      date_default_timezone_set('Asia/Kolkata');
    }

    public function index(){

    }


/********************************* Unit ***********************************/
  // Add Unit...
  public function unit(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' || $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $unit_status = $this->input->post('unit_status');
      if(!isset($unit_status)){ $unit_status = '1'; }
      $save_data = $_POST;
      $save_data['unit_status'] = $unit_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['unit_addedby'] = $kol_user_id;
      $user_id = $this->Master_Model->save_data('kol_unit', $save_data);

      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/unit');
    }

    $data['unit_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','unit_id','ASC','kol_unit');
    $data['page'] = 'Unit';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/unit', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Unit...
  public function edit_unit($unit_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' || $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $unit_status = $this->input->post('unit_status');
      if(!isset($unit_status)){ $unit_status = '1'; }
      $update_data = $_POST;
      $update_data['unit_status'] = $unit_status;
      $update_data['unit_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('unit_id', $unit_id, 'kol_unit', $update_data);

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/unit');
    }

    $unit_info = $this->Master_Model->get_info_arr('unit_id',$unit_id,'kol_unit');
    if(!$unit_info){ header('location:'.base_url().'Master/unit'); }
    $data['update'] = 'update';
    $data['update_unit'] = 'update';
    $data['unit_info'] = $unit_info[0];
    $data['act_link'] = base_url().'Master/edit_unit/'.$unit_id;

    $data['unit_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','unit_id','ASC','kol_unit');
    $data['page'] = 'Edit Unit';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/unit', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Unit...
  public function delete_unit($unit_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' || $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $this->Master_Model->delete_info('unit_id', $unit_id, 'kol_unit');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/unit');
  }

/*********************************** Brand *********************************/

  // Add Brand....
  public function brand(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $brand_status = $this->input->post('brand_status');
      if(!isset($brand_status)){ $brand_status = '1'; }
      $save_data = $_POST;
      $save_data['brand_status'] = $brand_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['brand_addedby'] = $kol_user_id;
      $brand_id = $this->Master_Model->save_data('kol_brand', $save_data);

      if($_FILES['brand_logo']['name']){
        $time = time();
        $image_name = 'brand_logo_'.$brand_id.'_'.$time;
        $config['upload_path'] = 'assets/images/brand/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['brand_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('brand_logo') && $brand_id && $image_name && $ext && $filename){
          $brand_logo_up['brand_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('brand_id', $brand_id, 'kol_brand', $brand_logo_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      if($_FILES['brand_image']['name']){
        $time = time();
        $image_name = 'brand_image_'.$brand_id.'_'.$time;
        $config['upload_path'] = 'assets/images/brand/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['brand_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('brand_image') && $brand_id && $image_name && $ext && $filename){
          $brand_image_up['brand_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('brand_id', $brand_id, 'kol_brand', $brand_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/brand');
    }

    $data['brand_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','brand_id','DESC','kol_brand');
    $data['page'] = 'Brand';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/brand', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Brand...
  public function edit_brand($brand_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $brand_status = $this->input->post('brand_status');
      if(!isset($brand_status)){ $brand_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_brand_logo']);
      unset($update_data['old_brand_image']);
      $update_data['brand_status'] = $brand_status;
      $update_data['brand_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('brand_id', $brand_id, 'kol_brand', $update_data);

      if($_FILES['brand_logo']['name']){
        $time = time();
        $image_name = 'brand_logo_'.$brand_id.'_'.$time;
        $config['upload_path'] = 'assets/images/brand/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['brand_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('brand_logo') && $brand_id && $image_name && $ext && $filename){
          $brand_logo_up['brand_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('brand_id', $brand_id, 'kol_brand', $brand_logo_up);
          if($_POST['old_brand_logo']){ unlink("assets/images/brand/".$_POST['old_brand_logo']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      if($_FILES['brand_image']['name']){
        $time = time();
        $image_name = 'brand_image_'.$brand_id.'_'.$time;
        $config['upload_path'] = 'assets/images/brand/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['brand_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('brand_image') && $brand_id && $image_name && $ext && $filename){
          $brand_image_up['brand_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('brand_id', $brand_id, 'kol_brand', $brand_image_up);
          if($_POST['old_brand_image']){ unlink("assets/images/brand/".$_POST['old_brand_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/brand');
    }

    $brand_info = $this->Master_Model->get_info_arr('brand_id',$brand_id,'kol_brand');
    if(!$brand_info){ header('location:'.base_url().'Master/brand'); }
    $data['update'] = 'update';
    $data['update_brand'] = 'update';
    $data['brand_info'] = $brand_info[0];
    $data['act_link'] = base_url().'Master/edit_brand/'.$brand_id;

    $data['brand_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','brand_id','DESC','kol_brand');
    $data['page'] = 'Edit Brand';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/brand', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Brand...
  public function delete_brand($brand_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $brand_info = $this->Master_Model->get_info_arr_fields('brand_image, brand_id', 'brand_id', $brand_id, 'kol_brand');
    if($brand_info){
      $brand_image = $brand_info[0]['brand_image'];
      if($brand_image){ unlink("assets/images/brand/".$brand_image); }
      $brand_logo = $brand_info[0]['brand_logo'];
      if($brand_logo){ unlink("assets/images/brand/".$brand_logo); }
    }
    $this->Master_Model->delete_info('brand_id', $brand_id, 'kol_brand');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/brand');
  }


/*********************************** Blog *********************************/

  // Add Blog....
  public function blog(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('blog_name', 'Blog Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $blog_status = $this->input->post('blog_status');
      if(!isset($blog_status)){ $blog_status = '1'; }
      $save_data = $_POST;
      $save_data['blog_status'] = $blog_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['blog_addedby'] = $kol_user_id;
      $blog_id = $this->Master_Model->save_data('kol_blog', $save_data);

      if($_FILES['blog_image']['name']){
        $time = time();
        $image_name = 'blog_image_'.$blog_id.'_'.$time;
        $config['upload_path'] = 'assets/images/blog/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['blog_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('blog_image') && $blog_id && $image_name && $ext && $filename){
          $blog_image_up['blog_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('blog_id', $blog_id, 'kol_blog', $blog_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/blog');
    }
    $data['main_blog_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','blog_category_name','ASC','kol_blog_category');

    $data['blog_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','blog_id','DESC','kol_blog');
    $data['page'] = 'Blog';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/blog', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Blog...
  public function edit_blog($blog_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('blog_name', 'Blog Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $blog_status = $this->input->post('blog_status');
      if(!isset($blog_status)){ $blog_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_blog_image']);
      $update_data['blog_status'] = $blog_status;
      $update_data['blog_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('blog_id', $blog_id, 'kol_blog', $update_data);

      if($_FILES['blog_image']['name']){
        $time = time();
        $image_name = 'blog_image_'.$blog_id.'_'.$time;
        $config['upload_path'] = 'assets/images/blog/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['blog_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('blog_image') && $blog_id && $image_name && $ext && $filename){
          $blog_image_up['blog_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('blog_id', $blog_id, 'kol_blog', $blog_image_up);
          if($_POST['old_blog_image']){ unlink("assets/images/blog/".$_POST['old_blog_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/blog');
    }

    $blog_info = $this->Master_Model->get_info_arr('blog_id',$blog_id,'kol_blog');
    if(!$blog_info){ header('location:'.base_url().'Master/blog'); }
    $data['update'] = 'update';
    $data['update_blog'] = 'update';
    $data['blog_info'] = $blog_info[0];
    $data['act_link'] = base_url().'Master/edit_blog/'.$blog_id;
    $main_blog_category_id = $blog_info[0]['blog_mcategory_id'];
    $data['main_blog_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','blog_category_name','ASC','kol_blog_category');
    $data['sub_blog_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','0','main_blog_category_id',$main_blog_category_id,'','','blog_category_name','ASC','kol_blog_category');

    $data['blog_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','blog_id','DESC','kol_blog');
    $data['page'] = 'Edit Blog';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/blog', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Blog...
  public function delete_blog($blog_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $blog_info = $this->Master_Model->get_info_arr_fields('blog_image, blog_id', 'blog_id', $blog_id, 'kol_blog');
    if($blog_info){
      $blog_image = $blog_info[0]['blog_image'];
      if($blog_image){ unlink("assets/images/blog/".$blog_image); }
    }
    $this->Master_Model->delete_info('blog_id', $blog_id, 'kol_blog');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/blog');
  }


/*********************************** Tourism *********************************/

  // Add Tourism....
  public function tourism(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('tourism_name', 'Tourism Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $tourism_status = $this->input->post('tourism_status');
      if(!isset($tourism_status)){ $tourism_status = '1'; }
      $save_data = $_POST;
      $save_data['tourism_status'] = $tourism_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['tourism_addedby'] = $kol_user_id;
      $tourism_id = $this->Master_Model->save_data('kol_tourism', $save_data);

      if($_FILES['tourism_image']['name']){
        $time = time();
        $image_name = 'tourism_image_'.$tourism_id.'_'.$time;
        $config['upload_path'] = 'assets/images/tourism/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['tourism_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('tourism_image') && $tourism_id && $image_name && $ext && $filename){
          $tourism_image_up['tourism_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('tourism_id', $tourism_id, 'kol_tourism', $tourism_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/tourism');
    }

    $data['tourism_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','tourism_id','DESC','kol_tourism');
    $data['page'] = 'Tourism';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/tourism', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Tourism...
  public function edit_tourism($tourism_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('tourism_name', 'Tourism Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $tourism_status = $this->input->post('tourism_status');
      if(!isset($tourism_status)){ $tourism_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_tourism_image']);
      $update_data['tourism_status'] = $tourism_status;
      $update_data['tourism_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('tourism_id', $tourism_id, 'kol_tourism', $update_data);

      if($_FILES['tourism_image']['name']){
        $time = time();
        $image_name = 'tourism_image_'.$tourism_id.'_'.$time;
        $config['upload_path'] = 'assets/images/tourism/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['tourism_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('tourism_image') && $tourism_id && $image_name && $ext && $filename){
          $tourism_image_up['tourism_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('tourism_id', $tourism_id, 'kol_tourism', $tourism_image_up);
          if($_POST['old_tourism_image']){ unlink("assets/images/tourism/".$_POST['old_tourism_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/tourism');
    }

    $tourism_info = $this->Master_Model->get_info_arr('tourism_id',$tourism_id,'kol_tourism');
    if(!$tourism_info){ header('location:'.base_url().'Master/tourism'); }
    $data['update'] = 'update';
    $data['update_tourism'] = 'update';
    $data['tourism_info'] = $tourism_info[0];
    $data['act_link'] = base_url().'Master/edit_tourism/'.$tourism_id;
    
    $data['tourism_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','tourism_id','DESC','kol_tourism');
    $data['page'] = 'Edit Tourism';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/tourism', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Tourism...
  public function delete_tourism($tourism_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $tourism_info = $this->Master_Model->get_info_arr_fields('tourism_image, tourism_id', 'tourism_id', $tourism_id, 'kol_tourism');
    if($tourism_info){
      $tourism_image = $tourism_info[0]['tourism_image'];
      if($tourism_image){ unlink("assets/images/tourism/".$tourism_image); }
    }
    $this->Master_Model->delete_info('tourism_id', $tourism_id, 'kol_tourism');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/tourism');
  }


/*********************************** Product Category *********************************/

  // Add Product Category....
  public function product_category(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('product_category_name', 'Product Category Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $product_category_status = $this->input->post('product_category_status');
      if(!isset($product_category_status)){ $product_category_status = '1'; }
      $save_data = $_POST;
      $save_data['product_category_status'] = $product_category_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['product_category_addedby'] = $kol_user_id;
      // unset($save_data['product_category_addedby'])

      $main_product_category_id = $this->input->post('main_product_category_id');
      if($main_product_category_id == '-1'){
        $save_data['is_main'] = 1;
        $save_data['main_product_category_id'] = 0;
      } else{
        $save_data['is_main'] = 0;
      }


      $product_category_id = $this->Master_Model->save_data('kol_product_category', $save_data);

      if($_FILES['product_category_logo']['name']){
        $time = time();
        $image_name = 'product_category_logo_'.$product_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/product_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['product_category_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('product_category_logo') && $product_category_id && $image_name && $ext && $filename){
          $product_category_logo_up['product_category_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('product_category_id', $product_category_id, 'kol_product_category', $product_category_logo_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      if($_FILES['product_category_image']['name']){
        $time = time();
        $image_name = 'product_category_image_'.$product_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/product_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['product_category_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('product_category_image') && $product_category_id && $image_name && $ext && $filename){
          $product_category_image_up['product_category_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('product_category_id', $product_category_id, 'kol_product_category', $product_category_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/product_category');
    }
    $data['main_product_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','product_category_name','ASC','kol_product_category');

    $data['product_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','product_category_name','ASC','kol_product_category');
    $data['page'] = 'Product Category';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/product_category', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Product Category...
  public function edit_product_category($product_category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('product_category_name', 'Product Category Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $product_category_status = $this->input->post('product_category_status');
      if(!isset($product_category_status)){ $product_category_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_product_category_logo']);
      unset($update_data['old_product_category_image']);
      $update_data['product_category_status'] = $product_category_status;
      $update_data['product_category_addedby'] = $kol_user_id;

      $main_product_category_id = $this->input->post('main_product_category_id');
      if($main_product_category_id == '-1'){
        $update_data['is_main'] = 1;
        $update_data['main_product_category_id'] = 0;
      } else{
        $update_data['is_main'] = 0;
      }

      $this->Master_Model->update_info('product_category_id', $product_category_id, 'kol_product_category', $update_data);

      if($_FILES['product_category_logo']['name']){
        $time = time();
        $image_name = 'product_category_logo_'.$product_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/product_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['product_category_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('product_category_logo') && $product_category_id && $image_name && $ext && $filename){
          $product_category_logo_up['product_category_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('product_category_id', $product_category_id, 'kol_product_category', $product_category_logo_up);
          if($_POST['old_product_category_logo']){ unlink("assets/images/product_category/".$_POST['old_product_category_logo']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      if($_FILES['product_category_image']['name']){
        $time = time();
        $image_name = 'product_category_image_'.$product_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/product_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['product_category_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('product_category_image') && $product_category_id && $image_name && $ext && $filename){
          $product_category_image_up['product_category_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('product_category_id', $product_category_id, 'kol_product_category', $product_category_image_up);
          if($_POST['old_product_category_image']){ unlink("assets/images/product_category/".$_POST['old_product_category_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/product_category');
    }

    $product_category_info = $this->Master_Model->get_info_arr('product_category_id',$product_category_id,'kol_product_category');
    if(!$product_category_info){ header('location:'.base_url().'Master/product_category'); }
    $data['update'] = 'update';
    $data['update_product_category'] = 'update';
    $data['product_category_info'] = $product_category_info[0];
    $data['act_link'] = base_url().'Master/edit_product_category/'.$product_category_id;

    $data['main_product_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','product_category_name','ASC','kol_product_category');

    $data['product_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','product_category_name','ASC','kol_product_category');
    $data['page'] = 'Edit Product Category';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/product_category', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Product Category...
  public function delete_product_category($product_category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $product_category_info = $this->Master_Model->get_info_arr_fields('product_category_image, product_category_id', 'product_category_id', $product_category_id, 'kol_product_category');
    if($product_category_info){
      $product_category_image = $product_category_info[0]['product_category_image'];
      if($product_category_image){ unlink("assets/images/product_category/".$product_category_image); }
      $product_category_logo = $product_category_info[0]['product_category_logo'];
      if($product_category_logo){ unlink("assets/images/product_category/".$product_category_logo); }
    }
    $this->Master_Model->delete_info('product_category_id', $product_category_id, 'kol_product_category');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/product_category');
  }

/*********************************** Blog Category *********************************/

  // Add Blog Category....
  public function blog_category(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('blog_category_name', 'Blog Category Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $blog_category_status = $this->input->post('blog_category_status');
      if(!isset($blog_category_status)){ $blog_category_status = '1'; }
      $save_data = $_POST;
      $save_data['blog_category_status'] = $blog_category_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['blog_category_addedby'] = $kol_user_id;
      // unset($save_data['blog_category_addedby'])

      $main_blog_category_id = $this->input->post('main_blog_category_id');
      if($main_blog_category_id == '-1'){
        $save_data['is_main'] = 1;
        $save_data['main_blog_category_id'] = 0;
      } else{
        $save_data['is_main'] = 0;
      }


      $blog_category_id = $this->Master_Model->save_data('kol_blog_category', $save_data);

      if($_FILES['blog_category_logo']['name']){
        $time = time();
        $image_name = 'blog_category_logo_'.$blog_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/blog_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['blog_category_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('blog_category_logo') && $blog_category_id && $image_name && $ext && $filename){
          $blog_category_logo_up['blog_category_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('blog_category_id', $blog_category_id, 'kol_blog_category', $blog_category_logo_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      if($_FILES['blog_category_image']['name']){
        $time = time();
        $image_name = 'blog_category_image_'.$blog_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/blog_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['blog_category_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('blog_category_image') && $blog_category_id && $image_name && $ext && $filename){
          $blog_category_image_up['blog_category_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('blog_category_id', $blog_category_id, 'kol_blog_category', $blog_category_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/blog_category');
    }
    $data['main_blog_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','blog_category_name','ASC','kol_blog_category');

    $data['blog_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','blog_category_name','ASC','kol_blog_category');
    $data['page'] = 'Blog Category';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/blog_category', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Blog Category...
  public function edit_blog_category($blog_category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('blog_category_name', 'Blog Category Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $blog_category_status = $this->input->post('blog_category_status');
      if(!isset($blog_category_status)){ $blog_category_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_blog_category_logo']);
      unset($update_data['old_blog_category_image']);
      $update_data['blog_category_status'] = $blog_category_status;
      $update_data['blog_category_addedby'] = $kol_user_id;

      $main_blog_category_id = $this->input->post('main_blog_category_id');
      if($main_blog_category_id == '-1'){
        $update_data['is_main'] = 1;
        $update_data['main_blog_category_id'] = 0;
      } else{
        $update_data['is_main'] = 0;
      }

      $this->Master_Model->update_info('blog_category_id', $blog_category_id, 'kol_blog_category', $update_data);

      if($_FILES['blog_category_logo']['name']){
        $time = time();
        $image_name = 'blog_category_logo_'.$blog_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/blog_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['blog_category_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('blog_category_logo') && $blog_category_id && $image_name && $ext && $filename){
          $blog_category_logo_up['blog_category_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('blog_category_id', $blog_category_id, 'kol_blog_category', $blog_category_logo_up);
          if($_POST['old_blog_category_logo']){ unlink("assets/images/blog_category/".$_POST['old_blog_category_logo']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      if($_FILES['blog_category_image']['name']){
        $time = time();
        $image_name = 'blog_category_image_'.$blog_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/blog_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['blog_category_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('blog_category_image') && $blog_category_id && $image_name && $ext && $filename){
          $blog_category_image_up['blog_category_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('blog_category_id', $blog_category_id, 'kol_blog_category', $blog_category_image_up);
          if($_POST['old_blog_category_image']){ unlink("assets/images/blog_category/".$_POST['old_blog_category_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/blog_category');
    }

    $blog_category_info = $this->Master_Model->get_info_arr('blog_category_id',$blog_category_id,'kol_blog_category');
    if(!$blog_category_info){ header('location:'.base_url().'Master/blog_category'); }
    $data['update'] = 'update';
    $data['update_blog_category'] = 'update';
    $data['blog_category_info'] = $blog_category_info[0];
    $data['act_link'] = base_url().'Master/edit_blog_category/'.$blog_category_id;

    $data['main_blog_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','blog_category_name','ASC','kol_blog_category');

    $data['blog_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','blog_category_name','ASC','kol_blog_category');
    $data['page'] = 'Edit Blog Category';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/blog_category', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Blog Category...
  public function delete_blog_category($blog_category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $blog_category_info = $this->Master_Model->get_info_arr_fields('blog_category_image, blog_category_id', 'blog_category_id', $blog_category_id, 'kol_blog_category');
    if($blog_category_info){
      $blog_category_image = $blog_category_info[0]['blog_category_image'];
      if($blog_category_image){ unlink("assets/images/blog_category/".$blog_category_image); }
      $blog_category_logo = $blog_category_info[0]['blog_category_logo'];
      if($blog_category_logo){ unlink("assets/images/blog_category/".$blog_category_logo); }
    }
    $this->Master_Model->delete_info('blog_category_id', $blog_category_id, 'kol_blog_category');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/blog_category');
  }


/*********************************** Coupon *********************************/

  // Add Coupon....
  public function coupon(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('coupon_code', 'Coupon Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $coupon_status = $this->input->post('coupon_status');
      if(!isset($coupon_status)){ $coupon_status = '1'; }
      $save_data = $_POST;
      $save_data['coupon_status'] = $coupon_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['coupon_addedby'] = $kol_user_id;
      $coupon_id = $this->Master_Model->save_data('kol_coupon', $save_data);

      if($_FILES['coupon_image']['name']){
        $time = time();
        $image_name = 'coupon_image_'.$coupon_id.'_'.$time;
        $config['upload_path'] = 'assets/images/coupon/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['coupon_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('coupon_image') && $coupon_id && $image_name && $ext && $filename){
          $coupon_image_up['coupon_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('coupon_id', $coupon_id, 'kol_coupon', $coupon_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/coupon');
    }

    $data['coupon_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','coupon_id','DESC','kol_coupon');
    $data['page'] = 'Coupon';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/coupon', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Coupon...
  public function edit_coupon($coupon_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('coupon_code', 'Coupon Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $coupon_status = $this->input->post('coupon_status');
      if(!isset($coupon_status)){ $coupon_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_coupon_image']);
      $update_data['coupon_status'] = $coupon_status;
      $update_data['coupon_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('coupon_id', $coupon_id, 'kol_coupon', $update_data);

      if($_FILES['coupon_image']['name']){
        $time = time();
        $image_name = 'coupon_image_'.$coupon_id.'_'.$time;
        $config['upload_path'] = 'assets/images/coupon/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['coupon_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('coupon_image') && $coupon_id && $image_name && $ext && $filename){
          $coupon_image_up['coupon_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('coupon_id', $coupon_id, 'kol_coupon', $coupon_image_up);
          if($_POST['old_coupon_image']){ unlink("assets/images/coupon/".$_POST['old_coupon_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/coupon');
    }

    $coupon_info = $this->Master_Model->get_info_arr('coupon_id',$coupon_id,'kol_coupon');
    if(!$coupon_info){ header('location:'.base_url().'Master/coupon'); }
    $data['update'] = 'update';
    $data['update_coupon'] = 'update';
    $data['coupon_info'] = $coupon_info[0];
    $data['act_link'] = base_url().'Master/edit_coupon/'.$coupon_id;

    $data['coupon_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','coupon_id','DESC','kol_coupon');
    $data['page'] = 'Edit Coupon';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/coupon', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Coupon...
  public function delete_coupon($coupon_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $coupon_info = $this->Master_Model->get_info_arr_fields('coupon_image, coupon_id', 'coupon_id', $coupon_id, 'kol_coupon');
    if($coupon_info){
      $coupon_image = $coupon_info[0]['coupon_image'];
      if($coupon_image){ unlink("assets/images/coupon/".$coupon_image); }
    }
    $this->Master_Model->delete_info('coupon_id', $coupon_id, 'kol_coupon');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/coupon');
  }

/*********************************** Slider *********************************/

  // Add Slider....
  public function slider(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('slider_name', 'Slider Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $slider_status = $this->input->post('slider_status');
      if(!isset($slider_status)){ $slider_status = '1'; }
      $save_data = $_POST;
      $save_data['slider_status'] = $slider_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['slider_addedby'] = $kol_user_id;
      $slider_id = $this->Master_Model->save_data('kol_slider', $save_data);

      if($_FILES['slider_image']['name']){
        $time = time();
        $image_name = 'slider_image_'.$slider_id.'_'.$time;
        $config['upload_path'] = 'assets/images/slider/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['slider_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('slider_image') && $slider_id && $image_name && $ext && $filename){
          $slider_image_up['slider_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('slider_id', $slider_id, 'kol_slider', $slider_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/slider');
    }

    $data['slider_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','slider_id','DESC','kol_slider');
    $data['page'] = 'Slider';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/slider', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Slider...
  public function edit_slider($slider_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('slider_name', 'Slider Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $slider_status = $this->input->post('slider_status');
      if(!isset($slider_status)){ $slider_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_slider_image']);
      $update_data['slider_status'] = $slider_status;
      $update_data['slider_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('slider_id', $slider_id, 'kol_slider', $update_data);

      if($_FILES['slider_image']['name']){
        $time = time();
        $image_name = 'slider_image_'.$slider_id.'_'.$time;
        $config['upload_path'] = 'assets/images/slider/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['slider_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('slider_image') && $slider_id && $image_name && $ext && $filename){
          $slider_image_up['slider_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('slider_id', $slider_id, 'kol_slider', $slider_image_up);
          if($_POST['old_slider_image']){ unlink("assets/images/slider/".$_POST['old_slider_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/slider');
    }

    $slider_info = $this->Master_Model->get_info_arr('slider_id',$slider_id,'kol_slider');
    if(!$slider_info){ header('location:'.base_url().'Master/slider'); }
    $data['update'] = 'update';
    $data['update_slider'] = 'update';
    $data['slider_info'] = $slider_info[0];
    $data['act_link'] = base_url().'Master/edit_slider/'.$slider_id;

    $data['slider_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','slider_id','DESC','kol_slider');
    $data['page'] = 'Edit Slider';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/slider', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Slider...
  public function delete_slider($slider_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $slider_info = $this->Master_Model->get_info_arr_fields('slider_image, slider_id', 'slider_id', $slider_id, 'kol_slider');
    if($slider_info){
      $slider_image = $slider_info[0]['slider_image'];
      if($slider_image){ unlink("assets/images/slider/".$slider_image); }
    }
    $this->Master_Model->delete_info('slider_id', $slider_id, 'kol_slider');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/slider');
  }


/*********************************** Product *********************************/

  // Add Product....
  public function product(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $save_data = $_POST;

      $product_status = $this->input->post('product_status');
      if(!isset($product_status)){ $product_status = '1'; }
      $save_data['product_status'] = $product_status;

      $product_featured = $this->input->post('product_featured');
      if(!isset($product_featured)){ $product_featured = '0'; }
      $save_data['product_featured'] = $product_featured;

      $save_data['company_id'] = $kol_company_id;
      $save_data['product_addedby'] = $kol_user_id;
      $product_id = $this->Master_Model->save_data('kol_product', $save_data);

      if($_FILES['product_image']['name']){
        $time = time();
        $image_name = 'product_image_'.$product_id.'_'.$time;
        $config['upload_path'] = 'assets/images/product/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['product_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('product_image') && $product_id && $image_name && $ext && $filename){
          $product_image_up['product_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('product_id', $product_id, 'kol_product', $product_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/product');
    }
    $data['main_product_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','product_category_name','ASC','kol_product_category');
    $data['brand_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','brand_name','ASC','kol_brand');
    $data['unit_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','unit_name','ASC','kol_unit');
    $data['gst_slab_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','gst_slab_per','ASC','kol_gst_slab');

    $data['product_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','product_id','DESC','kol_product');
    $data['page'] = 'Product';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/product', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Product...
  public function edit_product($product_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $product_status = $this->input->post('product_status');
      if(!isset($product_status)){ $product_status = '1'; }
      $update_data = $_POST;

      $product_featured = $this->input->post('product_featured');
      if(!isset($product_featured)){ $product_featured = '0'; }
      $update_data['product_featured'] = $product_featured;

      unset($update_data['old_product_image']);
      $update_data['product_status'] = $product_status;
      $update_data['product_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('product_id', $product_id, 'kol_product', $update_data);

      if($_FILES['product_image']['name']){
        $time = time();
        $image_name = 'product_image_'.$product_id.'_'.$time;
        $config['upload_path'] = 'assets/images/product/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['product_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('product_image') && $product_id && $image_name && $ext && $filename){
          $product_image_up['product_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('product_id', $product_id, 'kol_product', $product_image_up);
          if($_POST['old_product_image']){ unlink("assets/images/product/".$_POST['old_product_image']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/product');
    }

    $product_info = $this->Master_Model->get_info_arr('product_id',$product_id,'kol_product');
    if(!$product_info){ header('location:'.base_url().'Master/product'); }
    $data['update'] = 'update';
    $data['update_product'] = 'update';
    $data['product_info'] = $product_info[0];
    $data['act_link'] = base_url().'Master/edit_product/'.$product_id;
    $main_product_category_id = $product_info[0]['product_mcategory_id'];
    $data['main_product_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','1','','','','','product_category_name','ASC','kol_product_category');
    $data['sub_product_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','0','main_product_category_id',$main_product_category_id,'','','product_category_name','ASC','kol_product_category');
    $data['brand_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','brand_name','ASC','kol_brand');
    $data['unit_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','unit_name','ASC','kol_unit');
    $data['gst_slab_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','gst_slab_per','ASC','kol_gst_slab');

    $data['product_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','product_id','DESC','kol_product');
    $data['page'] = 'Edit Product';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/product', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Product...
  public function delete_product($product_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $product_info = $this->Master_Model->get_info_arr_fields('product_image, product_id', 'product_id', $product_id, 'kol_product');
    if($product_info){
      $product_image = $product_info[0]['product_image'];
      if($product_image){ unlink("assets/images/product/".$product_image); }
    }
    $this->Master_Model->delete_info('product_id', $product_id, 'kol_product');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/product');
  }


















/********************************* Tax Rate ***********************************/

  // Add Tax Rate...
  public function tax_rate(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('tax_rate_name', 'tax_rate Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $tax_rate_status = $this->input->post('tax_rate_status');
      if(!isset($tax_rate_status)){ $tax_rate_status = '1'; }

      $save_data = $_POST;
      $save_data['tax_rate_status'] = $tax_rate_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['tax_rate_addedby'] = $kol_user_id;
      $tax_rate_id = $this->Master_Model->save_data('rest_tax_rate', $save_data);

      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/tax_rate');
    }

    $data['tax_rate_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','tax_rate_id','DESC','rest_tax_rate');

    $data['page'] = 'Tax Rate';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/tax_rate', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit Tax Rate...
  public function edit_tax_rate($tax_rate_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('tax_rate_name', 'tax_rate title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $tax_rate_status = $this->input->post('tax_rate_status');
      if(!isset($tax_rate_status)){ $tax_rate_status = '1'; }

      $update_data = $_POST;
      $update_data['tax_rate_status'] = $tax_rate_status;
      $update_data['tax_rate_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('tax_rate_id', $tax_rate_id, 'rest_tax_rate', $update_data);

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/tax_rate');
    }
    $tax_rate_info = $this->Master_Model->get_info_arr('tax_rate_id',$tax_rate_id,'rest_tax_rate');
    if(!$tax_rate_info){ header('location:'.base_url().'Master/tax_rate'); }
    $data['update'] = 'update';
    $data['update_tax_rate'] = 'update';
    $data['tax_rate_info'] = $tax_rate_info[0];
    $data['act_link'] = base_url().'Master/edit_tax_rate/'.$tax_rate_id;

    $data['tax_rate_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','tax_rate_id','DESC','rest_tax_rate');
    $data['page'] = 'Edit Tax Rate';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/tax_rate', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Delete Tax Rate...
  public function delete_tax_rate($tax_rate_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $this->Master_Model->delete_info('tax_rate_id', $tax_rate_id, 'rest_tax_rate');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/tax_rate');
  }

/********************************* Shipping Method ***********************************/

  // Add Shipping Method...
  public function shipping_method(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('shipping_method_name', 'shipping_method Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $shipping_method_status = $this->input->post('shipping_method_status');
      if(!isset($shipping_method_status)){ $shipping_method_status = '1'; }

      $save_data = $_POST;
      $save_data['shipping_method_status'] = $shipping_method_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['shipping_method_addedby'] = $kol_user_id;
      $shipping_method_id = $this->Master_Model->save_data('rest_shipping_method', $save_data);

      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/shipping_method');
    }

    $data['shipping_method_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','shipping_method_id','DESC','rest_shipping_method');

    $data['page'] = 'Shipping Method';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/shipping_method', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit Shipping Method...
  public function edit_shipping_method($shipping_method_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('shipping_method_name', 'shipping_method title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $shipping_method_status = $this->input->post('shipping_method_status');
      if(!isset($shipping_method_status)){ $shipping_method_status = '1'; }

      $update_data = $_POST;
      $update_data['shipping_method_status'] = $shipping_method_status;
      $update_data['shipping_method_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('shipping_method_id', $shipping_method_id, 'rest_shipping_method', $update_data);

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/shipping_method');
    }
    $shipping_method_info = $this->Master_Model->get_info_arr('shipping_method_id',$shipping_method_id,'rest_shipping_method');
    if(!$shipping_method_info){ header('location:'.base_url().'Master/shipping_method'); }
    $data['update'] = 'update';
    $data['update_shipping_method'] = 'update';
    $data['shipping_method_info'] = $shipping_method_info[0];
    $data['act_link'] = base_url().'Master/edit_shipping_method/'.$shipping_method_id;

    $data['shipping_method_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','shipping_method_id','DESC','rest_shipping_method');
    $data['page'] = 'Edit Shipping Method';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/shipping_method', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Delete Shipping Method...
  public function delete_shipping_method($shipping_method_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $this->Master_Model->delete_info('shipping_method_id', $shipping_method_id, 'rest_shipping_method');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/shipping_method');
  }

/********************************* Order Status ***********************************/

  // Add Order Status...
  public function order_status(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' || $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('order_status_name', 'Order Status Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $order_status = $this->input->post('order_status');
      if(!isset($order_status)){ $order_status = '1'; }
      $save_data = $_POST;
      $save_data['order_status_status'] = $order_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['order_status_addedby'] = $kol_user_id;
      $user_id = $this->Master_Model->save_data('rest_order_status', $save_data);

      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/order_status');
    }

    $data['order_status_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','order_status_id','ASC','rest_order_status');
    $data['page'] = 'Order Status';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/order_status', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Order Status...
  public function edit_order_status($order_status_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' || $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('order_status_name', 'Order Status Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $order_status = $this->input->post('order_status');
      if(!isset($order_status)){ $order_status = '1'; }
      $update_data = $_POST;
      $update_data['order_status_status'] = $order_status;
      $update_data['order_status_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('order_status_id', $order_status_id, 'rest_order_status', $update_data);

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/order_status');
    }

    $order_status_info = $this->Master_Model->get_info_arr('order_status_id',$order_status_id,'rest_order_status');
    if(!$order_status_info){ header('location:'.base_url().'Master/order_status'); }
    $data['update'] = 'update';
    $data['update_order_status'] = 'update';
    $data['order_status_info'] = $order_status_info[0];
    $data['act_link'] = base_url().'Master/edit_order_status/'.$order_status_id;

    $data['order_status_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','order_status_id','ASC','rest_order_status');
    $data['page'] = 'Edit Order Status';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/order_status', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Order Status...
  public function delete_order_status($order_status_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' || $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $this->Master_Model->delete_info('order_status_id', $order_status_id, 'rest_order_status');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/order_status');
  }

/********************************* Food Category ***********************************/

  // Add Food Category...
  public function food_category(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('food_category_name', 'food_category title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $food_category_status = $this->input->post('food_category_status');
      if(!isset($food_category_status)){ $food_category_status = '1'; }
      $food_category_offer = $this->input->post('food_category_offer');
      if(!isset($food_category_offer)){ $food_category_offer = '0'; }

      $save_data = $_POST;
      $save_data['food_category_status'] = $food_category_status;
      $save_data['food_category_offer'] = $food_category_offer;
      $save_data['company_id'] = $kol_company_id;
      $save_data['food_category_addedby'] = $kol_user_id;

      $main_food_category_id = $this->input->post('main_food_category_id');
      if($main_food_category_id == '0'){
        $save_data['is_primary'] = 1;
        $save_data['main_food_category_id'] = 0;
      } else{
        $save_data['is_primary'] = 0;
      }
      $food_category_id = $this->Master_Model->save_data('rest_food_category', $save_data);

      if($_FILES['food_category_image']['name']){
        $time = time();
        $image_name = 'food_category_'.$food_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/food_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['food_category_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('food_category_image') && $food_category_id && $image_name && $ext && $filename){
          $food_category_image_up['food_category_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('food_category_id', $food_category_id, 'rest_food_category', $food_category_image_up);
          // unlink("assets/images/tours/".$food_category_image_old);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/food_category');
    }
    $data['main_food_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_primary','1','','','','','food_category_id','DESC','rest_food_category');

    $data['food_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','food_category_id','DESC','rest_food_category');
    $data['page'] = 'Food Category';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/food_category', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit Food Category...
  public function edit_food_category($food_category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('food_category_name', 'food_category title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $food_category_status = $this->input->post('food_category_status');
      if(!isset($food_category_status)){ $food_category_status = '1'; }
      $food_category_offer = $this->input->post('food_category_offer');
      if(!isset($food_category_offer)){ $food_category_offer = '0'; }
      $update_data = $_POST;
      unset($update_data['old_food_category_img']);
      $update_data['food_category_status'] = $food_category_status;
      $update_data['food_category_offer'] = $food_category_offer;
      $update_data['food_category_addedby'] = $kol_user_id;
      $main_food_category_id = $this->input->post('main_food_category_id');
      if($main_food_category_id == '0'){
        $update_data['is_primary'] = 1;
        $update_data['main_food_category_id'] = 0;
      } else{
        $update_data['is_primary'] = 0;
      }
      $this->Master_Model->update_info('food_category_id', $food_category_id, 'rest_food_category', $update_data);

      if($_FILES['food_category_image']['name']){
        $time = time();
        $image_name = 'food_category_'.$food_category_id.'_'.$time;
        $config['upload_path'] = 'assets/images/food_category/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['food_category_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('food_category_image') && $food_category_id && $image_name && $ext && $filename){
          $food_category_image_up['food_category_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('food_category_id', $food_category_id, 'rest_food_category', $food_category_image_up);
          if($_POST['old_food_category_img']){ unlink("assets/images/food_category/".$_POST['old_food_category_img']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/food_category');
    }
    $food_category_info = $this->Master_Model->get_info_arr('food_category_id',$food_category_id,'rest_food_category');
    if(!$food_category_info){ header('location:'.base_url().'Master/food_category'); }
    $data['update'] = 'update';
    $data['update_food_category'] = 'update';
    $data['food_category_info'] = $food_category_info[0];
    $data['act_link'] = base_url().'Master/edit_food_category/'.$food_category_id;
    $data['main_food_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_primary','1','','','','','food_category_id','DESC','rest_food_category');

    $data['food_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','food_category_id','DESC','rest_food_category');
    $data['page'] = 'Edit Food Category';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/food_category', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Delete Food Category...
  public function delete_food_category($food_category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $food_category_info = $this->Master_Model->get_info_arr_fields('food_category_image, food_category_id', 'food_category_id', $food_category_id, 'rest_food_category');
    if($food_category_info){
      $food_category_image = $food_category_info[0]['food_category_image'];
      if($food_category_image){ unlink("assets/images/food_category/".$food_category_image); }
    }
    $this->Master_Model->delete_info('food_category_id', $food_category_id, 'rest_food_category');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/food_category');
  }


/********************************* Food ***********************************/

  // Add Food...
  public function food(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('food_name', 'food title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $food_status = $this->input->post('food_status');
      if(!isset($food_status)){ $food_status = '1'; }
      $food_offer = $this->input->post('food_offer');
      if(!isset($food_offer)){ $food_offer = '0'; }

      $save_data = $_POST;
      $save_data['food_status'] = $food_status;
      $save_data['food_offer'] = $food_offer;
      $save_data['company_id'] = $kol_company_id;
      $save_data['food_addedby'] = $kol_user_id;
      $food_id = $this->Master_Model->save_data('rest_food', $save_data);

      if($_FILES['food_image']['name']){
        $time = time();
        $image_name = 'food_'.$food_id.'_'.$time;
        $config['upload_path'] = 'assets/images/food/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['food_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('food_image') && $food_id && $image_name && $ext && $filename){
          $food_image_up['food_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('food_id', $food_id, 'rest_food', $food_image_up);
          // unlink("assets/images/tours/".$food_image_old);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/food');
    }
    $data['main_food_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_primary','1','','','','','food_category_id','DESC','rest_food_category');
    // print_r($data['main_food_category_list']);
    // $data['food_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','food_id','DESC','rest_food');
    $data['page'] = 'Food';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/food', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit Food...
  public function edit_food($food_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('food_name', 'food title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $food_status = $this->input->post('food_status');
      if(!isset($food_status)){ $food_status = '1'; }
      $food_offer = $this->input->post('food_offer');
      if(!isset($food_offer)){ $food_offer = '0'; }
      $update_data = $_POST;
      unset($update_data['old_food_img']);
      $update_data['food_status'] = $food_status;
      $update_data['food_offer'] = $food_offer;
      $update_data['food_addedby'] = $kol_user_id;

      $this->Master_Model->update_info('food_id', $food_id, 'rest_food', $update_data);

      if($_FILES['food_image']['name']){
        $time = time();
        $image_name = 'food_'.$food_id.'_'.$time;
        $config['upload_path'] = 'assets/images/food/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['food_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('food_image') && $food_id && $image_name && $ext && $filename){
          $food_image_up['food_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('food_id', $food_id, 'rest_food', $food_image_up);
          if($_POST['old_food_img']){ unlink("assets/images/food/".$_POST['old_food_img']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/food');
    }
    $food_info = $this->Master_Model->get_info_arr('food_id',$food_id,'rest_food');
    if(!$food_info){ header('location:'.base_url().'Master/food'); }
    $data['update'] = 'update';
    $data['update_food'] = 'update';
    $data['food_info'] = $food_info[0];
    $data['act_link'] = base_url().'Master/edit_food/'.$food_id;
    $data['main_food_category_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'is_primary','1','','','','','food_category_id','DESC','rest_food_category');

    $data['food_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','food_id','DESC','rest_food');
    $data['page'] = 'Edit Food';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/food', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Delete Food...
  public function delete_food($food_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $food_info = $this->Master_Model->get_info_arr_fields('food_image, food_id', 'food_id', $food_id, 'rest_food');
    if($food_info){
      $food_image = $food_info[0]['food_image'];
      if($food_image){ unlink("assets/images/food/".$food_image); }
    }
    $this->Master_Model->delete_info('food_id', $food_id, 'rest_food');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/food');
  }




/*********************************** Announcement *********************************/

  // Add Announcement....
  public function announcement(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('announcement_name', 'Batch Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $announcement_status = $this->input->post('announcement_status');
      if(!isset($announcement_status)){ $announcement_status = '1'; }
      $save_data = $_POST;
      $save_data['announcement_status'] = $announcement_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['announcement_addedby'] = $kol_user_id;
      $announcement_id = $this->Master_Model->save_data('rest_announcement', $save_data);

      if($_FILES['announcement_image']['name']){
        $time = time();
        $image_name = 'announcement_'.$announcement_id.'_'.$time;
        $config['upload_path'] = 'assets/images/announcement/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['announcement_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('announcement_image') && $announcement_id && $image_name && $ext && $filename){
          $announcement_image_up['announcement_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('announcement_id', $announcement_id, 'rest_announcement', $announcement_image_up);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/announcement');
    }

    $data['announcement_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','announcement_id','DESC','rest_announcement');
    $data['page'] = 'Announcement';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/announcement', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit/Update Announcement...
  public function edit_announcement($announcement_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('announcement_name', 'First Name', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $announcement_status = $this->input->post('announcement_status');
      if(!isset($announcement_status)){ $announcement_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_announcement_img']);
      $update_data['announcement_status'] = $announcement_status;
      $update_data['announcement_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('announcement_id', $announcement_id, 'rest_announcement', $update_data);

      if($_FILES['announcement_image']['name']){
        $time = time();
        $image_name = 'announcement_'.$announcement_id.'_'.$time;
        $config['upload_path'] = 'assets/images/announcement/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['announcement_image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('announcement_image') && $announcement_id && $image_name && $ext && $filename){
          $announcement_image_up['announcement_image'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('announcement_id', $announcement_id, 'rest_announcement', $announcement_image_up);
          if($_POST['old_announcement_img']){ unlink("assets/images/announcement/".$_POST['old_announcement_img']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }

      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/announcement');
    }

    $announcement_info = $this->Master_Model->get_info_arr('announcement_id',$announcement_id,'rest_announcement');
    if(!$announcement_info){ header('location:'.base_url().'Master/announcement'); }
    $data['update'] = 'update';
    $data['update_announcement'] = 'update';
    $data['announcement_info'] = $announcement_info[0];
    $data['act_link'] = base_url().'Master/edit_announcement/'.$announcement_id;

    $data['announcement_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','announcement_id','DESC','rest_announcement');
    $data['page'] = 'Edit Announcement';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/announcement', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  //Delete Announcement...
  public function delete_announcement($announcement_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $announcement_info = $this->Master_Model->get_info_arr_fields('announcement_image, announcement_id', 'announcement_id', $announcement_id, 'rest_announcement');
    if($announcement_info){
      $announcement_image = $announcement_info[0]['announcement_image'];
      if($announcement_image){ unlink("assets/images/announcement/".$announcement_image); }
    }
    $this->Master_Model->delete_info('announcement_id', $announcement_id, 'rest_announcement');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/announcement');
  }


/********************************* Customer ***********************************/

  // Add Customer...
  public function customer(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('customer_name', 'customer title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $customer_status = $this->input->post('customer_status');
      if(!isset($customer_status)){ $customer_status = '1'; }
      $save_data = $_POST;
      $save_data['customer_status'] = $customer_status;
      $save_data['company_id'] = $kol_company_id;
      $save_data['customer_addedby'] = $kol_user_id;
      $customer_id = $this->Master_Model->save_data('rest_customer', $save_data);

      if($_FILES['customer_logo']['name']){
        $time = time();
        $image_name = 'customer_'.$customer_id.'_'.$time;
        $config['upload_path'] = 'assets/images/customer/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['customer_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('customer_logo') && $customer_id && $image_name && $ext && $filename){
          $customer_logo_up['customer_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('customer_id', $customer_id, 'rest_customer', $customer_logo_up);
          // unlink("assets/images/tours/".$customer_logo_old);
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('save_success','success');
      header('location:'.base_url().'Master/customer');
    }
    $data['country_list'] = $this->Master_Model->get_list_by_id3('','','','','','','','country_name','ASC','country');
    // $data['state_list'] = $this->Master_Model->get_list_by_id3('','','','','','','','state_name','ASC','state');
    // $data['city_list'] = $this->Master_Model->get_list_by_id3('','','','','','','','city_name','ASC','city');

    $data['customer_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','customer_id','DESC','rest_customer');
    $data['page'] = 'Customer';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/customer', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit Customer...
  public function edit_customer($customer_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('customer_name', 'customer title', 'trim|required');
    if ($this->form_validation->run() != FALSE) {
      $customer_status = $this->input->post('customer_status');
      if(!isset($customer_status)){ $customer_status = '1'; }
      $update_data = $_POST;
      unset($update_data['old_customer_logo']);
      $update_data['customer_status'] = $customer_status;
      $update_data['customer_addedby'] = $kol_user_id;
      $this->Master_Model->update_info('customer_id', $customer_id, 'rest_customer', $update_data);

      if($_FILES['customer_logo']['name']){
        $time = time();
        $image_name = 'customer_'.$customer_id.'_'.$time;
        $config['upload_path'] = 'assets/images/customer/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name'] = $image_name;
        $filename = $_FILES['customer_logo']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $this->upload->initialize($config); // if upload library autoloaded
        if ($this->upload->do_upload('customer_logo') && $customer_id && $image_name && $ext && $filename){
          $customer_logo_up['customer_logo'] =  $image_name.'.'.$ext;
          $this->Master_Model->update_info('customer_id', $customer_id, 'rest_customer', $customer_logo_up);
          if($_POST['old_customer_logo']){ unlink("assets/images/customer/".$_POST['old_customer_logo']); }
          $this->session->set_flashdata('upload_success','File Uploaded Successfully');
        }
        else{
          $error = $this->upload->display_errors();
          $this->session->set_flashdata('upload_error',$error);
        }
      }
      $this->session->set_flashdata('update_success','success');
      header('location:'.base_url().'Master/customer');
    }
    $customer_info = $this->Master_Model->get_info_arr('customer_id',$customer_id,'rest_customer');
    if(!$customer_info){ header('location:'.base_url().'Master/customer'); }
    $data['update'] = 'update';
    $data['update_customer'] = 'update';
    $data['customer_info'] = $customer_info[0];
    $data['act_link'] = base_url().'Master/edit_customer/'.$customer_id;
    $state_id = $customer_info[0]['state_id'];
    $country_id = $customer_info[0]['country_id'];
    $data['country_list'] = $this->Master_Model->get_list_by_id3('','','','','','','','country_name','ASC','country');
    $data['state_list'] = $this->Master_Model->get_list_by_id3('','country_id',$country_id,'','','','','state_name','ASC','state');
    $data['city_list'] = $this->Master_Model->get_list_by_id3('','state_id',$state_id,'','','','','city_name','ASC','city');

    $data['customer_list'] = $this->Master_Model->get_list_by_id3($kol_company_id,'','','','','','','customer_id','DESC','rest_customer');
    $data['page'] = 'Edit Customer';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/customer', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Delete Customer...
  public function delete_customer($customer_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $customer_info = $this->Master_Model->get_info_arr_fields('customer_logo, customer_id', 'customer_id', $customer_id, 'rest_customer');
    if($customer_info){
      $customer_logo = $customer_info[0]['customer_logo'];
      if($customer_logo){ unlink("assets/images/customer/".$customer_logo); }
    }
    $this->Master_Model->delete_info('customer_id', $customer_id, 'rest_customer');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/customer');
  }


/*****************************************************************************************/
  // Check Duplication
  public function check_duplication(){
    $column_name = $this->input->post('column_name');
    $column_val = $this->input->post('column_val');
    $table_name = $this->input->post('table_name');
    $company_id = '';
    $cnt = $this->Master_Model->check_duplication($company_id,$column_val,$column_name,$table_name);
    echo $cnt;
  }

  // get_sub_testgroup_by_main
  // public function get_sub_testgroup_by_main(){
  //   $test_group_id = $this->input->post('test_group_id');
  //   $test_subgroup_list = $this->Master_Model->get_list_by_id3('','primary_test_group_id',$test_group_id,'test_group_status','1','','','test_group_name','ASC','test_group');
  //   echo "<option value='' selected >Select Test SubGroup</option>";
  //   foreach ($test_subgroup_list as $list) {
  //     echo "<option value='".$list->test_group_id."'> ".$list->test_group_name." </option>";
  //   }
  // }

  // get_state_by_country
  public function get_state_by_country(){
    $country_id = $this->input->post('country_id');
    $state_list = $this->Master_Model->get_list_by_id3('','country_id',$country_id,'','','','','state_name','ASC','state');
    echo "<option value='' selected >Select State</option>";
    foreach ($state_list as $list) {
      echo "<option value='".$list->state_id."'> ".$list->state_name." </option>";
    }
  }

  // get_city_by_state
  public function get_city_by_state(){
    $state_id = $this->input->post('state_id');
    $city_list = $this->Master_Model->get_list_by_id3('','state_id',$state_id,'','','','','city_name','ASC','city');
    echo "<option value='' selected >Select City</option>";
    foreach ($city_list as $list) {
      echo "<option value='".$list->city_id."'> ".$list->city_name." </option>";
    }
  }

  // category_by_type
  public function category_by_type(){
    $food_category_type = $this->input->post('food_category_type');
    $food_category_list = $this->Master_Model->get_list_by_id3('','food_category_type',$food_category_type,'food_category_status','1','','','food_category_name','ASC','rest_food_category');
    echo "<option value='' selected >Select Category</option>";
    foreach ($food_category_list as $list) {
      echo "<option value='".$list->food_category_id."'> ".$list->food_category_name." </option>";
    }
  }











  /********************************* Category Information ***********************************/

  // Add Category...
  public function category(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('category_name', 'category Name', 'trim|required');

      $data['page'] = 'Category';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/category', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit Category...
  public function edit_category($category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('category_name', 'category title', 'trim|required');


    $this->load->view('Admin/Include/head');
    $this->load->view('Admin/Include/navbar');
    $this->load->view('Admin/Master/category');
    $this->load->view('Admin/Include/footer');
  }

  // Delete Category...
  public function delete_category($category_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $this->Master_Model->delete_info('category_id', $category_id, 'rest_category');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/category');
  }




/********************************* Role Information ***********************************/

  // Add Role...
  public function role(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('role_name', 'role Name', 'trim|required');

      $data['page'] = 'Role';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/role', $data);
    $this->load->view('Admin/Include/footer', $data);
  }

  // Edit Role...
  public function edit_role($role_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('role_name', 'role title', 'trim|required');


    $this->load->view('Admin/Include/head');
    $this->load->view('Admin/Include/navbar');
    $this->load->view('Admin/Master/role');
    $this->load->view('Admin/Include/footer');
  }

  // Delete Role...
  public function delete_role($role_id){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }
    $this->Master_Model->delete_info('role_id', $role_id, 'rest_role');
    $this->session->set_flashdata('delete_success','success');
    header('location:'.base_url().'Master/role');
  }



/********************************* Order List Information ***********************************/

  // Add Order List...
  public function order(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('product_name', 'product Name', 'trim|required');

      $data['page'] = 'Order';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/order', $data);
    $this->load->view('Admin/Include/footer', $data);
  }


    /********************************* Customer List Information ***********************************/

  // Add Customer List...
  public function customer_list(){
    $kol_user_id = $this->session->userdata('kol_user_id');
    $kol_company_id = $this->session->userdata('kol_company_id');
    $kol_role_id = $this->session->userdata('kol_role_id');
    if($kol_user_id == '' && $kol_company_id == ''){ header('location:'.base_url().'User'); }

    $this->form_validation->set_rules('product_name', 'product Name', 'trim|required');

      $data['page'] = 'Customer List';
    $this->load->view('Admin/Include/head', $data);
    $this->load->view('Admin/Include/navbar', $data);
    $this->load->view('Admin/Master/customer_list', $data);
    $this->load->view('Admin/Include/footer', $data);
  }



/*************************************************************************************/

  // Get Sub Product Category By Main...
  public function get_sub_by_main_product_category(){
    $kol_company_id = $this->session->userdata('kol_company_id');
    $product_mcategory_id = $this->input->post('product_mcategory_id');

    $sub_product_category_list = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','0','main_product_category_id',$product_mcategory_id,'','','product_category_name','ASC','kol_product_category');

    echo "<option value='' >Select Sub Category</option>";
    foreach ($sub_product_category_list as $list) {
      echo "<option value='".$list->product_category_id."'> ".$list->product_category_name." </option>";
    }
  }

  // Get Sub Blog Category By Main...
  public function get_sub_by_main_blog_category(){
    $kol_company_id = $this->session->userdata('kol_company_id');
    $blog_mcategory_id = $this->input->post('blog_mcategory_id');

    $sub_blog_category_list = $this->Master_Model->get_list_by_id3($kol_company_id,'is_main','0','main_blog_category_id',$blog_mcategory_id,'','','blog_category_name','ASC','kol_blog_category');

    echo "<option value='' >Select Sub Category</option>";
    foreach ($sub_blog_category_list as $list) {
      echo "<option value='".$list->blog_category_id."'> ".$list->blog_category_name." </option>";
    }
  }


}
?>
