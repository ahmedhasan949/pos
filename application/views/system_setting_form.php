 
 
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h3 class="page-title"><?= str_replace('_',' ',ucfirst($this->uri->segment(1))); ?> Form</h3>
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
                <div class="card">

                    <?php if (validation_errors()) { ?>
                        <div class="alert alert-danger">
                            <?= validation_errors(); ?>
                        </div> 
                    <?php } ?>

                    <form class="form-validate form-horizontal" id="d" method="post" action="<?= site_url('System_setting/update_system_setting');?>" enctype="multipart/form-data">
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="fname" class="col-sm-2 text-left control-label col-form-label">Company Name</label>
                                <div class="col-sm-9">
                                     <input class="form-control" id="fullname" name="company_name" required minlength="5" type="text"  value="<?= (isset($system_det['company_name'])) ? $system_det['company_name'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Company Logo</label>
                                <div class="col-sm-9">
                                    <input type="file" name="company_logo">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Timezone</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="email" type="timezone" name="timezone" required value="<?= (isset($system_det['timezone'])) ? $system_det['timezone'] : set_value('email'); ?>" />
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
    
  
<!--main content end-->