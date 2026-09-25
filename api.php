<?php
require __DIR__.'/lib/bootstrap.php';
requireLogin(true);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');
try {
    $action=$_GET['action']??'';
    if($action==='dashboard') jsonOut(['ok'=>true,'data'=>dashboardData()]);

    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='create-company'){
        verifyCsrf();$row=createCompany($_POST);jsonOut(['ok'=>true,'message'=>'Badan usaha berhasil dibuat.','company'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='create-user'){
        verifyCsrf();$row=createUserAccount($_POST);jsonOut(['ok'=>true,'message'=>'User berhasil dibuat.','user'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='create-budget'){
        verifyCsrf();$row=createBudget($_POST);jsonOut(['ok'=>true,'message'=>'Budget berhasil disimpan.','budget'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='create-asset'){
        verifyCsrf();$row=createAsset($_POST);jsonOut(['ok'=>true,'message'=>'Aset berhasil ditambahkan.','asset'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='create-bank-account'){
        verifyCsrf();$row=createBankAccount($_POST);jsonOut(['ok'=>true,'message'=>'Rekening bank berhasil ditambahkan.','bank_account'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='post-daily-income'){
        verifyCsrf();$row=postDailyIncome($_POST);
        if(!empty($row['approval_required'])) jsonOut(['ok'=>true,'pending_approval'=>true,'message'=>'Pendapatan harian melewati batas approval dan masuk antrian persetujuan.','entry'=>$row,'dashboard'=>dashboardData()]);
        jsonOut(['ok'=>true,'message'=>'Pendapatan harian berhasil diposting ke ledger.','entry'=>$row,'dashboard'=>dashboardData()]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='create-income-category'){
        verifyCsrf();$row=createIncomeCategory($_POST);jsonOut(['ok'=>true,'message'=>'Kategori pendapatan berhasil ditambahkan.','category'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='post-journal'){
        verifyCsrf();
        $entry=postJournal($_POST);
        if(!empty($entry['approval_required'])) jsonOut(['ok'=>true,'pending_approval'=>true,'message'=>'Transaksi melewati batas approval dan masuk antrian persetujuan.','entry'=>$entry,'dashboard'=>dashboardData()]);
        jsonOut(['ok'=>true,'message'=>'Jurnal berhasil diposting dan balance.','entry'=>$entry,'dashboard'=>dashboardData()]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='approval-decision'){
        verifyCsrf();
        $row=decideApproval((int)($_POST['id']??0),(string)($_POST['decision']??''));
        jsonOut(['ok'=>true,'message'=>'Approval diperbarui menjadi '.$row['status'].'.','approval'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='close-period'){
        verifyCsrf();
        $row=closePeriod((int)($_POST['year']??0),(int)($_POST['month']??0));
        jsonOut(['ok'=>true,'message'=>'Periode berhasil ditutup.','period'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='reconcile-bank'){
        verifyCsrf();
        $row=reconcileBank((int)($_POST['bank_id']??0),(int)($_POST['journal_id']??0));
        jsonOut(['ok'=>true,'message'=>'Mutasi bank berhasil direkonsiliasi.','bank'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='reverse-journal'){
        verifyCsrf();
        $row=reverseJournal((int)($_POST['journal_id']??0),(string)($_POST['reason']??''));
        jsonOut(['ok'=>true,'message'=>'Jurnal reversal berhasil dibuat tanpa menghapus jejak transaksi asli.','entry'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='allocate-payment'){
        verifyCsrf();
        $row=allocateInvoicePayment((int)($_POST['invoice_id']??0),(float)($_POST['amount']??0),(string)($_POST['date']??date('Y-m-d')),(string)($_POST['method']??'bank'),(string)($_POST['reference']??''));
        jsonOut(['ok'=>true,'message'=>'Pembayaran berhasil dialokasikan dan jurnal kas/bank dibuat.','payment'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='intercompany-eliminate'){
        verifyCsrf();
        $row=createElimination((int)($_POST['from_company_id']??0),(int)($_POST['to_company_id']??0),(float)($_POST['amount']??0),(string)($_POST['period']??date('Y-m')),(string)($_POST['description']??''));
        jsonOut(['ok'=>true,'message'=>'Eliminasi intercompany berhasil diposting ke consolidation layer.','elimination'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='calculate-tax'){
        verifyCsrf();
        $row=calculateTax((float)($_POST['base']??0),(float)($_POST['rate']??0),!empty($_POST['inclusive']));
        jsonOut(['ok'=>true,'tax'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='record-fx'){
        verifyCsrf();
        $row=recordFxEvent((int)($_POST['company_id']??0),(string)($_POST['date']??date('Y-m-d')),(string)($_POST['currency']??''),(float)($_POST['foreign_amount']??0),(float)($_POST['book_rate']??0),(float)($_POST['settlement_rate']??0),(string)($_POST['description']??''));
        jsonOut(['ok'=>true,'message'=>'FX settlement dicatat dan gain/loss dihitung.','fx'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='import-bank-csv'){
        verifyCsrf();
        $csv=(string)($_POST['csv']??'');
        if(isset($_FILES['file'])&&is_uploaded_file($_FILES['file']['tmp_name']))$csv=(string)file_get_contents($_FILES['file']['tmp_name']);
        $row=importBankCsv((int)($_POST['company_id']??0),$csv);
        jsonOut(['ok'=>true,'message'=>$row['count'].' mutasi bank berhasil diimpor sebagai unmatched.','import'=>$row]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='switch-demo-user'){
        verifyCsrf(); global $config;
        if(!$config['demo_mode']) throw new RuntimeException('Pergantian user cepat hanya tersedia pada Demo Mode.');
        $s=loadStore();$id=(int)($_POST['user_id']??0);$found=null;foreach(($s['users']??[]) as $u)if((int)$u['id']===$id)$found=$u;
        if(!$found)throw new InvalidArgumentException('User demo tidak ditemukan.');
        $_SESSION['user_id']=$id;$_SESSION['user_name']=$found['name'];writeAudit('session.user_switched',['user_id'=>$id,'role'=>$found['role']]);
        jsonOut(['ok'=>true,'message'=>'Role aktif: '.$found['role']]);
    }
    if($_SERVER['REQUEST_METHOD']==='POST'&&$action==='reset-demo'){
        verifyCsrf(); global $config;if(!$config['demo_mode'])throw new RuntimeException('Reset hanya tersedia di Demo Mode.');saveStore(demoSeed());writeAudit('demo.reset');jsonOut(['ok'=>true]);
    }
    jsonOut(['ok'=>false,'message'=>'Endpoint tidak ditemukan.'],404);
} catch(Throwable $e){ jsonOut(['ok'=>false,'message'=>$e->getMessage()],422); }
