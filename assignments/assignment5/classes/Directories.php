<?php

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
            return ["The directory already exists with that name.", ""];
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
