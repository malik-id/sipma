<?php
namespace App\Notifications;
use App\Models\CandidateRegistration;
use Illuminate\Notifications\Notification;
class RegistrationUpdated extends Notification
{
    public function __construct(public CandidateRegistration $registration) {}
    public function via(object $notifiable): array { return ['database']; }
    public function toArray(object $notifiable): array { return ['registration_id'=>$this->registration->id,'message'=>'Pendaftaran '.$this->registration->registration_number.': '.$this->registration->status->label()]; }
}
