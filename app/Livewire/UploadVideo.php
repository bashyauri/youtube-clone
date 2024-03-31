<?php

namespace App\Livewire;

use App\Jobs\EncodeVideo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\On;
use Livewire\Component;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Support\Str;

class UploadVideo extends Component
{
    public bool $modal2 = false;
    public Video $video;

    #[On('toggleModal')]
    public function toggleModal()
    {


        $this->modal2 = !$this->modal2;
    }
    public function handleChunk(Request $request)
    {
        $reciever = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class
        );
        $save = $reciever->receive();
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
        EncodeVideo::dispatch($this->video);
    }
    public function render()
    {

        return view('livewire.upload-video');
    }
}
