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
$inp = ['class' => 'form-control', 'labelClass' => 'form-label'];
$sel = ['class' => 'form-select',  'labelClass' => 'form-label'];

$formConfig = [
  'masterStatus' => ['error' => false],

  // TEXT INPUTS WITH CUSTOM ERROR MESSAGES
  'fname' => [
    'type'      => 'text',
    'regex'     => 'name',
    'label'     => 'First Name',
    'name'      => 'fname',
    'id'        => 'fname',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'errorMsg'  => 'You must enter a valid first name'
  ] + $inp,

  'lname' => [
    'type'      => 'text',
    'regex'     => 'name',
    'label'     => 'Last Name',
    'name'      => 'lname',
    'id'        => 'lname',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'errorMsg'  => 'You must enter a valid last name'
  ] + $inp,

  'address' => [
    'type'      => 'text',
    'regex'     => 'address',
    'label'     => 'Address',
    'name'      => 'address',
    'id'        => 'address',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'errorMsg'  => 'You must enter a valid address'
  ] + $inp,

  'city' => [
    'type'      => 'text',
    'regex'     => 'name',
    'label'     => 'City',
    'name'      => 'city',
    'id'        => 'city',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'errorMsg'  => 'You must enter a valid city'
  ] + $inp,

  // ZIP must exist end-to-end
  'zip' => [
    'type'      => 'text',
    'regex'     => 'zip',
    'label'     => 'Zip Code',
    'name'      => 'zip',
    'id'        => 'zip',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'errorMsg'  => 'You must enter a valid zip code'
  ] + $inp,

  'phone' => [
    'type'      => 'text',
    'regex'     => 'phone',
    'label'     => 'Phone',
    'name'      => 'phone',
    'id'        => 'phone',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'placeholder' => '999.999.9999',
    'errorMsg'  => 'You must enter a valid phone number'
  ] + $inp,

  'email' => [
    'type'      => 'text',
    'regex'     => 'email',
    'label'     => 'Email',
    'name'      => 'email',
    'id'        => 'email',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'errorMsg'  => 'You must enter a valid email address'
  ] + $inp,

  'dob' => [
    'type'      => 'text',
    'regex'     => 'dob',
    'label'     => 'Date of Birth',
    'name'      => 'dob',
    'id'        => 'dob',
    'required'  => true,
    'value'     => '',
    'error'     => '',
    'placeholder' => 'mm/dd/yyyy',
    'errorMsg'  => 'You must enter a valid date of birth'
  ] + $inp,

  // STATE SELECT WITH CUSTOM MESSAGE
  'state' => [
    'type'      => 'select',
    'label'     => 'State',
    'name'      => 'state',
    'id'        => 'state',
    'required'  => true,
    'selected'  => '',
    'error'     => '',
    'errorMsg'  => 'You must select a state',
    'options'   => [
      ''          => 'Please Select',
      'Michigan'  => 'Michigan',
      'Ohio'      => 'Ohio',
      'Indiana'   => 'Indiana',
      'Illinois'  => 'Illinois',
      'Wisconsin' => 'Wisconsin'
    ]
  ] + $sel,

  // AGE RADIO – VALIDATED HERE, RENDERED MANUALLY
  'age' => [
    'type'      => 'radio',
    'label'     => 'Choose an Age Range',
    'name'      => 'age',
    'id'        => 'age',
    'required'  => true,
    'error'     => '',
    'errorMsg'  => 'You must select an age range',
    'options'   => [
      ['label' => '0-17',  'value' => '0-17',  'checked' => false],
      ['label' => '18-30','value' => '18-30','checked' => false],
      ['label' => '30-50','value' => '30-50','checked' => false],
      ['label' => '50+',  'value' => '50+',  'checked' => false],
    ],
    'inputClass' => 'form-check-input',
    'labelClass' => 'form-check-label',
    'groupClass' => 'form-check',
  ],

  // CONTACT OPTIONS CHECKBOXES (NOT REQUIRED)
  'contact' => [
    'type'      => 'checkbox',
    'label'     => 'Select One or More Options',
    'name'      => 'contact',
    'id'        => 'contact',
    'required'  => false,
    'error'     => '',
    'errorMsg'  => '',
    'options'   => [
      ['label' => 'newsletter', 'value' => 'newsletter', 'checked' => false],
      ['label' => 'email',      'value' => 'email',      'checked' => false],
      ['label' => 'text',       'value' => 'text',       'checked' => false],
    ],
    'inputClass' => 'form-check-input',
    'labelClass' => 'form-check-label',
    'groupClass' => 'form-check',
  ],
];

$ack = null;
$msg = null;

/* Handle POST safely */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $validated = $sticky->validateForm($_POST, $formConfig);

  // Keep $formConfig an array
  if (is_array($validated)) {
    $formConfig = $validated;
  } else {
    $formConfig['masterStatus']['error'] = true;
    $msg = is_string($validated) && $validated !== '' ? $validated : 'There was an error with the form.';
  }

  if (!$sticky->hasErrors() && $formConfig['masterStatus']['error'] == false) {
    $contactsSelected = [];
    foreach ($formConfig['contact']['options'] as $opt) {
      if (!empty($opt['checked'])) {
        $contactsSelected[] = $opt['value'];
      }
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
      'age'      => array_values(array_filter(array_map(
                      fn($o) => !empty($o['checked']) ? $o['value'] : null,
                      $formConfig['age']['options']
                    )))[0] ?? '',
    ];

    $res = insert_contact($data);
    if ($res === 'noerror') {
      $ack = 'Contact Added';
    } else {
      $msg = 'There was an error adding the record';
    }
  }
}

/* Render */
render_page('Add Contact', function () use (&$sticky, &$formConfig, &$ack, &$msg) {

  // On success, clear fields & selections (but keep error config)
  if ($ack) {
    foreach ($formConfig as $k => &$e) {
      if ($k === 'masterStatus') continue;
      if (is_array($e)) {
        if (isset($e['value']))    $e['value'] = '';
        if (isset($e['error']))    $e['error'] = '';
        if (isset($e['selected'])) $e['selected'] = '';
        if (isset($e['options'])) {
          foreach ($e['options'] as &$o) {
            if (is_array($o) && isset($o['checked'])) {
              $o['checked'] = false;
            }
          }
        }
      }
    }
  }
  ?>

  <?php if ($ack): ?>
    <p><?= htmlspecialchars($ack) ?></p>
  <?php endif; ?>

  <?php if ($msg): ?>
    <p><?= htmlspecialchars($msg) ?></p>
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

      <!-- Age (inline radios + right-side error) -->
      <div class="col-12">
        <label class="form-label d-block">Choose an Age Range</label>
        <div class="d-flex align-items-center flex-wrap">
          <!-- radios -->
          <div class="d-flex flex-wrap">
            <?php foreach ($formConfig['age']['options'] as $i => $opt):
              $id = 'age_' . $i;
              $checked = !empty($opt['checked']) ? 'checked' : '';
            ?>
              <div class="form-check form-check-inline d-flex align-items-center me-4 mb-0">
                <input class="form-check-input" type="radio" name="age" id="<?= $id ?>"
                       value="<?= htmlspecialchars($opt['value']) ?>" <?= $checked ?>>
                <label class="form-check-label ms-2 mb-0" for="<?= $id ?>"><?= htmlspecialchars($opt['label']) ?></label>
              </div>
            <?php endforeach; ?>
          </div>

          <?php if (!empty($formConfig['age']['error'])): ?>
            <span class="text-danger small ms-3 flex-shrink-0" style="white-space:nowrap;">
              <?= htmlspecialchars($formConfig['age']['error']) ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Contact options (manual inline Bootstrap) -->
      <div class="col-12">
        <label class="form-label d-block">Select One or More Options</label>
        <div class="d-flex flex-wrap">
          <?php foreach ($formConfig['contact']['options'] as $i => $opt):
            $id = 'contact_' . $i;
            $checked = !empty($opt['checked']) ? 'checked' : '';
          ?>
            <div class="form-check form-check-inline d-flex align-items-center me-4 mb-0">
              <input class="form-check-input" type="checkbox" name="contact[]" id="<?= $id ?>"
                     value="<?= htmlspecialchars($opt['value']) ?>" <?= $checked ?>>
              <label class="form-check-label ms-2 mb-0" for="<?= $id ?>"><?= htmlspecialchars($opt['label']) ?></label>
            </div>
          <?php endforeach; ?>
        </div>
        <?php if (!empty($formConfig['contact']['error'])): ?>
          <div class="text-danger small mt-1"><?= htmlspecialchars($formConfig['contact']['error']) ?></div>
        <?php endif; ?>
      </div>

      <div class="col-12">
        <button class="btn btn-primary">Add Contact</button>
      </div>
    </div>
  </form>

<?php });
