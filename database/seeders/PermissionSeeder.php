<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions_admin = [


            'role-table',
            'role-add',
            'role-edit',
            'role-delete',

            'employee-table',
            'employee-add',
            'employee-edit',
            'employee-delete',

            'customer-table',
            'customer-add',
            'customer-edit',
            'customer-delete',


            'order-table',
            'order-add',
            'order-edit',
            'order-delete',



            'delivery-table',
            'delivery-add',
            'delivery-edit',
            'delivery-delete',

            'notification-table',
            'notification-add',
            'notification-edit',
            'notification-delete',

            'setting-table',
            'setting-add',
            'setting-edit',
            'setting-delete',

            'category-table',
            'category-add',
            'category-edit',
            'category-delete',

            'unit-table',
            'unit-add',
            'unit-edit',
            'unit-delete',

            'representative-table',
            'representative-add',
            'representative-edit',
            'representative-delete',

            'country-table',
            'country-add',
            'country-edit',
            'country-delete',

            'wholeSale-table',
            'wholeSale-add',
            'wholeSale-edit',
            'wholeSale-delete',

            'shop-table',
            'shop-add',
            'shop-edit',
            'shop-delete',

            'product-table',
            'product-add',
            'product-edit',
            'product-delete',

            'offer-table',
            'offer-add',
            'offer-edit',
            'offer-delete',

            'coupon-table',
            'coupon-add',
            'coupon-edit',
            'coupon-delete',

            'warehouse-table',
            'warehouse-add',
            'warehouse-edit',
            'warehouse-delete',

            'noteVoucherType-table',
            'noteVoucherType-add',
            'noteVoucherType-edit',
            'noteVoucherType-delete',

            'noteVoucher-table',
            'noteVoucher-add',
            'noteVoucher-edit',
            'noteVoucher-delete',

            'city-table',
            'city-add',
            'city-edit',
            'city-delete',
       
            'page-table',
            'page-add',
            'page-edit',
            'page-delete',

        ];

         foreach ($permissions_admin as $permission_ad) {
            Permission::create(['name' => $permission_ad, 'guard_name' => 'admin']);
        }
    }
}
