<?php

namespace Filament\Resources\Actions;

use Filament\Components\Concerns;
use Filament\Forms\Form;
use Filament\Forms\HasForm;
use Filament\Page;
use Livewire\Component;

class EditRecord extends Page
{
    use Concerns\HasTitle;
    use Concerns\SendsToastNotifications;
    use Concerns\UsesResource;
    use HasForm;

    public $record;

    public $indexRoute = 'index';

    public function delete()
    {
        $this->record->delete();

        $this->redirect($this->getResource()::route($this->indexRoute));
    }

    public function getForm()
    {
        return Form::make($this->fields())
            ->context(static::class)
            ->record($this->record);
    }

    public function mount($record)
    {
        $this->record = static::getModel()::findOrFail($record);
    }

    public function submit()
    {
        $this->validateTemporaryUploadedFiles();

        $this->storeTemporaryUploadedFiles();

        $this->validate();

        $this->record->save();

        $this->notify('Saved!');
    }

    public function render()
    {
        return view('filament::resources.actions.edit-record', [
            'title' => static::getTitle(),
        ])->layout('filament::components.layouts.app');
    }
}
