<?php
/*
 * Food Waste Report View
 * Displays pre-made food items wasted via the POS "Add Waste" button.
 * Filtered by a single date. Shows product list and waste units with expandable batch details on row click.
 */
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">

<style>
    /* Main row interactive styling */
    #datatable tbody tr.product-row {
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }
    #datatable tbody tr.product-row:hover {
        background-color: #f6f3fc !important;
    }
    #datatable tbody tr.product-row.details-shown {
        background-color: #eee9f8 !important;
    }
    
    /* Child row resets - prevent parent datatable table/td/span contamination */
    #datatable tr.child-batch-row > td {
        padding: 0 !important;
        border-top: none !important;
        border-bottom: 2px solid #6f42c1 !important;
        background-color: #faf9fd !important;
    }
    #datatable tr.child-batch-row td:first-child span,
    #datatable tr.child-batch-row span,
    .batch-card-wrapper span {
        width: auto !important;
        height: auto !important;
        border-radius: initial !important;
        display: inline !important;
        line-height: inherit !important;
        float: none !important;
    }

    /* Batch Card Container */
    .batch-card-wrapper {
        margin: 10px 16px 16px 16px;
        padding: 16px 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #6f42c1;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(111, 66, 193, 0.08);
        font-family: inherit;
    }

    .batch-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 12px;
        margin-bottom: 14px;
        border-bottom: 1px dashed #cbd5e1;
    }

    .batch-card-header .batch-title {
        font-size: 14px;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .batch-card-header .batch-summary {
        font-size: 13px;
        font-weight: 500;
        color: #4a5568;
    }

    .batch-card-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .batch-card-item {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 12px 16px;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .batch-card-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .batch-col {
        padding: 0 12px;
        box-sizing: border-box;
    }

    .batch-col-badge {
        flex: 0 0 120px;
        text-align: center;
        border-right: 1px solid #e2e8f0;
        padding-right: 14px;
        padding-left: 0;
    }

    .batch-pill {
        display: inline-block;
        background: #6f42c1;
        color: #ffffff;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 12px;
        margin-bottom: 4px;
    }

    .batch-ref-text {
        font-size: 11px;
        color: #475569;
        font-family: monospace;
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
    }

    .batch-col-qty {
        flex: 0 0 130px;
    }

    .batch-col-loss {
        flex: 0 0 130px;
    }

    .batch-col-staff {
        flex: 0 0 170px;
    }

    .batch-col-note {
        flex: 1 1 auto;
    }

    .batch-lbl {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .batch-val {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.3;
    }

    .batch-val-qty {
        color: #dc2626;
        font-size: 14px;
    }

    .batch-val-staff {
        color: #334155;
        font-weight: 500;
    }

    .batch-val-note {
        color: #64748b;
        font-weight: normal;
        font-style: italic;
    }

    /* Chevron rotation */
    .batch-toggle-icon {
        display: inline-block;
        transition: transform 0.25s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .batch-card-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .batch-col {
            padding: 0;
            width: 100%;
        }
        .batch-col-badge {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            width: 100%;
            text-align: left;
        }
    }
</style>

<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header text-left"><?php echo lang('food_waste_report'); ?></h3>
        <input type="hidden" class="datatable_name" data-title="<?php echo lang('food_waste_report'); ?>" data-id_name="datatable">
    </section>

    <div class="my-2">
        <?php if (isLMni() && isset($outlet_id)): ?>
            <h4><?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id)); ?></h4>
        <?php endif; ?>
        <?php if (isset($selected_date) && $selected_date): ?>
            <h4><?php echo lang('report_date'); ?>: <?php echo escape_output(date($this->session->userdata('date_format'), strtotime($selected_date))); ?></h4>
        <?php endif; ?>
    </div>

    <div class="box-wrapper">
        <!-- Filter Form -->
        <div class="row mb-3">
            <?php echo form_open(base_url() . 'Report/foodWasteReport'); ?>

            <!-- Single Date Filter -->
            <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                <div class="form-group">
                    <input tabindex="1" type="text" id="food_waste_date" name="date" readonly
                        class="form-control customDatepicker"
                        placeholder="<?php echo lang('date'); ?>"
                        value="<?php echo isset($selected_date) ? escape_output(date($this->session->userdata('date_format'), strtotime($selected_date))) : ''; ?>">
                </div>
            </div>

            <!-- Outlet Filter (multi-outlet admin only) -->
            <?php if (isLMni()): ?>
                <div class="col-sm-12 col-md-4 col-lg-2 mb-3">
                    <div class="form-group">
                        <select tabindex="2" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
                            <?php
                            $outlets = getAllOutlestByAssign();
                            foreach ($outlets as $outlet_item):
                            ?>
                                <option <?= set_select('outlet_id', $outlet_item->id) ?> value="<?php echo escape_output($outlet_item->id); ?>"><?php echo escape_output($outlet_item->outlet_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Submit -->
            <div class="col-sm-12 col-md-3 col-lg-2 mb-3">
                <div class="form-group">
                    <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100">
                        <?php echo lang('submit'); ?>
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>

        <!-- Tip Banner -->
        <?php if (isset($foodWasteReport) && !empty($foodWasteReport)): ?>
        <div class="alert py-2 px-3 mb-3 d-flex align-items-center" style="background:#f4f0fb; border:1px solid #dcd1f6; color:#572f9b; border-radius:6px; font-size:13px;">
            <i class="fa fa-info-circle mr-2" style="font-size:16px;"></i>
            <span><strong>Tip:</strong> Click on any food item row (e.g. <strong>Samosa</strong>) to view or hide its individual waste batches and details.</span>
        </div>
        <?php endif; ?>

        <!-- Results Table -->
        <div class="table-box">
            <div class="box-body table-responsive">
                <table id="datatable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="ir_w_1"><?php echo lang('sn'); ?></th>
                            <th><?php echo lang('product_list'); ?></th>
                            <th><?php echo lang('waste_unit'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_items = 0;
                        if (isset($foodWasteReport) && !empty($foodWasteReport)):
                            foreach ($foodWasteReport as $key => $row):
                                $total_items++;
                                $batch_count = !empty($row->batches) ? count($row->batches) : 0;
                        ?>
                            <tr class="product-row" style="cursor: pointer;" title="Click to view batches">
                                <td class="ir_txt_center"><?php echo escape_output($total_items); ?></td>
                                <td>
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <div style="font-weight:600; font-size:15px; color:#2d3748;"><?php echo escape_output($row->product_name); ?></div>
                                        <?php if ($batch_count > 1): ?>
                                            <div style="background-color: #6f42c1; color: #fff; font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: 500; display:inline-flex; align-items:center; gap:5px;">
                                                <i class="fa fa-cubes"></i> <?php echo $batch_count; ?> Batches <i class="fa fa-chevron-down batch-toggle-icon"></i>
                                            </div>
                                        <?php elseif ($batch_count == 1): ?>
                                            <div style="background-color: #e2e8f0; color: #475569; font-size: 11px; padding: 4px 10px; border-radius: 12px; font-weight: 500; display:inline-flex; align-items:center; gap:5px;">
                                                1 Batch <i class="fa fa-chevron-down batch-toggle-icon"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Hidden Batch Details Template for DataTables Child Row -->
                                    <div class="batch-details-content" style="display: none;">
                                        <div class="batch-card-wrapper">
                                            <div class="batch-card-header">
                                                <div class="batch-title">
                                                    <i class="fa fa-cubes" style="color: #6f42c1; font-size: 16px;"></i>
                                                    Waste Batches: <strong><?php echo escape_output($row->product_name); ?></strong>
                                                </div>
                                                <div class="batch-summary">
                                                    Total: <strong style="color: #dc2626;"><?php echo escape_output(getAmtPCustom($row->waste_amount)); ?> <?php echo escape_output($row->unit_name); ?></strong> across <?php echo $batch_count; ?> <?php echo $batch_count > 1 ? 'batches' : 'batch'; ?>
                                                </div>
                                            </div>

                                            <div class="batch-card-list">
                                                <?php 
                                                $bIdx = 1;
                                                if (!empty($row->batches)):
                                                    foreach ($row->batches as $b):
                                                ?>
                                                <div class="batch-card-item">
                                                    <div class="batch-col batch-col-badge">
                                                        <div class="batch-pill">Batch #<?php echo $bIdx++; ?></div>
                                                        <div class="batch-ref-text">Ref: <?php echo escape_output($b->reference_no); ?></div>
                                                    </div>
                                                    <div class="batch-col batch-col-qty">
                                                        <div class="batch-lbl">Wasted Qty</div>
                                                        <div class="batch-val batch-val-qty"><?php echo escape_output(getAmtPCustom($b->waste_amount)); ?> <?php echo escape_output($row->unit_name); ?></div>
                                                    </div>
                                                    <div class="batch-col batch-col-loss">
                                                        <div class="batch-lbl">Loss Amount</div>
                                                        <div class="batch-val"><?php echo escape_output(getAmtCustom($b->loss_amount)); ?></div>
                                                    </div>
                                                    <div class="batch-col batch-col-staff">
                                                        <div class="batch-lbl">Responsible Staff</div>
                                                        <div class="batch-val batch-val-staff">
                                                            <i class="fa fa-user-circle text-muted" style="margin-right: 4px;"></i><?php echo escape_output($b->employee_name ? $b->employee_name : 'Staff'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="batch-col batch-col-note">
                                                        <div class="batch-lbl">Note / Reason</div>
                                                        <div class="batch-val batch-val-note"><?php echo escape_output($b->note); ?></div>
                                                    </div>
                                                </div>
                                                <?php 
                                                    endforeach;
                                                else:
                                                ?>
                                                <div class="p-3 text-center text-muted">No batch records found.</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php echo escape_output(getAmtPCustom($row->waste_amount)); ?>
                                    <?php echo $row->unit_name ? escape_output($row->unit_name) : ''; ?>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                    <?php if (isset($foodWasteReport) && !empty($foodWasteReport)): ?>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-right"><?php echo lang('total'); ?>:</th>
                            <th><?php echo escape_output($total_items); ?> <?php echo lang('item'); ?>(s)</th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

</section>

<!-- DataTables Scripts -->
<script src="<?php echo base_url(); ?>assets/datatable_custom/jquery-3.3.1.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/datatable_custom/buttons.print.min.js"></script>
<script>
    if (typeof $.fn.dataTable !== 'undefined') {
        $.fn.dataTable.ext.errMode = 'none';
    }
</script>
<script src="<?php echo base_url(); ?>frequent_changing/newDesign/js/forTable.js"></script>
<script src="<?php echo base_url(); ?>frequent_changing/js/custom_report.js"></script>
<script>
jqry(document).ready(function() {
    var table = jqry('#datatable').DataTable();
    
    // Sort by column 1 (Product List) ascending by default
    table.order([[1, 'asc']]);
    
    // Dynamically assign sequential row numbers (1, 2, 3...) on draw
    table.on('draw.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });
    
    // Redraw to apply the sequential numbers and default sort
    table.draw();

    // Toggle child row batch details on row click
    jqry('#datatable tbody').on('click', 'tr.product-row', function(e) {
        if (jqry(e.target).closest('a').length) {
            return;
        }

        var tr = jqry(this);
        var row = table.row(tr);
        var icon = tr.find('.batch-toggle-icon');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('details-shown');
            icon.css('transform', 'rotate(0deg)');
        } else {
            var batchContent = tr.find('.batch-details-content').html();
            if (batchContent && batchContent.trim() !== '') {
                row.child(batchContent, 'child-batch-row').show();
                tr.addClass('details-shown');
                icon.css('transform', 'rotate(180deg)');
            }
        }
    });
});
</script>
