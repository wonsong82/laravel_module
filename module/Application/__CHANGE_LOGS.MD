## 2019/05/09
- locale not nullable
- currency | payterm | unit all cascade
- constant
    - constant_code, constant_label, constant_code_text
- scrollbar
- popup
- datatable in crud
- unit, currency, payterm active | inactive



## 2019/05/01
- Added Locale (locales table) and updated all related files.
- Added belongsTos() in Model (Relations section). This will detect related changes in HasModelChanges
- Added module_loaded() helper function to check if certain module is loaded
    - look into sidebar_content for its implication
- Added defaults in scopeSearch and scopeOrder in Model
- Added module:make Locale 
- Added module.php in Translation. This will contain general information including nav menus

