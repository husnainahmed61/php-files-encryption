<?php
/**
 * Define the number of blocks that should be read from the source file for each chunk.
 * For 'AES-128-CBC' each block consists of 16 bytes.
 * So if we read 10,000 blocks, we load 160kb into memory. You may adjust this value
 * to read/write shorter or longer chunks.
 */
define('FILE_ENCRYPTION_BLOCKS', 10000);
/**
 * Decrypts the passed file and removes the ".enc" extension.
 *
 * @param string $source Path to the encrypted file
 * @param string $key    The key used for decryption
 * @return bool Returns true if the file was decrypted and renamed successfully, false otherwise
 */
function decryptFile($source, $key)
{
    $key = substr(sha1($key, true), 0, 16);

    $dest = preg_replace('/\.enc$/', '', $source); // Remove the ".enc" extension

    $error = false;
    if ($fpIn = fopen($source, 'rb')) {
        // Read the initialization vector from the beginning of the file
        $iv = fread($fpIn, 16);

        if ($fpOut = fopen($dest, 'wb')) {
            // Skip the IV in the output file
            fwrite($fpOut, substr($iv, 16));

            while (!feof($fpIn)) {
                $ciphertext = fread($fpIn, 16 * FILE_ENCRYPTION_BLOCKS);
                $plaintext = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
                // Use the last 16 bytes of the current ciphertext as the next initialization vector
                $iv = substr($ciphertext, -16);
                fwrite($fpOut, $plaintext);
            }
            fclose($fpOut);
        } else {
            $error = true;
        }
        fclose($fpIn);
    } else {
        $error = true;
    }

    if (!$error) {
        unlink($source);
        return rename($dest, preg_replace('/\.dec$/', '', $dest)); // Remove the ".dec" extension
    } else {
        unlink($dest);
        return false;
    }
}

/**
 * Decrypt files in a directory and its subdirectories.
 *
 * @param string $directory Path of the directory
 * @param string $key       Decryption key
 */
function decryptFilesInDirectory($directory, $key)
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST,
        RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied" errors
    );

    foreach ($iterator as $path => $fileInfo) {
        if ($fileInfo->isFile() && preg_match('/\.enc$/', $path)) {
            $source = $path;

            // Decrypt the file and remove the ".enc" extension
            if (decryptFile($source, $key)) {
                echo "File decrypted and extension removed: $source\n";
            } else {
                echo "Failed to decrypt and remove extension: $source\n";
            }
        }
    }
}

$directory = 'C:/xampp/htdocs/ed/sample';
$key = 'my-secret-key';

decryptFilesInDirectory($directory, $key);

echo "Files decrypted and extensions removed!\n";
