<?php
declare(strict_types=1);
namespace Nexa\Reports;

use Nexa\Accounting\Account;
use Nexa\Accounting\AccountType;
use Nexa\Accounting\LedgerService;

final class StatementService
{
    public function __construct(private readonly LedgerService $ledger = new LedgerService()) {}

    /** @param list<array<string,mixed>> $entries @param array<int,Account> $accounts */
    public function profitLoss(array $entries, array $accounts, ?int $companyId = null, ?string $period = null): array
    {
        $balances=$this->ledger->balances($entries,$accounts,$companyId,$period);$revenue=$expense=0;$rows=[];
        foreach($accounts as $id=>$account){$value=$balances[$id]['balance']??0;if($value===0)continue;if($account->type===AccountType::Revenue){$revenue+=$value;$rows[]=['section'=>'revenue','code'=>$account->code,'name'=>$account->name,'amount'=>$value];}elseif($account->type===AccountType::Expense){$expense+=$value;$rows[]=['section'=>'expense','code'=>$account->code,'name'=>$account->name,'amount'=>$value];}}
        return ['revenue'=>$revenue,'expense'=>$expense,'profit'=>$revenue-$expense,'margin'=>$revenue!==0?(($revenue-$expense)/$revenue*100):0.0,'rows'=>$rows];
    }

    /** @param list<array<string,mixed>> $entries @param array<int,Account> $accounts */
    public function balanceSheet(array $entries, array $accounts, ?int $companyId = null, ?string $period = null): array
    {
        $balances=$this->ledger->balances($entries,$accounts,$companyId,$period);$assets=$liabilities=$equity=0;$rows=[];
        foreach($accounts as $id=>$account){$value=$balances[$id]['balance']??0;if($value===0)continue;if($account->type===AccountType::Asset)$assets+=$value;elseif($account->type===AccountType::Liability)$liabilities+=$value;elseif($account->type===AccountType::Equity)$equity+=$value;$rows[]=['type'=>$account->type->value,'code'=>$account->code,'name'=>$account->name,'amount'=>$value];}
        $pl=$this->profitLoss($entries,$accounts,$companyId,$period);$equityWithProfit=$equity+$pl['profit'];
        return ['assets'=>$assets,'liabilities'=>$liabilities,'equity'=>$equityWithProfit,'retained_current_profit'=>$pl['profit'],'difference'=>$assets-($liabilities+$equityWithProfit),'rows'=>$rows];
    }

    /** @param list<array<string,mixed>> $entries @param array<int,Account> $accounts */
    public function cashPosition(array $entries,array $accounts,?int $companyId=null,?string $period=null): int
    {
        $balances=$this->ledger->balances($entries,$accounts,$companyId,$period);$cash=0;foreach($accounts as $id=>$account)if($account->isCash)$cash+=$balances[$id]['balance']??0;return $cash;
    }
}
