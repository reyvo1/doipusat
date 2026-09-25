<?php
declare(strict_types=1);
namespace Nexa\Accounting;

use Nexa\Core\DomainException;

final readonly class JournalLine
{
    public function __construct(
        public int $accountId,
        public int $debit,
        public int $credit,
        public string $description = '',
        public ?int $intercompanyCompanyId = null,
    ) {
        if ($accountId <= 0) {
            throw new DomainException('Account ID baris jurnal tidak valid.');
        }
        if ($debit < 0 || $credit < 0 || ($debit > 0 && $credit > 0) || ($debit === 0 && $credit === 0)) {
            throw new DomainException('Baris jurnal harus hanya memiliki debit atau kredit positif.');
        }
    }

    public function amount(): int { return max($this->debit, $this->credit); }
    public function reverse(): self { return new self($this->accountId, $this->credit, $this->debit, $this->description, $this->intercompanyCompanyId); }
}
