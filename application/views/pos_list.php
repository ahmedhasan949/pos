<style>
    #inventory_tbl {
        width : 100% !important;
    }

    .text-large {
        font-size: 150%;
        font-weight: bold;
    }

    .left-total {
        border-top: 1px dotted black;
        border-right: 1px dotted black;
        border-bottom: 1px dotted black;
        padding-top: 10px;
    }

    .amount-due {
        border-top: 1px dotted black; 
        border-bottom: 1px dotted black;
        padding-top: 10px;
    }

    .active_amount_select {
        background-color: #ca0000;
        border-color : #ca0000;
    }

</style>

<div class="page-wrapper">

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center"> 
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= str_replace('_', ' ', ucfirst($this->uri->segment(1))); ?></li>
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
                        <form class="form-validate form-horizontal" method="POST" action="<?= site_url($this->uri->segment(1) . '/insert_cart'); ?>" id="pos_item_form">
                            <div class="row"> 

                                <div class="col-lg-10 input-group input-group-lg" style="padding:0px !important;">                                
                                    <input type="text" name="pos_item" class="form-control is-valid input-lg" id="validationServer01" placeholder="Enter the Name or Scan Barcode"> 
                                </div> 

                                <div class="col-lg-2" style="padding-left:1px;">
                                    <button type="submit" class="btn btn-lg btn-success">Recieve &nbsp; <i class="fas fa-shopping-cart"></i></button>
                                </div>  

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

        <div class="alert" style="display:none;"></div>
        <div class="row"> 
            <div class="col-8">  
                <div class="card"> 
                    <div class="card-body">  
                        <div class="table-responsive">
                            <table id="inventory_tbl" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="40%">Item</th>
                                        <th width="20%">Price (R.S)</th>
                                        <th width="20%">Qty</th> 
                                        <th width="20%">Sub Total</th> 
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total_amount = 0; ?>
                                    <?php if ($cart = $this->cart->contents()) { ?>

                                        <?php foreach ($cart as $item) { ?> 
                                            <tr>
                                                <td>
                                                    <?= $item['name']; ?>
                                                    <input type="hidden" name="product_id[]" class="product_id" value="<?= $item['id']; ?>">
                                                </td>
                                                <td><?= $item['price']; ?></td>
                                                <td>
                                                    <input class="form-control" id="item_qty" type="number" value="<?= $item['qty']; ?>" />
                                                    <input type="hidden" name="item_qty[]" class="item_qty" value="<?= $item['qty']; ?>">
                                                </td>
                                                <td class="sub_total"><?= number_format($item['price'] * $item['qty'], 2); ?></td>
                                                <td><a class="btn btn-danger delete"><i style="color:#ffff;" class="fas fa-trash"></i></a></td>
                                            </tr>
                                            <?php $total_amount += $item['price'] * $item['qty']; ?>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr id="no_record">
                                            <td colspan="5">No Record Found</td> 
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>  
                    </div>
                </div> 
            </div> 


            <div class="col-4">  
                <div class="card"> 
                    <div class="card-body">   

                        <div class="row"> 

                            <div class="col-lg-12" style="padding:0px;">
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">10</a>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">20</a>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">50</a>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">100</a>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">500</a> <br><br>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">1000</a>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">5000</a>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">10,000</a>
                                <a href="#" class="btn btn-lg btn-info amount_select_btn">15,000</a>
                                <br><br>
                            </div> 


                            <div class="col-lg-6 left-total">
                                <span>Total</span><br><br>
                                <p class="pull-right text-large"><span id="total_amount">Rs. <?= number_format($total_amount, 2); ?></span></p>
                                <input type="hidden" id="total_amount_val" value="<?= (!empty($total_amount)) ? $total_amount : ''; ?>">
                            </div>

                            <div class="col-lg-6 amount-due">
                                <span>Amount Due</span><br><br>
                                <p class="pull-right text-large"><span id="amount_due">Rs. 0.00</span></p>
                            </div>


                            <div class="col-lg-12">
                                <br><br>
                                <span><b>Comments</b></span>
                                <div class="form-group row"> 
                                    <div class="col-sm-12">
                                        <textarea id="comments" class="form-control"></textarea>
                                    </div>
                                </div>  

                                <button type="button" class="btn btn-lg btn-danger" id="empty_cart">Clear All&nbsp; <i class="fas fa-undo-alt"></i></button> 
                                <a href="#" class="btn btn-lg btn-success float-right" data-toggle="modal" data-target="#customer_type">Finish Transaction</a>

                            </div> 

                        </div> 

                    </div>
                </div> 
            </div>  
        </div>   


        <div id="customer_type" class="modal modal-large fade " role="dialog">
            <div class="modal-dialog"> 

                <div class="modal-content">
                    <form class="form-validate form-horizontal" id="customer_info_frm" method="POST" action="<?php echo site_url($this->uri->segment(1) . '/transaction'); ?>" >

                        <div class="modal-header">                          
                            <h4 class="modal-title">Customer Information</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body"> 
                            <div class="row">  
                                <div class="col-md-12">

                                    <div class="form-group row">
                                        <label class="col-sm-4 text-left control-label col-form-label">Customer Name</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" id="customer_name" name="customer_name" type="text" required value="" />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 text-left control-label col-form-label">Mobile #</label>
                                        <div class="col-sm-6">
                                            <input class="form-control" id="customer_mobile" name="customer_mobile" type="number" required value="" />
                                        </div>
                                    </div>

                                </div> 
                            </div> 
                        </div>

                        <div class="modal-footer"> 
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>

                    </form>
                </div> 

            </div>
        </div>


    </div>    

    <!--main content end-->
    <script>


        $(document).ready(function () {

            $('.amount_select_btn').click(function () {

                $('.amount_select_btn').not(this).removeClass('active_amount_select');
                $(this).addClass('active_amount_select');

                var amount_select = $(this).text();
                var total_amount = $('#total_amount_val').val();

                var amount_due = parseInt(amount_select) - parseInt(total_amount);
                $('#amount_due').html("Rs. " + amount_due.toFixed(2));
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
                                    url: '<?php echo site_url($this->uri->segment(1) . '/delete_post_item'); ?>',
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

            $('#empty_cart').click(function (e) {
                $.ajax({
                    type: "POST",
                    datatype: "JSON",
                    url: "<?php echo site_url($this->uri->segment(1) . '/empty_cart'); ?>",
                    success: function (data) {
                        window.location.reload();
                    }
                });
            });


            $("#pos_item_form").submit(function (e) {
                var form = $(this);
                var url = form.attr('action');
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    datatype: "JSON",
                    url: url,
                    data: form.serialize(),
                    success: function (data) {
                        var record = JSON.parse(data);
//                       $('#inventory_tbl tbody').empty();
                        if (record.success == true) {
                            $('#no_record').remove();
                            $('#inventory_tbl tbody').append('<tr>\n\
                                                                <td>' + record.cart_data['name'] + '<input type="hidden" name="product_id[]"  class="product_id" value="' + record.cart_data['id'] + '"></td>\n\
                                                                <td>' + record.cart_data['price'] + '</td>\n\
                                                                <td><input class="form-control" id="item_qty" name="item_qty[]" type="number" value="' + record.cart_data['qty'] + '" /></td>\n\
                                                                <td class="sub_total">' + parseInt(record.cart_data['price'] * record.cart_data['qty']).toFixed(2) + '</td>\n\
                                                                <td><a class="btn btn-danger delete"><i style="color:#ffff;" class="fas fa-trash"></i></a></td>\n\
                                                             </tr>');


                        }

                        var total_amount = 0;
                        $('#inventory_tbl').find(".sub_total").each(function () {
                            var value = $(this).text();
                            total_amount += parseInt(value);
                        });
                        $('#total_amount').html('Rs. ' + total_amount.toFixed(2));
                        $('#total_amount_val').val(total_amount);
                    }
                });

            });


            $("#customer_info_frm").submit(function (se) {

                se.preventDefault();

                var customer_name = $('#customer_name');
                var customer_cell = $('#customer_mobile');
                var form_trans = $(this);

                var total_amount = $('#total_amount_val').val();
                var comments = $('#comments').val();
                var product_ids = [];
                var product_qty = [];

                $('.product_id').each(function () {
                    product_ids.push($(this).val());
                });

                $('.item_qty').each(function () {
                    product_qty.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    datatype: "json",
                    url: form_trans.attr('action'),
                    data: {
                        total_amount: total_amount,
                        comments: comments,
                        product_ids: product_ids,
                        product_qty: product_qty,
                        frm_data: form_trans.serialize()
                    },

                    success: function (resp) {
                        var data = JSON.parse(resp);

                        if (data.success === 'yes') {
                            
                            $('#customer_type').modal('hide');
                            $('.alert').show();
                            $('.alert').addClass('alert-success');
                            $('.alert').html(data.msg);


                            setTimeout(function () {
                                $('.alert').slideUp('slow');
                                window.location.reload();
                            }, 2000); 
                        }
                    }
                });

            });

//            $("#item_qty").change(function (e) {
//                e.preventDefault();
//                var value = $(this).val();
//
//                $.ajax({
//                    type: "POST",
//                    datatype: "JSON",
//                    url: "<?php echo site_url($this->uri->segment(1) . '/update_cart'); ?>",
//                    data: {qty: value},
//                    success: function (data) {
//                        var record = JSON.parse(data);
//                        if (record.success == true) {
//
//                        }
//                    }
//                });
//
//            });

        });


    </script>