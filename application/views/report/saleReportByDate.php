<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">
<style>
.quick-date-btn {
    height: 38px;
    padding: 0 14px;
    border: 1px solid #7367f0;
    background: #fff;
    color: #7367f0;
    border-radius: 0.358rem;
    font-size: 13px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    white-space: nowrap;
    text-decoration: none;
}
.quick-date-btn:hover {
    background: #7367f0;
    color: #fff !important;
    box-shadow: 0 3px 8px rgba(115, 103, 240, 0.3);
}
.quick-date-btn.active {
    background: #7367f0;
    color: #fff !important;
    box-shadow: 0 3px 8px rgba(115, 103, 240, 0.35);
}
.quick-date-btn svg, .quick-date-btn i {
    width: 14px;
    height: 14px;
}

.btn-filter-custom {
    height: 38px;
    padding: 0 20px;
    background-color: #7367f0;
    color: #fff !important;
    border: none;
    border-radius: 0.358rem;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease-in-out;
}
.btn-filter-custom:hover {
    background-color: #5e50ee;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(115, 103, 240, 0.35);
}
.btn-filter-custom svg, .btn-filter-custom i {
    width: 14px;
    height: 14px;
}

.btn-reset-custom {
    height: 38px;
    padding: 0 18px;
    background-color: #f3f2f7;
    color: #6e6b7b !important;
    border: 1px solid #d8d6de;
    border-radius: 0.358rem;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none !important;
    white-space: nowrap;
    transition: all 0.2s ease-in-out;
}
.btn-reset-custom:hover {
    background-color: #e4e2eb;
    color: #4b465c !important;
    border-color: #c0bdd0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.btn-reset-custom svg, .btn-reset-custom i {
    width: 14px;
    height: 14px;
}
.stock-filter-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
}
.stock-filter-item {
    margin-right: 12px;
    margin-bottom: 8px;
}
.stock-filter-item:last-child {
    margin-right: 0;
}
</style>

<section class="main-content-wrapper">
  
    <section class="content-header">
        <h3 class="top-left-header text-left"><?php echo lang('daily_sale_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('daily_sale_report'); ?>" data-id_name="datatable">
    </section>

    <div class="my-2">
        <?php if (!empty($start_date) || !empty($end_date)): ?>
            <h4 class="text-primary mb-2">
                <i data-feather="calendar" style="width: 20px; height: 20px; vertical-align: middle;"></i>
                <?php 
                    $date_fmt = $this->session->userdata('date_format') ? $this->session->userdata('date_format') : 'Y-m-d';
                    if (!empty($start_date) && !empty($end_date) && $end_date != $start_date) {
                        echo lang('report_date') . ": " . date($date_fmt, strtotime($start_date)) . " - " . date($date_fmt, strtotime($end_date));
                    } elseif (!empty($start_date)) {
                        echo lang('report_date') . ": " . date($date_fmt, strtotime($start_date));
                    } elseif (!empty($end_date)) {
                        echo lang('report_date') . ": " . date($date_fmt, strtotime($end_date));
                    }
                ?>
            </h4>
        <?php endif; ?>
        <?php if (isLMni() && isset($outlet_id)): ?>
            <h4 class="txt-color-grey"><?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id)); ?></h4>
        <?php endif; ?>
        <?php if (isset($user_id) && $user_id): ?>
            <h4 class="txt-color-grey"><?php echo lang('user'); ?>: <?php echo userName($user_id); ?></h4>
        <?php endif; ?>
    </div>

    <div class="box-wrapper">
        <div class="row mb-3">
            <?php 
                $todayDate = date('Y-m-d');
                $yesterdayDate = date('Y-m-d', strtotime('-1 day'));
                $isTodayActive = (isset($start_date) && $start_date == $todayDate && isset($end_date) && $end_date == $todayDate);
                $isYesterdayActive = (isset($start_date) && $start_date == $yesterdayDate && isset($end_date) && $end_date == $yesterdayDate);
            ?>
            <?php echo form_open(base_url() . 'Report/saleReportByDate', ['id' => 'saleReportFilterForm']); ?>
            <input type="hidden" name="submit" value="submit">
            <div class="stock-filter-container">

                <!-- Start Date -->
                <div class="stock-filter-item" style="min-width: 140px; flex: 1 1 140px;">
                    <input tabindex="1" type="text" id="startDate" name="startDate" readonly class="form-control customDatepicker"
                        placeholder="<?php echo lang('start_date'); ?>" value="<?= (isset($start_date) && $start_date) ? escape_output($start_date) : '' ?>">
                </div>

                <!-- End Date -->
                <div class="stock-filter-item" style="min-width: 140px; flex: 1 1 140px;">
                    <input tabindex="2" type="text" id="endDate" name="endDate" readonly class="form-control customDatepicker"
                        placeholder="<?php echo lang('end_date'); ?> (Optional)" value="<?= (isset($end_date) && $end_date) ? escape_output($end_date) : '' ?>">
                </div>

                <!-- Quick Date Buttons: Today & Yesterday -->
                <div class="stock-filter-item d-flex align-items-center">
                    <button type="button" class="quick-date-btn <?= ($isTodayActive ? 'active' : '') ?>" onclick="filterQuickDate('<?= $todayDate ?>')" title="Filter strictly for Today" style="margin-right: 6px;">
                        <i data-feather="calendar"></i> Today
                    </button>
                    <button type="button" class="quick-date-btn <?= ($isYesterdayActive ? 'active' : '') ?>" onclick="filterQuickDate('<?= $yesterdayDate ?>')" title="Filter strictly for Yesterday">
                        <i data-feather="clock"></i> Yesterday
                    </button>
                </div>

                <!-- User Filter -->
                <div class="stock-filter-item" style="min-width: 170px; flex: 1 1 170px;">
                    <select tabindex="3" class="form-control select2 ir_w_100" id="user_id" name="user_id">
                        <option value=""><?php echo lang('user'); ?></option>
                        <option value="<?= escape_output($this->session->userdata['user_id']); ?>">
                            <?= escape_output($this->session->userdata['full_name']); ?></option>
                        <?php foreach ($users as $value) { ?>
                            <option value="<?php echo escape_output($value->id) ?>" <?= (isset($user_id) && $user_id == $value->id) ? 'selected' : '' ?>>
                                <?php echo escape_output($value->full_name) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Outlet Filter (multi-outlet admin only) -->
                <?php if (isLMni()): ?>
                    <div class="stock-filter-item" style="min-width: 150px; flex: 1 1 150px;">
                        <select tabindex="4" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
                            <?php
                            $outlets = getAllOutlestByAssign();
                            foreach ($outlets as $value):
                            ?>
                                <option <?= (isset($outlet_id) && $outlet_id == $value->id) ? 'selected' : '' ?> value="<?php echo escape_output($value->id) ?>"><?php echo escape_output($value->outlet_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons: Filter & Reset -->
                <div class="stock-filter-item d-flex align-items-center ms-auto">
                    <button type="submit" name="submit" value="submit" class="btn-filter-custom" style="margin-right: 8px;">
                        <i data-feather="filter"></i> Filter
                    </button>
                    <a href="<?php echo base_url(); ?>Report/saleReportByDate" class="btn-reset-custom" title="Reset All Filters">
                        <i data-feather="refresh-cw"></i> Reset
                    </a>
                </div>

            </div>
            <?php echo form_close(); ?>
        </div>
            
        <div class="table-box">
            <!-- /.box-header -->
            <div class="table-responsive">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th class="ir_w2_txt_center"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('date'); ?></th>
                            <th><?php echo lang('total_sale'); ?></th>
                            <th><?php echo lang('total_refund'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grandTotal = 0;
                        $grandRefund = 0;
                        if (isset($saleReportByDate) && !empty($saleReportByDate)):
                            foreach ($saleReportByDate as $key => $value) {
                                $grandTotal += (float)$value->total_payable;
                                $grandRefund += (float)$value->total_refund;
                                $key++;
                                ?>
                        <tr>
                            <td class="ir_txt_center"><?php echo escape_output($key); ?></td>
                            <td><?= escape_output(date($this->session->userdata('date_format'), strtotime($value->sale_date))) ?>
                            </td>
                            <td><?php echo escape_output(getAmtCustom($value->total_payable)) ?></td>
                            <td><?php echo escape_output(getAmt($value->total_refund)) ?></td>
                        </tr>
                        <?php
                            }
                        endif;
                        ?>
                    </tbody>
                    <?php if (isset($saleReportByDate) && !empty($saleReportByDate)): ?>
                    <tfoot>
                        <tr>
                            <th class="ir_txt_center"></th>
                            <th><strong>Total</strong></th>
                            <th><strong><?php echo escape_output(getAmtCustom($grandTotal)); ?></strong></th>
                            <th><strong><?php echo escape_output(getAmt($grandRefund)); ?></strong></th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    
</section>

<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
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

<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>

<script>
    if (typeof $.fn.dataTable !== 'undefined') {
        $.fn.dataTable.ext.errMode = 'none';
    }

    function filterQuickDate(dateStr) {
        var startInput = document.getElementById('startDate');
        var endInput = document.getElementById('endDate');
        var form = document.getElementById('saleReportFilterForm');
        if (startInput) startInput.value = dateStr;
        if (endInput) endInput.value = dateStr;
        if (form) {
            form.submit();
        }
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        if (typeof $.fn.datepicker !== 'undefined') {
            $('.customDatepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        }
    });
</script>