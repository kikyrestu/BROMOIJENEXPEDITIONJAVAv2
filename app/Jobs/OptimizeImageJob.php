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
        protected string $fieldName = 'thumbnail',
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
            if ($newPath && $newPath !== $this->filePath) {
                $model = $this->modelClass::find($this->modelId);
                if (!$model) return;

                // Handle array fields (e.g., gallery)
                $currentValue = $model->{$this->fieldName};
                if (is_array($currentValue)) {
                    $updated = array_map(
                        fn ($p) => $p === $this->filePath ? $newPath : $p,
                        $currentValue
                    );
                    if ($updated !== $currentValue) {
                        $model->withoutEvents(fn () => $model->update([$this->fieldName => $updated]));
                    }
                } else {
                    // Handle scalar fields (e.g., thumbnail)
                    if ($currentValue === $this->filePath) {
                        $model->withoutEvents(fn () => $model->update([$this->fieldName => $newPath]));
                    }
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
