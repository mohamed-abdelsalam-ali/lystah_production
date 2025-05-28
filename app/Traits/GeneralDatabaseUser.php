<?php

namespace App\Traits;

use App\Models\Company\User;

trait GeneralDatabaseUser
{
    public function getGeneralUser()
    {
        return User::on('mysql_general')->where('email', $this->email)->first();
    }

    public function updateFromGeneralUser()
    {
        $generalUser = $this->getGeneralUser();
        
        if ($generalUser) {
            $this->subscription_plan = $generalUser->subscription;
            $this->subscription_expires_at = $generalUser->subscription_expires_at;
            $this->subscription_status = $generalUser->subscription_status;
            $this->payment_status = $generalUser->payment_status;
            $this->save();
        }
    }

    public function isSubscriptionActive()
    {
        $generalUser = $this->getGeneralUser();
        return $generalUser && 
               $generalUser->subscription_status === 'active' && 
               $generalUser->subscription_expires_at > now();
    }

    public function isPaymentComplete()
    {
        $generalUser = $this->getGeneralUser();
        return $generalUser && $generalUser->payment_status === 'completed';
    }

    public function isUserActive()
    {
        $generalUser = $this->getGeneralUser();
        return $generalUser && $generalUser->status === 'active';
    }
} 