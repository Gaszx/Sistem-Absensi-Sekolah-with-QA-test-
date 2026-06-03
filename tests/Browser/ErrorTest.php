<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class ErrorTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_err_001_admin_akses_guru()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@sekolah.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/guru/dashboard')
                    ->assertSee('403')
                    ->screenshot('ERR-001_Admin_Akses_Guru');
        });
    }

    public function test_err_002_tahun_ajaran_tak_ditemukan()
    {
        $guru = User::create([
            'name' => 'Guru User',
            'email' => 'guru@sekolah.com',
            'password' => bcrypt('password123'),
            'role' => 'guru'
        ]);

        $this->browse(function (Browser $browser) use ($guru) {
            $browser->loginAs($guru)
                    ->visit('/guru/dashboard')
                    ->assertSee('Tidak ada jadwal mengajar hari ini')
                    ->screenshot('ERR-002_Tahun_Ajaran_Tak_Ditemukan');
        });
    }
}
