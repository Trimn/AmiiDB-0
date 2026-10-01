<?php

namespace App\Forms;

use App\Models\NavCategories;
use ProtoneMedia\Splade\SpladeForm;
use ProtoneMedia\Splade\AbstractForm;
use ProtoneMedia\Splade\FormBuilder\Text;
use ProtoneMedia\Splade\FormBuilder\Select;
use ProtoneMedia\Splade\FormBuilder\Submit;

class CreateNavigationForm extends AbstractForm
{
    public function configure(SpladeForm $form)
    {
        $form
        ->action(route('admin.nav_create'))
        ->method('POST');
    }

    public function fields(): array
    {
        $keys = array_keys(\Illuminate\Support\Facades\Route::getRoutes()->getRoutesByName());
        $routes = array_combine($keys, $keys);

        return [
            Text::make('name')->label('Name')->rules('required'),
            Select::make('route')->options($routes)->label('Route')->rules('required'),
            Select::make('category')->options(NavCategories::options())->label('Category')->rules('required'),
            Text::make('order')->label('Order')->rules('required|numeric'),
            Text::make('permission')->label('Permission')->rules('required'),
            Submit::make('submit')->label('Submit'),
        ];
    }
}
