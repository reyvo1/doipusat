<?php
declare(strict_types=1);
namespace Nexa\Security;

enum Permission: string
{
    case DashboardView = 'dashboard.view';
    case CompanyView = 'company.view';
    case CompanyManage = 'company.manage';
    case JournalView = 'journal.view';
    case JournalPost = 'journal.post';
    case JournalReverse = 'journal.reverse';
    case IncomeManage = 'income.manage';
    case ApprovalDecide = 'approval.decide';
    case PeriodClose = 'period.close';
    case ArapManage = 'arap.manage';
    case BankManage = 'bank.manage';
    case BankImport = 'bank.import';
    case BankReconcile = 'bank.reconcile';
    case BudgetManage = 'budget.manage';
    case AssetManage = 'asset.manage';
    case TaxManage = 'tax.manage';
    case FxManage = 'fx.manage';
    case ConsolidationManage = 'consolidation.manage';
    case IntegrationManage = 'integration.manage';
    case ReportView = 'report.view';
    case ReportExport = 'report.export';
    case UserManage = 'user.manage';
    case AuditView = 'audit.view';
    case BackupManage = 'backup.manage';
}
