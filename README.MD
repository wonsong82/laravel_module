# INSTALLATION

DO NOT RUN

    composer require wonsong82/webpack
    composer require wonsong82/laravel-DBController
    
-------------------------
    
INSTALLATION
    
    composer install
    php artisan vendor:publish --provider="Won\Webpack\WebpackServiceProvider" --force
    
---------------------

    edit package.json
    npm install

    sudo chgrp -R www-data storage
    sudo chgrp -R www-data bootstrap/cache
    
    sudo chmod -R ug+rwx storage
    sudo chmod -R ug+rwx bootstrap/cache
    
----------------------

INIT

    php artisan module:vendor:patch
    
    
    
    



# Development Convention

## # Barcode
- Item, Products: ean-13(Korea, Europe, East Asia), upc-e(United States)
- Receiving Order, Palete, Box: code 128-B

## # Naming

#### @ Sub Name
- Descriptive name.

#### @ Database Name
- snake_case type.

#### @ Database Table Name
- snake_case type.
- Strictly follow laravel table structure & convention.
  - ModelEntity -> model_entities (plural)
  - One to One | One to Many -> parent_entity_childrens (singular_plural)
  - Many to Many -> relation_relation (singular_singular)

#### @ Database Fields Name
- Include 'id', 'created_at', 'updated_at' (complete table structure)
- 'bold' for foreign key (ctrl+B)
- 'italic' for index (for multi index, please make a note) (ctrl+I)
- 'unique' for unique (for multi unique, please make a note) (ctrl+U)
- 'strike-through' for nullable (alt+shift+5)
- Any field that can contain multiple values, use 'Constant'.
  - Field name must include _code at the end.
    - Example `'Shipping Status' => 'shipping_status_code'`
    - Example `'Item Process Type' => 'item_process_type_code'`
  - List up all the constants into note
- For anything else (like default value), make a note

#### @ Database Field type convention
- Primary key
  - 'id' bigint unsigned increment
- DateTime
  - '_at' datetime
- Date
  - '_date' date
- Constants
  - '_code' integer
- Boolean
  - 'is_' boolean
- Price
  - Decimal (22, 8)
  - Cast for decimal must be setted `real` for type casting

#### @ File Name
- hyphen-case type.

#### @ URI Name
- hyphen-case type.

#### @ Class Name
- PascalCase type.

#### @ All Others
- camelCase
---

## # Git Branch

- Make branch from master
- Decide branch name with a team

---

# Module

#### 1. Generate Module
```
php artisan module:generate ModuleName ModuleNumber
```
- Module name is PascalCase type.
- Assign number in migration orders.
  - Example
    ```
    Item cannot be assigned before Business because Item requires one or more tables from Business
    ```
- Module number is for migration order
- Decide Module number with a team
- Reference module structure from Company module


#### 2. Add Module To Service Provider

- After generating module, Add module to providers list in config/app.php
  ```
  Module\Receiving\ModuleNameServiceProvider::class
  ```
- Provider method executing order is..
  1. register()
  2. boot()


#### 3. Generate Crud Controller
```
php artisan module:make TestModule CrudController TestClass
```
- CrudController name is PascalCase type.
- Heading
```
 traits:
  CrudTrait
  CustomCrudTrait
  AddressCrudTrait    
```

#### 4. Generate Model
```
php artisan module:make TestModule Model TestModel
```
- CrudController name is PascalCase type.
- Input field which user can write on `$fillable`
- Hasorder is using (Left Right Algorithm)
- Datetime type support Carbon library -> useful for date and time data handling
- Trait for CRUD must placed to bottom and others to top
- Heading
```
 traits:
  HasConstants
  HasOrder
  HasActivityLogs
  HasModelChanges
  HasPhones
  HasAddresses
  HasNotifications

 scopeOrder

 scopeSearch
```

#### 5. Generate Event
```
php artisan module:make Test Event Test
```
- CrudController name is PascalCase type.


#### 6. Generate Request
```
php artisan module:make Test Request Test
```
- CrudController name is PascalCase type.


#### 7. Generate Constant with Status
```
php artisan module:make Test Constant Test
```
- CrudController name is PascalCase type.
- Field name must be ${var}_code
  - Example
    ```
    status -> status_code
    ```
- 200101 = 2:ModuleNumber, 1:Constant, 1:Value
- Add translation by Constant name in `Traslations/ko`
- $this->${var} will return object

#### 8. Generate Logic Contoller
```
php artisan module:make Test:LogicController Test
```
- CrudController name is PascalCase type.
- Add 'Create', 'Update', 'Delete' Method.


#### 9. Generate Database Migration
```
2018_01_01_000200_create_company_tables.php
```
- Ordered by module number and date
- Write both up and down function for `migrate:refresh`

#### 10. Add Translation
- Generals use: module::filename.fieldname
- Constants: module::constant.constant_key
- For crud list page, Use constant type (constant.blade.php)


## Other tips

- 코드 수정 후 php artisan tinker
- 키이름은 다른 모듈들 참조해서 동일하게 하거나 DB에 Constants 테이블에 가보면 생성되어있는데 그대로 사용
- 데이터베이스 접근 및 사용 시 로그액티비티 자세하게 기록
리스너에 추가
- For custom view using `@extends`
  ```
  @extends ('application::crud.custom.create')
  ```
- 커스텀 트레잇은 항상 추가 컴퍼니트레잇은 컴퍼니를 사용하는 데만
- 컬럼추가시에 type 필드 추가하면 typescasting 가능
- SerialCode
  ```
  $dispatchesEvent = [
    'creating' => SerializedModelCreating::class,
    'created' => SerializedModelCreated::class
  ];
  public $serialCode = 'code';
  ```
```
popup
multi list
list
```

    
    
    