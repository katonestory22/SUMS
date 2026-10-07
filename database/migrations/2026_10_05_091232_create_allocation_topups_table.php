<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('allocation_topups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2); // negative = manual correction
            $table->date('received_date');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::transaction(function () {
            $byProject = DB::table('allocations')->orderBy('id')->get()->groupBy('project_id');

            foreach ($byProject as $rows) {
                $keep = $rows->first();

                foreach ($rows as $row) {
                    DB::table('allocation_topups')->insert([
                        'allocation_id' => $keep->id,
                        'amount' => $row->amount,
                        'received_date' => $row->allocation_date,
                        'notes' => $row->notes ?? null,
                        'created_at' => $row->created_at,
                        'updated_at' => now(),
                    ]);

                    if ($row->id !== $keep->id) {
                        DB::table('expenses')
                            ->where('allocation_id', $row->id)
                            ->update(['allocation_id' => $keep->id]);
                        DB::table('allocations')->where('id', $row->id)->delete();
                    }
                }

                DB::table('allocations')->where('id', $keep->id)
                    ->update(['amount' => $rows->sum('amount')]);
            }
        });

        Schema::table('allocations', function (Blueprint $table) {
            $table->unique('project_id'); // one row per project from now on
        });
    }

    public function down(): void
    {
        Schema::table('allocations', fn(Blueprint $t) => $t->dropUnique(['project_id']));
        Schema::dropIfExists('allocation_topups');
    }
};
