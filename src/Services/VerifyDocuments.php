<?php

namespace Srmklive\PayPal\Services;

use GuzzleHttp\Psr7\MimeType;

class VerifyDocuments
{
    /**
     * @var array
     */
    protected static $dispute_evidence_types = [
        'application/pdf',
        'image/gif',
        'image/jpeg',
        'image/png',
    ];

    /**
     * @var string
     */
    protected static $dispute_evidence_file_size = 10;

    /**
     * @var string
     */
    protected static $dispute_evidences_size = 50;

    /**
     * Get Mime type from filename.
     *
     * @param string $file
     *
     * @return string
     */
    public static function getMimeType($file)
    {
        return MimeType::fromFilename($file);
    }

    /**
     * Check if the evidence file being submitted mime type is valid.
     *
     * @param array $files
     *
     * @return bool
     */
    public static function isValidEvidenceFile(array $files)
    {
        $validFile = true;
        $validSize = true;
        $total_size = 0;

        self::setFilesSize();

        foreach ($files as $file) {
            $mime_type = self::getMimeType($file);

            if (!in_array($mime_type, self::$dispute_evidence_types)) {
                $validFile = false;
                break;
            }

            $size = filesize($file);

            if ($size > self::$dispute_evidence_file_size) {
                $validSize = false;
                break;
            }

            $total_size += $size;

            if ($size > self::$dispute_evidences_size) {
                $validSize = false;
                break;
            }
        }

        if (($validFile === false) || ($validSize === false)) {
            return false;
        }

        return true;
    }

    /**
     * Check file size.
     *
     * @param int $bytes
     * @param int $decimals
     *
     * @return array
     */
    protected static function setFilesSize()
    {
        $size_in_bytes = pow(1024, 2);
    
        self::$dispute_evidence_file_size *= $size_in_bytes;
        self::$dispute_evidences_size *= $size_in_bytes;
    }
}
