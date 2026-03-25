<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->foreignId('course_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('stripe_payment_intent_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('usd');
            $table->string('status', 20)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
