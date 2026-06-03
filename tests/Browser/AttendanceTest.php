<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Support\Carbon;

class AttendanceTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $guru;
    protected $guruLain;
    protected $schedule;
    protected $student1;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->guru = User::create([
            'name' => 'Guru A',
            'email' => 'guru.a@sekolah.com',
            'password' => bcrypt('password123'),
            'role' => 'guru'
        ]);

        $this->guruLain = User::create([
            'name' => 'Guru B',
            'email' => 'guru.b@sekolah.com',
            'password' => bcrypt('password123'),
            'role' => 'guru'
        ]);

        $ay = AcademicYear::create([
            'name' => '2026/2027',
            'semester' => 'Ganjil',
            'is_active' => true
        ]);

        $class = Classroom::create([
            'name' => 'Kelas XA',
            'academic_year_id' => $ay->id
        ]);

        $subject = Subject::create([
            'name' => 'Matematika'
        ]);

        $this->student1 = Student::create([
            'name' => 'Budi',
            'gender' => 'L',
            'classroom_id' => $class->id
        ]);

        $hariIni = Carbon::now()->isoFormat('dddd');
        $this->schedule = Schedule::create([
            'classroom_id' => $class->id,
            'subject_id' => $subject->id,
            'user_id' => $this->guru->id,
            'day' => $hariIni,
            'start_time' => '07:00',
            'end_time' => '09:00'
        ]);
        
        Schedule::create([
            'classroom_id' => $class->id,
            'subject_id' => $subject->id,
            'user_id' => $this->guruLain->id,
            'day' => $hariIni,
            'start_time' => '09:00',
            'end_time' => '11:00'
        ]);
    }

    public function test_att_001_tampil_jadwal_sesuai()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->guru)
                    ->visit('/guru/dashboard')
                    ->assertSee('Kelas XA')
                    ->screenshot('ATT-001_Tampil_Jadwal');
        });
    }

    public function test_att_002_akses_jadwal_guru_lain()
    {
        $this->browse(function (Browser $browser) {
            $guruBSchedule = Schedule::where('user_id', $this->guruLain->id)->first();
            $browser->loginAs($this->guru)
                    ->visit('/guru/absensi/' . $guruBSchedule->id)
                    ->assertSee('403')
                    ->screenshot('ATT-002_Akses_Jadwal_Lain');
        });
    }

    public function test_att_003_submit_absensi_format()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->guru)
                    ->visit('/guru/absensi/' . $this->schedule->id)
                    ->script("document.querySelector(\"input[name='attendances[{$this->student1->id}]'][value='hadir']\").checked = true;");
            $browser->press('Simpan Absensi')
                    ->waitForLocation('/guru/dashboard', 5)
                    ->pause(500)
                    ->assertPathIs('/guru/dashboard')
                    ->screenshot('ATT-003_Submit_Absensi_Format');
        });
    }

    public function test_att_004_ubah_absensi_sama()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->guru)
                    ->visit('/guru/absensi/' . $this->schedule->id)
                    ->script("document.querySelector(\"input[name='attendances[{$this->student1->id}]'][value='hadir']\").checked = true;");
            $browser->press('Simpan Absensi')
                    ->waitForLocation('/guru/dashboard', 5)
                    ->pause(1000);

            $browser->visit('/guru/absensi/' . $this->schedule->id)
                    ->script("document.querySelector(\"input[name='attendances[{$this->student1->id}]'][value='sakit']\").checked = true;");
            $browser->press('Simpan Absensi')
                    ->waitForLocation('/guru/dashboard', 5)
                    ->pause(500)
                    ->assertPathIs('/guru/dashboard')
                    ->screenshot('ATT-004_Ubah_Absensi_Sama');
        });
    }

    public function test_att_005_submit_absen_kosong()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->guru)
                    ->visit('/guru/absensi/' . $this->schedule->id)
                    ->press('Simpan Absensi')
                    ->assertPathIs('/guru/absensi/' . $this->schedule->id)
                    ->screenshot('ATT-005_Submit_Absen_Kosong');
        });
    }
}
