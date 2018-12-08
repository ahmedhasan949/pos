<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="<?php echo (!empty($module_det['module_logo'])) ? $module_det['module_logo'] : ''; ?>"></i> <?php echo ($module_det['module_title']) ? $module_det['module_title'] : ''; ?></h3>
                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="<?= site_url('dashboard') ?>>">Home</a></li>
                    <li><i class="<?php echo (!empty($module_det['module_logo'])) ? $module_det['module_logo'] : ''; ?>"></i>Modules</li>
                    <li><i class="fa fa-square-o"></i>View</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class="panel panel-primary">
            <div class="panel-body"> 
                <div class="row">
                    <?php if ($create_perm) { ?>
                        <div class="col-md-12">
                            <a href="#" class="btn btn-success add_btn"  data-toggle="modal" data-target="#modal_form"><i class="icon_plus_alt2"></i> Add</a>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped" id="modules" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sort Order</th>
                                    <th>Module Title</th>
                                    <th>System Name</th>
                                    <th>System Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            <thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button> 
                        <h4 class="modal-title">Add Module</h4> 
                    </div>
                    <div class="modal-body" style="background-color: white;">
                        <div class="alert" style="display:none;"></div>    
                        <form class="form-validate form-horizontal" id="module_form" method="post" action="#">
                            <div class="row">
                                <div class="col-lg-12"> 
                                    <div class="panel-body">   
                                        <!--                                                <div class="form-group ">  
                                                                                            <label for="parent_module" class="control-label col-lg-3">Parent Module</label>
                                                                                            <div class="col-lg-8">
                                                                                               
                                                                                                <select name="parent_module" class="form-control" id="parent_module" required>
                                                                                                    <option value="0">None</option>
                                        <?php $group_id = (isset($user_det['parent_module'])) ? $user_det['parent_module'] : set_value('parent_module'); ?>
                                        <?php
                                        foreach ($modules as $grp):
                                            ?>
                                                                                                                    <option value="<?= $grp['id'] ?>" <?= ($group_id) ? ($group_id == $grp['id'] ? 'selected' : '') : '' ?>><?= $grp['module_name'] ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>-->
                                        <div class="form-group">
                                            <label for="module_name" class="control-label col-lg-3">Module Name <span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="module_name" name="module_name" minlength="5" type="text" required value="<?= (isset($user_det['module_name'])) ? $user_det['module_name'] : set_value('module_name'); ?>" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="module_title" class="control-label col-lg-3">Module Title<span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input class="form-control " id="module_title" type="text" name="module_title" required value="<?= (isset($user_det['module_title'])) ? $user_det['module_title'] : set_value('module_title'); ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="module_method_location" class="control-label col-lg-3">Method Location <span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="module_method_location" name="module_method_location" minlength="5" type="text" required value="<?= (isset($user_det['module_method_location_no'])) ? $user_det['module_method_location_no'] : set_value('module_method_location'); ?>" />
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="module_logo" class="control-label col-lg-3">Module Logo <span class="required">*</span></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="module_logo" name="module_logo" minlength="5" type="text" required value="<?= (isset($user_det['module_logo'])) ? $user_det['module_logo'] : set_value('module_logo'); ?>" />
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="confirm_password" class="control-label col-lg-3">Status <span class="required">*</span></label>
                                            <?php $status = (isset($module_det['is_active'])) ? $module_det['is_active'] : set_value('is_active'); ?>
                                            <div class="col-lg-8">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" <?= ($module_det) ? ($module_det == 1 ? 'checked' : '') : '' ?> style="margin-top:13px;">
                                                <input type="hidden" name="module_id" id="module_id" value="">
                                            </div>
                                        </div> 

                                        <div class="form-group" id="control_div">
                                            <label for="create_control" class="control-label col-lg-3">Controller <span class="required">*</span></label>

                                            <div class="col-lg-8">
                                                <input type="checkbox" name="controller" id="controller" value="1" style="margin-top:13px;"> 
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


        var modules = $('#modules').DataTable({
            "ajax": "<?= site_url('modules/view_modules') ?>",
            "responsive": true,
            "searching": true,
            "lengthChange": false,
            "columns": [{
                    "data": "sort_order",
                    "render": function (data, type, full, meta) {
                        if (full.sort_order == null || full.sort_order == 0) {
                            return "<center><input type='number' style='text-align:center; width:60px;' min='0'  id='sort_value' value='0' data-id='" + full.id + "'></center>";
                        } else if (full.sort_order != 0) {
                            return "<center><input type='number' style='text-align:center; width:60px;' min='0' id='sort_value' value='" + full.sort_order + "' data-id='" + full.id + "'></center>";
                        }
                    }
                }, {
                    "data": "module_title"
                }, {
                    "data": "module_name"
                }, {
                    "data": "module_method_location"
                }, {
                    "data": "is_active",

                    "render": function (data, type, full, meta) {
                        if (full.is_active == 0) {
                            return "Block";
                        } else if (full.is_active == 1) {
                            return "Active";
                        }
                    }
                }, {
                    "data": "actions"
                }],
            "order": [
                [0, 'desc']
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
                                url: '<?php echo site_url('Modules/delete'); ?>',
                                data: {id: id},
                                dataType: 'json',
                                success: function (data) {
                                    if (data.success === 'yes') {
                                        swal('Record Successfully Deleted', "", "success");
                                        modules.ajax.reload();
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

            var module_name = $('#module_name').val();
            var sort_order = $('#sort_order').val();
            var module_title = $('#module_title').val();
            var module_method_location = $('#module_method_location').val();
            var is_active = ($('#is_active').is(":checked")) ? 1 : 0;
            var controller = ($('#controller').is(":checked")) ? 1 : 0;
            var module_logo = $('#module_logo').val();
            var module_id = ($('#module_id').val()) ? $('#module_id').val() : null;
            $('#modal_form').trigger("reset");
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('Modules/add_module'); ?>',
                data: {
                    module_name: module_name,
                    sort_order: sort_order,
                    module_title: module_title,
                    module_method_location: module_method_location,
                    is_active: is_active,
                    module_logo: module_logo,
                    module_id: module_id,
                    controller: controller
                },

                dataType: 'json',
                success: function (data) {


                    if (data.success == 'yes') {

                        $('.alert').removeClass('alert-danger');
                        $('.alert').addClass('alert-success');
                        $('.alert').show();
                        $('.alert').html(data.msg);
                        modules.ajax.reload();
                        setTimeout(function () {
                            $('.alert').slideUp('slow');
                            $("#modal_form").modal("hide");
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

        $('body').on('change', '#sort_value', function () {
            var sort_val = $(this).val();
            var module_id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('Modules/update_sort_order'); ?>',
                data: {sort_val: sort_val, module_id: module_id},
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        swal(data.msg, "", "success");
                        modules.ajax.reload();
                    } else {
                        swal(data.msg, "", "error");
                    }
                }
            });

        });

        $('body').on('click', '.edit_modal', function () {

            var id = ($(this).attr('id')) ? $(this).attr('id') : null;
            $('.modal-title').html('Edit Module');
            $('#control_div').hide();

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('Modules/add_module'); ?>',
                dataType: 'json',
                data: {
                    id: id
                },

                success: function (data) {
                    $('#module_form').trigger("reset");
                    if (data.success == 'yes') {
                        $('#module_name').val(data.records['module_name']);
                        $('#sort_order').val(data.records['sort_order']);
                        $('#module_title').val(data.records['module_title']);
                        $('#module_method_location').val(data.records['module_method_location']);
                        (data.records['is_active'] == 1) ? $('#is_active').prop('checked', true) : '';
                        $('#module_logo').val(data.records['module_logo']);
                        $('#module_id').val(data.records['id']);

                    } else if (data.success == false) {
                        swal('', data.msg, 'error');
                    }
                }
            });
        });
    });
</script>