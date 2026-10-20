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
</style>

<section class="main-content-wrapper">

    <section class="content-header">
        <h3 class="top-left-header text-left">Daily Stock Report</h3>
        <input type="hidden" class="datatable_name" data-title="Daily Stock Report" data-id_name="datatable">
    </section>

    <div class="my-2">
        <?php if (isLMni() && isset($outlet_id)): ?>
            <h4><?php echo lang('outlet'); ?>: <?php echo escape_output(getOutletNameById($outlet_id)); ?></h4>
        <?php endif; ?>
    </div>

    <div class="box-wrapper">
        <!-- Filter Form -->
        <div class="row mb-3">
            <?php echo form_open(base_url() . 'Report/stockReport'); ?>
            <div class="row">

                <!-- Type Filter -->
                <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
                    <div class="form-group">
                        <select tabindex="1" class="form-control select2 ir_w_100" id="ing_type" name="ing_type">
                            <option value="">All Types (Pre-Made & Raw)</option>
                            <option value="Pre-made Item" <?= (isset($ing_type) && $ing_type == 'Pre-made Item') ? 'selected' : '' ?>>Pre-Made Foods Only</option>
                            <option value="Plain Ingredient" <?= (isset($ing_type) && $ing_type == 'Plain Ingredient') ? 'selected' : '' ?>>Raw Ingredients Only</option>
                        </select>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-sm-12 col-md-3 col-lg-3 mb-2">
                    <div class="form-group">
                        <select tabindex="2" class="form-control select2 ir_w_100" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            <?php if (isset($ingredient_categories) && !empty($ingredient_categories)): ?>
                                <?php foreach ($ingredient_categories as $cat): ?>
                                    <option value="<?php echo escape_output($cat->id); ?>" <?= (isset($category_id) && $category_id == $cat->id) ? 'selected' : '' ?>><?php echo escape_output($cat->category_name); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Outlet Filter (multi-outlet admin only) -->
                <?php if (isLMni()): ?>
                    <div class="col-sm-12 col-md-3 col-lg-2 mb-2">
                        <div class="form-group">
                            <select tabindex="3" class="form-control select2 ir_w_100" id="outlet_id" name="outlet_id">
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

                <!-- Submit & Reset -->
                <div class="col-sm-12 col-md-3 col-lg-2 mb-2">
                    <button type="submit" name="submit" value="submit" class="btn bg-blue-btn w-100">
                        <i data-feather="filter"></i> Filter
                    </button>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-2 mb-2">
                    <a href="<?php echo base_url(); ?>Report/stockReport" class="btn btn-secondary w-100">
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
        $totalStockUnits = 0;

        if (!empty($inventory) && isset($inventory)):
            foreach ($inventory as $item):
                if (!empty($ing_type) && $item->ing_type != $ing_type) {
                    continue;
                }
                $conversion_rate = (int)$item->conversion_rate ? $item->conversion_rate : 1;
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
                            <th class="text-end">Total Produced/Purchased</th>
                            <th class="text-end">POS Sales / Consumed</th>
                            <th class="text-end">Wasted</th>
                            <th class="text-end">Current Stock</th>
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

                                $totalIn = ($value->ing_type == 'Pre-made Item')
                                    ? ($value->total_production * $conversion_rate)
                                    : ($value->total_purchase * $conversion_rate);

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
