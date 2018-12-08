   
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
            <div class="col-md-12"> 
                <?php if($create_perm) { ?>
                <a href="<?= site_url('users/add_user') ?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Add User</a>  
                <?php } ?>
            </div>
        </div> <br>
        <div class="row">

            <div class="col-12">  


                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" href="#active_records" role="tab" data-toggle="tab">Active Records</a></li>
                    <li class="nav-item"><a class="nav-link" href="#deleted_records" role="tab" data-toggle="tab">Trash Records</a></li> 
                </ul>


                <div class="card"> 
                    <div class="card-body">   
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active show" id="active_records">
                                 <div class="table-responsive">
                                    <table id="users" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Join date</th>
                                                
                                                <th>User group</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                 </div> 
                            </div>
                            <!----------------------------------- TABS ---------------------------------->
                            <div role="tabpanel" class="tab-pane fade" id="deleted_records">
                                <div class="table-responsive">
                                    <table id="del_user" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Join date</th> 
                                                <th>User group</th>
                                                <th>Status</th>
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
        </div>

    </div>

</div>
 




<script>




    $(document).ready(function () {


        var users = $('#users').DataTable({
            "ajax": "<?= site_url('users/view_users') ?>",
            "responsive": true,
            "columns": [{
                    "data": "username"
                }, {
                    "data": "join_date"
                },{
                    "data": "group_name"
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
                [1, 'desc']
            ],
        });

        var deleted_user = $('#del_user').DataTable({
            "ajax": "<?= site_url('users/view_trash_records') ?>",
            "responsive": true,
            "columns": [{
                    "data": "username"
                }, {
                    "data": "join_date"
                }, {
                    "data": "group_name"
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
                [1, 'desc']
            ],
        });



        $('body').on('click', '.delete', function () {
            var id = $(this).attr('data-id');

            swal({
                title: "Are you sure?",
                text: "Record will move in Trash !",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
                    function (isConfirm) {
                        if (isConfirm) {

                            $.ajax({
                                type: 'POST',
                                url: '<?php echo site_url('Users/delete'); ?>',
                                data: {id: id},
                                dataType: 'json',
                                success: function (data) {

                                    if (data.success === true) {
                                        swal('Record Successfully Moved to Trash', "", "success");
                                        users.ajax.reload();
                                        deleted_user.ajax.reload();
                                    } else if (data.success === false) {
                                        swal(data.msg, "", "error");
                                    }

                                }
                            });
                        } else {
                            swal("Cancelled", "Delete Cancelled", "error");
                        }
                    });
        });


        $('body').on('click', '.delete_restore', function () {
            var id = $(this).attr('data-id');
            var deleted = $(this).attr('data-val');

            swal({
                title: "Are you sure?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: true,
                closeOnCancel: false
            },
                    function (isConfirm) {
                        if (isConfirm) {

                            $.ajax({
                                type: 'POST',
                                url: '<?php echo site_url('Users/delete_restore'); ?>',
                                data: {id: id, deleted: deleted},
                                dataType: 'json',
                                success: function (data) {
                                    if (data.success == 'yes') {
                                        swal(data.msg, "", "success");
                                        users.ajax.reload();
                                        deleted_user.ajax.reload();
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