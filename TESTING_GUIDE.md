# 📋 Beldon POS - External Food & Daily Operations Testing Guide
> **පද්ධතියේ External Food, POS Live Stock Display, Quick POS Purchase, Dedicated Daily Waste, සහ Enhanced Date-wise Stock Reports සඳහා වන සම්පූර්ණ පරීක්ෂණ මාර්ගෝපදේශය (Full QA Testing Guide)**

---

## 📌 1. හැඳින්වීම සහ ප්‍රධාන Features (Overview of Features)

1. **Pre-Made / Direct Food Auto-Sync (`PreMadeFood`):** Pre-made food එකක් ඇතුළත් කළ විට එය ස්වයංක්‍රීයව POS Food Menu එකක් ලෙස sync වී POS screen එකේ සෘජුවම විකිණීමට හැකි වීම.
2. **POS Live Stock Badge on Product Cards:** Pre-Made / Short-eats වර්ග වල ඉතිරි live stock එක POS screen එකේ අදාළ item card එක මතම වර්ණ කේත (Color-coded Badges) සහිතව සජීවීව පෙන්වීම:
   - 🟢 **කොළ (In Stock):** Stock > 5 (උදා: `Stock: 20 pcs`)
   - 🟠 **තැඹිලි (Low Stock):** Stock ≤ 5 (උදා: `Low: 3 pcs`)
   - 🔴 **රතු (Out of Stock):** Stock ≤ 0 (උදා: `Out of Stock (0)`)
3. **Quick POS External Food Purchase (`Purchase/saveExternalPreMadePurchase`):** පිටස්තර බේකරි වලින් ලබාගන්නා කෙටි කෑම වර්ග POS screen එකෙන් ඉවත් නොවී modal එකක් මගින් කෙලින්ම Stock එකට add කිරීම.
4. **Dedicated Manual Daily Waste (`Waste/dailyWaste`):** දවස අවසානයේ විකිණීමට නොහැකි වූ ඉතිරි කෙටි කෑම (Short-eats/Bakery items) Loss Amount ගණනය සමග එකවර Waste ලෙස සටහන් කිරීම.
5. **Enhanced Stock Report with Date Filters (`Report/stockReport`):** තෝරාගත් දිනකට හෝ Date Range එකකට අදාළව Stock In, Out, Waste සහ Live Stock වෙන වෙනම විශ්ලේෂණය කිරීම.
6. **Daily Sales Reports & Quick Date Navigation:** Sidebar එකෙන් Daily Sale වෙත පිවිසීම සහ Today / Yesterday Quick Filter buttons.

---

## ⚙️ 2. මූලික සූදානම (Pre-requisites)

- Admin හෝ Cashier ගිණුමකින් පද්ධතියට Login වීම.
- වලංගු Outlet එකක් select කර තිබීම.
- POS Register එක විවෘතව (Open) පැවතීම (`Sale/POS`).

---

## 🧪 3. පියවරෙන් පියවර පරීක්ෂණ අදියර (Step-by-Step Test Scenarios)

### 🔹 Test Case 1: Pre-Made Food එකක් සාදා POS එකට Auto-Sync වීම පරීක්ෂාව
1. Navigation Menu එකෙන් **Master > Pre-Made Food > Add Pre-Made Food** (`PreMadeFood/addEditPreMadeFood`) වෙත පිවිසෙන්න.
2. පහත දත්ත ඇතුළත් කරන්න:
   - **Name:** `Fish Pastry (Test)`
   - **Code:** `FP-01`
   - **Purchase Price (Cost):** `60.00`
   - **Sale Price:** `90.00`
   - **Category:** (Bakery items හෝ කැමති category එකක්)
3. **Submit** button එක click කරන්න.
* **අපේක්ෂිත ප්‍රතිඵලය:** POS screen එකේ `Fish Pastry (Test)` නමින් Food Card එකක් සෑදී තිබීම.

---

### 🔹 Test Case 2: POS Screen එකේ Live Stock Badge එක පරීක්ෂාව
1. **Sale > POS** screen එක open කරන්න.
2. අලුතින් එක් කළ `Fish Pastry (Test)` card එක බලන්න:
   - මුලින් stock නොමැති නිසා රතු පැහැති **`Out of Stock (0)`** badge එකක් දිස්විය යුතුය.
   - සාමාන්‍ය Kitchen කෑම වල (Fried Rice, Kottu ආදී) මෙම badge එක නොපෙන්වා card එක පිරිසිදුව පැවතිය යුතුය.

---

### 🔹 Test Case 3: POS Screen එකෙන් External Food Quick Purchase කිරීම සහ Stock Badge එක වැඩි වීම
1. POS top bar එකේ ඇති **"External Food"** (<i class="fa fa-store"></i>) button එක click කරන්න.
2. Modal එකේ:
   - **Item:** `Fish Pastry (Test)` තෝරන්න.
   - **Quantity Amount:** `20`
   - **Unit Price:** `60.00`
   - **Payment Status:** `Paid Now`
3. **"Add to Stock"** click කරන්න.
* **අපේක්ෂිත ප්‍රතිඵලය:** Modal එක close වී card එකේ badge එක ක්ෂණිකව කොළ පැහැති **`Stock: 20 pcs`** බවට පත්විය යුතුය.

---

### 🔹 Test Case 4: POS Sale එකක් දැමූ විට Card එකේ Stock එක අඩුවීම
1. POS screen එකේ `Fish Pastry (Test)` item එක click කර Cart එකට **5 pcs** එකතු කරන්න.
2. Sale එක complete කරන්න (Pay & Print).
* **අපේක්ෂිත ප්‍රතිඵලය:** බිල complete වූ සැනින් `Fish Pastry` card එකේ badge එක **`Stock: 15 pcs`** (20 - 5) ලෙස ස්වයංක්‍රීයව අඩුවිය යුතුය.
3. නැවත තවත් **12 pcs** විකුණන්න:
* **අපේක්ෂිත ප්‍රතිඵලය:** ඉතිරි තොගය 3 වූ විට badge එක තැඹිලි පැහැති **`Low: 3 pcs`** ලෙස alert එකක් පෙන්විය යුතුය.

---

### 🔹 Test Case 5: Daily Manual Waste (දවස අවසානයේ ඉතිරි කෑම Waste කිරීම)
1. POS screen එකේ ඇති **"Add Waste"** (<i class="far fa-trash-alt"></i>) button එක click කරන්න (`Waste/dailyWaste`).
2. `Fish Pastry (Test)` item එකේ current stock එක `3 pcs` ලෙස පෙන්වයි.
3. Waste Qty එකට `3` ඇතුළත් කරන්න හෝ "Waste All Remaining" click කරන්න (Loss: `180.00`).
4. **"Save Waste"** click කරන්න.
* **අපේක්ෂිත ප්‍රතිඵලය:** Waste save වී POS වෙත පැමිණි විට badge එක නැවත රතු පැහැති **`Out of Stock (0)`** ලෙස දිස්විය යුතුය.

---

### 🔹 Test Case 6: Stock Report (Date Filter සහ In/Out/Waste ගණනය)
1. **Report > Stock Report** (`Report/stockReport`) වෙත පිවිසෙන්න.
2. **"Today"** quick filter button එක click කරන්න.
* **අපේක්ෂිත ප්‍රතිඵලය:** Total In: `20`, Total Out: `17` (5 + 12), Total Waste: `3`, Current Stock: `0 pcs` ලෙස නිවැරදිව balance විය යුතුය.

---

## 🗄️ 4. Database Verification Queries (SQL Queries)

```sql
-- 1. Pre-Made Food සහ Food Menu Auto-Sync පරීක්ෂාව
SELECT id, name, code, purchase_price, consumption_unit_cost, is_direct_food 
FROM tbl_ingredients 
WHERE ing_type = 'Pre-made Item' AND del_status = 'Live' 
ORDER BY id DESC LIMIT 1;

-- 2. Quick POS External Purchase Records පරීක්ෂාව
SELECT id, reference_no, date, grand_total, paid, due 
FROM tbl_purchase 
ORDER BY id DESC LIMIT 1;

-- 3. Sale Consumption Record පරීක්ෂාව
SELECT id, sale_id, food_menu_id, ingredient_id, consumption 
FROM tbl_sale_consumptions_of_menus 
ORDER BY id DESC LIMIT 5;

-- 4. Waste Records පරීක්ෂාව
SELECT id, reference_no, date, total_loss 
FROM tbl_wastes 
ORDER BY id DESC LIMIT 1;
```

---

## ✅ 5. Final QA Sign-Off Checklist

- [ ] Pre-made items වලට පමණක් POS Product Card එකේ Stock Badge එක පෙන්වයි.
- [ ] සාමාන්‍ය Kitchen foods වලට අනවශ්‍ය ලෙස Stock Badge එක නොපෙන්වයි.
- [ ] Stock > 5 වන විට කොළ පැහැයෙන් `Stock: X pcs` දිස්වේ.
- [ ] Stock 1 - 5 අතර වන විට තැඹිලි පැහැයෙන් `Low: X pcs` දිස්වේ.
- [ ] Stock 0 හෝ ඊට අඩු වන විට රතු පැහැයෙන් `Out of Stock (0)` දිස්වේ.
- [ ] External Food Modal එකෙන් මිලදී ගත් විට badge එක ක්ෂණිකව වැඩි වේ.
- [ ] බිලක් දැමූ විට card එකේ stock count එක ක්ෂණිකව අඩු වේ.

