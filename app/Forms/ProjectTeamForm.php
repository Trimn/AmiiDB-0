<?php

namespace App\Forms;

use App\Models\User;
use App\Models\ProjectTeam;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Select;

class ProjectTeamForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
            ->action(route('reference.store', 'project_team'));
    }

    public function fields(): array
    {
        $existingUserIds = ProjectTeam::pluck('user_id')->toArray();

        $users = User::whereNotIn('id', $existingUserIds)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return [
            Select::make('user_id')
                ->label('User')
                ->options($users)
                ->rules('required', 'exists:users,id', 'unique:project_team,user_id'),
        ];
    }
}
