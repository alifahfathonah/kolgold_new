<!DOCTYPE html>
<html>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header pt-0 pb-2">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12 text-left mt-2">
            <h4>Slider</h4>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card <?php if(!isset($update)){ echo 'collapsed-card'; } ?>">
              <div class="card-header">
                <h3 class="card-title"> <?php if(isset($update)){ echo 'Update'; } else{ echo 'Add New'; } ?> slider</h3>
                <div class="card-tools">
                  <?php if(!isset($update)){
                    echo '<button type="button" class="btn btn-sm btn-primary" data-card-widget="collapse">Add New</button>';
                  } else{
                    echo '<a href="'.base_url().'Master/slider" type="button" class="btn btn-sm btn-outline-info" >Cancel Update</a>';
                  } ?>
                </div>
              </div>
              <!--  -->
                <div class="card-body p-0 " <?php if(isset($update)){ echo 'style="display: block;"'; } else{ echo 'style="display: none;"'; } ?>>
                  <form class="input_form m-0" id="form_action" role="form" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="row p-4">
                      <div class="form-group col-md-6 offset-md-3 select_sm">
                        <label>Slider Position</label>
                        <select class="form-control select2" name="slider_possition" id="slider_possition" data-placeholder="Select Slider Position" required>
                          <option value="">Select Slider Position</option>
                          <option value="1" <?php if(isset($slider_info) && $slider_info['slider_possition'] == '1'){ echo 'selected'; } ?>>Position 1</option>
                        </select>
                      </div>
                      <div class="form-group col-md-12 ">
                        <label>Enter Slider Name</label>
                        <input type="text" class="form-control form-control-sm" name="slider_name" id="slider_name" value="<?php if(isset($slider_info)){ echo $slider_info['slider_name']; } ?>" placeholder="Enter slider Name" required>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Enter Description</label>
                        <textarea class="form-control form-control-sm" rows="3" name="slider_descr" id="slider_descr" placeholder="Enter Description" ><?php if(isset($slider_info)){ echo $slider_info['slider_descr']; } ?></textarea>
                      </div>

                      <div class="form-group col-md-4">
                        <label>Slider Image</label>
                        <input type="file" class="form-control form-control-sm valid_image" name="slider_image" id="slider_image" >
                          <label>.jpg/.jpeg/.png file. Size less than 500kb.</label>
                      </div>
                      <div class="form-group col-md-4">
                        <?php if(isset($slider_info) && $slider_info['slider_image']){ ?>
                          <img width="150px" src="<?php echo base_url() ?>assets/images/slider/<?php echo $slider_info['slider_image'];  ?>" alt="Slider Image">
                          <input type="hidden" name="old_slider_image" value="<?php echo $slider_info['slider_image']; ?>">
                        <?php } ?>
                      </div>
                    </div>
                    <div class="card-footer clearfix" style="display: block;">
                      <div class="row">
                        <div class="col-md-6 text-left">
                          <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" name="slider_status" id="slider_status" value="0" <?php if(isset($slider_info) && $slider_info['slider_status'] == 0){ echo 'checked'; } ?>>
                            <label for="slider_status" class="custom-control-label">Disable This slider</label>
                          </div>
                        </div>
                        <div class="col-md-6 text-right">
                          <a href="<?php base_url(); ?>slider" class="btn btn-sm btn-default px-4 mx-4">Cancel</a>
                          <?php if(isset($update)){
                            echo '<button class="btn btn-sm btn-primary float-right px-4">Update</button>';
                          } else{
                            echo '<button class="btn btn-sm btn-success float-right px-4">Save</button>';
                          } ?>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
            </div>
          </div>


          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List All slider Information</h3>
              </div>
              <div class="card-body p-2">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th class="d-none">#</th>
                    <th class="wt_50">Action</th>
                    <th>Slider Name</th>
                    <th>Slider Position</th>
                    <th class="wt_50">Image</th>
                    <th class="wt_50">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($slider_list)){
                     $i=0; foreach ($slider_list as $list) { $i++;
                    ?>
                    <tr>
                      <td class="d-none"><?php echo $i; ?></td>
                      <td class="text-center">
                        <div class="btn-slider">
                          <a href="<?php echo base_url() ?>Master/edit_slider/<?php echo $list->slider_id; ?>" type="button" class="btn btn-sm btn-default"><i class="fa fa-edit text-primary"></i></a>
                          <a href="<?php echo base_url() ?>Master/delete_slider/<?php echo $list->slider_id; ?>" type="button" class="btn btn-sm btn-default" onclick="return confirm('Delete this Slider Information');"><i class="fa fa-trash text-danger"></i></a>
                        </div>
                      </td>
                      <td><?php echo $list->slider_name; ?></td>
                      <td><?php echo $list->slider_possition; ?></td>
                      <td class="text-center"><img width="50px" src="<?php echo base_url() ?>assets/images/slider/<?php echo $list->slider_image;  ?>" alt="Slider Image">
                      <td>
                        <?php if($list->slider_status == 0){ echo '<span class="text-danger">Inactive</span>'; }
                          else{ echo '<span class="text-success">Active</span>'; } ?>
                      </td>
                    </tr>
                  <?php } } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

</body>
</html>
