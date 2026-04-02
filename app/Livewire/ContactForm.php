<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name = '';

    public string $email = '';

    public string $message = '';

    public bool $sent = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function submit(): void
    {
        $this->sent = false;
        $this->validate();

        ContactMessage::create([
            'name' => strip_tags(trim($this->name)),
            'email' => strip_tags(trim($this->email)),
            'message' => strip_tags(trim($this->message)),
        ]);

        $this->reset(['name', 'email', 'message']);
        $this->resetValidation();
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
