<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class AdminMasterDataTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_master_data_e2e_flow()
    {
        // 1. Setup Admin
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@sekolah.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->resize(1920, 1080);
            
            // --- LOGIN ---
            $browser->loginAs($admin)
                    ->visit('/admin/dashboard')
                    ->assertSee('Admin Utama')
                    ->screenshot('E2E-001-Login-Admin');

            // --- 1. MATA PELAJARAN ---
            $browser->visit('/admin/mapel/create')
                    ->waitFor('input[name="name"]')
                    ->type('name', 'Pemrograman Web')
                    ->press('Simpan')
                    ->pause(1000)
                    ->assertPathIs('/admin/mapel')
                    ->assertSee('Pemrograman Web')
                    ->screenshot('E2E-002-Create-Mapel');

            // --- 2. GURU ---
            $mapel = \App\Models\Subject::where('name', 'Pemrograman Web')->first();
            $browser->visit('/admin/guru/create')
                    ->waitFor('input[name="name"]')
                    ->type('name', 'Budi Programmer, S.Kom')
                    ->type('email', 'budi@sekolah.com')
                    ->select('subject_id', (string)$mapel->id)
                    ->type('password', 'password123')
                    ->press('Simpan Data')
                    ->pause(1000)
                    ->assertPathIs('/admin/guru')
                    ->assertSee('Budi Programmer, S.Kom')
                    ->screenshot('E2E-003-Create-Guru');

            // --- 3. TAHUN Ajaran ---
            $browser->visit('/admin/tahun-ajaran/create')
                    ->waitFor('input[name="name"]')
                    ->script("
                        document.querySelector('input[name=\"name\"]').value = '2026/2027';
                        document.querySelector('select[name=\"semester\"]').value = 'Ganjil';
                        document.querySelector('input[name=\"is_active\"]').checked = true;
                    ");
            $browser->script("document.querySelector('.form-card form').submit();");
            $browser->pause(1000)
                    ->assertPathIs('/admin/tahun-ajaran')
                    ->assertSee('2026/2027')
                    ->screenshot('E2E-004-Create-Tahun-Ajaran');

            // --- 4. KELAS ---
            $tahun = \App\Models\AcademicYear::where('name', '2026/2027')->first();
            $browser->visit('/admin/kelas/create')
                    ->waitFor('input[name="name"]')
                    ->script("
                        document.querySelector('select[name=\"academic_year_id\"]').value = '" . $tahun->id . "';
                        document.querySelector('input[name=\"name\"]').value = 'XII RPL 1';
                    ");
            $browser->script("document.querySelector('.form-card form').submit();");
            $browser->pause(1000)
                    ->assertPathIs('/admin/kelas')
                    ->assertSee('XII RPL 1')
                    ->screenshot('E2E-005-Create-Kelas');

            // --- 5. SISWA ---
            $kelas = \App\Models\Classroom::where('name', 'XII RPL 1')->first();
            $browser->visit('/admin/siswa/create')
                    ->waitFor('input[name="name"]')
                    ->script("
                        document.querySelector('input[name=\"name\"]').value = 'Andi Siswa';
                        document.querySelector('select[name=\"gender\"]').value = 'L';
                        document.querySelector('select[name=\"classroom_id\"]').value = '" . $kelas->id . "';
                    ");
            $browser->script("document.querySelector('.form-card form').submit();");
            $browser->pause(1000)
                    ->assertPathIs('/admin/siswa')
                    ->assertSee('Andi Siswa')
                    ->screenshot('E2E-006-Create-Siswa');

            // --- 6. JADWAL ---
            $guru = \App\Models\User::where('email', 'budi@sekolah.com')->first();
            $browser->visit('/admin/jadwal/create')
                    ->waitFor('input[name="start_time"]')
                    ->script("
                        document.querySelector('select[name=\"classroom_id\"]').value = '" . $kelas->id . "';
                        document.querySelector('select[name=\"subject_id\"]').value = '" . $mapel->id . "';
                        document.querySelector('select[name=\"user_id\"]').value = '" . $guru->id . "';
                        document.querySelector('select[name=\"day\"]').value = '" . \Carbon\Carbon::now()->isoFormat('dddd') . "';
                        document.querySelector('input[name=\"start_time\"]').value = '07:00';
                        document.querySelector('input[name=\"end_time\"]').value = '09:00';
                    ");
            $browser->script("document.querySelector('.form-card form').submit();");
            $browser->pause(1000)
                    ->assertPathIs('/admin/jadwal')
                    ->assertSee('07:00')
                    ->screenshot('E2E-007-Create-Jadwal');

            // --- 7. REKAP ABSENSI (ADMIN) ---
            $browser->visit('/admin/rekap')
                    ->assertSee('Rekap Absensi')
                    ->screenshot('E2E-008-Rekap-Admin');
                    
            // --- 8. LOGOUT ---
            $browser->script("document.querySelector('.btn-logout').click();");
            $browser->pause(1000)
                    ->assertPathIs('/')
                    ->screenshot('E2E-009-Logout-Sukses');
        });
    }
}
