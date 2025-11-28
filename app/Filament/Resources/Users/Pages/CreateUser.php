<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Mail\InviteUserMail;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Hash the password only if it's provided
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $data['invitation_code'] = Str::uuid();

        return User::create($data);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure the password is hashed
        $data['first_name'] = $data['first_name'] ?? '-/-';
        $data['last_name'] = $data['last_name'] ?? '-/-';
        $data['password'] = Hash::make(Str::uuid());

        return $data;
    }

    protected function afterCreate(): void
    {
        Mail::to($this->record->email)->send(new InviteUserMail($this->record));
        $this->redirect(UserResource::getUrl('index'));

    }
}
