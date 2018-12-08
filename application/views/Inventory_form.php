
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
                <a href="<?= base_url('index.php/inventory'); ?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Back </a> 
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

                    <form class="form-validate form-horizontal" method="post" action="<?= (isset($user_det)) ? site_url($this->uri->segment(1) . '/add_edit/' . $id) : site_url($this->uri->segment(1) . '/add_edit/'); ?>" enctype="multipart/form-data">
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="cono1" class="col-sm-2 text-left control-label col-form-label">Product</label>
                                <div class="col-sm-9">
                                    <select  name="product_id" class="select2 form-control m-t-15" style="height: 36px;width: 100%;">
                                        <option value="">Select Product</option>  
                                        <?php foreach ($products as $val): ?>
                                            <option value="<?= $val['id'] ?>" <?= (isset($user_det) && $val['id'] == $user_det['product_id']) ? 'selected="selected"' : ''; ?>><?= $val['product_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select> 
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lname" class="col-sm-2 text-left control-label col-form-label">Purchased Quantity</label>
                                <div class="col-sm-9">
                                    <input class="form-control " id="purchase_qty" type="number" name="purchase_qty" required value="<?= (isset($user_det['purchase_qty'])) ? $user_det['purchase_qty'] : set_value('purchase_qty'); ?>" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email1" class="col-sm-2 text-left control-label col-form-label">Purchased Price</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="product_qty" name="purchase_price" minlength="5" type="number" required value="<?= (isset($user_det['purchase_price'])) ? $user_det['purchase_price'] : set_value('purchase_price'); ?>" />
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