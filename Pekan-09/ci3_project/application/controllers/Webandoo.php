<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/WebandooProfileSupport.php';

class Webandoo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'form'));
        $this->load->library('session');
    }

    public function index()
    {
        $this->login();
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            redirect('dashboard');
            return;
        }

        $this->load->view('webandoo/login', array(
            'status' => (string) $this->input->get('status', TRUE),
        ));
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            redirect('dashboard');
            return;
        }

        $this->load->view('webandoo/register', array(
            'status' => (string) $this->input->get('status', TRUE),
        ));
    }

    public function process_login()
    {
        if ($this->input->method() !== 'post') {
            redirect('login');
            return;
        }

        $services = $this->makeServices();
        $services['repository']->ensureSchema();

        $email = trim((string) $this->input->post('email', TRUE));
        $password = (string) $this->input->post('password', FALSE);

        if ($services['auth']->login($email, $password)) {
            redirect('dashboard');
            return;
        }

        redirect('login?status=login-failed');
    }

    public function process_register()
    {
        if ($this->input->method() !== 'post') {
            redirect('register');
            return;
        }

        $services = $this->makeServices();
        $services['repository']->ensureSchema();

        try {
            $name = trim((string) $this->input->post('nama', TRUE));
            $email = trim((string) $this->input->post('email', TRUE));
            $password = (string) $this->input->post('password', FALSE);

            $services['auth']->register($name, $email, $password);
            redirect('login?status=register-success');
            return;
        } catch (Throwable $exception) {
            redirect('register?status=register-failed');
            return;
        }
    }

    public function dashboard()
    {
        $services = $this->makeServices();
        $user = $services['profile']->boot();

        $this->load->view('webandoo/dashboard', array(
            'user_data' => $user,
            'nama_user' => !empty($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'Tamu'),
            'foto_profil' => isset($_SESSION['foto_profil']) ? $_SESSION['foto_profil'] : $services['fileManager']->getDefaultPhoto(),
        ));
    }

    public function profile()
    {
        $services = $this->makeServices();
        $user = $services['profile']->boot();

        $this->load->view('webandoo/profile', array(
            'user' => $user,
            'pesan' => $services['sessionManager']->pullFlash('profil_success'),
            'error' => $services['sessionManager']->pullFlash('profil_error'),
            'fotoProfil' => $services['fileManager']->getDisplayPath($user['foto_profil'] ?? ''),
            'punyaProfil' => $services['profile']->hasProfileData($user),
        ));
    }

    public function update_profile()
    {
        $services = $this->makeServices();

        try {
            $user = $services['profile']->boot();

            if ($this->input->method() !== 'post') {
                redirect('profil');
                return;
            }

            $aksi = (string) ($this->input->post('aksi', TRUE) ?: 'simpan');
            $email = (string) $_SESSION['email'];

            if ($aksi === 'hapus') {
                $services['sessionManager']->flash('profil_success', $services['profile']->delete($email, $user));
                redirect('profil');
                return;
            }

            $services['sessionManager']->flash(
                'profil_success',
                $services['profile']->save($email, $_POST, $_FILES['foto_profil'] ?? null, $user)
            );
        } catch (Throwable $exception) {
            $services['sessionManager']->flash('profil_error', $exception->getMessage());
        }

        redirect('profil');
    }

    public function logout()
    {
        session_destroy();
        redirect('login');
    }

    public function warisan()
    {
        $this->comingSoon('Warisan & Cagar Budaya');
    }

    public function peta()
    {
        $this->comingSoon('Peta Lokasi');
    }

    public function event()
    {
        $this->comingSoon('Event & Jadwal');
    }

    public function belajar()
    {
        $this->comingSoon('Materi Belajar');
    }

    private function comingSoon($title)
    {
        $this->makeServices()['profile']->boot();

        $this->load->view('webandoo/placeholder', array(
            'title' => $title,
        ));
    }

    private function makeServices()
    {
        $connection = $this->db->conn_id;
        $fileManager = new ProfileFileManager(FCPATH);
        $sessionManager = new ProfileSessionManager();
        $repository = new ProfileRepository($connection);

        return array(
            'fileManager' => $fileManager,
            'sessionManager' => $sessionManager,
            'repository' => $repository,
            'auth' => new UserAuthService($connection, $repository, $sessionManager, $fileManager),
            'profile' => new ProfileService($repository, $fileManager, $sessionManager),
        );
    }

    private function isLoggedIn()
    {
        return !empty($_SESSION['email']);
    }
}
