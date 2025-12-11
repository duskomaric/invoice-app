<?php

return [
    'notification_text' => 'Certain features may be temporarily limited as we roll out new updates. We appreciate your understanding during this transition.',
    'notification_type' => 'danger',
    'notification_enabled' => false,
    'dashboard_under_maintenance' => false,
    'dashboard_under_maintenance_title' => 'Dashboard Under Maintenance',
    'dashboard_under_maintenance_text' => '<p>We&rsquo;re currently performing scheduled maintenance.</p><p>Please check back soon!</p>',

    'github_token' => '',
    'log_viewer_access_key' => '',
    'pagination' => [10, 25, 50, 100],
    'support_link' => 'https://plusplusit.atlassian.net/servicedesk/customer/portal/1',

    'modal_width' => 'max',
    'default_pagination_option' => 10,
    'top_navigation' => false,
    'primary_color' => 'Amber',
    'danger_color' => 'Red',
    'gray_color' => 'Slate',
    'info_color' => 'Blue',
    'success_color' => 'Green',
    'warning_color' => 'Orange',

    'invoice_email_subject' => 'Invoice #{{ number }}',
    'invoice_email_body' => "# Hello {{ client }},\n\nPlease find attached the invoice #{{ number }} for your recent purchase.\n\n**Total Amount:** {{ amount }}\n**Due Date:** {{ due_date }}\n\nThank you for your business,\n{{ company }}",
    'invoice_email_subject_sr' => 'Faktura #{{ number }}',
    'invoice_email_body_sr' => "# Poštovani {{ client }},\n\nU prilogu se nalazi faktura #{{ number }} za vašu nedavnu kupovinu.\n\n**Ukupan iznos:** {{ amount }}\n**Datum dospijeća:** {{ due_date }}\n\nHvala na povjerenju,\n{{ company }}",
    'invoice_pdf_filename_format' => 'invoice_{{ number }}.pdf',

    'company_name' => 'Your Company Name',
    'company_address' => '123 Business Street, City, Country',
    'company_email' => 'info@company.com',
    'company_phone' => '+1234567890',
    'company_vat_id' => '',
    'company_bank_account' => '',

    // OFS Fiscalization Settings (Test credentials from api.ofs.ba)
    'ofs_base_url' => 'https://pos.ofs.ba',
    'ofs_api_key' => 'bb7584a167578b89c459d6ab1759b0cc',
    'ofs_serial_number' => 'F41AEFFF110A4B5ABB266299A41EE479',
    'ofs_pac' => '123456',
    'ofs_seller_tin' => '4401136590007',
    'ofs_seller_name' => 'Throwcode sp',
    'ofs_seller_address' => 'Ulica bb',
    'ofs_seller_town' => 'Pranjvor',

    // Invoice Numbering Settings
    'invoice_prefixes' => [
        'BAM' => 'BAM',
        'EUR' => 'EUR',
    ],
    'invoice_sequences' => [
        'BAM' => [],
        'EUR' => [],
    ],
    'invoice_default_currency' => 'BAM',

];
