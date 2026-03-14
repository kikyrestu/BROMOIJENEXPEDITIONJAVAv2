<?php

namespace App\Jobs;

use App\Services\ImageOptimizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OptimizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(
        protected string $modelClass,
        protected int $modelId,
        protected string $filePath,
        protected string $disk = 'public',
        protected string $mode = 'standard',
    ) {}

    public function handle(): void
    {
        if (!Storage::disk($this->disk)->exists($this->filePath)) {
            Log::warning('OptimizeImageJob: file not found, skipping.', ['path' => $this->filePath]);
            return;
        }

        $optimizer = new ImageOptimizationService();

        if ($this->mode === 'in_place') {
            $newPath = $optimizer->optimizeInPlace($this->filePath, $this->disk);
            if ($newPath) {
                $model = $this->modelClass::find($this->modelId);
                if ($model && $model->thumbnail === $this->filePath) {
                    $model->withoutEvents(fn () => $model->update(['thumbnail' => $newPath]));
                }
            }
            return;
        }

        $result = $optimizer->optimize($this->filePath, $this->disk);

        $model = $this->modelClass::find($this->modelId);
        if (!$model) {
            return;
        }

        $model->withoutEvents(function () use ($model, $result) {
            $model->update([
                'optimized_path' => $result['optimized_path'],
                'thumbnail_path' => $result['thumbnail_path'],
            ]);
        });
    }
}
