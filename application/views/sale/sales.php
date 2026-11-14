<base data-base="<?php echo base_url(); ?>">
</base>
<input type="hidden" id="warning" value="<?php echo lang('alert'); ?>">
<input type="hidden" id="a_error" value="<?php echo lang('error'); ?>">
<input type="hidden" id="ok" value="<?php echo lang('ok'); ?>">
<input type="hidden" id="cancel" value="<?php echo lang('cancel'); ?>">
<input type="hidden" id="view_invoice" value="<?php echo getPOSChecker("123","view_invoice"); ?>">
<input type="hidden" id="change_date" value="<?php echo getPOSChecker("123","change_date"); ?>">
<input type="hidden" id="change_delivery_address" value="<?php echo getPOSChecker("123","change_delivery_address"); ?>">
<input type="hidden" id="menu_not_permit_access" value="<?php echo lang('menu_not_permit_access'); ?>">
<input type="hidden" id="status_changed_successfully" value="<?php echo lang('status_changed_successfully'); ?>">
<input type="hidden" id="filter_date" value="<?php echo isset($filter_date) && $filter_date ? escape_output($filter_date) : ''; ?>">
<section class="main-content-wrapper">

    <?php
if ($this->session->flashdata('exception')) {
    echo '<section class="alert-wrapper"><div class="alert alert-success alert-dismissible fade show"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <div class="alert-body">
    <p><i class="m-right fa fa-check"></i>';
    echo escape_output($this->session->flashdata('exception'));unset($_SESSION['exception']);
    echo '</p></div></div></section>';
}
?>
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12 col-md-10">
                <h2 class="top-left-header"><?php echo lang('sale'); ?> </h2>
            </div>
            <?php if(isServiceAccessOnlyLogin('sGmsJaFJE')): ?>
            <div class="col-sm-12 col-md-2">

            </div>
                <?php else:
                $export_daily_sale = $this->session->userdata('export_daily_sale');
                if($export_daily_sale && $export_daily_sale=="enable"): ?>
            <div class="col-md-2 text-end">
                <a href="<?php echo base_url() ?>Sale/exportDailySales"  data-access="exportDailySales-123" class="btn_list m-right btn bg-blue-btn delete menu_assign_class"><?php echo lang('exportDailySales'); ?></a>
            </div>
            <?php else: ?>
            <div class="col-md-2">

            </div>

            <?php endif; endif; ?>
        </div>
    </section>

    <div class="box-wrapper">
        <?php if(isset($filter_date) && $filter_date): ?>
        <div class="alert py-2 px-3 mb-3 d-flex justify-content-between align-items-center" style="background: #eef2ff; border: 1px solid #c7d2fe; color: #3730a3; border-radius: 6px;">
            <div style="font-size: 14px; font-weight: 500;">
                <i class="fa fa-calendar mr-2" style="font-size: 15px;"></i>
                Showing sales for: <strong><?php echo escape_output(date($this->session->userdata('date_format'), strtotime($filter_date))); ?></strong>
            </div>
            <a href="<?php echo base_url(); ?>Sale/sales?clear_filter=1" class="btn btn-sm btn-danger" style="border-radius: 4px; font-size: 12px; padding: 4px 12px; text-decoration: none; color: #fff;">
                <i class="fa fa-times mr-1"></i> View All Sales
            </a>
        </div>
        <?php endif; ?>
        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                            <th class="ir_w_8"><?php echo lang('sale_no'); ?></th>
                            <th class="ir_w_8"><?php echo lang('order_type'); ?></th>
                            <th class="ir_w_12"><?php echo lang('date'); ?>(<?php echo lang('time'); ?>)</th>
                            <th class="ir_w_10"><?php echo lang('customer'); ?> (<?php echo lang('phone'); ?>)</th>
                            <th class="ir_w_8"><?php echo lang('total_payable'); ?></th>
                            <th class="ir_w_10"><?php echo lang('refund_amount'); ?></th>
                            <th class="ir_w_20"><?php echo lang('payment_method'); ?></th>
                            <th class="ir_w_10"><?php echo lang('added_by'); ?></th>
                            <th class="ir_w5_txt_center not-export-col"><?php echo lang('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>

</section>


<!-- Modal -->
<div class="modal fade" id="change_date_modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('change_date'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="sale_id_hidden" id="sale_id_hidden">
                <input name="change_date_sale" placeholder="<?php echo lang('date')?>" id="change_date_sale_modal"
                    class="ir_w100_height35x">
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn bg-blue-btn"
                    id="save_change_date"><?php echo lang('save_changes'); ?></button>
                <button type="button" class="btn bg-red-btn" data-bs-dismiss="modal"
                    id="close_change_date_modal"><?php echo lang('close'); ?></button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="change_delivery_address_update" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('change_delivery_address'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="sale_id_hidden_d" id="sale_id_hidden_d">
                    <select class="form-control select2" name="status" id="status">
                        <option value="Pending"><?php echo lang('Pending'); ?></option>
                        <option value="Delivered"><?php echo lang('Delivered'); ?></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-blue-btn"
                        id="save_change_status"><?php echo lang('save_changes'); ?></button>
                <button type="button" class="btn bg-red-btn" data-bs-dismiss="modal"
                        id="close_change_date_modal"><?php echo lang('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change_delivery_address" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo lang('change_delivery_address'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            <i data-feather="x"></i>
        </button>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <input type="hidden" name="sale_id_hidden_d" id="sale_id_hidden_d">
                <select class="form-control select2" name="status" id="status">
                    <option value="Pending"><?php echo lang('Pending'); ?></option>
                    <option value="Delivered"><?php echo lang('Delivered'); ?></option>
                </select>
          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn bg-blue-btn"
                    id="save_change_status"><?php echo lang('save_changes'); ?></button>
                <button type="button" class="btn bg-red-btn" data-bs-dismiss="modal"
                    id="close_change_date_modal"><?php echo lang('close'); ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="refund_modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="" id="exampleModalLabel"><?php echo lang('refund_items'); ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th><?php echo lang('food_menu'); ?>(<?php echo lang('code'); ?>)</th>
                            <th><?php echo lang('quantity'); ?></th>
                            <th><?php echo lang('price'); ?></th>
                            <th><?php echo lang('vat'); ?></th>
                            <th><?php echo lang('discount'); ?></th>
                            <th><?php echo lang('refund_qty'); ?></th>
                            <th><?php echo lang('refund_amount'); ?></th>
                        </tr>
                        </thead>
                        <tbody id="sale_refund_cart">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-red-btn" data-bs-dismiss="modal"
                ><?php echo lang('close'); ?></button>
            </div>
        </div>
    </div>
</div>


<!-- <script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script> -->
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js">
</script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/POS/js/jquery.cookie.js"></script>
<script>
var salePageFilterDate = '<?php echo isset($filter_date) && $filter_date ? escape_output($filter_date) : ''; ?>';
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>frequent_changing/js/sale.js?v=<?php echo time(); ?>"></script>