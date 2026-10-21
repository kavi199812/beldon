<?php
/*
 * Stock Report / Daily Stock View
 * Displays current stock of Pre-Made Foods and Ingredients
 * Allows filtering by type (All, Pre-Made Food, Raw Ingredient) and Category
 */
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/custom/report.css">
<style>
.badge-premade {
    background-color: #6f42c1;
    color: #fff;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}
.badge-ingredient {
    background-color: #0d6efd;
    color: #fff;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}
.badge-instock {
    background-color: #198754;
    color: #fff;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}
.badge-lowstock {
    background-color: #ffc107;
    color: #000;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}
.badge-outofstock {
    background-color: #dc3545;
    color: #fff;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}
.stock-summary-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    border-left: 4px solid #0d6efd;
}
.stock-summary-card h4 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
}
.stock-summary-card p {
    margin: 0;
    color: #6c757d;
    font-size: 13px;
}
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
.quick-date-btn svg {
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
.btn-filter-custom svg {
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
.btn-reset-custom svg {
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
        <h3 class="top-left-header text-left">Daily Stock Report</h3>
        <input type="hidden" class="datatable_name" data-title="Daily Stock Report" data-id_name="datatable">
    </section>

    <div class="my-2">
        <?php if (!empty($start_date)): ?>
            <h4 class="text-primary mb-2">
                <i data-feather="calendar" style="width: 20px; height: 20px; vertical-align: middle;"></i>
                <?php 
                    $date_fmt = $this->session->userdata('date_format') ? $this->session->userdata('date_format') : 'Y-m-d';
                    if (!empty($end_date) && $end_date != $start_date) {
                        echo "Period: " . date($date_fmt, strtotime($start_date)) . " - " . date($date_fmt, strtotime($end_date));
                    } else {
                        echo "Date: " . date($date_fmt, strtotime($start_date));
                    }
                ?>
            </h4>
        <?php endif; ?>
        <?php if (isLMni() && isset($outlet_id)): ?>
            <h4><?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id)); ?></h4>
        <?php endif; ?>
    </div>

    <div class="box-wrapper">
        <!-- Filter Form -->
        <div class="row mb-3">
            <?php 
                $todayDate = date('Y-m-d');
                $yesterdayDate = date('Y-m-d', strtotime('-1 day'));
                $isTodayActive = (isset($start_date) && $start_date == $todayDate && isset($end_date) && $end_date == $todayDate);
                $isYesterdayActive = (isset($start_date) && $start_date == $yesterdayDate && isset($end_date) && $end_date == $yesterdayDate);
            ?>
            <?php echo form_open(base_url() . 'Report/stockReport', ['id' => 'stockReportFilterForm']); ?>
            <input type="hidden" name="submit" value="submit">
            <div class="stock-filter-container">

                <!-- Start Date -->
                <div class="stock-filter-item" style="min-width: 135px; flex: 1 1 135px;">
                    <input tabindex="1" type="text" id="startDate" name="startDate" readonly class="form-control customDatepicker"
                        placeholder="Start Date" value="<?= (isset($start_date) && $start_date) ? escape_output($start_date) : '' ?>">
                </div>

                <!-- End Date -->
                <div class="stock-filter-item" style="min-width: 135px; flex: 1 1 135px;">
                    <input tabindex="2" type="text" id="endDate" name="endDate" readonly class="form-control customDatepicker"
                        placeholder="End Date (Optional)" value="<?= (isset($end_date) && $end_date) ? escape_output($end_date) : '' ?>">
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

                <!-- Type Filter -->
                <div class="stock-filter-item" style="min-width: 180px; flex: 1 1 180px;">
                    <select tabindex="3" class="form-control select2 ir_w_100" id="ing_type" name="ing_type">
                        <option value="">All Types (Pre-Made & Raw)</option>
                        <option value="Pre-made Item" <?= (isset($ing_type) && $ing_type == 'Pre-made Item') ? 'selected' : '' ?>>Pre-Made Foods Only</option>
                        <option value="Plain Ingredient" <?= (isset($ing_type) && $ing_type == 'Plain Ingredient') ? 'selected' : '' ?>>Raw Ingredients Only</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="stock-filter-item" style="min-width: 170px; flex: 1 1 170px;">
                    <select tabindex="4" class="form-control select2 ir_w_100" id="category_id" name="category_id">
                        <option value="">All Categories</option>
                        <?php if (isset($ingredient_categories) && !empty($ingredient_categories)): ?>
                            <?php foreach ($ingredient_categories as $cat): ?>
                                <option value="<?php echo escape_output($cat->id); ?>" <?= (isset($category_id) && $category_id == $cat->id) ? 'selected' : '' ?>><?php echo escape_output($cat->category_name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Outlet Filter (multi-outlet admin only) -->
                <?php if (isLMni()): ?>
                    <div class="stock-filter-item" style="min-width: 150px; flex: 1 1 150px;">
                        <select tabindex="5" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
                            <?php
                            $outlets = getAllOutlestByAssign();
                            foreach ($outlets as $outlet_item):
                            ?>
                                <option <?= (isset($outlet_id) && $outlet_id == $outlet_item->id) ? 'selected' : '' ?> value="<?php echo escape_output($outlet_item->id); ?>"><?php echo escape_output($outlet_item->outlet_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons: Filter & Reset -->
                <div class="stock-filter-item d-flex align-items-center ms-auto">
                    <button type="submit" name="submit" value="submit" class="btn-filter-custom" style="margin-right: 8px;">
                        <i data-feather="filter"></i> Filter
                    </button>
                    <a href="<?php echo base_url(); ?>Report/stockReport" class="btn-reset-custom" title="Reset All Filters">
                        <i data-feather="refresh-cw"></i> Reset
                    </a>
                </div>

            </div>
            <?php echo form_close(); ?>
        </div>

        <?php
        // Pre-calculate summary stats
        $totalItemsCount = 0;
        $premadeCount = 0;
        $lowStockCount = 0;

        if (!empty($inventory) && isset($inventory)):
            foreach ($inventory as $item):
                if (!empty($ing_type) && $item->ing_type != $ing_type) {
                    continue;
                }
                $conversion_rate = (int)$item->conversion_rate ? $item->conversion_rate : 1;
                if (!empty($start_date)) {
                    $cPurchase = isset($item->closing_purchase) ? $item->closing_purchase : $item->total_purchase;
                    $cProduction = isset($item->closing_production) ? $item->closing_production : $item->total_production;
                    $cConsumption = isset($item->closing_consumption) ? $item->closing_consumption : $item->total_consumption;
                    $cModifiers = isset($item->closing_modifiers_consumption) ? $item->closing_modifiers_consumption : $item->total_modifiers_consumption;
                    $cWaste = isset($item->closing_waste) ? $item->closing_waste : $item->total_waste;
                    $cPlus = isset($item->closing_consumption_plus) ? $item->closing_consumption_plus : $item->total_consumption_plus;
                    $cMinus = isset($item->closing_consumption_minus) ? $item->closing_consumption_minus : $item->total_consumption_minus;
                    $cTransPlus = isset($item->closing_transfer_plus) ? $item->closing_transfer_plus : $item->total_transfer_plus;
                    $cTransMinus = isset($item->closing_transfer_minus) ? $item->closing_transfer_minus : $item->total_transfer_minus;
                    $cTransPlus2 = isset($item->closing_transfer_plus_2) ? $item->closing_transfer_plus_2 : $item->total_transfer_plus_2;
                    $cTransMinus2 = isset($item->closing_transfer_minus_2) ? $item->closing_transfer_minus_2 : $item->total_transfer_minus_2;

                    $currentStock = ($cPurchase * $conversion_rate)
                        - $cConsumption
                        - $cModifiers
                        - $cWaste
                        + $cPlus
                        - $cMinus
                        + ($cTransPlus * $conversion_rate)
                        - ($cTransMinus * $conversion_rate)
                        + ($cTransPlus2 * $conversion_rate)
                        - ($cTransMinus2 * $conversion_rate)
                        + ($cProduction * $conversion_rate);
                } else {
                    $currentStock = ($item->total_purchase * $conversion_rate)
                        - $item->total_consumption
                        - $item->total_modifiers_consumption
                        - $item->total_waste
                        + $item->total_consumption_plus
                        - $item->total_consumption_minus
                        + ($item->total_transfer_plus * $conversion_rate)
                        - ($item->total_transfer_minus * $conversion_rate)
                        + ($item->total_transfer_plus_2 * $conversion_rate)
                        - ($item->total_transfer_minus_2 * $conversion_rate)
                        + ($item->total_production * $conversion_rate);
                }

                $totalItemsCount++;
                if ($item->ing_type == 'Pre-made Item') {
                    $premadeCount++;
                }
                if ($currentStock <= $item->alert_quantity) {
                    $lowStockCount++;
                }
            endforeach;
        endif;
        ?>

        <!-- Summary Cards -->
        <div class="row mb-3">
            <div class="col-sm-6 col-md-4 mb-2">
                <div class="stock-summary-card" style="border-left-color: #0d6efd;">
                    <h4><?php echo $totalItemsCount; ?></h4>
                    <p>Total Items Monitored</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 mb-2">
                <div class="stock-summary-card" style="border-left-color: #6f42c1;">
                    <h4><?php echo $premadeCount; ?></h4>
                    <p>Pre-Made Food Items</p>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 mb-2">
                <div class="stock-summary-card" style="border-left-color: #dc3545;">
                    <h4 style="color: #dc3545;"><?php echo $lowStockCount; ?></h4>
                    <p>Low / Out of Stock Alerts</p>
                </div>
            </div>
        </div>

        <!-- Table Box -->
        <div class="table-box">
            <div class="table-responsive">
                <table id="datatable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="ir_w_5 text-center"><?php echo lang('sn'); ?></th>
                            <th>Item Name (Code)</th>
                            <th class="text-center">Item Type</th>
                            <th><?php echo lang('category'); ?></th>
                            <th class="text-end"><?= !empty($start_date) ? 'Produced / Purchased (Period)' : 'Total Produced / Purchased' ?></th>
                            <th class="text-end"><?= !empty($start_date) ? 'POS Sales / Consumed (Period)' : 'POS Sales / Consumed' ?></th>
                            <th class="text-end"><?= !empty($start_date) ? 'Wasted (Period)' : 'Wasted' ?></th>
                            <th class="text-end"><?= !empty($start_date) ? 'Closing Stock' : 'Current Stock' ?></th>
                            <th class="text-end">Alert Qty</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sn = 1;
                        if (!empty($inventory) && isset($inventory)):
                            foreach ($inventory as $value):
                                if (!empty($ing_type) && $value->ing_type != $ing_type) {
                                    continue;
                                }

                                $conversion_rate = (int)$value->conversion_rate ? $value->conversion_rate : 1;

                                if (!empty($start_date)) {
                                    $cPurchase = isset($value->closing_purchase) ? $value->closing_purchase : $value->total_purchase;
                                    $cProduction = isset($value->closing_production) ? $value->closing_production : $value->total_production;
                                    $cConsumption = isset($value->closing_consumption) ? $value->closing_consumption : $value->total_consumption;
                                    $cModifiers = isset($value->closing_modifiers_consumption) ? $value->closing_modifiers_consumption : $value->total_modifiers_consumption;
                                    $cWaste = isset($value->closing_waste) ? $value->closing_waste : $value->total_waste;
                                    $cPlus = isset($value->closing_consumption_plus) ? $value->closing_consumption_plus : $value->total_consumption_plus;
                                    $cMinus = isset($value->closing_consumption_minus) ? $value->closing_consumption_minus : $value->total_consumption_minus;
                                    $cTransPlus = isset($value->closing_transfer_plus) ? $value->closing_transfer_plus : $value->total_transfer_plus;
                                    $cTransMinus = isset($value->closing_transfer_minus) ? $value->closing_transfer_minus : $value->total_transfer_minus;
                                    $cTransPlus2 = isset($value->closing_transfer_plus_2) ? $value->closing_transfer_plus_2 : $value->total_transfer_plus_2;
                                    $cTransMinus2 = isset($value->closing_transfer_minus_2) ? $value->closing_transfer_minus_2 : $value->total_transfer_minus_2;

                                    $totalStock = ($cPurchase * $conversion_rate)
                                        - $cConsumption
                                        - $cModifiers
                                        - $cWaste
                                        + $cPlus
                                        - $cMinus
                                        + ($cTransPlus * $conversion_rate)
                                        - ($cTransMinus * $conversion_rate)
                                        + ($cTransPlus2 * $conversion_rate)
                                        - ($cTransMinus2 * $conversion_rate)
                                        + ($cProduction * $conversion_rate);

                                    $lPurchase = isset($value->live_purchase) ? $value->live_purchase : $value->total_purchase;
                                    $lProduction = isset($value->live_production) ? $value->live_production : $value->total_production;
                                    $lConsumption = isset($value->live_consumption) ? $value->live_consumption : $value->total_consumption;
                                    $lModifiers = isset($value->live_modifiers_consumption) ? $value->live_modifiers_consumption : $value->total_modifiers_consumption;
                                    $lWaste = isset($value->live_waste) ? $value->live_waste : $value->total_waste;
                                    $lPlus = isset($value->live_consumption_plus) ? $value->live_consumption_plus : $value->total_consumption_plus;
                                    $lMinus = isset($value->live_consumption_minus) ? $value->live_consumption_minus : $value->total_consumption_minus;
                                    $lTransPlus = isset($value->live_transfer_plus) ? $value->live_transfer_plus : $value->total_transfer_plus;
                                    $lTransMinus = isset($value->live_transfer_minus) ? $value->live_transfer_minus : $value->total_transfer_minus;
                                    $lTransPlus2 = isset($value->live_transfer_plus_2) ? $value->live_transfer_plus_2 : $value->total_transfer_plus_2;
                                    $lTransMinus2 = isset($value->live_transfer_minus_2) ? $value->live_transfer_minus_2 : $value->total_transfer_minus_2;

                                    $liveStock = ($lPurchase * $conversion_rate)
                                        - $lConsumption
                                        - $lModifiers
                                        - $lWaste
                                        + $lPlus
                                        - $lMinus
                                        + ($lTransPlus * $conversion_rate)
                                        - ($lTransMinus * $conversion_rate)
                                        + ($lTransPlus2 * $conversion_rate)
                                        - ($lTransMinus2 * $conversion_rate)
                                        + ($lProduction * $conversion_rate);
                                } else {
                                    $totalStock = ($value->total_purchase * $conversion_rate)
                                        - $value->total_consumption
                                        - $value->total_modifiers_consumption
                                        - $value->total_waste
                                        + $value->total_consumption_plus
                                        - $value->total_consumption_minus
                                        + ($value->total_transfer_plus * $conversion_rate)
                                        - ($value->total_transfer_minus * $conversion_rate)
                                        + ($value->total_transfer_plus_2 * $conversion_rate)
                                        - ($value->total_transfer_minus_2 * $conversion_rate)
                                        + ($value->total_production * $conversion_rate);
                                    $liveStock = $totalStock;
                                }

                                $totalIn = (($value->total_production + $value->total_purchase) * $conversion_rate);
                                $totalOut = $value->total_consumption + $value->total_modifiers_consumption;
                                $totalWaste = $value->total_waste;

                                $unit_display = escape_output($value->unit_name);

                                // Determine Status Badge
                                if ($totalStock <= 0) {
                                    $statusBadge = '<span class="badge-outofstock">Out of Stock</span>';
                                } elseif ($totalStock <= $value->alert_quantity) {
                                    $statusBadge = '<span class="badge-lowstock">Low Stock</span>';
                                } else {
                                    $statusBadge = '<span class="badge-instock">In Stock</span>';
                                }
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $sn++; ?></td>
                                <td>
                                    <strong><?php echo escape_output($value->name); ?></strong>
                                    <span class="text-muted">(<?php echo escape_output($value->code); ?>)</span>
                                </td>
                                <td class="text-center">
                                    <?php if ($value->ing_type == 'Pre-made Item'): ?>
                                        <span class="badge-premade">Pre-Made Food</span>
                                    <?php else: ?>
                                        <span class="badge-ingredient">Raw Ingredient</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo escape_output($value->category_name); ?></td>
                                <td class="text-end">
                                    <?php echo getAmtP($totalIn) . ' ' . $unit_display; ?>
                                </td>
                                <td class="text-end text-danger">
                                    <?php echo ($totalOut > 0 ? '-' : '') . getAmtP($totalOut) . ' ' . $unit_display; ?>
                                </td>
                                <td class="text-end text-warning">
                                    <?php echo ($totalWaste > 0 ? '-' : '') . getAmtP($totalWaste) . ' ' . $unit_display; ?>
                                </td>
                                <td class="text-end">
                                    <strong style="font-size: 15px; color: <?= ($totalStock <= $value->alert_quantity) ? '#dc3545' : '#198754' ?>;">
                                        <?php echo getAmtP($totalStock) . ' ' . $unit_display; ?>
                                    </strong>
                                    <?php if (!empty($start_date) && round($totalStock, 2) != round($liveStock, 2)): ?>
                                        <div class="small text-muted" title="Real-time live stock right now">
                                            Now: <?php echo getAmtP($liveStock) . ' ' . $unit_display; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php echo getAmtP($value->alert_quantity) . ' ' . $unit_display; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $statusBadge; ?>
                                </td>
                            </tr>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    if (typeof $.fn.dataTable !== 'undefined') {
        $.fn.dataTable.ext.errMode = 'none';
    }

    function filterQuickDate(dateStr) {
        var startInput = document.getElementById('startDate');
        var endInput = document.getElementById('endDate');
        var form = document.getElementById('stockReportFilterForm');
        if (startInput) startInput.value = dateStr;
        if (endInput) endInput.value = dateStr;
        if (form) {
            form.submit();
        }
    }
</script>
