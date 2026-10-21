<?php
/*
  ###########################################################
  # PRODUCT NAME: 	iRestora PLUS - Next Gen Restaurant POS | NULLED by raz0r
  ###########################################################
  # AUTHER:		Doorsoft
  ###########################################################
  # EMAIL:		info@doorsoft.co
  ###########################################################
  # COPYRIGHTS:		RESERVED BY Door Soft
  ###########################################################
  # WEBSITE:		http://www.doorsoft.co
  ###########################################################
  # This is Inventory_model Model
  ###########################################################
 */
class Inventory_model extends CI_Model {

    /**
     * get Data By Cat Id
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getDataByCatId($cat_id, $table_name) {
        $this->db->select("id,name,code");
        $this->db->from($table_name);
        $this->db->where("category_id", $cat_id);
        $this->db->order_by("name", "ASC");
        $this->db->where("del_status", 'Live');
        return $this->db->get()->result();
    }
    /**
     * get Inventory
     * @access public
     * @return object
     * @param string
     * @param string
     * @param string
     */
    public function getInventory($category_id = "", $ingredient_id = "", $food_id = "", $start_date = "", $end_date = "", $outlet_id = "") {

        if (!$outlet_id) {
            $outlet_id = $this->session->userdata('outlet_id');
        }
        $company_id = $this->session->userdata('company_id');

        $where = '';
        $where1 = '';
        if($food_id!=''){
            $food = foodMenuRow($food_id);
            if($food->product_type==2){
                $getFMIds = $food->combo_ids;
            }else{
                $getFMIds = $food_id;
            }
        }else{
            $getFMIds = getFMIds($outlet_id);
        }
        if($category_id!=''){
            $where1.= "  AND ingr_tbl.category_id = '$category_id'";
        }
        if($ingredient_id!=''){
            $where1.= "  AND i.id = '$ingredient_id'";
        }

        if ($start_date && !$end_date) {
            $end_date = $start_date;
        } elseif (!$start_date && $end_date) {
            $start_date = $end_date;
        }

        $hasDateFilter = (!empty($start_date) && !empty($end_date));

        //get selected food menu ids
        if($food_id){
            $result = $this->db->query("SELECT ingr_tbl.*,i.food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, ingr_unit_tbl2.unit_name as unit_name2, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
                    (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
                    (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
                    (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
                    (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
                    (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.ingredient_id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
                    (select SUM(quantity_amount) from tbl_production_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_production_ingredients.del_status='Live' AND tbl_production_ingredients.status=1) total_production,
                    (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
                    (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
                    (select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
                    (select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2
                    FROM tbl_food_menus_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.ingredient_id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id  LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl2 ON ingr_unit_tbl2.id = ingr_tbl.purchase_unit_id WHERE FIND_IN_SET(`food_menu_id`, '$getFMIds') AND i.company_id= '$company_id' AND i.del_status='Live' $where  GROUP BY i.ingredient_id")->result();
            return $result;
        }else{
            if ($hasDateFilter) {
                $purchase_period = "JOIN tbl_purchase p ON p.id = pi.purchase_id WHERE pi.ingredient_id = i.id AND pi.outlet_id = $outlet_id AND pi.del_status = 'Live' AND p.del_status = 'Live' AND p.date >= '$start_date' AND p.date <= '$end_date'";
                $purchase_closing = "JOIN tbl_purchase p ON p.id = pi.purchase_id WHERE pi.ingredient_id = i.id AND pi.outlet_id = $outlet_id AND pi.del_status = 'Live' AND p.del_status = 'Live' AND p.date <= '$end_date'";

                $production_period = "JOIN tbl_production pr ON pr.id = pri.production_id WHERE pri.ingredient_id = i.id AND pri.outlet_id = $outlet_id AND pri.del_status = 'Live' AND pr.del_status = 'Live' AND pri.status = 1 AND pr.date >= '$start_date' AND pr.date <= '$end_date'";
                $production_closing = "JOIN tbl_production pr ON pr.id = pri.production_id WHERE pri.ingredient_id = i.id AND pri.outlet_id = $outlet_id AND pri.del_status = 'Live' AND pr.del_status = 'Live' AND pri.status = 1 AND pr.date <= '$end_date'";

                $consumption_period = "JOIN tbl_sales s ON s.id = scm.sales_id WHERE scm.ingredient_id = i.id AND scm.outlet_id = $outlet_id AND scm.del_status = 'Live' AND s.del_status = 'Live' AND s.sale_date >= '$start_date' AND s.sale_date <= '$end_date'";
                $consumption_closing = "JOIN tbl_sales s ON s.id = scm.sales_id WHERE scm.ingredient_id = i.id AND scm.outlet_id = $outlet_id AND scm.del_status = 'Live' AND s.del_status = 'Live' AND s.sale_date <= '$end_date'";

                $modifiers_period = "JOIN tbl_sales s ON s.id = sm.sales_id WHERE sm.ingredient_id = i.id AND sm.outlet_id = $outlet_id AND sm.del_status = 'Live' AND s.del_status = 'Live' AND s.sale_date >= '$start_date' AND s.sale_date <= '$end_date'";
                $modifiers_closing = "JOIN tbl_sales s ON s.id = sm.sales_id WHERE sm.ingredient_id = i.id AND sm.outlet_id = $outlet_id AND sm.del_status = 'Live' AND s.del_status = 'Live' AND s.sale_date <= '$end_date'";

                $waste_period = "JOIN tbl_wastes w ON w.id = wi.waste_id WHERE wi.ingredient_id = i.id AND wi.outlet_id = $outlet_id AND wi.del_status = 'Live' AND w.del_status = 'Live' AND w.date >= '$start_date' AND w.date <= '$end_date'";
                $waste_closing = "JOIN tbl_wastes w ON w.id = wi.waste_id WHERE wi.ingredient_id = i.id AND wi.outlet_id = $outlet_id AND wi.del_status = 'Live' AND w.del_status = 'Live' AND w.date <= '$end_date'";

                $adj_plus_period = "JOIN tbl_inventory_adjustment ia ON ia.id = iai.inventory_adjustment_id WHERE iai.ingredient_id = i.id AND iai.outlet_id = $outlet_id AND iai.del_status = 'Live' AND ia.del_status = 'Live' AND iai.consumption_status = 'Plus' AND ia.date >= '$start_date' AND ia.date <= '$end_date'";
                $adj_plus_closing = "JOIN tbl_inventory_adjustment ia ON ia.id = iai.inventory_adjustment_id WHERE iai.ingredient_id = i.id AND iai.outlet_id = $outlet_id AND iai.del_status = 'Live' AND ia.del_status = 'Live' AND iai.consumption_status = 'Plus' AND ia.date <= '$end_date'";

                $adj_minus_period = "JOIN tbl_inventory_adjustment ia ON ia.id = iai.inventory_adjustment_id WHERE iai.ingredient_id = i.id AND iai.outlet_id = $outlet_id AND iai.del_status = 'Live' AND ia.del_status = 'Live' AND iai.consumption_status = 'Minus' AND ia.date >= '$start_date' AND ia.date <= '$end_date'";
                $adj_minus_closing = "JOIN tbl_inventory_adjustment ia ON ia.id = iai.inventory_adjustment_id WHERE iai.ingredient_id = i.id AND iai.outlet_id = $outlet_id AND iai.del_status = 'Live' AND ia.del_status = 'Live' AND iai.consumption_status = 'Minus' AND ia.date <= '$end_date'";

                $trans_plus_period = "JOIN tbl_transfer t ON t.id = ti.transfer_id WHERE ti.ingredient_id = i.id AND ti.to_outlet_id = $outlet_id AND ti.del_status = 'Live' AND t.del_status = 'Live' AND ti.status = 1 AND ti.transfer_type = 1 AND t.date >= '$start_date' AND t.date <= '$end_date'";
                $trans_plus_closing = "JOIN tbl_transfer t ON t.id = ti.transfer_id WHERE ti.ingredient_id = i.id AND ti.to_outlet_id = $outlet_id AND ti.del_status = 'Live' AND t.del_status = 'Live' AND ti.status = 1 AND ti.transfer_type = 1 AND t.date <= '$end_date'";

                $trans_minus_period = "JOIN tbl_transfer t ON t.id = ti.transfer_id WHERE ti.ingredient_id = i.id AND ti.from_outlet_id = $outlet_id AND ti.del_status = 'Live' AND t.del_status = 'Live' AND (ti.status = 1) AND ti.transfer_type = 1 AND t.date >= '$start_date' AND t.date <= '$end_date'";
                $trans_minus_closing = "JOIN tbl_transfer t ON t.id = ti.transfer_id WHERE ti.ingredient_id = i.id AND ti.from_outlet_id = $outlet_id AND ti.del_status = 'Live' AND t.del_status = 'Live' AND (ti.status = 1) AND ti.transfer_type = 1 AND t.date <= '$end_date'";

                $trans_plus2_period = "JOIN tbl_transfer t ON t.id = tri.transfer_id WHERE tri.ingredient_id = i.id AND tri.to_outlet_id = $outlet_id AND tri.del_status = 'Live' AND t.del_status = 'Live' AND tri.status = 1 AND t.date >= '$start_date' AND t.date <= '$end_date'";
                $trans_plus2_closing = "JOIN tbl_transfer t ON t.id = tri.transfer_id WHERE tri.ingredient_id = i.id AND tri.to_outlet_id = $outlet_id AND tri.del_status = 'Live' AND t.del_status = 'Live' AND tri.status = 1 AND t.date <= '$end_date'";

                $trans_minus2_period = "JOIN tbl_transfer t ON t.id = tri.transfer_id WHERE tri.ingredient_id = i.id AND tri.from_outlet_id = $outlet_id AND tri.del_status = 'Live' AND t.del_status = 'Live' AND (tri.status = 1) AND t.date >= '$start_date' AND t.date <= '$end_date'";
                $trans_minus2_closing = "JOIN tbl_transfer t ON t.id = tri.transfer_id WHERE tri.ingredient_id = i.id AND tri.from_outlet_id = $outlet_id AND tri.del_status = 'Live' AND t.del_status = 'Live' AND (tri.status = 1) AND t.date <= '$end_date'";

                $result = $this->db->query("SELECT ingr_tbl.*, i.id as food_menu_id, ingr_cat_tbl.category_name, ingr_unit_tbl.unit_name, ingr_unit_tbl2.unit_name as unit_name2,
                    (SELECT COALESCE(SUM(pi.quantity_amount), 0) FROM tbl_purchase_ingredients pi $purchase_period) as total_purchase,
                    (SELECT COALESCE(SUM(scm.consumption), 0) FROM tbl_sale_consumptions_of_menus scm $consumption_period) as total_consumption,
                    (SELECT COALESCE(SUM(sm.consumption), 0) FROM tbl_sale_consumptions_of_modifiers_of_menus sm $modifiers_period) as total_modifiers_consumption,
                    (SELECT COALESCE(SUM(wi.waste_amount), 0) FROM tbl_waste_ingredients wi $waste_period) as total_waste,
                    (SELECT COALESCE(SUM(iai.consumption_amount), 0) FROM tbl_inventory_adjustment_ingredients iai $adj_plus_period) as total_consumption_plus,
                    (SELECT COALESCE(SUM(iai.consumption_amount), 0) FROM tbl_inventory_adjustment_ingredients iai $adj_minus_period) as total_consumption_minus,
                    (SELECT COALESCE(SUM(pri.quantity_amount), 0) FROM tbl_production_ingredients pri $production_period) as total_production,
                    (SELECT COALESCE(SUM(ti.quantity_amount), 0) FROM tbl_transfer_ingredients ti $trans_plus_period) as total_transfer_plus,
                    (SELECT COALESCE(SUM(ti.quantity_amount), 0) FROM tbl_transfer_ingredients ti $trans_minus_period) as total_transfer_minus,
                    (SELECT COALESCE(SUM(tri.quantity_amount), 0) FROM tbl_transfer_received_ingredients tri $trans_plus2_period) as total_transfer_plus_2,
                    (SELECT COALESCE(SUM(tri.quantity_amount), 0) FROM tbl_transfer_received_ingredients tri $trans_minus2_period) as total_transfer_minus_2,

                    -- Closing Stock Metrics (up to end_date)
                    (SELECT COALESCE(SUM(pi.quantity_amount), 0) FROM tbl_purchase_ingredients pi $purchase_closing) as closing_purchase,
                    (SELECT COALESCE(SUM(scm.consumption), 0) FROM tbl_sale_consumptions_of_menus scm $consumption_closing) as closing_consumption,
                    (SELECT COALESCE(SUM(sm.consumption), 0) FROM tbl_sale_consumptions_of_modifiers_of_menus sm $modifiers_closing) as closing_modifiers_consumption,
                    (SELECT COALESCE(SUM(wi.waste_amount), 0) FROM tbl_waste_ingredients wi $waste_closing) as closing_waste,
                    (SELECT COALESCE(SUM(iai.consumption_amount), 0) FROM tbl_inventory_adjustment_ingredients iai $adj_plus_closing) as closing_consumption_plus,
                    (SELECT COALESCE(SUM(iai.consumption_amount), 0) FROM tbl_inventory_adjustment_ingredients iai $adj_minus_closing) as closing_consumption_minus,
                    (SELECT COALESCE(SUM(pri.quantity_amount), 0) FROM tbl_production_ingredients pri $production_closing) as closing_production,
                    (SELECT COALESCE(SUM(ti.quantity_amount), 0) FROM tbl_transfer_ingredients ti $trans_plus_closing) as closing_transfer_plus,
                    (SELECT COALESCE(SUM(ti.quantity_amount), 0) FROM tbl_transfer_ingredients ti $trans_minus_closing) as closing_transfer_minus,
                    (SELECT COALESCE(SUM(tri.quantity_amount), 0) FROM tbl_transfer_received_ingredients tri $trans_plus2_closing) as closing_transfer_plus_2,
                    (SELECT COALESCE(SUM(tri.quantity_amount), 0) FROM tbl_transfer_received_ingredients tri $trans_minus2_closing) as closing_transfer_minus_2,

                    -- Live Current Stock Metrics (all-time)
                    (SELECT COALESCE(SUM(quantity_amount), 0) FROM tbl_purchase_ingredients WHERE ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') as live_purchase,
                    (SELECT COALESCE(SUM(consumption), 0) FROM tbl_sale_consumptions_of_menus WHERE ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') as live_consumption,
                    (SELECT COALESCE(SUM(consumption), 0) FROM tbl_sale_consumptions_of_modifiers_of_menus WHERE ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') as live_modifiers_consumption,
                    (SELECT COALESCE(SUM(waste_amount), 0) FROM tbl_waste_ingredients WHERE ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') as live_waste,
                    (SELECT COALESCE(SUM(consumption_amount), 0) FROM tbl_inventory_adjustment_ingredients WHERE ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live' AND consumption_status='Plus') as live_consumption_plus,
                    (SELECT COALESCE(SUM(consumption_amount), 0) FROM tbl_inventory_adjustment_ingredients WHERE ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live' AND consumption_status='Minus') as live_consumption_minus,
                    (SELECT COALESCE(SUM(quantity_amount), 0) FROM tbl_production_ingredients WHERE ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live' AND status=1) as live_production,
                    (SELECT COALESCE(SUM(quantity_amount), 0) FROM tbl_transfer_ingredients WHERE ingredient_id=i.id AND to_outlet_id=$outlet_id AND del_status='Live' AND status=1 AND transfer_type=1) as live_transfer_plus,
                    (SELECT COALESCE(SUM(quantity_amount), 0) FROM tbl_transfer_ingredients WHERE ingredient_id=i.id AND from_outlet_id=$outlet_id AND del_status='Live' AND (status=1) AND transfer_type=1) as live_transfer_minus,
                    (SELECT COALESCE(SUM(quantity_amount), 0) FROM tbl_transfer_received_ingredients WHERE ingredient_id=i.id AND to_outlet_id=$outlet_id AND del_status='Live' AND status=1) as live_transfer_plus_2,
                    (SELECT COALESCE(SUM(quantity_amount), 0) FROM tbl_transfer_received_ingredients WHERE ingredient_id=i.id AND from_outlet_id=$outlet_id AND del_status='Live' AND (status=1)) as live_transfer_minus_2

                FROM tbl_ingredients i
                LEFT JOIN (SELECT * FROM tbl_ingredients WHERE del_status='Live') ingr_tbl ON ingr_tbl.id = i.id
                LEFT JOIN (SELECT * FROM tbl_ingredient_categories WHERE del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id
                LEFT JOIN (SELECT * FROM tbl_units WHERE del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id
                LEFT JOIN (SELECT * FROM tbl_units WHERE del_status='Live') ingr_unit_tbl2 ON ingr_unit_tbl2.id = ingr_tbl.purchase_unit_id
                WHERE i.company_id = '$company_id' AND i.del_status = 'Live' $where1
                GROUP BY i.id")->result();

                return $result;
            } else {
                $result = $this->db->query("SELECT ingr_tbl.*,i.id as food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, ingr_unit_tbl2.unit_name as unit_name2, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
                        (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
                        (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
                        (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
                        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
                        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
                        (select SUM(quantity_amount) from tbl_production_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_production_ingredients.del_status='Live' AND tbl_production_ingredients.status=1) total_production,
                        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND tbl_transfer_ingredients.status=1  AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
                        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
                        (select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
                        (select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2
                        FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl2 ON ingr_unit_tbl2.id = ingr_tbl.purchase_unit_id  WHERE i.company_id= '$company_id' AND i.del_status='Live' $where1 GROUP BY i.id")->result();

                return $result;
            }
        }

    }
    public function getCurrentInventory($ingredient_id) {

        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        $where1 = '';
        if($ingredient_id!=''){
            $where1.= "  AND i.id = '$ingredient_id'";
        }
        $value = $this->db->query("SELECT ingr_tbl.*,i.id as food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name, ingr_unit_tbl2.unit_name as unit_name2, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
        (select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
        (select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
        (select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
        (select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
        (select SUM(quantity_amount) from tbl_production_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_production_ingredients.del_status='Live' AND tbl_production_ingredients.status=1) total_production,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND tbl_transfer_ingredients.status=1  AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
        (select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
        (select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2
        FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl2 ON ingr_unit_tbl2.id = ingr_tbl.purchase_unit_id  WHERE i.company_id= '$company_id' AND i.del_status='Live' $where1 GROUP BY i.id")->row();

        $conversion_rate = (int)$value->conversion_rate?$value->conversion_rate:1;
        $new_stock = 0;
        if($value->id):
            $totalStock = ($value->total_purchase*$value->conversion_rate)  - $value->total_consumption - $value->total_modifiers_consumption - $value->total_waste + $value->total_consumption_plus - $value->total_consumption_minus + ($value->total_transfer_plus*$value->conversion_rate) - ($value->total_transfer_minus*$value->conversion_rate)  +  ($value->total_transfer_plus_2*$value->conversion_rate) -  ($value->total_transfer_minus_2*$value->conversion_rate)+ ($value->total_production*$value->conversion_rate);
            if($value->conversion_rate==0 || $value->conversion_rate==''){
                $total_sale_unit = isset($value->conversion_rate) && (int)$value->conversion_rate?(int)($totalStock/1):'0';
            }else{
                $total_sale_unit = isset($value->conversion_rate) && (int)$value->conversion_rate?(int)($totalStock/$value->conversion_rate):'0';
            }
            $new_stock = (float)($total_sale_unit.".".$totalStock%$conversion_rate);
        endif;
        $return_array = array();
        $return_array['total_stock'] = $new_stock;
        $return_array['stock_unit'] = $value->unit_name2;
        return $return_array;

    }
    public function getInventoryFoodMenu($food_id = "",$category_id='') {

        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        $where = '';
        $where1 = '';
        if($food_id!=''){
            $getFMIds = $food_id;
        }else{
            $getFMIds = getFMIds($outlet_id);
        }
        if($category_id!=''){
            $where1.= "  AND i.category_id = '$category_id'";
        }
        //get selected food menu ids
        $result = $this->db->query("SELECT i.*,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=2) total_transfer_plus_2,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=2) total_transfer_minus_2,
        (select SUM(qty) from tbl_sales_details  where food_menu_id=i.id AND outlet_id=$outlet_id AND del_status='Live') sale_total
         FROM tbl_food_menus i  WHERE FIND_IN_SET(`id`, '$getFMIds') AND i.company_id= '$company_id' AND i.del_status='Live' $where1")->result();
        return $result;
    }
    public function checkInventory($food_id = "") {

        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');
        //get selected food menu ids
        $result = $this->db->query("SELECT i.*,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=2) total_transfer_plus_2,
        (select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=2) total_transfer_minus_2,
        (select SUM(qty) from tbl_sales_details  where food_menu_id=i.id AND outlet_id=$outlet_id AND del_status='Live') sale_total
         FROM tbl_food_menus i  WHERE id='$food_id' AND i.company_id= '$company_id' AND i.del_status='Live'")->row();
        return $result;
    }
    /**
     * get Inventory Alert List
     * @access public
     * @return object
     * @param no
     */
    public function getInventoryAlertList() {
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        $where = '';
        $getFMIds = getFMIds($outlet_id);

        $result = $this->db->query("SELECT ingr_tbl.*,i.id as food_menu_id,ingr_cat_tbl.category_name,ingr_unit_tbl.unit_name,ingr_unit_tbl2.unit_name as unit_name2, (select SUM(quantity_amount) from tbl_purchase_ingredients where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_purchase, 
(select SUM(consumption) from tbl_sale_consumptions_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND del_status='Live') total_consumption,
(select SUM(consumption) from tbl_sale_consumptions_of_modifiers_of_menus where ingredient_id=i.id AND outlet_id=$outlet_id AND  del_status='Live') total_modifiers_consumption,
(select SUM(waste_amount) from tbl_waste_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND tbl_waste_ingredients.del_status='Live') total_waste,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Plus') total_consumption_plus,
(select SUM(consumption_amount) from tbl_inventory_adjustment_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_inventory_adjustment_ingredients.del_status='Live' AND  tbl_inventory_adjustment_ingredients.consumption_status='Minus') total_consumption_minus,
(select SUM(quantity_amount) from tbl_production_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_production_ingredients.del_status='Live' AND tbl_production_ingredients.status=1) total_production,
(select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND  tbl_transfer_ingredients.status=1 AND tbl_transfer_ingredients.transfer_type=1) total_transfer_plus,
(select SUM(quantity_amount) from tbl_production_ingredients  where ingredient_id=i.id AND outlet_id=$outlet_id AND  tbl_production_ingredients.del_status='Live' AND tbl_production_ingredients.status=1) total_production,
(select SUM(quantity_amount) from tbl_transfer_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_ingredients.del_status='Live' AND (tbl_transfer_ingredients.status=1) AND tbl_transfer_ingredients.transfer_type=1) total_transfer_minus,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND to_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND  tbl_transfer_received_ingredients.status=1) total_transfer_plus_2,
(select SUM(quantity_amount) from tbl_transfer_received_ingredients  where ingredient_id=i.id AND from_outlet_id=$outlet_id AND  tbl_transfer_received_ingredients.del_status='Live' AND (tbl_transfer_received_ingredients.status=1)) total_transfer_minus_2

FROM tbl_ingredients i  LEFT JOIN (select * from tbl_ingredients where del_status='Live') ingr_tbl ON ingr_tbl.id = i.id LEFT JOIN (select * from tbl_ingredient_categories where del_status='Live') ingr_cat_tbl ON ingr_cat_tbl.id = ingr_tbl.category_id LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl ON ingr_unit_tbl.id = ingr_tbl.unit_id  LEFT JOIN (select * from tbl_units where del_status='Live') ingr_unit_tbl2 ON ingr_unit_tbl2.id = ingr_tbl.purchase_unit_id WHERE  i.company_id= '$company_id' AND i.del_status='Live' $where  GROUP BY i.id")->result();

        return $result;
    }
    /**
     * get All By Company Id For Dropdown
     * @access public
     * @return object
     * @param int
     * @param string
     */
    public function getAllByCompanyIdForDropdown($company_id, $table_name) {
        $result = $this->db->query("SELECT * 
          FROM $table_name 
          WHERE company_id=$company_id AND del_status = 'Live'  
          ORDER BY name ASC")->result();
        return $result;
    }

}

?>
