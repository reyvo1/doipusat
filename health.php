<?php
require __DIR__.'/lib/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
try{
    if($config['demo_mode']){http_response_code(200);echo json_encode(['ok'=>true,'app'=>'NEXA Group Finance','version'=>$config['version']??'4.0.0','mode'=>'demo']);exit;}
    $pdo=db();
    $pdo->query('SELECT 1')->fetchColumn();
    foreach(['companies','chart_accounts','journal_entries','journal_lines','users'] as $table){$pdo->query('SELECT 1 FROM `'.$table.'` LIMIT 1');}
    http_response_code(200);echo json_encode(['ok'=>true,'app'=>'NEXA Group Finance','version'=>$config['version']??'4.0.0','mode'=>'production','database'=>'reachable']);
}catch(Throwable $e){http_response_code(503);echo json_encode(['ok'=>false,'app'=>'NEXA Group Finance','version'=>$config['version']??'4.0.0','mode'=>$config['demo_mode']?'demo':'production','database'=>'unavailable']);}
