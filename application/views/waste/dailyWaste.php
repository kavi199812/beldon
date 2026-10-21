<!-- Font Awesome 6 & 4 Compatibility -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">

<style>
    .daily-waste-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
    }
    .daily-waste-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        color: #fff;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .daily-waste-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
    }
    .daily-waste-header p {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: #cbd5e1;
    }
    .stock-badge {
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 20px;
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 600;
        display: inline-block;
    }
    .stock-badge-empty {
        background: #f1f5f9;
        color: #64748b;
    }
    .kept-badge {
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
        display: inline-block;
        transition: all 0.2s ease;
    }
    .kept-safe {
        background: #dcfce7;
        color: #15803d;
    }
    .kept-warn {
        background: #fef3c7;
        color: #b45309;
    }
    .kept-danger {
        background: #fee2e2;
        color: #b91c1c;
    }
    .quick-qty-btn {
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        transition: all 0.15s;
    }
    .quick-qty-btn:hover {
        background: #0284c7;
        color: #fff;
        border-color: #0284c7;
    }
    .quick-qty-all:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }
    .waste-qty-input {
        width: 100px;
        font-weight: 700;
        text-align: right;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        padding: 5px 8px;
        font-size: 15px;
    }
    .waste-qty-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
        outline: none;
    }
    .waste-qty-input:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
    }
    .summary-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
    }
    .summary-box .title {
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .summary-box .value {
        font-size: 24px;
        font-weight: 800;
        margin-top: 5px;
        color: #0f172a;
    }
    .summary-box.loss-box {
        background: #fef2f2;
        border-color: #fecaca;
    }
    .summary-box.loss-box .value {
        color: #dc2626;
    }
    .table-custom-waste th {
        background: #f1f5f9;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 2px solid #cbd5e1;
    }
    .table-custom-waste td {
        padding: 12px 14px;
        vertical-align: middle !important;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .btn-return-pos {
        background: #0284c7;
        color: #fff !important;
        font-weight: 600;
        border-radius: 6px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        transition: background 0.2s;
    }
    .btn-return-pos:hover {
        background: #0369a1;
        color: #fff;
    }
    .add-extra-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 18px 20px;
        margin-top: 20px;
    }
</style>

<section class="main-content-wrapper">
    <div class="daily-waste-card">
        <!-- Header -->
        <div class="daily-waste-header">
            <div>
                <h2>
                    <i class="fa fa-trash-alt fa-trash"></i> Daily Leftover & Manual Waste
                </h2>
                <p>Record leftover food to discard. Any un-wasted quantity remains safely kept in stock for tomorrow.</p>
            </div>
            <div>
                <a href="<?php echo base_url('Sale/POS'); ?>" class="btn-return-pos">
                    <i class="fa fa-arrow-left"></i> Back to POS
                </a>
            </div>
        </div>

        <div style="padding: 24px;">
            <!-- Form Details -->
            <form id="daily_waste_form" autocomplete="off">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_token_waste">

                <div class="row mb-3">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Reference No</label>
                            <input type="text" class="form-control" name="reference_no" value="<?php echo escape_output($ref_no); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control customDatepicker" name="date" id="waste_date" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Responsible Person <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="employee_id" id="employee_id" required>
                                <?php foreach ($employees as $emp): ?>
                                    <option value="<?php echo escape_output($emp->id); ?>" <?php echo ($emp->id == $current_user_id) ? 'selected' : ''; ?>>
                                        <?php echo escape_output($emp->full_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="form-group">
                            <label class="form-label font-weight-bold">Note / Reason</label>
                            <input type="text" class="form-control" name="note" id="waste_note" placeholder="End of day food waste" value="Daily Leftover Food Waste">
                        </div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info d-flex align-items-center" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px; padding: 12px 18px;">
                    <i class="fa fa-info-circle mr-3" style="font-size: 20px; color: #22c55e;"></i>
                    <div>
                        <strong>Notice:</strong> Below are pre-made items in stock. Enter the quantity you want to discard. Any un-wasted stock remains safe for tomorrow.
                    </div>
                </div>

                <!-- Main Waste Table -->
                <div class="table-responsive mt-3">
                    <table class="table table-custom-waste" id="waste_items_table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 200px;">Item Name & Code</th>
                                <th style="width: 140px; text-align: center;">Current Stock</th>
                                <th style="width: 220px; text-align: center;">Waste Qty</th>
                                <th style="width: 170px; text-align: center;">Kept for Tomorrow</th>
                                <th style="width: 130px; text-align: right;">Unit Cost</th>
                                <th style="width: 140px; text-align: right;">Loss Amount</th>
                                <th style="width: 60px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="waste_tbody">
                            <?php if (!empty($pre_made_items)): ?>
                                <?php $idx = 1; foreach ($pre_made_items as $item): ?>
                                    <tr class="waste-row" data-id="<?php echo escape_output($item['id']); ?>" data-stock="<?php echo escape_output($item['stock_qty']); ?>" data-unit="<?php echo escape_output($item['unit_name']); ?>" data-price="<?php echo escape_output($item['unit_price']); ?>">
                                        <td><?php echo $idx++; ?></td>
                                        <td>
                                            <div class="font-weight-bold text-dark font-15"><?php echo escape_output($item['name']); ?></div>
                                            <small class="text-muted"><i class="fa fa-barcode"></i> <?php echo escape_output($item['code']); ?></small>
                                            <input type="hidden" name="ingredient_id[]" value="<?php echo escape_output($item['id']); ?>">
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($item['stock_qty'] > 0): ?>
                                                <span class="stock-badge">
                                                    <i class="fa fa-cube fa-cubes"></i> <?php echo escape_output($item['stock_qty']) . ' ' . escape_output($item['unit_name']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="stock-badge stock-badge-empty">
                                                    0 <?php echo escape_output($item['unit_name']); ?> (Out of Stock)
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="d-flex align-items-center justify-content-center" style="gap: 5px;">
                                                <input type="number" class="waste-qty-input item-waste-qty" step="any" min="0" max="<?php echo escape_output($item['stock_qty']); ?>" value="0" placeholder="0" <?php echo ($item['stock_qty'] <= 0) ? 'disabled' : ''; ?>>
                                                <?php if ($item['stock_qty'] > 0): ?>
                                                    <div class="d-flex flex-column" style="gap: 2px;">
                                                        <button type="button" class="quick-qty-btn quick-qty-all" title="Waste All remaining stock" onclick="setWasteQty(this, 'all')">All</button>
                                                        <button type="button" class="quick-qty-btn" title="Waste half of remaining stock" onclick="setWasteQty(this, 'half')">1/2</button>
                                                        <button type="button" class="quick-qty-btn" title="Clear to 0" onclick="setWasteQty(this, 'clear')">0</button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if ($item['stock_qty'] > 0): ?>
                                                <span class="kept-badge kept-safe item-kept-badge">
                                                    <i class="fa fa-shield"></i> <?php echo escape_output($item['stock_qty']) . ' ' . escape_output($item['unit_name']); ?> (Kept Safe)
                                                </span>
                                            <?php else: ?>
                                                <span class="kept-badge kept-warn item-kept-badge">
                                                    0 <?php echo escape_output($item['unit_name']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: right; font-weight: 600;">
                                            <?php echo getAmtP($item['unit_price']); ?>
                                            <input type="hidden" class="item-unit-price" value="<?php echo escape_output($item['unit_price']); ?>">
                                        </td>
                                        <td style="text-align: right; font-weight: 700; color: #dc2626;">
                                            <span class="item-loss-text"><?php echo getAmtP(0); ?></span>
                                            <input type="hidden" class="item-loss-val" value="0">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" title="Reset quantity to 0" onclick="setWasteQty(this, 'clear')">
                                                <i class="fa fa-undo"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="no_premade_row">
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fa fa-box-open fa-cube font-24 mb-2 d-block text-warning"></i>
                                        No Pre-Made food items currently in stock (> 0). You can select and add other items below.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Extra Ingredient Section -->
                <div class="add-extra-box">
                    <div class="row align-items-center">
                        <div class="col-md-8 col-sm-12 mb-2">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold mb-1">
                                    <i class="fa fa-plus-circle text-success"></i> Add Other Ingredient / Item
                                </label>
                                <select class="form-control select2" id="extra_ingredient_select">
                                    <option value="">-- Select Ingredient / Food Item --</option>
                                    <?php if (!empty($all_ingredients)): ?>
                                        <?php foreach ($all_ingredients as $ing): ?>
                                            <option value="<?php echo escape_output($ing->id); ?>">
                                                <?php echo escape_output($ing->code) . ' - ' . escape_output($ing->name); ?> (<?php echo escape_output($ing->unit_name); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-2" style="margin-top: 22px;">
                            <button type="button" class="btn btn-success btn-block" id="btn_add_extra_item" style="height: 40px; font-weight: 600;">
                                <i class="fa fa-plus"></i> Add to Waste List
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Row -->
                <div class="row mt-4">
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="summary-box">
                            <div class="title">Items to Waste</div>
                            <div class="value" id="summary_items_count">0</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="summary-box">
                            <div class="title">Total Quantity</div>
                            <div class="value" id="summary_total_qty">0.00</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="summary-box loss-box">
                            <div class="title">Total Financial Loss</div>
                            <div class="value" id="summary_total_loss"><?php echo getAmtP(0); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Submit / Cancel Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 2px solid #e2e8f0; flex-wrap: wrap; gap: 15px;">
                    <div>
                        <a href="<?php echo base_url('Sale/POS'); ?>" class="btn btn-secondary btn-lg" style="font-weight: 600;">
                            <i class="fa fa-times"></i> Cancel & Return to POS
                        </a>
                    </div>
                    <div>
                        <button type="button" id="btn_save_daily_waste" class="btn btn-danger btn-lg px-4" style="font-weight: 700; background: #dc2626; border-color: #b91c1c;">
                            <i class="fa fa-check mr-1"></i> Confirm & Save Waste
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Page Javascript -->
<script type="text/javascript">
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({ width: '100%' });
        }

        recalculateSummary();

        // Listen for input change on waste quantity
        $(document).on('input keyup change', '.item-waste-qty', function() {
            var row = $(this).closest('tr');
            handleQtyChange(row);
            recalculateSummary();
        });

        // Add extra ingredient via Ajax
        $('#btn_add_extra_item').on('click', function() {
            var ingId = $('#extra_ingredient_select').val();
            if (!ingId) {
                swal('Notice', 'Please select an ingredient or item from the dropdown.', 'warning');
                return;
            }

            // Check if already in table
            var exists = false;
            $('#waste_tbody tr.waste-row').each(function() {
                if ($(this).attr('data-id') == ingId) {
                    exists = true;
                    return false;
                }
            });

            if (exists) {
                swal('Already Added', 'This item is already in the waste table.', 'info');
                return;
            }

            var csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var csrfHash = $('#csrf_token_waste').val();
            var postData = { ingredient_id: ingId };
            postData[csrfTokenName] = csrfHash;

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Adding...');

            $.ajax({
                url: '<?php echo base_url(); ?>Waste/getIngredientStockAjax',
                type: 'POST',
                dataType: 'json',
                data: postData,
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa fa-plus"></i> Add to Waste List');
                    if (res && res.status === 'success') {
                        $('#no_premade_row').remove();

                        var rowCount = $('#waste_tbody tr.waste-row').length + 1;
                        var stock = parseFloat(res.stock_qty) || 0;
                        var price = parseFloat(res.unit_price) || 0;
                        var unit = res.unit_name || 'unit';

                        var trHtml = '<tr class="waste-row" data-id="' + res.id + '" data-stock="' + stock + '" data-unit="' + unit + '" data-price="' + price + '">' +
                            '<td>' + rowCount + '</td>' +
                            '<td>' +
                                '<div class="font-weight-bold text-dark font-15">' + res.name + '</div>' +
                                '<small class="text-muted"><i class="fa fa-barcode"></i> ' + res.code + '</small>' +
                                '<input type="hidden" name="ingredient_id[]" value="' + res.id + '">' +
                            '</td>' +
                            '<td style="text-align: center;">' +
                                '<span class="stock-badge"><i class="fa fa-cube fa-cubes"></i> ' + stock + ' ' + unit + '</span>' +
                            '</td>' +
                            '<td style="text-align: center;">' +
                                '<div class="d-flex align-items-center justify-content-center" style="gap: 5px;">' +
                                    '<input type="number" class="waste-qty-input item-waste-qty" step="any" min="0" max="' + stock + '" value="0" placeholder="0">' +
                                    '<div class="d-flex flex-column" style="gap: 2px;">' +
                                        '<button type="button" class="quick-qty-btn quick-qty-all" onclick="setWasteQty(this, \'all\')">All</button>' +
                                        '<button type="button" class="quick-qty-btn" onclick="setWasteQty(this, \'half\')">1/2</button>' +
                                        '<button type="button" class="quick-qty-btn" onclick="setWasteQty(this, \'clear\')">0</button>' +
                                    '</div>' +
                                '</div>' +
                            '</td>' +
                            '<td style="text-align: center;">' +
                                '<span class="kept-badge kept-safe item-kept-badge"><i class="fa fa-shield"></i> ' + stock + ' ' + unit + ' (Kept Safe)</span>' +
                            '</td>' +
                            '<td style="text-align: right; font-weight: 600;">' +
                                formatCurrency(price) +
                                '<input type="hidden" class="item-unit-price" value="' + price + '">' +
                            '</td>' +
                            '<td style="text-align: right; font-weight: 700; color: #dc2626;">' +
                                '<span class="item-loss-text">' + formatCurrency(0) + '</span>' +
                                '<input type="hidden" class="item-loss-val" value="0">' +
                            '</td>' +
                            '<td style="text-align: center;">' +
                                '<button type="button" class="btn btn-sm btn-outline-danger" title="Remove this row" onclick="removeRow(this)">' +
                                    '<i class="fa fa-trash"></i>' +
                                '</button>' +
                            '</td>' +
                        '</tr>';

                        $('#waste_tbody').append(trHtml);
                        $('#extra_ingredient_select').val('').trigger('change');
                    } else {
                        swal('Error', res ? res.message : 'Could not fetch item details.', 'error');
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fa fa-plus"></i> Add to Waste List');
                    swal('Error', 'Network error adding item.', 'error');
                }
            });
        });

        // Submit Waste Form
        $('#btn_save_daily_waste').on('click', function() {
            var items = [];
            var totalWasteQty = 0;

            $('#waste_tbody tr.waste-row').each(function() {
                var row = $(this);
                var ingId = row.attr('data-id');
                var wasteQty = parseFloat(row.find('.item-waste-qty').val()) || 0;
                var price = parseFloat(row.attr('data-price')) || 0;
                var loss = parseFloat(row.find('.item-loss-val').val()) || 0;

                if (wasteQty > 0) {
                    totalWasteQty += wasteQty;
                    items.push({
                        ingredient_id: ingId,
                        waste_amount: wasteQty,
                        last_purchase_price: price,
                        loss_amount: loss
                    });
                }
            });

            if (items.length === 0 || totalWasteQty <= 0) {
                swal({
                    title: 'No Items Selected',
                    text: 'Please enter a waste quantity greater than 0 for at least one item.',
                    type: 'warning'
                });
                return;
            }

            var totalLossText = $('#summary_total_loss').text();

            swal({
                title: 'Confirm Waste Submission?',
                text: 'You have selected ' + items.length + ' item(s) to discard. Total financial loss: ' + totalLossText + '. Do you wish to proceed?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Save Waste!',
                cancelButtonText: 'Cancel'
            }, function(isConfirm) {
                if (isConfirm) {
                    submitWasteData(items);
                }
            });
        });
    });

    function setWasteQty(btn, action) {
        var row = $(btn).closest('tr');
        var stock = parseFloat(row.attr('data-stock')) || 0;
        var input = row.find('.item-waste-qty');

        if (action === 'all') {
            input.val(stock);
        } else if (action === 'half') {
            input.val((stock / 2).toFixed(2));
        } else if (action === 'clear') {
            input.val(0);
        }
        handleQtyChange(row);
        recalculateSummary();
    }

    function handleQtyChange(row) {
        var stock = parseFloat(row.attr('data-stock')) || 0;
        var unit = row.attr('data-unit') || '';
        var price = parseFloat(row.attr('data-price')) || 0;
        var input = row.find('.item-waste-qty');
        var val = parseFloat(input.val()) || 0;

        if (val < 0) {
            val = 0;
            input.val(0);
        }

        var keptBadge = row.find('.item-kept-badge');
        var kept = stock - val;

        if (val > stock) {
            keptBadge.removeClass('kept-safe kept-warn').addClass('kept-danger')
                .html('<i class="fa fa-exclamation-triangle"></i> Exceeds stock (' + kept.toFixed(2) + ' ' + unit + ')');
        } else if (kept === 0) {
            keptBadge.removeClass('kept-safe kept-danger').addClass('kept-warn')
                .html('<i class="fa fa-times-circle"></i> None kept (0 ' + unit + ')');
        } else {
            keptBadge.removeClass('kept-warn kept-danger').addClass('kept-safe')
                .html('<i class="fa fa-shield"></i> ' + kept.toFixed(2) + ' ' + unit + ' (Kept Safe)');
        }

        var loss = val * price;
        row.find('.item-loss-val').val(loss.toFixed(2));
        row.find('.item-loss-text').text(formatCurrency(loss));
    }

    function removeRow(btn) {
        $(btn).closest('tr').remove();
        recalculateSummary();
    }

    function recalculateSummary() {
        var totalItems = 0;
        var totalQty = 0;
        var totalLoss = 0;

        $('#waste_tbody tr.waste-row').each(function() {
            var val = parseFloat($(this).find('.item-waste-qty').val()) || 0;
            var loss = parseFloat($(this).find('.item-loss-val').val()) || 0;

            if (val > 0) {
                totalItems++;
                totalQty += val;
                totalLoss += loss;
            }
        });

        $('#summary_items_count').text(totalItems);
        $('#summary_total_qty').text(totalQty.toFixed(2));
        $('#summary_total_loss').text(formatCurrency(totalLoss));
    }

    function submitWasteData(items) {
        var btn = $('#btn_save_daily_waste');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving Waste...');

        var csrfTokenName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = $('#csrf_token_waste').val();

        var payload = {
            date: $('#waste_date').val(),
            employee_id: $('#employee_id').val(),
            note: $('#waste_note').val(),
            items: items
        };
        payload[csrfTokenName] = csrfHash;

        $.ajax({
            url: '<?php echo base_url(); ?>Waste/saveDailyWaste',
            type: 'POST',
            dataType: 'json',
            data: payload,
            success: function(res) {
                if (res && res.status === 'success') {
                    swal({
                        title: 'Success!',
                        text: res.message,
                        type: 'success'
                    }, function() {
                        window.location.href = res.redirect_url || '<?php echo base_url('Sale/POS'); ?>';
                    });
                } else {
                    btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Confirm & Save Waste');
                    swal('Error', res ? res.message : 'Could not save waste.', 'error');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fa fa-check mr-1"></i> Confirm & Save Waste');
                console.error("Save waste error: ", xhr.responseText);
                swal('Error', 'Server or Network error while saving waste.', 'error');
            }
        });
    }

    function formatCurrency(amt) {
        var num = parseFloat(amt) || 0;
        var precision = parseInt($('#ir_precision').val()) || 2;
        return num.toLocaleString('en-US', { minimumFractionDigits: precision, maximumFractionDigits: precision });
    }
</script>
