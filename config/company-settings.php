<?php

return [
    'pagination' => [10, 25, 50, 100],
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

    'default_invoice_template' => 'classic',
    'default_invoice_language' => 'en',
    'default_invoice_currency' => 'USD',
    'default_invoice_due_days' => 3,
    'default_company_bank_account_id' => 0,

    'company_name' => 'Your Company Name',
    'company_address' => '123 Business Street, City, Country',
    'company_email' => 'info@company.com',
    'company_phone' => '+1234567890',
    'company_vat_id' => '',

    'ofs_base_url' => 'https://pos.ofs.ba',
    'ofs_api_key' => 'bb7584a167578b89c459d6ab1759b0cc',
    'ofs_serial_number' => 'F41AEFFF110A4B5ABB266299A41EE479',
    'ofs_pac' => '123456',
    'ofs_seller_tin' => '4401136590007',
    'ofs_seller_name' => 'Throw Code sp',
    'ofs_seller_address' => 'Ulica bb',
    'ofs_seller_town' => 'Pranjvor',

    'invoice_numbering_reset_yearly' => true,
    'invoice_numbering_pad_zeros' => 3,
    'invoice_numbering_starting_number' => 1,
    'invoice_numbering_prefix' => '',//''currency',

    'getting_started_checklist' => [],

    'smtp_host' => '',
    'smtp_port' => '',
    'smtp_username' => '',
    'smtp_password' => '',
    'smtp_encryption' => '',
    'smtp_from_address' => '',
    'smtp_from_name' => '',
];
