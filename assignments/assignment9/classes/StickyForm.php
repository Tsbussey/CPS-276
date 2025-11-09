<?php
class StickyForm extends Validation {

  public $formConfig = array(
    'masterStatus' => array('error' => false),
    'fields' => array(
      'first_name' => array('value' => '', 'error' => '', 'label' => 'First Name'),
      'last_name'  => array('value' => '', 'error' => '', 'label' => 'Last Name'),
      'email'      => array('value' => '', 'error' => '', 'label' => 'Email'),
      'password'   => array('value' => '', 'error' => '', 'label' => 'Password'),
      'confirm'    => array('value' => '', 'error' => '', 'label' => 'Confirm Password'),
    ),
    'messages' => array('success' => '', 'failure' => '')
  );

  public function setDefaultValues($defaults) {
    foreach ($defaults as $k => $v) {
      if (isset($this->formConfig['fields'][$k])) {
        $this->formConfig['fields'][$k]['value'] = $v;
      }
    }
  }
  public function setFieldError($name, $message) {
    if (isset($this->formConfig['fields'][$name])) {
      $this->formConfig['fields'][$name]['error'] = $message;
      $this->formConfig['masterStatus']['error'] = true; // why: single flag to gate inserts
    }
  }
  public function get($name) {
    return htmlspecialchars(isset($this->formConfig['fields'][$name]['value']) ? $this->formConfig['fields'][$name]['value'] : '', ENT_QUOTES, 'UTF-8');
  }
  public function set($name, $value) {
    if (isset($this->formConfig['fields'][$name])) {
      $this->formConfig['fields'][$name]['value'] = $value;
    }
  }
  public function clearValues() {
    foreach ($this->formConfig['fields'] as $k => $v) {
      $this->formConfig['fields'][$k]['value'] = '';
    }
  }
  public function hasErrors() {
    return !empty($this->formConfig['masterStatus']['error']);
  }
  public function resetErrors() {
    $this->formConfig['masterStatus']['error'] = false;
    foreach ($this->formConfig['fields'] as $k => $v) {
      $this->formConfig['fields'][$k]['error'] = '';
    }
  }
  public function errorFor($name) {
    $msg = isset($this->formConfig['fields'][$name]['error']) ? $this->formConfig['fields'][$name]['error'] : '';
    return $msg ? '<div class="text-danger small mt-1">'.$this->escape($msg).'</div>' : '';
  }
  private function escape($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
}