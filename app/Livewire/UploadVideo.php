<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class UploadVideo extends Component
{
    public bool $modal2 = false;

    #[On('toggleModal')]
    public function toggleModal()
    {


        $this->modal2 = !$this->modal2;
    }
    public function handleChunk()
    {
        return 0;
    }
    public function render()
    {

        return view('livewire.upload-video');
    }
}
