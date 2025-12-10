<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Contract;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contract_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Contract::class)->constrained()->nullable()->onDelete('cascade');
            $table->string('title');
            $table->longText('contents');      // HTML venant du front (placeholders déjà remplacés)
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_articles');
    }
};
