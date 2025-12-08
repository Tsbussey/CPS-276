<?php
// ==============================
// file: solution/classes/Validation.php
// (book style; regexes allowed to edit)
// ==============================
class Validation {
  private $errors = [];
  public function checkFormat($value, $type, $customErrorMsg = null) {
    $patterns = [
      'name'    => '/^[a-z\'\s-]{1,50}$/i',
      'phone'   => '/^\d{3}\.\d{3}\.\d{4}$/',
      'address' => '/^[a-zA-Z0-9\s,.\'-]{1,100}$/',
      'zip'     => '/^\d{5}(-\d{4})?$/',
      'email'   => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
      'none'    => '/.*/',
      'dob'     => '/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/'
    ];
    $pattern = $patterns[$type] ?? '/.*/';
    if (!preg_match($pattern, $value)) {
      $this->errors[$type] = $customErrorMsg ?? "Invalid $type format.";
      return false;
    }
    return true;
  }
  public function getErrors(){ return $this->errors; }
  public function hasErrors(){ return !empty($this->errors); }
}
