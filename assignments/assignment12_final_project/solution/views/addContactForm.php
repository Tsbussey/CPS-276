<?php
// ==============================
// file: solution/views/addContactForm.php
// ==============================
require_once __DIR__ . '/../includes/security.php';
require_login();

require_once __DIR__ . '/../classes/StickyForm.php';
require_once __DIR__ . '/../controllers/addContactProc.php';

$sticky = new StickyForm();

/* Bootstrap helpers */
$inp = ['class'=>'form-control','labelClass'=>'form-label'];
$sel = ['class'=>'form-select','labelClass'=>'form-label'];

$formConfig = [
  'masterStatus' => ['error'=>false],

  'fname'   => ['type'=>'text','regex'=>'name','label'=>'First Name','name'=>'fname','id'=>'fname','required'=>true,'value'=>'','error'=>''] + $inp,
  'lname'   => ['type'=>'text','regex'=>'name','label'=>'Last Name','name'=>'lname','id'=>'lname','required'=>true,'value'=>'','error'=>''] + $inp,
  'address' => ['type'=>'text','regex'=>'address','label'=>'Address','name'=>'address','id'=>'address','required'=>true,'value'=>'','error'=>''] + $inp,
  'city'    => ['type'=>'text','regex'=>'name','label'=>'City','name'=>'city','id'=>'city','required'=>true,'value'=>'','error'=>''] + $inp,

  'zip'     => ['type'=>'text','regex'=>'zip','label'=>'Zip Code','name'=>'zip','id'=>'zip','required'=>true,'value'=>'','error'=>''] + $inp,

  'phone'   => ['type'=>'text','regex'=>'phone','label'=>'Phone','name'=>'phone','id'=>'phone','required'=>true,'value'=>'','error'=>'','placeholder'=>'999.999.9999'] + $inp,
  'email'   => ['type'=>'text','regex'=>'email','label'=>'Email','name'=>'email','id'=>'email','required'=>true,'value'=>'','error'=>''] + $inp,
  'dob'     => ['type'=>'text','regex'=>'dob','label'=>'Date of Birth','name'=>'dob','id'=>'dob','required'=>true,'value'=>'','error'=>'','placeholder'=>'mm/dd/yyyy'] + $inp,

  'state'   => [
    'type'=>'select','label'=>'State','name'=>'state','id'=>'state','required'=>true,'selected'=>'','error'=>'',
    'options'=>[
      ''=>'Please Select','Michigan'=>'Michigan','Ohio'=>'Ohio','Indiana'=>'Indiana','Illinois'=>'Illinois','Wisconsin'=>'Wisconsin'
    ]
  ] + $sel,

  // validate here; render manually
  'age' => [
    'type'=>'radio','label'=>'Choose an Age Range','name'=>'age','id'=>'age','required'=>true,'error'=>'',
    'options'=>[
      ['label'=>'0-17','value'=>'0-17','checked'=>false],
      ['label'=>'18-30','value'=>'18-30','checked'=>false],
      ['label'=>'30-50','value'=>'30-50','checked'=>false],
      ['label'=>'50+','value'=>'50+','checked'=>false],
    ],
  ],
  'contact' => [
    'type'=>'checkbox','label'=>'Select One or More Options','name'=>'contact','id'=>'contact','required'=>false,'error'=>'',
    'options'=>[
      ['label'=>'newsletter','value'=>'newsletter','checked'=>false],
      ['label'=>'email','value'=>'email','checked'=>false],
      ['label'=>'text','value'=>'text','checked'=>false],
    ],
  ],
];

$ack = null;
$msg = null;

/* Handle POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $validated = $sticky->validateForm($_POST, $formConfig);
  if (is_array($validated)) {
    $formConfig = $validated;
  } else {
    $formConfig['masterStatus']['error'] = true;
    $msg = is_string($validated) && $validated !== '' ? $validated : 'There was an error with the form.';
  }

  if (!$sticky->hasErrors() && $formConfig['masterStatus']['error'] == false) {
    $contactsSelected = [];
    if (isset($_POST['contact']) && is_array($_POST['contact'])) {
      $contactsSelected = $_POST['contact'];
    }

    $data = [
      'fname'    => $formConfig['fname']['value'],
      'lname'    => $formConfig['lname']['value'],
      'address'  => $formConfig['address']['value'],
      'city'     => $formConfig['city']['value'],
      'state'    => $formConfig['state']['selected'],
      'zip'      => $formConfig['zip']['value'],
      'phone'    => $formConfig['phone']['value'],
      'email'    => $formConfig['email']['value'],
      'dob'      => $formConfig['dob']['value'],
      'contacts' => implode(',', $contactsSelected),
      'age'      => isset($_POST['age']) ? (string)$_POST['age'] : '',
    ];

    $res = insert_contact($data);
    if ($res === 'noerror') {
      // Success text
      $ack = 'Contact Added';

      // Wipe radios/checkboxes: clear POST and force options unchecked
      unset($_POST['age'], $_POST['contact']);

      if (isset($formConfig['age']['options']) && is_array($formConfig['age']['options'])) {
        foreach ($formConfig['age']['options'] as &$o) { if (is_array($o)) $o['checked'] = false; }
        unset($o);
      }
      if (isset($formConfig['contact']['options']) && is_array($formConfig['contact']['options'])) {
        foreach ($formConfig['contact']['options'] as &$o) { if (is_array($o)) $o['checked'] = false; }
        unset($o);
      }
    } else {
      $msg = 'There was an error adding the record';
    }
  }
}

/* SAFE BUILDERS – never index into strings */
function build_age_options(array $formConfig): array {
  $defaults = ['0-17','18-30','30-50','50+'];
  $selected = $_POST['age'] ?? (
    (isset($formConfig['age']) && is_array($formConfig['age']) && isset($formConfig['age']['selected']))
      ? (string)$formConfig['age']['selected']
      : ((isset($formConfig['age']) && is_string($formConfig['age'])) ? $formConfig['age'] : '')
  );
  return array_map(fn($v) => ['label'=>$v,'value'=>$v,'checked'=>($selected === $v)], $defaults);
}
function build_contact_options(array $formConfig): array {
  $defaults = ['newsletter','email','text'];
  $selected = [];
  if (isset($_POST['contact']) && is_array($_POST['contact'])) {
    $selected = $_POST['contact'];
  } elseif (isset($formConfig['contact']) && is_array($formConfig['contact']) && isset($formConfig['contact']['selected'])) {
    $sel = $formConfig['contact']['selected'];
    $selected = is_array($sel) ? $sel : ($sel !== '' ? [$sel] : []);
  } elseif (isset($formConfig['contact']) && is_string($formConfig['contact']) && $formConfig['contact'] !== '') {
    $selected = [$formConfig['contact']];
  }
  return array_map(fn($v) => ['label'=>$v,'value'=>$v,'checked'=>in_array($v,$selected,true)], $defaults);
}

render_page('Add Contact', function () use (&$sticky, &$formConfig, &$ack, &$msg) {

  if ($ack) {
    // Reset all simple fields
    foreach ($formConfig as $k => &$e) {
      if ($k === 'masterStatus' || !is_array($e)) continue;
      if (array_key_exists('value', $e))    $e['value']    = '';
      if (array_key_exists('error', $e))    $e['error']    = '';
      if (array_key_exists('selected', $e)) $e['selected'] = '';
      if (array_key_exists('options', $e) && is_array($e['options'])) {
        foreach ($e['options'] as $idx => &$o) {
          if (is_array($o)) { $o['checked'] = false; }
        }
        unset($o);
      }
    }
    unset($e);
  }

  $ageOptions     = build_age_options($formConfig);
  $contactOptions = build_contact_options($formConfig);
  ?>

  <?php if ($ack): ?>
    <p class="mb-2"><?= htmlspecialchars($ack) ?></p>
  <?php endif; ?>

  <?php if ($msg): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <h1 class="mb-3">Add Contact</h1>

  <form method="post" novalidate>
    <div class="row g-3">
      <div class="col-md-6"><?= $sticky->renderInput($formConfig['fname']); ?></div>
      <div class="col-md-6"><?= $sticky->renderInput($formConfig['lname']); ?></div>

      <div class="col-12"><?= $sticky->renderInput($formConfig['address']); ?></div>

      <div class="col-md-4"><?= $sticky->renderInput($formConfig['city']); ?></div>
      <div class="col-md-4"><?= $sticky->renderSelect($formConfig['state']); ?></div>
      <div class="col-md-4"><?= $sticky->renderInput($formConfig['zip']); ?></div>

      <div class="col-md-4"><?= $sticky->renderInput($formConfig['phone']); ?></div>
      <div class="col-md-5"><?= $sticky->renderInput($formConfig['email']); ?></div>
      <div class="col-md-3"><?= $sticky->renderInput($formConfig['dob']); ?></div>

      <!-- Age (manual inline Bootstrap) -->
      <div class="col-12">
        <label class="form-label d-block">Choose an Age Range</label>
        <div class="d-flex flex-wrap">
          <?php foreach ($ageOptions as $i => $opt):
            $id = 'age_' . $i; $checked = !empty($opt['checked']) ? 'checked' : '';
          ?>
            <div class="form-check form-check-inline d-flex align-items-center me-4 mb-0">
              <input class="form-check-input" type="radio" name="age" id="<?= $id ?>"
                     value="<?= htmlspecialchars($opt['value']) ?>" <?= $checked ?>>
              <label class="form-check-label ms-2 mb-0" for="<?= $id ?>"><?= htmlspecialchars($opt['label']) ?></label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Contact options (manual inline Bootstrap) -->
      <div class="col-12">
        <label class="form-label d-block">Select One or More Options</label>
        <div class="d-flex flex-wrap">
          <?php foreach ($contactOptions as $i => $opt):
            $id = 'contact_' . $i; $checked = !empty($opt['checked']) ? 'checked' : '';
          ?>
            <div class="form-check form-check-inline d-flex align-items-center me-4 mb-0">
              <input class="form-check-input" type="checkbox" name="contact[]" id="<?= $id ?>"
                     value="<?= htmlspecialchars($opt['value']) ?>" <?= $checked ?>>
              <label class="form-check-label ms-2 mb-0" for="<?= $id ?>"><?= htmlspecialchars($opt['label']) ?></label>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="col-12">
        <button class="btn btn-primary">Add Contact</button>
      </div>
    </div>
  </form>
<?php });
