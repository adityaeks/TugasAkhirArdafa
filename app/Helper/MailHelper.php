<?php
namespace App\Helper;

use App\Models\EmailConfiguration;

class MailHelper
{
    public static function setMailConfig()
    {
        $emailConfig = EmailConfiguration::first();

        $config = [
            'transport' => 'smtp',
            'host' => 'sandbox.smtp.mailtrap.io',
            'port' => '2525',
            'encryption' => 'tls',
            'username' => '2c9949550db4d0',
            'password' => '8317a85018137e',
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ];
    }
}
