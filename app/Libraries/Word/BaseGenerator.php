<?php

namespace Libraries\Word;

use App\Services\Files\StorageService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\TemplateProcessor;

abstract class BaseGenerator
{
    /**
     * Читаем шаблон
     *
     * @param string $filePath
     * @return TemplateProcessor
     * @throws Exception
     */
    public function readTemplate(string $filePath, $landscape = false): TemplateProcessor
    {
        try {
            $word = new \PhpOffice\PhpWord\PhpWord();
            if ($landscape) {
                $sectionStyle = array('orientation' => 'landscape', 'marginTop' => 600,
                    'colsNum' => 2,
                );
                $word->addSection($sectionStyle);
            }

            if (file_exists($filePath)) {
                $this->templateProcessor = $word->loadTemplate($filePath);
            } else {
                $this->templateProcessor = $word->loadTemplate(public_path($filePath));
            }
        } catch (CopyFileException $e) {
            abort(500);
        } catch (CreateTemporaryFileException $e) {
            abort(500);
        }

        return $this->templateProcessor;
    }

    /**
     * Сохраняем шаблон
     *
     * @param string $savePath
     * @return void
     */
    public function TPSaveAs(string $savePath): void
    {
        $result = $this->templateProcessor->save();

        if(Storage::disk(env('SIGN_STORAGE', 'local'))->put($savePath, file_get_contents($result))) {
            @unlink($result);
        }
    }
}
