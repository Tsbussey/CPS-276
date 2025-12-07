<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/security.php'; require_admin();
require_once __DIR__ . '/../classes/StickyForm.php';
require_once __DIR__ . '/../controllers/addAdminProc.php';

$sticky = new StickyForm();
$formConfig = [
  'masterStatus' => ['error'=>false],
  'fname'   => ['type'=>'text','regex'=>'name','label'=>'*First Name','name'=>'fname','id'=>'fname','required'=>true,'value'=>'','error'=>''],
  'lname'   => ['type'=>'text','regex'=>'name','label'=>'*Last Name','name'=>'lname','id'=>'lname','required'=>true,'value'=>'','error'=>''],
  'email'   => ['type'=>'text','regex'=>'email','label'=>'*Email','name'=>'email','id'=>'email','required'=>true,'value'=>'','error'=>''],
  'password'=> ['type'=>'text','regex'=>'none','label'=>'*Password','name'=>'password','id'=>'password','required'=>true,'value'=>'','error'=>''],
  'status'  => ['type'=>'select','label'=>'*Status','name'=>'status','id'=>'status','required'=>true,'selected'=>'','error'=>'',
                'options'=>[''=>'Please Select a Status','staff'=>'staff','admin'=>'admin']],
];

$ack = null; $msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $formConfig = $sticky->validateForm($_POST, $formConfig);
  if (!$sticky->hasErrors() && $formConfig['masterStatus']['error'] == false) {
    $email = $formConfig['email']['value'];
    if (email_exists($email)) {
      $formConfig['email']['error'] = 'Email already exists';
      $formConfig['masterStatus']['error'] = true;
    } else {
      $name = $formConfig['fname']['value'].' '.$formConfig['lname']['value'];
      $hash = password_hash($formConfig['password']['value'], PASSWORD_DEFAULT);
      $res = insert_admin($name, $email, $hash, $formConfig['status']['selected']);
      if ($res === 'noerror') { $ack = 'Admin Added'; }
      else { $msg = 'There was an error adding the record'; }
    }
  }
}

render_page('Add Admin', function () use ($sticky, &$formConfig, $ack, $msg) {
  if ($ack) { foreach ($formConfig as $k=>&$e){ if ($k==='masterStatus') continue; $e['value']=''; $e['error']=''; if(isset($e['selected'])) $e['selected']=''; } }
  ?>
  <?php if ($ack): ?><div class="alert alert-success"><?= $ack ?></div><?php endif; ?>
  <?php if ($msg): ?><div class="alert alert-danger"><?= $msg ?></div><?php endif; ?>
  <h1 class="h3 mb-3">Add Admin</h1>
  <form method="post" novalidate>
    <div class="row g-3">
      <div class="col-md-6"><?= $sticky->renderInput($formConfig['fname']); ?></div>
      <div class="col-md-6"><?= $sticky->renderInput($formConfig['lname']); ?></div>
      <div class="col-md-6"><?= $sticky->renderInput($formConfig['email']); ?></div>
      <div class="col-md-6"><?= $sticky->renderPassword($formConfig['password']); ?></div>
      <div class="col-md-6"><?= $sticky->renderSelect($formConfig['status']); ?></div>
      <div class="col-12"><button class="btn btn-primary">Add Admin</button></div>
    </div>
  </form>
  <?php
});
