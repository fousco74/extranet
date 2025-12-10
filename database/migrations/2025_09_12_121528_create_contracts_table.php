<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // Base
            $table->string('contract_type');                 // ex: "CONTRAT DE STAGE DE QUALIFICATION PROFESSIONNELLE", "CDI", "CDD"
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // Dates
            $table->date('effective_date');                  // date d'effet
            $table->date('expiration_date')->nullable();     // date d'expiration
            $table->date('contract_date')->nullable();       // pour l'affichage "Date de création" dans la liste

            // Affectations
            $table->foreignId('assigned_to')->constrained('users');         // collaborateur
            $table->foreignId('internship_supervisor')->nullable()
                  ->constrained('users');                                   // maître de stage (optionnel)

            // Infos entreprise
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_rcs')->nullable();
            $table->string('legal_representative')->nullable();

            // Paramètres contrat
            $table->unsignedInteger('contract_duration')->nullable();       // en mois
            $table->string('salary')->nullable();                            // tu gères le format côté front (FCFA)
            $table->string('work_location')->nullable();

            // Infos RH
            $table->string('hr_representative')->nullable();
            $table->string('hr_position')->nullable();
            $table->string('hr_contact')->nullable();

            // Signature(s)
            $table->boolean('signe')->default(false);                        // signé par le collaborateur ?
            $table->longText('signature')->nullable();                       // base64 (optionnel)
            $table->string('signature_path')->nullable();                    // chemin storage
            $table->string('signature_mime')->nullable();
            $table->timestamp('signature_date')->nullable();

            // (Optionnel) signature RH si tu veux l’activer plus tard
            $table->boolean('signe_rh')->default(false);
            $table->longText('signature_rh')->nullable();
            $table->string('signature_rh_path')->nullable();
            $table->string('signature_rh_mime')->nullable();
            $table->timestamp('signature_rh_date')->nullable();

            // Divers
            $table->boolean('archived')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
