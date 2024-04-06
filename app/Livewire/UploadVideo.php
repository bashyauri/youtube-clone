<?php

namespace App\Livewire;

use App\Jobs\EncodeVideo;
use App\Jobs\GenerateThumbnail;
use App\Livewire\Forms\UploadVideoForm;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\On;
use Livewire\Component;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UploadVideo extends Component
{
    use WithFileUploads;
    public bool $modal = false;
    public UploadVideoForm $form;

    public bool $uploaded = false;
    public Video $video;

    #[On('toggleModal')]
    public function toggleModal()
    {


        $this->modal = !$this->modal;
    }
    public function handleChunk(Request $request)
    {
        $receiver = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class
        );

        $save = $receiver->receive();

        if ($save->isFinished()) {
            return response()->json([
                'file' => $save->getFile()->getFilename()
            ]);
        }

        $save->handler();
    }
    public function handleSuccess($name, $path)
    {
        $file = new UploadedFile(storage_path('app/chunks/' . $path), $name);

        $this->video = auth()->user()->videos()->create([
            'title' => $file->getClientOriginalName(),
            'original_file_path' => $file->storeAs('videos', Str::uuid() . '.mp4', [
                'disk' => 'public'
            ])
        ]);
        $this->uploaded = true;
        EncodeVideo::dispatch($this->video);
        GenerateThumbnail::dispatch($this->video);
    }
    public function render()
    {

        return view('livewire.upload-video');
    }
    public function updateVideo()
    {
    }
}
