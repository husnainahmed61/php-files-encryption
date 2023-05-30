# PHP Files Encryption Code

This repository contains PHP code for encrypting and decrypting image, PDF, and Excel files within a directory. It provides a simple and secure way to protect sensitive files by encrypting them and decrypting them when needed.

**Note:**
- Before using this code, please ensure that you have read and understood the instructions and warnings provided in this README file.
- Make sure to double-check the key and directory path configurations before running the encryption or decryption processes.
- This code is intended for educational purposes only and should not be used for any malicious activities.

## Prerequisites

To use this PHP encryption code, you need to have the following:

- PHP installed on your system.
- Basic understanding of PHP and file handling.
- Appropriate permissions to read, write, and execute files.

## Usage

Follow the steps below to encrypt and decrypt files within a directory:

1. Clone or download this repository to your local machine.
2. Open the `index_e.php` file in a text editor.
3. Set the appropriate values for the encryption key and directory path variables:
    - `$key` - Enter a strong encryption key to secure your files. Make sure to keep this key safe and confidential.
    - `$directory` - Provide the path to the directory containing the files you want to encrypt.
4. Save the `index_e.php` file and run it using a PHP server or from the command line:

`php index_e.php
`

This will encrypt all the files within the specified directory and generate encrypted output files with the suffix "_e".
5. To decrypt the files, open the `index_d.php` file in a text editor.
6. Set the appropriate values for the encryption key and directory path variables:
- `$key` - Enter the same encryption key used during encryption.
- `$directory` - Provide the path to the directory containing the files you want to decrypt.
7. Save the `index_d.php` file and run it using a PHP server or from the command line:

`php decryption.php
`

This will decrypt all the encrypted files within the specified directory and generate decrypted output files with the suffix "_d".

## File Types Supported

This PHP code supports the encryption and decryption of the following file types:

- Image files (e.g., JPEG, PNG, GIF)
- PDF files
- Excel files (XLS, XLSX)

## Important Note

Please note the following before using this code:

- This code should be used responsibly and only for legal purposes.
- The encryption key must be kept confidential and known only to authorized individuals.
- OpenAI, the creators of ChatGPT, and the code contributors do not take any responsibility for the misuse of this code.

## Disclaimer

The code provided in this repository is intended for educational purposes only. The authors and contributors cannot be held responsible for any damages or illegal activities resulting from the use of this code. Use it at your own risk.

Please be aware of the laws and regulations in your jurisdiction regarding encryption and file handling. Always seek legal advice if you are uncertain about the legality of your actions.

---

Feel free to explore and experiment with the PHP files encryption code provided in this repository. If you have any questions or encounter any issues, please open an issue in this repository for assistance.


