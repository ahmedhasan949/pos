
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
                <a href="<?= base_url('index.php/product');?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Back </a> 
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

                    <form class="form-validate form-horizontal" method="post" action="<?= (isset($user_det)) ? site_url($this->uri->segment(1).'/add_edit/' . $id) : site_url($this->uri->segment(1).'/add_edit/'); ?>" enctype="multipart/form-data">
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="fname" class="col-sm-2 text-left control-label col-form-label">Product Name</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="fullname" name="product_name" minlength="5" type="text" required value="<?= (isset($user_det['product_name'])) ? $user_det['product_name'] : set_value('product_name'); ?>" />
                                </div>
                            </div>
                           <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <select  name="product_category" class="select2 form-control m-t-15" style="height: 36px;width: 100%;">
                                        <option value="">Select Category</option> 
                                        
                                        <?php foreach ($category as $val): ?>
                                            <option value="<?= $val['id'] ?>" <?= (isset($user_det) && $val['id'] == $user_det['product_category']) ? 'selected="selected"' : ''; ?>><?= $val['category_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Selling Price</label>
                                <div class="col-sm-9">
                                    <input class="form-control " id="product_sell_price" type="number" name="product_sell_price" required value="<?= (isset($user_det['product_sell_price'])) ? $user_det['product_sell_price'] : set_value('product_sell_price'); ?>" />
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Picture</label>
                                <div class="col-sm-9">
                                    <input type="file" name="product_picture">
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