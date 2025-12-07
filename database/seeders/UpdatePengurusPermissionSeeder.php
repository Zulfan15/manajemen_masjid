<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdatePengurusPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role pengurus_takmir
        $pengurusRole = Role::where('name', 'pengurus_takmir')->first();
        
        if (!$pengurusRole) {
            $this->command->error('Role pengurus_takmir tidak ditemukan!');
            return;
        }

        // Hapus semua permission takmir kecuali view
        $pengurusRole->revokePermissionTo([
            'takmir.create',
            'takmir.update',
            'takmir.delete',
        ]);

        // Pastikan hanya punya permission view
        if (!$pengurusRole->hasPermissionTo('takmir.view')) {
            $pengurusRole->givePermissionTo('takmir.view');
        }

        $this->command->info('✓ Permission pengurus_takmir berhasil diupdate');
        $this->command->info('  Permissions: ' . $pengurusRole->permissions->pluck('name')->implode(', '));
    }
}
