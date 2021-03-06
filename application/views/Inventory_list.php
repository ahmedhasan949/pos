<div class="page-wrapper">

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center"> 
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= str_replace('_',' ',ucfirst($this->uri->segment(1))); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div> 

    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12"> 
                <?php if($create_perm) { ?>
                <a href="<?= site_url($this->uri->segment(1).'/add_edit') ?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Add Inventory Item</a>  
                <?php } ?>
            </div>
        </div> <br>

        
        
        <div class="row">

            <div class="col-12">  
                <div class="card"> 
                    <div class="card-body">  
                        <div class="table-responsive">
                            <table id="inventory_tbl" class="table table-striped table-bordered" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th width="40%">Product Name</th>
                                        <th width="15%">Purchased Qty</th>
                                        <th width="15%">Per Item Purchase</th> 
                                        <th width="20%">Purchased Date</th> 
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div> 


                    </div>
                </div>


            </div>    
        </div>    
    </div>    
 
    <!--main content end-->
    <script>


        $(document).ready(function () {

            var group = $('#inventory_tbl').DataTable({
                "ajax": "<?= site_url($this->uri->segment(1).'/view_list') ?>",
                "responsive": true,
                "searching": false,
                "lengthChange": false,
                "columns": [
                    {
                        "data": "product_name",
                    }, {
                        "data": "purchase_qty",                        
                    }, {
                        "data": "purchase_price_per_item", 
                    }, {
                        "data": "purchase_date", 
                    }, {
                        "data": "actions"
                    }],
                "order": [
                    ['1', 'Desc']
                ],
            });


            $('body').on('click', '.delete', function () {
                var id = $(this).attr('data-id');
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this records!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, Cancel!",
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                        function (isConfirm) {
                            if (isConfirm) {
                                $.ajax({
                                    type: 'POST',
                                    url: '<?php echo site_url($this->uri->segment(1) . '/delete'); ?>',
                                    data: {id: id},
                                    dataType: 'json',
                                    success: function (data) {
                                        if (data.success === 'yes') {
                                            swal('Record Successfully Deleted', "", "success");
                                            group.ajax.reload();
                                        } else {
                                            swal(data.msg, "", "error");
                                        }
                                    }
                                });

                            } else {
                                swal("Cancelled", "Delete Cancelled", "error");
                            }
                        });
            });


        });


    </script>