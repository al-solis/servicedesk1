<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tblPREmployee', function (Blueprint $table) {
            $table->string('strEmployeeID', 40)->primary();
            $table->string('strCompany', 100)->nullable();
            $table->string('strSocialSecurity', 12)->nullable();
            $table->string('strTitle', 30)->nullable();
            $table->string('strFirstName', 25)->nullable();
            $table->string('strMiddleName', 20)->nullable();
            $table->string('strLastName', 25)->nullable();
            $table->string('strAddress', 100)->nullable();
            $table->string('strCounty', 25)->nullable();
            $table->string('strCity', 25)->nullable();
            $table->string('strState', 5)->nullable();
            $table->string('strZip', 12)->nullable();
            $table->string('strCountry', 25)->nullable();
            $table->string('strPhone', 25)->nullable();
            $table->string('strFax', 25)->nullable();
            $table->string('strEmail', 50)->nullable();
            $table->text('memWebSite')->nullable();
            $table->string('strDepartment', 30)->nullable();
            $table->string('strCommission', 25)->nullable();
            $table->float('dblCommissionPercent')->nullable();
            $table->dateTime('dtmDateHired')->nullable();
            $table->dateTime('dtmBirthDate')->nullable();
            $table->string('strSupervisor', 25)->nullable();
            $table->dateTime('dtmTerminated')->nullable();
            $table->string('strTerminatedReason', 40)->nullable();
            $table->string('strTerritory', 50)->nullable();
            $table->string('strNickName', 20)->nullable();
            $table->string('strSpouse', 40)->nullable();
            $table->string('strGender', 6)->nullable();
            $table->string('strEducation', 40)->nullable();
            $table->string('strDegree', 40)->nullable();
            $table->string('strNationality', 20)->nullable();
            $table->string('strEmergencyContact', 35)->nullable();
            $table->string('strEmergencyPhone', 35)->nullable();
            $table->dateTime('dtmDateEntered')->nullable();
            $table->dateTime('dtmLastModified')->nullable();
            $table->string('strNotes', 100)->nullable();
            $table->string('strDirections', 150)->nullable();
            $table->string('strCurrencyID', 3)->notNullable();
            $table->dateTime('dtmLastRaise')->nullable();
            $table->dateTime('dtmLastReview')->nullable();
            $table->string('strTaxGroup', 15)->nullable();
            $table->string('strEarningGroup', 15)->nullable();
            $table->string('strDeductionGroup', 15)->nullable();
            $table->string('strLiabilityGroup', 15)->nullable();
            $table->string('strTimeOffGroup', 15)->nullable();
            $table->string('strPayGroup', 15)->nullable();
            $table->string('strFilingStatus', 25)->nullable();
            $table->string('strPayPeriod', 15)->nullable();
            $table->integer('intAllowance')->nullable();
            $table->integer('intDependent')->nullable();
            $table->boolean('ysnPay')->default(false);
            $table->dateTime('dtmLastPaid')->nullable();
            $table->float('dblHours')->nullable();
            $table->string('strType', 15)->nullable();
            $table->boolean('ysnDirectDeposit')->default(false);
            $table->string('strBankName', 25)->nullable();
            $table->string('strBankNumber', 20)->nullable();
            $table->string('strBankAccount', 20)->nullable();
            $table->boolean('ysnStatutoryEmployee')->default(false);
            $table->boolean('ysnDeceased')->default(false);
            $table->boolean('ysnPensionPlan')->default(false);
            $table->boolean('ysnLegalRep')->default(false);
            $table->boolean('ysn1099Employee')->default(false);
            $table->boolean('ysnDeferredComp')->default(false);
            $table->float('dblAnnualSalary')->nullable();
            $table->timestamps(); // Equivalent to upsize_ts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblpremployee');
    }
};
