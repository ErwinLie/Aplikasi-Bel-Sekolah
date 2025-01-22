<?php

namespace App\Http\Controllers;
use App\Models\M_bel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class Home extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

public function dashboard()
{
    $id_level = session()->get('id_level');
    if (!$id_level) {
        return redirect()->route('login');
    }

    $model = new M_bel();
    $userId = session()->get('id_user');
    $username = session()->get('username');

    $where = array('id_user' => session()->get('id_user'));
	$data['user'] = $model->getWhere('tb_user', $where);

    $data['setting'] = $model->getWhere('tb_setting', ['id_setting' => 1]);

    echo view('header', $data);
    echo view('menu', $data);
    echo view('footer');
}

    public function login()
	{
        $model = new M_bel();
        $data['setting'] = $model->getWhere('tb_setting', ['id_setting' => 1]);
		echo view('header',$data);
		echo view('login',$data);
        echo view('footer');
	}

       // tes
       //tolong kaki saya sakit

       public function aksi_login(Request $request)
       {
           $u = $request->input('username');
           $p = $request->input('password');
           $captchaAnswer = $request->input('captcha_answer');
       
           // Log the activity
           $this->logActivity('User melakukan Login');
       
           $user = DB::table('tb_user')
               ->where('username', $u)
               ->where('password', md5($p))
               ->first();
       
           // Offline CAPTCHA answer (should match the one generated in the view)
           if (!$this->isOnline() && !empty($captchaAnswer)) {
               $correctAnswer = $request->input('correct_captcha_answer');
               if ($captchaAnswer != $correctAnswer) {
                   return redirect()->route('login')->with('error', 'Incorrect offline CAPTCHA.');
               }
           }
       
           if ($user) {
               // Handle sessions as usual
               session([
                   'id_user' => $user->id_user,
                   'id_level' => $user->id_level,
                //    'email' => $user->email,
                   'username' => $user->username,
               ]);
       
               // Redirect to the dashboard
               return redirect()->route('dashboard');
           } else {
               return redirect()->route('login')->with('error', 'Invalid username or password.');
           }
       }
       
       // Function to check if the client is online
       private function isOnline()
       {
           // A simple method to check if the client is online (can be more sophisticated)
           return @fopen("http://www.google.com:80/", "r") ? true : false;
       }
       
       // Function to log activity
       private function logActivity($activity)
       {
           // Data to be inserted into the table
           $data = [
               'id_user'   => session('id_user'), // Access session data in Laravel
               'activity'  => $activity,
               'timestamp' => now(), // Laravel helper for current timestamp
            //    'delete_at' => 0, // Assuming 0 indicates not deleted
           ];
       
           // Insert the data into the 'tb_activity' table using Laravel's DB facade
           DB::table('tb_activity')->insert($data);
       }
    
       public function setting()
       {
           if (session('id_level') == '1') {
               $this->logActivity('User Membuka Menu Setting');
       
               // Ambil data user berdasarkan id_user dari session
               $user = DB::table('tb_user')->where('id_user', session('id_user'))->first();
       
               // Ambil data setting berdasarkan id_setting
               $setting = DB::table('tb_setting')->where('id_setting', 1)->first();
       
               // Kirim data ke view
               $data = [
                   'user' => $user,
                   'setting' => $setting,
               ];
       
               echo view('header', $data);
                   echo view('menu', $data);
                   echo view('setting', $data);
                   echo view('footer');
           } else {
               return redirect()->route('error404');
           }
       }

       public function profile()
       {
           if (session('id_level') == '1') {
               $this->logActivity('User Membuka Menu Profile');
       
               // Ambil data user berdasarkan id_user dari session
               $user = DB::table('tb_user')->where('id_user', session('id_user'))->first();
       
               // Ambil data setting berdasarkan id_setting
               $setting = DB::table('tb_setting')->where('id_setting', 1)->first();

               $darren = DB::table('tb_user')->where('id_user', session('id_user'))->first();
       
               // Kirim data ke view
               $data = [
                   'user' => $user,
                   'setting' => $setting,
                   'darren' => $darren
               ];
       
                echo view('header', $data);
                   echo view('menu', $data);
                   echo view('profile', $data);
                   echo view('footer');
           } else {
               return redirect()->route('login');
           }
       }

       public function editfoto(Request $request)
{
    $model = new M_bel();

    // Log aktivitas pengguna
    $this->logActivity('User Mengedit Foto Profile');

    // Mendapatkan ID pengguna yang sedang login
    // $userId = Auth::id();
    $userId = session()->get('id_user');
    $user = $model->getById($userId);

    // Periksa apakah file foto diunggah
    if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
        $file = $request->file('foto');

        // Buat nama file baru
        $newFileName = $file->hashName();

        // Simpan file ke folder public/img/avatar
        $file->storeAs('public/img/avatar', $newFileName);

        // Jika pengguna sudah memiliki foto lama, hapus file lama
        if ($user->foto && file_exists(public_path('img/avatar/' . $user->foto))) {
            unlink(public_path('img/avatar/' . $user->foto));
        }

        // Perbarui nama file foto di database
        $user->foto = $newFileName;
        $user->save();
    }

    // Redirect ke halaman profil
    return redirect()->route('profile')->with('success', 'Foto profil berhasil diperbarui.');
}

       public function aksi_e_setting(Request $request)
{
    $this->logActivity('User Melakukan Edit Setting');

    $a = $request->input('nama_web');
    $icon = $request->file('logo_tab');
    $dash = $request->file('logo_dashboard');
    $login = $request->file('logo_login');

    // Data yang akan diupdate
    $data = ['nama_web' => $a];

    // Proses file logo_tab
    if ($icon && $icon->isValid()) {
        $iconPath = $icon->storeAs('public/img/avatar', $icon->getClientOriginalName());
        $data['logo_tab'] = basename($iconPath);
    }

    // Proses file logo_dashboard
    if ($dash && $dash->isValid()) {
        $dashPath = $dash->storeAs('public/img/avatar', $dash->getClientOriginalName());
        $data['logo_dashboard'] = basename($dashPath);
    }

    // Proses file logo_login
    if ($login && $login->isValid()) {
        $loginPath = $login->storeAs('public/img/avatar', $login->getClientOriginalName());
        $data['logo_login'] = basename($loginPath);
    }

    // Update data ke database
    DB::table('tb_setting')->where('id_setting', 1)->update($data);

    return redirect()->route('setting');
}

    public function logout()
    {
        $model = new M_bel();
        $id_user = session()->get('id_user');
    

        session()->flush();
        return redirect()->route('login'); 
    }

    public function activity()
{
    if (session('id_level') > 0) {
        $this->logActivity('User membuka Log Activity');

        // Ambil data user berdasarkan id_user dari session
        $user = DB::table('tb_user')->where('id_user', session('id_user'))->first();

        // Ambil data setting berdasarkan id_setting
        $setting = DB::table('tb_setting')->where('id_setting', 1)->first();

        // Ambil data aktivitas dengan join ke tb_user
        $activities = DB::table('tb_activity')
            ->join('tb_user', 'tb_activity.id_user', '=', 'tb_user.id_user')
            ->select('tb_activity.*', 'tb_user.username')
            ->where('tb_activity.id_user', session('id_user'))
            ->get();

        // Kirim data ke view
        $data = [
            'user' => $user,
            'setting' => $setting,
            'erwin' => $activities,
        ];

        echo view('header', $data);
        echo view('menu', $data);
        echo view('activity', $data);
        echo view('footer');
    } else {
        return redirect()->route('login');
    }
}

public function hapus_activity($id)
{
    $this->logActivity('User Melakukan Hapus Activity');

    // Hapus data activity berdasarkan id_activity
    DB::table('tb_activity')->where('id_activity', $id)->delete();

    return redirect()->route('activity');
}

}