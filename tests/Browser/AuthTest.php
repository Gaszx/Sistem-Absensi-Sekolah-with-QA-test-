<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (!User::where('email', 'admin@sekolah.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@sekolah.com',
                'password' => bcrypt('password123'),
                'role' => 'admin'
            ]);
        }

        if (!User::where('email', 'guru@sekolah.com')->exists()) {
            User::create([
                'name' => 'Guru User',
                'email' => 'guru@sekolah.com',
                'password' => bcrypt('password123'),
                'role' => 'guru'
            ]);
        }
    }

    public function test_auth_001_login_admin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->type('email', 'admin@sekolah.com')
                    ->type('password', 'password123')
                    ->press('Masuk')
                    ->assertPathIs('/admin/dashboard')
                    ->screenshot('AUTH-001_Login_Admin');
        });
    }

    public function test_auth_002_login_guru()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout();
            $browser->visit('/')
                    ->type('email', 'guru@sekolah.com')
                    ->type('password', 'password123')
                    ->press('Masuk')
                    ->waitForLocation('/guru/dashboard', 5)
                    ->pause(500)
                    ->assertPathIs('/guru/dashboard')
                    ->screenshot('AUTH-002_Login_Guru');
        });
    }

    public function test_auth_003_login_salah()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout();
            $browser->visit('/')
                    ->type('email', 'guru@sekolah.com')
                    ->type('password', 'salah123')
                    ->press('Masuk')
                    ->pause(1000)
                    ->assertPathIs('/')
                    ->screenshot('AUTH-003_Login_Salah');
        });
    }

    public function test_auth_004_logout()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', 'guru@sekolah.com')->first())
                    ->resize(1920, 1080)
                    ->visit('/guru/dashboard')
                    ->script("document.querySelector('.btn-logout').click();");
            $browser->assertPathIs('/')
                    ->screenshot('AUTH-004_Logout');
        });
    }
}
