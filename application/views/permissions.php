   
<div class="page-wrapper"> 
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center"> 
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= ucfirst($this->uri->segment(1)); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div> 

    <div class="container-fluid"> 

        <div class="row"> 
            <div class="col-12">  
                <div class="card">  
                    <div class="card-body"> 
                        <div class="row col-11">
                            <div class="col-md-2">
                                <a href="#add_permission" data-toggle="modal" class="btn btn-success"><i class="icon_plus_alt2"></i> Add permission</a>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control user-groups">
                                    <option value="0">Select group</option>
                                    <?php
                                    foreach ($groups as $grp):
                                        ?>
                                        <option value="<?= $grp['id'] ?>"><?= $grp['group_name'] ?></option>
                                        <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive"> 
                            <table id="permissions" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Module</th>
                                        <th>View all</th>
                                        <th>View</th>
                                        <th>Create</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div> 
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="add_permission" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <form class="ins_perm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Permission</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" style="background-color: white;">
                        <div class="alert" style="display:none;"></div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="form-group row">
                                            <label for="fname" class="col-sm-3 text-left control-label col-form-label">Select Group</label>
                                            <div class="col-sm-8">
                                                <select  name="group_id"  id="group_id" class="select2 form-control"  style="height: 36px;width: 100%;">
                                                   <option value="">Select group</option>
                                                    <?php
                                                    foreach ($groups as $grp):
                                                        ?>
                                                        <option value="<?= $grp['id'] ?>"><?= $grp['group_name'] ?></option>
                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </select> 
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row show-modules" style="display: none;">
                                            <label class="col-sm-3 text-left control-label col-form-label">Select Module </label>
                                            <div class="col-sm-8">
                                                <select class="select2 form-control" id="module_id" name="module_id">

                                                </select>
                                            </div>
                                        </div>
 
                                        <br>
                                         
                                        <div class="form-group show-perms row" style="display: none;">
                                            <label for="module_id" class="control-label col-lg-3">Select Permissions</label>
                                            <div class="col-lg-8">
                                                <div class="col-lg-3">
                                                    <input type="checkbox" name="view_all_perm" id="view_all_perm" value="1">
                                                    <label for="view_all_perm" class="control-label">View all</label>
                                                </div>
                                                <div class="col-lg-2">
                                                    <input type="checkbox" name="view_perm" id="view_perm" value="1">
                                                    <label for="view_perm" class="control-label">View</label>
                                                </div>
                                                <div class="col-lg-2">
                                                    <input type="checkbox" name="create_perm" id="create_perm" value="1">
                                                    <label for="create_perm" class="control-label">Create</label>
                                                </div>
                                                <div class="col-lg-2">
                                                    <input type="checkbox" name="edit_perm" id="edit_perm" value="1">
                                                    <label for="edit_perm" class="control-label">Edit</label>
                                                </div>
                                                <div class="col-lg-2">
                                                    <input type="checkbox" name="delete_perm" id="delete_perm" value="1">
                                                    <label for="delete_perm" class="control-label">Delete</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer btns-modal" style="display: none;">
                        <button type="button" class="btn btn-primary btn-submit-form">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>







<script>



    $(document).ready(function () {
        var permissions = $('#permissions').DataTable({
            "ajax": {
                "url": "<?= site_url('permissions/view_permissions') ?>",
                "method": "POST",
                "data": function (d) {
                    d.grp_id = $('.user-groups').val()
                }
            },
            "responsive": true,
            "searching": false,
            "lengthChange": false,
            "columns": [{
                    "data": "module_title"
                }, {
                    "data": "view_all_perm",
                    "render": function (data, type, full, meta) {
                        var disabled = (full.protected == 1) ? "disabled" : "";
                        if (full.view_all_perm == 0) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-warning btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="1" data-perm="view_all">OFF</button>' +
                                    '</div>';
                        } else if (full.view_all_perm == 1) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-success btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="0" data-perm="view_all">ON</button>' +
                                    '</div>';
                        }
                    }
                }, {
                    "data": "view_perm",
                    "render": function (data, type, full, meta) {
                        var disabled = (full.protected == 1) ? "disabled" : "";
                        if (full.view_perm == 0) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-warning btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="1" data-perm="view">OFF</button>' +
                                    '</div>';
                        } else if (full.view_perm == 1) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-success btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="0" data-perm="view">ON</button>' +
                                    '</div>';
                        }
                    }
                }, {
                    "data": "create_perm",
                    "render": function (data, type, full, meta) {
                        var disabled = (full.protected == 1) ? "disabled" : "";

                        if (full.create_perm == 0) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-warning btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="1" data-perm="create">OFF</button>' +
                                    '</div>';
                        } else if (full.create_perm == 1) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-success btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="0" data-perm="create">ON</button>' +
                                    '</div>';
                        }
                    }
                }, {
                    "data": "edit_perm",
                    "render": function (data, type, full, meta) {

                        var disabled = (full.protected == 1) ? "disabled" : "";

                        if (full.edit_perm == 0) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-warning ' + disabled + ' btn-perm" data-id="' + full.id + '" data-operation="1" data-perm="edit">OFF</button>' +
                                    '</div>';
                        } else if (full.edit_perm == 1) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-success btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="0" data-perm="edit">ON</button>' +
                                    '</div>';
                        }
                    }
                }, {
                    "data": "delete_perm",
                    "render": function (data, type, full, meta) {
                        var disabled = (full.protected == 1) ? "disabled" : "";

                        if (full.delete_perm == 0) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-warning btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="1" data-perm="delete">OFF</button>' +
                                    '</div>';
                        } else if (full.delete_perm == 1) {
                            return '<div class="col-sm-6 text-center">' +
                                    '<button class="btn btn-success btn-perm" ' + disabled + ' data-id="' + full.id + '" data-operation="0" data-perm="delete">ON</button>' +
                                    '</div>';
                        }
                    }
                }, {
                    "data": "actions"
                }],
            "order": [
                [0, 'desc']
            ],
        });
        //Refresh table with filtered records
        $('.user-groups').on('change', function (e) {
            permissions.ajax.reload();
        });
        //Change permissions
        $('body').on('click', '.btn-perm', function (e) {
            e.preventDefault();
            id = $(this).attr('data-id');
            operation = $(this).attr('data-operation');
            perm = $(this).attr('data-perm');
            $.ajax({
                method: "POST",
                url: "<?= site_url('permissions/change_permission') ?>",
                data: {id: id, opr: operation, perm: perm},
                dataType: "JSON",
                success: function (d) {
                    if (d.success == true) {
                        permissions.ajax.reload();
                    } else
                    {
                        swal('', d.msg, 'error');
                    }
                }
            });
        });
        //Select group to populate modules
        $('#group_id').on('change', function (e) {
            id = $(this).val();
            $.ajax({
                method: "POST",
                url: "<?= site_url('permissions/get_modules') ?>",
                data: {id: id},
                dataType: "JSON",
                success: function (d) {
                    if (d.success == true) {
                        $('.show-modules').show();
                        $('#module_id').empty();
                        $('#module_id').append('<option value="">Select Module</option>');
                        $.each(d.data, function (i, v) {
                            $('#module_id').append('<option value="' + v.id + '">' + v.module_title + '</option>');
                        });
                    }
                }
            });
        });
        //Select Modules to show Permissions
        $('#module_id').on('change', function (e) {
            $('.show-perms').show();
            $('.btns-modal').show();
        });
        //Add permission set
        $('.btn-submit-form').on('click', function (e) {
            $.ajax({
                method: "POST",
                url: "<?= site_url('permissions/add_permission') ?>",
                dataType: "JSON",
                data: $('.ins_perm').serialize(),
                success: function (d) {
                    if (d.success == true)
                    {
                        $('.ins_perm').trigger('reset');
                        $('.show-modules').hide();
                        $('.show-perms').hide();
                        $('.btns-modal').hide();
                        $('#add_permission').modal('toggle');
                        swal('', d.msg, 'success');
                        permissions.ajax.reload();
                    } else
                    {
                        swal('', d.msg, 'error');
                    }
                }
            });

        });


        $('body').on('click', '.delete', function () {
            var id = $(this).attr('data-id');

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this record!",
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
                                url: '<?php echo site_url('Permissions/delete'); ?>',
                                data: {id: id},
                                dataType: 'json',
                                success: function (data) {
                                    if (data.success == 'yes') {
                                        permissions.ajax.reload();
                                        swal('', 'Permission Successfully Deleted', 'success');
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





