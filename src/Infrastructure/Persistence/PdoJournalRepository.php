<?php
declare(strict_types=1);
namespace Nexa\Infrastructure\Persistence;
use Nexa\Accounting\JournalEntry;
use Nexa\Core\Ids;
final class PdoJournalRepository
{
    public function __construct(private readonly \PDO $pdo){}
    public function insert(JournalEntry$entry,?int$userId=null,?int$branchId=null,?int$departmentId=null):array{$no=Ids::journalNo($entry->date,$entry->source==='daily_income'?'INC':($entry->source==='reversal'?'REV':'JRN'));$this->pdo->beginTransaction();try{$st=$this->pdo->prepare("INSERT INTO journal_entries(company_id,branch_id,department_id,journal_no,journal_date,description,source_type,status,posted_at,created_by,reversal_of,external_ref) VALUES(?,?,?,?,?,?,?,'posted',NOW(),?,?,?)");$st->execute([$entry->companyId,$branchId,$departmentId,$no,$entry->date,$entry->description,$entry->source,$userId,$entry->reversalOf,$entry->metadata['external_ref']??null]);$id=(int)$this->pdo->lastInsertId();$line=$this->pdo->prepare("INSERT INTO journal_lines(journal_id,account_id,description,debit,credit,intercompany_company_id) VALUES(?,?,?,?,?,?)");foreach($entry->lines as$l)$line->execute([$id,$l->accountId,$l->description,$l->debit,$l->credit,$l->intercompanyCompanyId]);$this->pdo->commit();return['id'=>$id,'journal_no'=>$no];}catch(\Throwable$e){if($this->pdo->inTransaction())$this->pdo->rollBack();throw$e;}}
    public function markReversed(int$originalId,int$reversalId):void{$st=$this->pdo->prepare("UPDATE journal_entries SET reversed_by=? WHERE id=? AND reversed_by IS NULL");$st->execute([$reversalId,$originalId]);if($st->rowCount()!==1)throw new \RuntimeException('Jurnal sudah direversal atau tidak ditemukan.');}
}
