<?php
declare(strict_types=1);

namespace BarkaneArts;

/**
 * @param string $message
 * @param int $responseCode
 */
function fatalError(string $message, int $responseCode = 0)
{
    switch ($responseCode) {
        case 404:
            \header('HTTP/1.1 404 Not Found');
            break;
        default:
            \header('HTTP/1.1 400 Bad Request');
    }
    echo \json_encode(
        [
            'error' =>
                $message
        ],
        JSON_PRETTY_PRINT
    );
    exit;
}

/**
 * Copy then fetch the desired file
 *
 * @param string $desiredFilename
 * @param string $templateFilePath
 * @return string
 * @throws \Exception
 */
function getDataFile(string $desiredFilename, string $templateFilePath): string
{
    if (!\file_exists($desiredFilename)) {
        if (!\copy($templateFilePath, $desiredFilename)) {
            throw new \Exception(
                \sprintf(
                    'Could not copy %s to %s',
                    $templateFilePath,
                    $desiredFilename
                )
            );
        }
    }
    return $desiredFilename;
}
