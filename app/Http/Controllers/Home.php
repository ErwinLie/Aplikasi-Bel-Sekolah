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

public function event()
{
    if (session('id_level') == '1') {
        $this->logActivity('User Membuka Menu Event');

        $model = new M_bel();

        // Ambil data user berdasarkan id_user dari session
        $user = DB::table('tb_user')->where('id_user', session('id_user'))->first();

        // Ambil data setting berdasarkan id_setting
        $setting = DB::table('tb_setting')->where('id_setting', 1)->first();

        // Panggil model dan gunakan method tampil untuk mengambil data dari tb_event
        $events = $model->tampil('tb_event');

        // Kirim data ke view
        $data = [
            'user' => $user,
            'setting' => $setting,
            'events' => $events,
        ];

        echo view('header', $data);
            echo view('menu', $data);
            echo view('event', $data);
            echo view('footer');
    } else {
        return redirect()->route('login');
    }
}

public function aksi_t_event(Request $request)
    {
        $model = new M_bel();

        $this->logActivity('User Melakukan Tambah Event');

        $request->validate([
            'nama_event' => 'required|string|max:255',
        ]);
            
            $data = [
                'nama_event' => $request->input('nama_event'),
            ];
            
            $model->tambah('tb_event', $data);
            return redirect()->route('event')->with('success', 'Data event berhasil ditambahkan.');
            // print_r($isi);
        }

        public function aksi_e_event(Request $request)
        {
            $model = new M_bel();
        
            $this->logActivity('User Melakukan Edit Event');
        
            // Validasi input
            $request->validate([
                'id_event' => 'required|integer|exists:tb_event,id_event',
                'nama_event' => 'required|string|max:255',
            ]);
        
            // Ambil data dari form
            $id_event = $request->input('id_event');
            $nama_event = $request->input('nama_event');
        
            // Data yang akan diperbarui
            $data = [
                'nama_event' => $nama_event,
            ];
        
            // Update data di database
            $model->edit2('tb_event', $data, ['id_event' => $id_event]);
        
            // print_r($data);
            // Redirect kembali ke halaman event dengan pesan sukses
            return redirect()->route('event')->with('success', 'Data event berhasil diperbarui.');
        }                

public function hapus_event($id)
{
    $this->logActivity('User Melakukan Hapus Event');

    // Hapus data activity berdasarkan id_activity
    DB::table('tb_event')->where('id_event', $id)->delete();

    return redirect()->route('event');
}

public function jadwal()
{
    if (session('id_level') == '1') {
        $this->logActivity('User Membuka Menu Jadwal');

        $model = new M_bel();

        // Ambil data user berdasarkan id_user dari session
        $user = DB::table('tb_user')->where('id_user', session('id_user'))->first();

        // Ambil data setting berdasarkan id_setting
        $setting = DB::table('tb_setting')->where('id_setting', 1)->first();

        // join tb_jadwal dengan tb_event berdasarkan id_event
        $jadwals = $model->join('tb_jadwal', 
        'tb_event', 
        'tb_jadwal.id_event', 'tb_event.id_event');

        $events= $model->tampil('tb_event');

        // Kirim data ke view
        $data = [
            'user' => $user,
            'setting' => $setting,
            'jadwals' => $jadwals,
            'events' => $events,
        ];

        echo view('header', $data);
            echo view('menu', $data);
            echo view('jadwal', $data);
            echo view('footer');
    } else {
        return redirect()->route('login');
    }
}

public function aksi_t_jadwal(Request $request)
{
    $model = new M_bel();

    $this->logActivity('User Melakukan Tambah Jadwal');

    // Validate the input
    $request->validate([
        'id_event' => 'required|integer|exists:tb_event,id_event',
        'sesi' => 'required|string|max:255',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i',
    ]);

    // Prepare the data to be inserted
    $data = [
        'id_event' => $request->input('id_event'),
        'sesi' => $request->input('sesi'),
        'jam_mulai' => $request->input('jam_mulai'),
        'jam_selesai' => $request->input('jam_selesai'),
    ];

    // Insert the data into the tb_jadwal table
    $model->tambah('tb_jadwal', $data);

    // Redirect to jadwal page with success message
    return redirect()->route('jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
}

public function aksi_e_jadwal(Request $request)
{
    $model = new M_bel();

    $this->logActivity('User Melakukan Edit Jadwal');

    // Validate the input
    $request->validate([
        'id_jadwal' => 'required|integer|exists:tb_jadwal,id_jadwal',
        'id_event' => 'required|integer|exists:tb_event,id_event',
        'sesi' => 'required|string|max:255',
        'jam_mulai' => 'required|date_format:H:i',
        'jam_selesai' => 'required|date_format:H:i',
    ]);

    // Get the data from the form
    $id_jadwal = $request->input('id_jadwal');
    $id_event = $request->input('id_event');
    $sesi = $request->input('sesi');
    $jam_mulai = $request->input('jam_mulai');
    $jam_selesai = $request->input('jam_selesai');

    // Prepare the data to be updated
    $data = [
        'id_event' => $id_event,
        'sesi' => $sesi,
        'jam_mulai' => $jam_mulai,
        'jam_selesai' => $jam_selesai,
    ];

    // Update the data in the tb_jadwal table
    $model->edit2('tb_jadwal', $data, ['id_jadwal' => $id_jadwal]);

    // Redirect back to jadwal page with success message
    return redirect()->route('jadwal')->with('success', 'Jadwal berhasil diperbarui.');
}


public function hapus_jadwal($id)
{
    $this->logActivity('User Melakukan Hapus Jadwal');

    // Hapus data activity berdasarkan id_activity
    DB::table('tb_jadwal')->where('id_jadwal', $id)->delete();

    return redirect()->route('jadwal');
}

}