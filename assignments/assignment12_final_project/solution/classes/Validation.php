<?php
class Validation {
  public function validName($value) {
    return (bool)preg_match("/^[A-Za-z' ]+$/", $value);
  }
  public function validEmail($email) {
    return (bool)preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i", $email);
  }
  public function strongPassword($pwd) {
    return (bool)preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/", $pwd);
  }
}