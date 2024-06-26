<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveFiles extends Component
{
    use NotifyToast;
    use WithFileUploads;
    
    public $model;
    public $files;
    public $file;
    public $random;
    public $showForm = false;

    protected $listeners = [
        'addFileActivity' => 'addFileToggle',
        'fileDeleted' => 'getFiles',
    ];

    public function mount($model)
    {
        $this->model = $model;
        $this->random = rand();
        $this->getFiles();

        if ($this->files->count() < 1) {
            $this->showForm = true;
        }
    }

    public function upload()
    {
        $data = $this->validate([
            'file' => 'required',
        ]);

        $file = $this->file->store('laravel-crm/'.strtolower(class_basename($this->model)).'/'.$this->model->id.'/files');
        
        $this->model->files()->create([
            'external_id' => Uuid::uuid4()->toString(),
            'file' => $file,
            'name' => $this->file->getClientOriginalName(),
            'filesize' => $this->file->getSize(),
            'mime' => $this->file->getMimeType(),
        ]);

        $this->notify(
            'File uploaded',
        );

        $this->dispatchBrowserEvent('fileUploaded');

        $this->resetFields();
    }
    
    public function getFiles()
    {
        $this->files = $this->model->files()->latest()->get();
    }

    public function addFileToggle()
    {
        $this->showForm = ! $this->showForm;
        $this->dispatchBrowserEvent('addFileToggled');
    }

    private function resetFields()
    {
        $this->reset('file');
        $this->random = rand();
        $this->getFiles();
    }
    
    public function render()
    {
        return view('laravel-crm::livewire.files');
    }
}
