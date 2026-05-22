<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crudjs extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Crudjs_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('form_validation', 'upload', 'session'));
    }

    /**
     * Debug page to check setup
     */
    public function debug()
    {
        $debug_info = array();
        
        // Check database connection
        $debug_info['database'] = array(
            'connected' => $this->db->conn_id ? 'Yes' : 'No',
            'database' => $this->db->database
        );
        
        // Check table exists
        $debug_info['table_exists'] = $this->db->table_exists('posts') ? 'Yes' : 'No';
        
        // Try to get records
        $query = $this->db->get('posts', 1);
        $debug_info['table_accessible'] = $query ? 'Yes' : 'No';
        $debug_info['record_count'] = $this->db->count_all('posts');
        
        // Check upload folder
        $upload_path = './uploads/posts/';
        $debug_info['upload_folder'] = array(
            'exists' => is_dir($upload_path) ? 'Yes' : 'No',
            'writable' => is_writable($upload_path) ? 'Yes' : 'No'
        );
        
        // Get records for testing
        $debug_info['sample_records'] = array();
        try {
            $records = $this->Crudjs_model->get_all();
            if (!empty($records)) {
                // Show first record as sample
                $first = $records[0];
                $debug_info['sample_records'] = array(
                    'count' => count($records),
                    'first_record' => $first
                );
            }
        } catch (Exception $e) {
            $debug_info['sample_records']['error'] = $e->getMessage();
        }
        
        echo '<pre>' . json_encode($debug_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</pre>';
    }

    /**
     * Display main index page
     */
    public function index()
    {
        $data['title'] = 'CRUD with AJAX';
        $this->load->view('crudjs/index', $data);
    }

    /**
     * Get all records via AJAX
     */
    public function get_all()
    {
        // Clear any previous output
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $records = $this->Crudjs_model->get_all();
            
            // Format response
            $response = array(
                'status' => 'success',
                'data' => $records
            );
            
            echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Failed to load records: ' . $e->getMessage()
            ));
        }
        exit;
    }

    /**
     * Get single record via AJAX
     */
    public function get_record($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (!$this->Crudjs_model->exists($id)) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Record not found'
                ));
                exit;
            }

            $record = $this->Crudjs_model->get_by_id($id);

            echo json_encode(array(
                'status' => 'success',
                'data' => $record
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Failed to load record: ' . $e->getMessage()
            ));
        }
        exit;
    }

    /**
     * Store new record via AJAX
     */
    public function store()
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Set validation rules
            $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
            $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
            $this->form_validation->set_rules('article', 'Article', 'required');

            if ($this->form_validation->run() === FALSE) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Validation error: ' . strip_tags(validation_errors())
                ));
                exit;
            }

            $data = array(
                'title' => $this->input->post('title'),
                'author' => $this->input->post('author'),
                'article' => $this->input->post('article'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $upload_result = $this->_upload_image();
                
                if ($upload_result['status']) {
                    $data['image'] = 'posts/' . $upload_result['file_name'];
                } else {
                    echo json_encode(array(
                        'status' => 'error',
                        'message' => $upload_result['error']
                    ));
                    exit;
                }
            }

            $record_id = $this->Crudjs_model->insert($data);

            if ($record_id) {
                $new_record = $this->Crudjs_model->get_by_id($record_id);
                echo json_encode(array(
                    'status' => 'success',
                    'message' => 'Record created successfully!',
                    'data' => $new_record
                ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Failed to create record'
                ));
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Exception error: ' . $e->getMessage()
            ));
        }
        exit;
    }

    /**
     * Update record via AJAX
     */
    public function update($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (!$this->Crudjs_model->exists($id)) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Record not found'
                ));
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                $this->_parse_put_multipart();
            }

            $this->form_validation->set_rules('title', 'Title', 'max_length[255]');
            $this->form_validation->set_rules('author', 'Author', 'max_length[255]');

            if ($this->form_validation->run() === FALSE) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Validation error: ' . strip_tags(validation_errors())
                ));
                exit;
            }

            $data = array('updated_at' => date('Y-m-d H:i:s'));

            if ($this->input->post('title') !== NULL) {
                $data['title'] = $this->input->post('title');
            }
            if ($this->input->post('author') !== NULL) {
                $data['author'] = $this->input->post('author');
            }
            if ($this->input->post('article') !== NULL) {
                $data['article'] = $this->input->post('article');
            }

            if ($this->_has_uploaded_image()) {
                $old_record = $this->Crudjs_model->get_by_id($id);
                $upload_result = $this->_upload_image();

                if (!$upload_result['status']) {
                    echo json_encode(array(
                        'status' => 'error',
                        'message' => 'Upload failed: ' . $upload_result['error']
                    ));
                    exit;
                }

                $data['image'] = 'posts/' . $upload_result['file_name'];

                if (!empty($old_record->image)) {
                    $old_image_path = './uploads/' . $old_record->image;
                    if (is_file($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            }

            $result = $this->Crudjs_model->update($id, $data);

            if ($result !== FALSE) {
                $updated_record = $this->Crudjs_model->get_by_id($id);
                echo json_encode(array(
                    'status' => 'success',
                    'message' => 'Record updated successfully!',
                    'data' => $updated_record
                ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Failed to update record'
                ));
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Exception error: ' . $e->getMessage()
            ));
        }
        exit;
    }

    /**
     * Parse multipart/form-data sent with PUT because PHP only fills $_POST and
     * $_FILES automatically for POST requests.
     */
    private function _parse_put_multipart()
    {
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

        if (!preg_match('/boundary=(?:"([^"]+)"|([^;]+))/i', $content_type, $matches)) {
            return;
        }

        $boundary = isset($matches[1]) && $matches[1] !== '' ? $matches[1] : trim($matches[2]);
        $raw_input = file_get_contents('php://input');

        if ($raw_input === '' || $boundary === '') {
            return;
        }

        $parts = array_slice(explode('--' . $boundary, $raw_input), 1);

        foreach ($parts as $part) {
            $part = ltrim($part, "\r\n");
            if ($part === '' || trim($part) === '--') {
                continue;
            }

            $part = preg_replace("/\r\n--$/", '', $part);
            if (strpos($part, "\r\n\r\n") === FALSE) {
                continue;
            }

            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);
            if (substr($body, -2) === "\r\n") {
                $body = substr($body, 0, -2);
            }
            $headers = array();

            foreach (explode("\r\n", $raw_headers) as $header) {
                if (strpos($header, ':') !== FALSE) {
                    list($key, $value) = explode(':', $header, 2);
                    $headers[strtolower(trim($key))] = trim($value);
                }
            }

            if (empty($headers['content-disposition'])) {
                continue;
            }

            if (!preg_match('/name="([^"]+)"/', $headers['content-disposition'], $name_match)) {
                continue;
            }

            $field_name = $name_match[1];

            if (preg_match('/filename="([^"]*)"/', $headers['content-disposition'], $file_match)) {
                if ($file_match[1] === '') {
                    continue;
                }

                $tmp_file = tempnam(sys_get_temp_dir(), 'put_upload_');
                file_put_contents($tmp_file, $body);

                $_FILES[$field_name] = array(
                    'name' => $file_match[1],
                    'type' => isset($headers['content-type']) ? $headers['content-type'] : 'application/octet-stream',
                    'tmp_name' => $tmp_file,
                    'error' => UPLOAD_ERR_OK,
                    'size' => filesize($tmp_file),
                    'is_put_upload' => TRUE
                );
            } else {
                $_POST[$field_name] = $body;
            }
        }
    }

    private function _has_uploaded_image()
    {
        return isset($_FILES['image'])
            && !empty($_FILES['image']['name'])
            && isset($_FILES['image']['error'])
            && $_FILES['image']['error'] === UPLOAD_ERR_OK
            && isset($_FILES['image']['size'])
            && $_FILES['image']['size'] > 0;
    }

    /**
     * Private method to handle image upload
     */
    private function _upload_image()
    {
        $upload_path = './uploads/posts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        if (!empty($_FILES['image']['is_put_upload'])) {
            return $this->_save_put_uploaded_image($upload_path);
        }

        $original_name = $_FILES['image']['name'];
        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $original_name);
        $file_name = time() . '_' . $sanitized_name;

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size'] = 2048;
        $config['file_name'] = $file_name;
        $config['overwrite'] = FALSE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            return array('status' => TRUE, 'file_name' => $upload_data['file_name']);
        }

        return array('status' => FALSE, 'error' => $this->upload->display_errors('', ''));
    }

    private function _save_put_uploaded_image($upload_path)
    {
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'webp');
        $max_size = 2048 * 1024;
        $tmp_name = $_FILES['image']['tmp_name'];
        $original_name = $_FILES['image']['name'];
        $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed_extensions)) {
            return array('status' => FALSE, 'error' => 'The filetype you are attempting to upload is not allowed.');
        }

        if ($_FILES['image']['size'] > $max_size) {
            return array('status' => FALSE, 'error' => 'The file you are attempting to upload is larger than the permitted size.');
        }

        if (!is_file($tmp_name)) {
            return array('status' => FALSE, 'error' => 'Uploaded file was not found.');
        }

        $image_info = @getimagesize($tmp_name);
        if ($image_info === FALSE) {
            return array('status' => FALSE, 'error' => 'The file you selected is not a valid image.');
        }

        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $original_name);
        $file_name = time() . '_' . uniqid() . '_' . $sanitized_name;
        $destination = $upload_path . $file_name;

        if (!rename($tmp_name, $destination)) {
            return array('status' => FALSE, 'error' => 'Failed to save uploaded file.');
        }

        return array('status' => TRUE, 'file_name' => $file_name);
    }

    /**
     * Delete record via AJAX
     */
    public function delete($id)
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $record = $this->Crudjs_model->get_by_id($id);

            if (!$record) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Record not found'
                ));
                exit;
            }

            // Delete image if exists
            if ($record->image) {
                $image_path = './uploads/' . $record->image;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $result = $this->Crudjs_model->delete($id);

            if ($result) {
                echo json_encode(array(
                    'status' => 'success',
                    'message' => 'Record deleted successfully!'
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Failed to delete record'
                ));
            }
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Exception error: ' . $e->getMessage()
            ));
        }
        exit;
    }

    /**
     * Test simple endpoint
     */
    public function test()
    {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array('status' => 'success', 'message' => 'API is working'));
        exit;
    }

}
