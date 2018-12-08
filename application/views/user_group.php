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
                <a href="<?= site_url($this->uri->segment(1).'/add_edit_user') ?>" class="btn btn-success" style="float:right;"><i class="icon_plus_alt2"></i> Add Group</a>  
                <?php } ?>
            </div>
        </div> <br>

        
        
        <div class="row">

            <div class="col-12">  
                <div class="card"> 
                    <div class="card-body">  
                        <div class="table-responsive">
                            <table id="user_group" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="60%">Group Name</th>
                                        <th width="20%"><center>No.Users</center></th>
                                        <th width="10%"><center>Status</center></th>
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

            var group = $('#user_group').DataTable({
                "ajax": "<?= site_url('User_group/view_group') ?>",
                "responsive": true,
                "searching": false,
                "lengthChange": false,
                "columns": [
                    {
                        "data": "group_name",
                    }, {
                        "data": "no_user",
                        "render": function (data, type, full, meta) {
                            return "<center>" + full.no_user + "</center>";
                        }
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