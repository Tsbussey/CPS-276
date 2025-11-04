<?php
/* ========================================================================
 * File: ~/public_html/cps276/assignments/assignment8/classes/Date_time.php
 * Purpose: Show REAL errors; robust datetime parsing; assignment logic.
 * ====================================================================== */
declare(strict_types=1);
require_once __DIR__ . '/Db_conn.php';

final class Date_time {
    public string $message = '';
    /** @var array<int, array{date_time:int, note:string}> */
    public array $rows = [];

    public function checkSubmit(string $context): void {
        // Display raw PHP errors in-page for this run.
        ini_set('display_errors', '1'); error_reporting(E_ALL);

        if ($context === 'add' && isset($_POST['addNote'])) {
            $this->addNote($_POST['dateTime'] ?? '', $_POST['note'] ?? '');
        } elseif ($context === 'display' && isset($_POST['getNotes'])) {
            $this->getNotes($_POST['begDate'] ?? '', $_POST['endDate'] ?? '');
        }
    }

    private function addNote(string $dateTimeLocal, string $note): void {
        $dateTimeLocal = trim($dateTimeLocal);
        $note          = trim($note);

        if ($dateTimeLocal === '' || $note === '') { $this->message = 'You must enter a date, time, and note.'; return; }

        $ts = $this->parseDateTimeToTs($dateTimeLocal);
        if ($ts === null) { $this->message = 'Invalid date/time.'; return; }

        $pdo  = Db_conn::pdo();                         // ← any PDO problem will throw visibly
        $stmt = $pdo->prepare('INSERT INTO note (date_time, note) VALUES (:ts, :n)');
        $stmt->execute([':ts' => $ts, ':n' => $note]);

        $this->message = 'Note added.';
    }

    /** Accepts both "YYYY-MM-DDTHH:MM" and "MM/DD/YYYY, hh:mm AM/PM". */
    private function parseDateTimeToTs(string $raw): ?int {
        $formats = ['Y-m-d\TH:i', 'm/d/Y, h:i A', 'm/d/Y, H:i'];
        foreach ($formats as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, $raw);
            if ($dt instanceof \DateTime) return $dt->getTimestamp();
        }
        return null;
    }

    private function getNotes(string $begDate, string $endDate): void {
        $begDate = trim($begDate); $endDate = trim($endDate);
        if ($begDate === '' || $endDate === '') { $this->message = 'No notes found for the date range selected'; $this->rows = []; return; }

        $beg = \DateTime::createFromFormat('Y-m-d H:i:s', $begDate . ' 00:00:00');
        $end = \DateTime::createFromFormat('Y-m-d H:i:s', $endDate . ' 23:59:59');
        if (!$beg || !$end) { $this->message = 'No notes found for the date range selected'; return; }

        $pdo  = Db_conn::pdo();
        $stmt = $pdo->prepare('SELECT date_time,note FROM note WHERE date_time BETWEEN :b AND :e ORDER BY date_time DESC');
        $stmt->execute([':b' => $beg->getTimestamp(), ':e' => $end->getTimestamp()]);
        $this->rows = $stmt->fetchAll();

        if (!$this->rows) { $this->message = 'No notes found for the date range selected'; }
    }

    public static function fmt(int $ts): string { return date('m/d/Y h:i A', $ts); }
}
