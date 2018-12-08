<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="<?php echo (!empty($module_det['module_logo'])) ? $module_det['module_logo'] : ''; ?>"></i> <?php echo ($module_det['module_title']) ? $module_det['module_title'] : ''; ?></h3>
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?= site_url('dashboard') ?>>">Home</a></li>
                    <li><i class="<?php echo (!empty($module_det['module_logo'])) ? $module_det['module_logo'] : ''; ?>"></i><?php echo ($module_det['module_title']) ? $module_det['module_title'] : ''; ?></li>
                    <li><i class="fa fa-square-o"></i>View</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <a  class="btn btn-success" data-toggle="modal" data-target="#product_form"><i class="icon_plus_alt2"></i> Add</a> 
                    </div>
                </div>  

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="product" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="60%">Product Name</th>  
                                    <th width="10%"><center>Status</center></th>
                                    <th width="10%">Actions</th>
                            </tr>
                            <thead>
                        </table>
                    </div>  
                </div>  
            </div>
        </div>

        <div class="modal fade" id="product_form" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button> 
                        <h4 class="modal-title">Add Product</h4> 
                    </div>
                    <div class="modal-body" style="background-color: white;">
                        <div class="alert" style="display:none;"></div>    
                        <form class="form-validate form-horizontal" id="module_form" method="post" action="#">
                            <div class="row">
                                <div class="col-lg-12"> 
                                    <div class="panel-body">   

                                        <div class="form-group">
                                            <label for="module_name" class="control-label col-lg-3">Product Name <span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="product_name" name="product_name" minlength="5" type="text" required value="" />
                                            </div>
                                        </div> 

                                        <div class="form-group ">
                                            <label for="confirm_password" class="control-label col-lg-3">Status <span class="required">*</span></label> 
                                            <div class="col-lg-8">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" style="margin-top:13px;">
                                                <input type="hidden" name="product_id" id="product_id" value="">
                                            </div>
                                        </div>  

                                    </div> 
                                </div>
                            </div> 
                        </form> 

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button type="button" class="btn btn-primary submit_modal">Submit</button> 
                    </div>
                </div> 
            </div>
        </div>


        <!-- page end-->
    </section>
</section>
<!--main content end-->
<script>


    $(document).ready(function () {

        var product = $('#product').DataTable({
            "ajax": "<?= site_url('Product/view_list') ?>",
            "responsive": true,
            "searching": false,
            "lengthChange": false,

            "columns": [{
                    "data": "product_name"
                }, {
                    "data": "is_active",
                    "render": function (data, type, full, meta) {
                        if (full.is_active == 0) {
                            return "<center>Block</center>";
                        } else if (full.is_active == 1) {
                            return "<center>Active</center>";
                        }
                    }
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
                                        product.ajax.reload();
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


        $('body').on('click', '.submit_modal', function () {

            var product_id = $('#product_id').val();
            var product_name = $('#product_name').val();
            var is_active = ($('#is_active').is(":checked")) ? 1 : 0;

            $('#product_form').trigger("reset");

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url($this->uri->segment(1) . '/add_edit'); ?>',
                data: {
                    product_name: product_name,
                    is_active: is_active,
                    product_id: product_id,
                },

                dataType: 'json',
                success: function (data) {


                    if (data.success == 'yes') {

                        $('.alert').removeClass('alert-danger');
                        $('.alert').addClass('alert-success');
                        $('.alert').show();
                        $('.alert').html(data.msg);
                        product.ajax.reload();
                        setTimeout(function () {
                            $('.alert').slideUp('slow');
                            $("#product_form").modal("hide");
                        }, 1000);

                    } else if (data.success == 'no') {
                        $('.alert').addClass('alert-danger');
                        $('.alert').show();
                        $('.alert').html(data.msg);
                        setTimeout(function () {
                            $('.alert').slideUp('slow');
                        }, 3000);
                    } else if (data.success == false) {
                        swal('', data.msg, 'error');
                    }

                }
            });
        });

        $('body').on('click', '.add_btn', function () {
            $('#module_form').trigger("reset");
            $('.modal-title').html('Add Module');
            $('#control_div').show();
        });

        $('body').on('click', '.edit_modal', function () {

            var id = ($(this).attr('id')) ? $(this).attr('id') : null;
            $('.modal-title').html('Edit Module');
            $('#control_div').hide();

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url($this->uri->segment(1) . '/get_edit_data'); ?>',
                dataType: 'json',
                data: {
                    id: id
                },

                success: function (data) {

                    $('#product_form').trigger("reset");
                    if (data.success == 'yes') {

                        $('#product_name').val(data.records[0]['product_name']);
                        (data.records[0]['is_active'] == 1) ? $('#is_active').prop('checked', true) : '';
                        $('#product_id').val(data.records[0]['id']);

                    } else if (data.success == false) {
                        swal('', data.msg, 'error');
                    }
                }
            });
        });


    });


</script>