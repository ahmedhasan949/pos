<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title"><?= str_replace('_', '', ucfirst($this->uri->segment(1))); ?> Form</h3>
                <div class="ml-auto text-left">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= str_replace('_', ' ', ucfirst($this->uri->segment(1))); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>




    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"> 
                <a href="<?= base_url('index.php/user_group'); ?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Back </a> 
            </div> 
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <?php if (validation_errors()) { ?>
                        <div class="alert alert-danger">
                            <?= validation_errors(); ?>
                        </div> 
                    <?php } ?>

                    <form class="form-validate form-horizontal"  method="post" action="<?=  (isset($edit_id)) ? site_url('User_group/add_edit_user/' . $edit_id) : site_url('User_group/add_edit_user'); ?>">
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="fname" class="col-sm-2 text-left control-label col-form-label">Group Name</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="group_name" name="group_name" type="text" required value="<?= (isset($user_grp_det['group_name'])) ? $user_grp_det['group_name'] : set_value('group_name'); ?>" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select  name="is_active" class="select2 form-control"  style="height: 36px;width: 100%;">
                                        <option value="">Select Status</option> 
                                        <option value="1" <?= (isset($user_det['is_active']) && $user_det['is_active'] == 1) ? "selected='selected'" : ''; ?>>Active</option> 
                                        <option value="0" <?= (isset($user_det['is_active']) && $user_det['is_active'] == 0) ? "selected='selected'" : ''; ?>>Inactive</option> 
                                    </select> 
                                </div>
                            </div>
                            
                        </div>
                        <div class="border-top">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>

        </div>
    </div>

    <!--main content end-->

    <script>
        $(function () {
            $(".select2").select2();
        });
    </script>


