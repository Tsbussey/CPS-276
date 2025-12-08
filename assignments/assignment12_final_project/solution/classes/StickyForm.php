<?php
// ==============================
// file: solution/classes/StickyForm.php
// (book style renderer compatible with our config)
// ==============================
require_once 'classes/Validation.php';
class StickyForm extends Validation {
  public function validateForm($data, $formConfig) {
    foreach ($formConfig as $key => &$el) {
      if ($key === 'masterStatus') continue;
      $type = $el['type'] ?? '';
      if ($type === 'text' || $type === 'textarea') {
        $el['value'] = $data[$key] ?? '';
        if (($el['required'] ?? false) && $el['value'] === '') {
          $el['error'] = $el['errorMsg'] ?? 'This field is required.';
          $formConfig['masterStatus']['error'] = true;
        } elseif (!empty($el['regex']) && $el['value'] !== '') {
          if (!$this->checkFormat($el['value'], $el['regex'], $el['errorMsg'] ?? null)) {
            $errs = $this->getErrors(); $el['error'] = $errs[$el['regex']] ?? 'Invalid format';
          }
        }
      } elseif ($type === 'select') {
        $el['selected'] = $data[$key] ?? '';
        if (($el['required'] ?? false) && ($el['selected'] === '' || $el['selected'] === '0')) {
          $el['error'] = $el['errorMsg'] ?? 'This field is required.';
          $formConfig['masterStatus']['error'] = true;
        }
      } elseif ($type === 'checkbox') {
        if (isset($el['options'])) {
          $any = false;
          foreach ($el['options'] as &$o) {
            $o['checked'] = in_array($o['value'], $data[$key] ?? []);
            if ($o['checked']) $any = true;
          }
          if (($el['required'] ?? false) && !$any) {
            $el['error'] = $el['errorMsg'] ?? 'This field is required.';
            $formConfig['masterStatus']['error'] = true;
          }
        } else {
          $el['checked'] = isset($data[$key]);
          if (($el['required'] ?? false) && !$el['checked']) {
            $el['error'] = $el['errorMsg'] ?? 'This field is required.';
            $formConfig['masterStatus']['error'] = true;
          }
        }
      } elseif ($type === 'radio') {
        $checked = false;
        foreach ($el['options'] as &$o) {
          $o['checked'] = ($o['value'] === ($data[$key] ?? ''));
          if ($o['checked']) $checked = true;
        }
        if (($el['required'] ?? false) && !$checked) {
          $el['error'] = $el['errorMsg'] ?? 'This field is required.';
          $formConfig['masterStatus']['error'] = true;
        }
      }
    }
    return $formConfig;
  }

  public function createOptions($options, $selected) {
    $html = '';
    foreach ($options as $value => $label) {
      $sel = ($value == $selected) ? 'selected' : '';
      $html .= "<option value=\"$value\" $sel>$label</option>";
    }
    return $html;
  }
  private function renderError($el) { return !empty($el['error']) ? "<div class=\"invalid-feedback d-block\">{$el['error']}</div>" : ''; }

  public function renderInput($el, $class='') {
    $err = $this->renderError($el);
    $val = htmlspecialchars($el['value'] ?? '');
    return "<div class=\"$class\"><label class=\"form-label\" for=\"{$el['id']}\">{$el['label']}</label><input type=\"text\" class=\"form-control\" id=\"{$el['id']}\" name=\"{$el['name']}\" value=\"$val\">$err</div>";
  }
  public function renderPassword($el, $class='') {
    $err = $this->renderError($el);
    return "<div class=\"$class\"><label class=\"form-label\" for=\"{$el['id']}\">{$el['label']}</label><input type=\"password\" class=\"form-control\" id=\"{$el['id']}\" name=\"{$el['name']}\">$err</div>";
  }
  public function renderTextarea($el, $class='') {
    $err = $this->renderError($el);
    $val = htmlspecialchars($el['value'] ?? '');
    return "<div class=\"$class\"><label class=\"form-label\" for=\"{$el['id']}\">{$el['label']}</label><textarea class=\"form-control\" id=\"{$el['id']}\" name=\"{$el['name']}\">$val</textarea>$err</div>";
  }
  public function renderRadio($el, $class='', $layout='vertical') {
    $err = $this->renderError($el);
    $layoutClass = $layout === 'horizontal' ? 'form-check-inline' : '';
    $ops = '';
    foreach ($el['options'] as $o) {
      $checked = $o['checked'] ? 'checked' : '';
      $ops .= "<div class=\"form-check $layoutClass\"><input class=\"form-check-input\" type=\"radio\" id=\"{$el['id']}_{$o['value']}\" name=\"{$el['name']}\" value=\"{$o['value']}\" $checked><label class=\"form-check-label\" for=\"{$el['id']}_{$o['value']}\">{$o['label']}</label></div>";
    }
    return "<div class=\"$class\"><label class=\"form-label\">{$el['label']}</label><br>$ops$err</div>";
  }
  public function renderCheckboxGroup($el, $class='', $layout='vertical') {
    $err = $this->renderError($el);
    $layoutClass = $layout === 'horizontal' ? 'form-check-inline' : '';
    $ops = '';
    foreach ($el['options'] as $i => $o) {
      $checked = $o['checked'] ? 'checked' : '';
      $ops .= "<div class=\"form-check $layoutClass\"><input class=\"form-check-input\" type=\"checkbox\" id=\"{$el['id']}_$i\" name=\"{$el['name']}[]\" value=\"{$o['value']}\" $checked><label class=\"form-check-label\" for=\"{$el['id']}_$i\">{$o['label']}</label></div>";
    }
    return "<div class=\"$class\"><label class=\"form-label\">{$el['label']}</label><br>$ops$err</div>";
  }
  public function renderSelect($el, $class='') {
    $err = $this->renderError($el);
    $ops = $this->createOptions($el['options'], $el['selected']);
    return "<div class=\"$class\"><label class=\"form-label\" for=\"{$el['id']}\">{$el['label']}</label><select class=\"form-control\" id=\"{$el['id']}\" name=\"{$el['name']}\">$ops</select>$err</div>";
  }
}
