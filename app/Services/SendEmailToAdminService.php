<?php

namespace App\Services;

use App\Mail\SendGridMailer;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendEmailToAdminService
{
    public function sendEmailToAdmins($templateId, $valueArray, $includeAdmins = false)
    {
        foreach (
            User::without('expert', 'location', 'sectors')
                ->role($includeAdmins ? ['SuperAdmin', 'Admin'] : ['SuperAdmin'])
                ->select('email', 'name')
                ->get() as $admin
        ) {
            Mail::to([
                ['email' => $admin->email, 'name' => $admin->name],
            ])->send(new SendGridMailer($templateId, array_merge(
                ['admin_name' => $admin->name],
                $valueArray
            )));
        }
    }
}
