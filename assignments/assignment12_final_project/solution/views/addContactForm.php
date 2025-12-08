<?php
// ==============================
// file: solution/views/addContactForm.php
// ==============================
require_once __DIR__ . '/../includes/security.php';
require_login(); // ONLY this guard – no userIsAdmin() here

require_once __DIR__ . '/../classes/StickyForm.php';
require_once __DIR__ . '/../controllers/addContactProc.php';

$sticky = new StickyForm();

/* Bootstrap helper options to reuse */
$inp  = ['class'=>'form-control', 'labelClass'=>'form-label'];
$sel  = ['class'=>'form-select',  'labelClass'=>'form-label'];
$rChk = [
  'inputClass'   => 'form-check-input',
  'labelClass'   => 'form-check-label',
  'groupClass'   => 'form-check',
  'inlineGroup'  => 'form-check form-check-inline',
];

$formConfig = [
  'masterStatus' => ['error'=>false],

  // text inputs
  'fname'   => ['type'=>'text','regex'=>'name','label'=>'First Name','name'=>'fname','id'=>'fname','required'=>true,'value'=>'','error'=>''] + $inp,
  'lname'   => ['type'=>'text','regex'=>'name','label'=>'Last Name','name'=>'lname','id'=>'lname','required'=>true,'value'=>'','error'=>''] + $inp,
  'address' => ['type'=>'text','regex'=>'address','label'=>'Address','name'=>'address','id'=>'address','required'=>true,'value'=>'','error'=>''] + $inp,
  'city'    => ['type'=>'text','regex'=>'name','label'=>'City','name'=>'city','id'=>'city','required'=>true,'value'=>'','error'=>''] + $inp,
  'phone'   => ['type'=>'text','regex'=>'phone','label'=>'Phone','name'=>'phone','id'=>'phone','required'=>true,'value'=>'','error'=>'','placeholder'=>'999.999.9999'] + $inp,
  'email'   => ['type'=>'text','regex'=>'email','label'=>'Email','name'=>'email','id'=>'email','required'=>true,'value'=>'','error'=>''] + $inp,
  'dob'     => ['type'=>'text','regex'=>'dob','label'=>'Date of Birth','name'=>'dob','id'=>'dob','required'=>true,'value'=>'','error'=>'','placeholder'=>'mm/dd/yyyy'] + $inp,

  // select
  'state'   => [
    'type'=>'select','label'=>'State','name'=>'state','id'=>'state','required'=>true,'selected'=>'','error'=>'',
    'options'=>[
      ''          => 'Please Select',
      'Michigan'  => 'Michigan',
      'Ohio'      => 'Ohio',
      'Indiana'   => 'Indiana',
      'Illinois'  => 'Illinois',
      'Wisconsin' => 'Wisconsin'
    ]
  ] + $sel,

  // radios (inline)
  'age'     => [
    'type'=>'radio','label'=>'Choose an Age Range','name'=>'age','id'=>'age','required'=>true,'error'=>'',
    'options'=>[
      ['label'=>'0-17','value'=>'0-17','checked'=>false],
      ['label'=>'18-30','value'=>'18-30','checked'=>false],
      ['label'=>'30-50','value'=>'30-50','checked'=>false],
      ['label'=>'50+','value'=>'50+','checked'=>false],
    ],
    'inputClass'  => $rChk['inputClass'],
    'labelClass'  => $rChk['labelClass'],
    'groupClass'  => $rChk['inlineGroup'], // show horizontally
  ],

  // checkboxes (inline)
  'contact' => [
    'type'=>'checkbox','label'=>'Select One or More Options','name'=>'contact','id'=>'contact','required'=>false,'error'=>'',
    'options'=>[
      ['label'=>'newsletter','value'=>'newsletter','checked'=>false],
      ['label'=>'email','value'=>'email','checked'=>false],
      ['label'=>'text','value'=>'text','checked'=>false],
    ],
    'inputClass'  => $rChk['inputClass'],
    'labelClass'  => $rChk['labelClass'],
    'groupClass'  => $rChk['inlineGroup'], // show horizontally
  ],
];

$ack = null;
$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $formConfig = $sticky->validateForm($_POST, $formConfig);

  if (!$sticky->hasErrors() && $formConfig['masterStatus']['error'] == false) {
    $contactsSelected = [];
    foreach ($formConfig['contact']['options'] as $opt) {
      if ($opt['checked']) {
        $contactsSelected[] = $opt['value'];
      }
    }

    $data = [
      'fname'    => $formConfig['fname']['value'],
      'lname'    => $formConfig['lname']['value'],
      'address'  => $formConfig['address']['value'],
      'city'     => $formConfig['city']['value'],
      'state'    => $formConfig['state']['selected'],
      'phone'    => $formConfig['phone']['value'],
      'email'    => $formConfig['email']['value'],
      'dob'      => $formConfig['dob']['value'],
      'contacts' => implode(',', $contactsSelected),
      'age'      => array_values(array_filter(array_map(
                      fn($o) => $o['checked'] ? $o['value'] : null,
                      $formConfig['age']['options']
                    )))[0] ?? '',
    ];

    $res = insert_contact($data);

    if ($res === 'noerror') {
      $ack = 'Contact Information Added';
    } else {
      $msg = 'There was an error adding the record';
    }
  }
}

render_page('Add Contact', function () use ($sticky, &$formConfig, $ack, $msg) {

  if ($ack) {
    foreach ($formConfig as $k => &$e) {
      if ($k === 'masterStatus') continue;
      $e['value'] = '';
      $e['error'] = '';
      if (isset($e['selected'])) $e['selected'] = '';
      if (isset($e['options'])) {
        foreach ($e['options'] as &$o) {
          $o['checked'] = false;
        }
      }
    }
  }
  ?>
  <style>
    /* Keep radios/checkboxes horizontal and nicely spaced */
    .inline-options > * {
      display: inline-block;
      margin-right: 1rem;
    }
  </style>

  <?php if ($ack): ?>
    <div class="alert alert-success"><?= $ack ?></div>
  <?php endif; ?>

  <?php if ($msg): ?>
    <div class="alert alert-danger"><?= $msg ?></div>
  <?php endif; ?>

  <h1 class="mb-3">Add Contact</h1>

  <form method="post" novalidate>
    <div class="row g-3">

      <div class="col-md-6"><?= $sticky->renderInput($formConfig['fname']); ?></div>
      <div class="col-md-6"><?= $sticky->renderInput($formConfig['lname']); ?></div>

      <div class="col-12"><?= $sticky->renderInput($formConfig['address']); ?></div>

      <div class="col-md-4"><?= $sticky->renderInput($formConfig['city']); ?></div>
      <div class="col-md-4"><?= $sticky->renderSelect($formConfig['state']); ?></div>
      <div class="col-md-4">
        <?php
          $zipCfg = [
            'type'       => 'text',
            'label'      => 'Zip Code',
            'name'       => 'zip',
            'id'         => 'zip',
            'value'      => '',
            'error'      => '',
            'class'      => 'form-control',
            'labelClass' => 'form-label'
          ];
          echo $sticky->renderInput(($formConfig['zip'] ?? $zipCfg));
        ?>
      </div>

      <div class="col-md-4"><?= $sticky->renderInput($formConfig['phone']); ?></div>
      <div class="col-md-5"><?= $sticky->renderInput($formConfig['email']); ?></div>
      <div class="col-md-3"><?= $sticky->renderInput($formConfig['dob']); ?></div>

      <div class="col-12">
        <div class="inline-options">
          <?= $sticky->renderRadio($formConfig['age']); ?>
        </div>
      </div>

      <div class="col-12">
        <div class="inline-options">
          <?= $sticky->renderCheckboxGroup($formConfig['contact']); ?>
        </div>
      </div>

      <div class="col-12">
        <button class="btn btn-primary">Add Contact</button>
      </div>

    </div>
  </form>
<?php });
