<?php
declare(strict_types=1);
$root=dirname(__DIR__);$required=[
 'src/Application/EnterpriseKernel.php','src/Accounting/JournalEntry.php','src/Revenue/DailyIncomePostingService.php',
 'src/ARAP/PaymentAllocationService.php','src/Banking/ReconciliationService.php','src/Consolidation/EliminationService.php',
 'src/Tax/TaxCalculator.php','src/FX/FxCalculator.php','src/Assets/DepreciationService.php','src/Reports/StatementService.php',
 'database/migrations/20260925_v6_enterprise_r3.sql'
];$fail=0;foreach($required as $file){$ok=is_file($root.'/'.$file)&&filesize($root.'/'.$file)>100;echo($ok?'PASS':'FAIL')."  {$file}\n";if(!$ok)$fail++;}
$phpFiles=[];$it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/src'));foreach($it as $f)if($f->isFile()&&$f->getExtension()==='php')$phpFiles[]=$f->getPathname();echo "INFO  enterprise PHP modules: ".count($phpFiles)."\n";if(count($phpFiles)<30){echo "FAIL  modularity floor not met\n";$fail++;}else echo "PASS  modularity floor met\n";exit($fail?1:0);
