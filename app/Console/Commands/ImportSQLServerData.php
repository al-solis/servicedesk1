<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSQLServerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sqlserver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Employee table from SQL Server to MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting import from SQL Server to MySQL...');
        // Connect to SQL Server
        $records = DB::connection('sqlsrv')->table('tblPREmployee')->get();
        foreach ($records as $record) {
            DB::connection('mysql')->table('tblPREmployee')->insert([
                'strEmployeeID' => $record->strEmployeeID,
                'strCompany' => $record->strCompany,
                'strSocialSecurity' => $record->strSocialSecurity,
                'strTitle' => $record->strTitle,
                'strFirstName' => $record->strFirstName,
                'strLastName' => $record->strLastName,
                'strMiddleName' => $record->strMiddleName,
                'strAddress' => $record->strAddress,
                'strCounty' => $record->strCounty,
                'strCity' => $record->strCity,
                'strState' => $record->strState,
                'strZip' => $record->strZip,
                'strCountry' => $record->strCountry,
                'strPhone' => $record->strPhone,
                'strFax' => $record->strFax,
                'strEmail' => $record->strEmail,
                'memWebSite' => $record->memWebSite,
                'strDepartment' => $record->strDepartment,
                'strCommission' => $record->strCommission,
                'dblCommissionPercent' => $record->dblCommissionPercent,
                'dtmDateHired' => $record->dtmDateHired,
                'dtmBirthDate' => $record->dtmBirthDate,
                'strSupervisor' => $record->strSupervisor,
                'dtmTerminated' => $record->dtmTerminated,
                'strTerminatedReason' => $record->strTerminatedReason,
                'strTerritory' => $record->strTerritory,
                'strNickName' => $record->strNickName,
                'strSpouse' => $record->strSpouse,
                'strGender' => $record->strGender,
                'strEducation' => $record->strEducation,
                'strDegree' => $record->strDegree,
                'strNationality' => $record->strNationality,
                'strEmergencyContact' => $record->strEmergencyContact,
                'strEmergencyPhone' => $record->strEmergencyPhone,
                'dtmDateEntered' => $record->dtmDateEntered,
                'dtmLastModified' => $record->dtmLastModified,
                'strNotes' => $record->strNotes,
                'strDirections' => $record->strDirections,
                'strCurrencyID' => $record->strCurrencyID,
                'dtmLastRaise' => $record->dtmLastRaise,
                'dtmLastReview' => $record->dtmLastReview,
                'strTaxGroup' => $record->strTaxGroup,
                'strEarningGroup' => $record->strEarningGroup,
                'strDeductionGroup' => $record->strDeductionGroup,
                'strLiabilityGroup' => $record->strLiabilityGroup,
                'strTimeOffGroup' => $record->strTimeOffGroup,
                'strPayGroup' => $record->strPayGroup,
                'strFilingStatus' => $record->strFilingStatus,
                'strPayPeriod' => $record->strPayPeriod,
                'intAllowance' => $record->intAllowance,
                'intDependent' => $record->intDependent,
                'ysnPay' => $record->ysnPay,
                'dtmLastPaid' => $record->dtmLastPaid,
                'dblHours' => $record->dblHours,
                'strType' => $record->strType,
                'ysnDirectDeposit' => $record->ysnDirectDeposit,
                'strBankName' => $record->strBankName,
                'strBankNumber' => $record->strBankNumber,
                'strBankAccount' => $record->strBankAccount,
                'ysnStatutoryEmployee' => $record->ysnStatutoryEmployee,
                'ysnDeceased' => $record->ysnDeceased,
                'ysnPensionPlan' => $record->ysnPensionPlan,
                'ysnLegalRep' => $record->ysnLegalRep,
                'ysn1099Employee' => $record->ysn1099Employee,
                'ysnDeferredComp' => $record->ysnDeferredComp,
                'dblAnnualSalary' => $record->dblAnnualSalary,
                //'timestamps' => $record->upsize_ts,
            ]);
        }
        $this->info('Import completed successfully.');
        $this->info('Total records imported: ' . count($records));
    }
}
