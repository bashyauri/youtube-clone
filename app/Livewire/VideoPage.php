<?php

namespace App\Livewire;

use App\Models\Video;
use Livewire\Component;

class VideoPage extends Component
{
    public Video $video;

    public function like(): void
    {
        $this->video->updateLikeStatus('like');
        $this->video->loadCount(['likes', 'dislikes']);
    }

    public function dislike(): void
    {
        $this->video->updateLikeStatus('dislike');
        $this->video->loadCount(['likes', 'dislikes']);
    }

    public function render()
    {
        return view('livewire.video-page');
    }
}
