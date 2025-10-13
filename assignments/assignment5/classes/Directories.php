<?php
/*
-----------------------------------------------------------
Assignment 5 – File Directory Class
Author: [Your Name]
Course: CPS 276
-----------------------------------------------------------
Q&A:
1️⃣ Creating a directory vs file:
   mkdir() creates folders; fopen()/fwrite() handles files.
2️⃣ Form submission flow:
   User fills form → PHP receives POST data → class creates dir + file.
3️⃣ Why close file handles:
   fclose() frees memory and ensures data is saved.
4️⃣ Why permission 0777:
   Allows read/write/execute for all users in testing environments.
5️⃣ Benefit of class approach:
   Code reusability, cleaner separation between logic and output.
-----------------------------------------------------------
*/

class Directories {
    private string $dirname;
    private string $content;
    private string $basePath = "directories";

    public function __construct(string $dirname, string $content) {
        $this->dirname = trim($dirname);
        $this->content = trim($content);
    }

    public function createDirectory(): array {
        if ($this->dirname === "") {
            return ["Directory name cannot be empty.", ""];
        }

        $targetDir = $this->basePath . "/" . $this->dirname;

        // Create directories folder if not exists
        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0777, true);
        }

        // If directory already exists
        if (is_dir($targetDir)) {
            return ["The directory already exists.", ""];
        }

        // Create new directory
        if (!mkdir($targetDir, 0777)) {
            return ["Failed to create directory.", ""];
        }

        // Create and write to file
        $filePath = $targetDir . "/readme.txt";
        $file = fopen($filePath, "w");

        if (!$file) {
            return ["Failed to create file inside directory.", ""];
        }

        fwrite($file, $this->content);
        fclose($file);

        $fullLink = $filePath;
        return ["The directory has been created successfully!", $fullLink];
    }
}
?>
