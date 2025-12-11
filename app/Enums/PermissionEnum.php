<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case DASHBOARD_VIEW = 'dashboard:view';

    // User permissions
    case USER_CREATE = 'user:create';
    case USER_EDIT = 'user:edit';
    case USER_LIST = 'user:list';
    case USER_VIEW = 'user:view';
    case USER_DELETE = 'user:delete';

    // Client permissions
    case CLIENT_CREATE = 'client:create';
    case CLIENT_EDIT = 'client:edit';
    case CLIENT_LIST = 'client:list';
    case CLIENT_VIEW = 'client:view';
    case CLIENT_DELETE = 'client:delete';

    // Invoice permissions
    case INVOICE_CREATE = 'invoice:create';
    case INVOICE_EDIT = 'invoice:edit';
    case INVOICE_LIST = 'invoice:list';
    case INVOICE_VIEW = 'invoice:view';
    case INVOICE_DELETE = 'invoice:delete';

    // Article permissions
    case ARTICLE_CREATE = 'article:create';
    case ARTICLE_EDIT = 'article:edit';
    case ARTICLE_LIST = 'article:list';
    case ARTICLE_VIEW = 'article:view';
    case ARTICLE_DELETE = 'article:delete';

    // Payment permissions
    case PAYMENT_CREATE = 'payment:create';
    case PAYMENT_EDIT = 'payment:edit';
    case PAYMENT_LIST = 'payment:list';
    case PAYMENT_VIEW = 'payment:view';
    case PAYMENT_DELETE = 'payment:delete';

    public function publicName(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW => 'View Dashboard',

            self::USER_CREATE => 'Create Users',
            self::USER_EDIT => 'Edit Users',
            self::USER_LIST => 'List Users',
            self::USER_VIEW => 'View User Details',
            self::USER_DELETE => 'Delete Users',

            self::CLIENT_CREATE => 'Create Clients',
            self::CLIENT_EDIT => 'Edit Clients',
            self::CLIENT_LIST => 'List Clients',
            self::CLIENT_VIEW => 'View Client Details',
            self::CLIENT_DELETE => 'Delete Clients',

            self::INVOICE_CREATE => 'Create Invoices',
            self::INVOICE_EDIT => 'Edit Invoices',
            self::INVOICE_LIST => 'List Invoices',
            self::INVOICE_VIEW => 'View Invoice Details',
            self::INVOICE_DELETE => 'Delete Invoices',

            self::ARTICLE_CREATE => 'Create Articles',
            self::ARTICLE_EDIT => 'Edit Articles',
            self::ARTICLE_LIST => 'List Articles',
            self::ARTICLE_VIEW => 'View Article Details',
            self::ARTICLE_DELETE => 'Delete Articles',

            self::PAYMENT_CREATE => 'Create Payments',
            self::PAYMENT_EDIT => 'Edit Payments',
            self::PAYMENT_LIST => 'List Payments',
            self::PAYMENT_VIEW => 'View Payment Details',
            self::PAYMENT_DELETE => 'Delete Payments',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW => 'Allows viewing the dashboard.',

            self::USER_CREATE => 'Allows the user to create new users.',
            self::USER_EDIT => 'Allows the user to edit users (including roles/permissions).',
            self::USER_LIST => 'Allows the user to view users list.',
            self::USER_VIEW => 'Allows the user to view user details.',
            self::USER_DELETE => 'Allows the user to soft-delete users.',

            self::CLIENT_CREATE => 'Allows creating new clients.',
            self::CLIENT_EDIT => 'Allows editing client information.',
            self::CLIENT_LIST => 'Allows viewing the clients list.',
            self::CLIENT_VIEW => 'Allows viewing client details.',
            self::CLIENT_DELETE => 'Allows deleting clients.',

            self::INVOICE_CREATE => 'Allows creating new invoices.',
            self::INVOICE_EDIT => 'Allows editing invoices.',
            self::INVOICE_LIST => 'Allows viewing the invoices list.',
            self::INVOICE_VIEW => 'Allows viewing invoice details.',
            self::INVOICE_DELETE => 'Allows deleting invoices.',

            self::ARTICLE_CREATE => 'Allows creating new articles/products.',
            self::ARTICLE_EDIT => 'Allows editing articles/products.',
            self::ARTICLE_LIST => 'Allows viewing the articles list.',
            self::ARTICLE_VIEW => 'Allows viewing article details.',
            self::ARTICLE_DELETE => 'Allows deleting articles.',

            self::PAYMENT_CREATE => 'Allows creating new payments.',
            self::PAYMENT_EDIT => 'Allows editing payments.',
            self::PAYMENT_LIST => 'Allows viewing the payments list.',
            self::PAYMENT_VIEW => 'Allows viewing payment details.',
            self::PAYMENT_DELETE => 'Allows deleting payments.',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::DASHBOARD_VIEW => 'Dashboard',
            
            self::USER_CREATE, self::USER_EDIT, self::USER_LIST, self::USER_VIEW, self::USER_DELETE => 'Users',
            
            self::CLIENT_CREATE, self::CLIENT_EDIT, self::CLIENT_LIST, self::CLIENT_VIEW, self::CLIENT_DELETE => 'Clients',
            
            self::INVOICE_CREATE, self::INVOICE_EDIT, self::INVOICE_LIST, self::INVOICE_VIEW, self::INVOICE_DELETE => 'Invoices',
            
            self::ARTICLE_CREATE, self::ARTICLE_EDIT, self::ARTICLE_LIST, self::ARTICLE_VIEW, self::ARTICLE_DELETE => 'Articles',
            
            self::PAYMENT_CREATE, self::PAYMENT_EDIT, self::PAYMENT_LIST, self::PAYMENT_VIEW, self::PAYMENT_DELETE => 'Payments',
        };
    }
}
