
<style>

</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title"><?= ucfirst($this->uri->segment(1)); ?> Form</h3>
                <div class="ml-auto text-left">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= ucfirst($this->uri->segment(1)); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>




    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"> 
                <a href="<?= base_url('index.php/users');?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Back </a> 
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

                    <form class="form-validate form-horizontal" method="post" action="<?= (isset($user_det)) ? site_url('users/add_user/' . $id) : site_url('users/add_user'); ?>" enctype="multipart/form-data">
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="fname" class="col-sm-2 text-left control-label col-form-label">Full Name</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="fullname" name="fullname" minlength="5" type="text" required value="<?= (isset($user_det['fullname'])) ? $user_det['fullname'] : set_value('fullname'); ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Designation</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="designation" name="designation" type="text" required value="<?= (isset($user_det['designation'])) ? $user_det['designation'] : set_value('designation'); ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input class="form-control " id="email" type="email" name="email" required value="<?= (isset($user_det['email'])) ? $user_det['email'] : set_value('email'); ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email1" class="col-sm-2 text-left control-label col-form-label">Primary Contact</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="contact" name="contact" minlength="5" type="number" required value="<?= (isset($user_det['contact_no'])) ? $user_det['contact_no'] : set_value('contact'); ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Picture</label>
                                <div class="col-sm-9">
                                    <input type="file" name="profile_picture">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Group</label>
                                <div class="col-sm-9">
                                    <select  name="group[]" class="select2 form-control m-t-15" multiple="multiple" style="height: 36px;width: 100%;">
                                        <option value="">Select Group</option> 
                                        <?php $selected_grop = (isset($selected_grop)) ? $selected_grop : array(); ?>
                                        <?php foreach ($groups as $grp): ?>
                                            <option value="<?= $grp['id'] ?>" <?= (in_array($grp['id'], $selected_grop)) ? 'selected="selected"' : ''; ?>><?= $grp['group_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select> 
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

                            <br>
                            <hr><br>
                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Username</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="username" name="username" minlength="5" <?= (isset($user_det['username'])) ? 'disabled' : ''; ?> type="text" required value="<?= (isset($user_det['username'])) ? $user_det['username'] : set_value('username'); ?>"> 
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input class="form-control " id="password" type="password" name="password" <?= (isset($user_det['password'])) ? '' : 'required'; ?> />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Confirm Password</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="confirm_password" name="confirm_password" minlength="5" type="password" <?= (isset($user_det['password'])) ? '' : 'required'; ?> />
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