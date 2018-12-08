
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
                <a href="<?= base_url('index.php/url');?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Back </a> 
            </div> 
        </div>
         <br>
        <div class="row">
            <div class="col-md-12">
			<?php if (validation_errors()) { ?>
                        <div class="alert alert-danger">
                            <?= validation_errors(); ?>
                        </div> 
                    <?php } ?> 
                <div class="card"> 

                    <form class="form-validate form-horizontal" method="post" action="<?= (isset($user_det)) ? site_url($this->uri->segment(1).'/add_edit/' . $id) : site_url($this->uri->segment(1).'/add_edit/' . $id); ?>" enctype="multipart/form-data">
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="fname" class="col-sm-2 text-left control-label col-form-label">URL Title</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="title" name="title" type="text" required value="" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Long URL</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="long_url" name="long_url" type="text" required value="" />
                                </div>
                            </div>
							
                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Short URL</label>
                                <div class="col-sm-9">
                                    <input class="form-control " id="short_url" type="text" name="short_url" required value="" />
                                </div>
                            </div>
							
							<div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Description</label>
                                <div class="col-sm-9">
									<textarea class="form-control" name="description" id="description" ></textarea>
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
			 
			
		$("#long_url").blur(function() {
			
			var long_url = $(this).val(); 
			$.ajax({
                type: 'POST',
                url: '<?php echo site_url($this->uri->segment(1) . '/get_short_url'); ?>',
                data: {long_url: long_url},
                dataType: 'json',
                success: function (data) {
                    if (data.success === 'yes') {
                        $("#short_url").val(data.short_url);
                    } else {
                         $("#short_url").val('');
                    }
                }
            });
		   
		});
			
			
			
        });
    </script>