<?php

namespace App\Services\Files;

use App\Models\AVR;
use App\Models\Contract;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class StorageService
 * @package App\Services\Signing
 */
class StorageService
{
    /**
     * @var string
     */
    private $storage;

    /**
     * @var string
     */
    private $prefix = 'documents';

    /**
     * StorageService constructor.
     */
    public function __construct()
    {
        $this->storage = env('SIGN_STORAGE', 'local');
        Storage::disk($this->storage)->makeDirectory($this->prefix);
    }

    /**
     * Store data to storage
     *
     * @param string $fileName
     * @param string $content
     * @return string|null
     */
    public function save(string $fileName, string $content): ?string
    {
        if(Storage::disk($this->storage)->put($fileName, $content)) {
            return $fileName;
        }

        return null;
    }

    /**
     * Get storage path
     * @param string $fileName
     * @return string
     */
    public static function path(string $fileName): string
    {
        return storage_path('app/' . $fileName);
    }

    /**
     * Preview storage file
     * @param string $fileName
     * @param bool $inline
     * @return StreamedResponse
     */
    public static function preview(string $fileName): StreamedResponse
    {
        $info = pathinfo($fileName);

        return Storage::disk(env('SIGN_STORAGE', 'local'))->download($fileName, $info['basename'], [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$info['basename'].'"',
        ]);
    }

    /**
     * Download storage file
     * @param string $fileName
     * @param string|null $name
     * @return StreamedResponse
     */
    public static function download(string $fileName, string $name = null): StreamedResponse
    {
        $info = pathinfo($fileName);
        return Storage::disk(env('SIGN_STORAGE', 'local'))->download($fileName, $name ?: $info['basename']);
    }
}
