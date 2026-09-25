<?php
require __DIR__.'/lib/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
try{
    if($config['demo_mode']){http_response_code(200);echo json_encode(['ok'=>true,'app'=>'NEXA Group Finance','version'=>$config['version']??'4.0.0','mode'=>'demo']);exit;}
    $pdo=db();
    $pdo->query('SELECT 1')->fetchColumn();
    foreach(['companies','branches','departments','chart_accounts','journal_batches','journal_entries','journal_lines','users','invoices','bank_reconciliation_sessions','consolidation_runs','integration_events'] as $table){$pdo->query('SELECT 1 FROM `'.$table.'` LIMIT 1');}
    require_once __DIR__.'/src/autoload.php';$state=loadStore();$enterprise=(new \Nexa\Application\EnterpriseKernel((int)$config['approval_threshold']))->health($state);http_response_code(200);echo json_encode(['ok'=>true,'app'=>'NEXA Group Finance','version'=>$config['version']??'5.0.0-rc3','mode'=>'production','database'=>'reachable','architecture'=>$enterprise['architecture'],'companies'=>$enterprise['companies'],'entries'=>$enterprise['entries']]);
}catch(Throwable $e){http_response_code(503);echo json_encode(['ok'=>false,'app'=>'NEXA Group Finance','version'=>$config['version']??'4.0.0','mode'=>$config['demo_mode']?'demo':'production','database'=>'unavailable']);}
