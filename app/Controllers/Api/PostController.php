<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class PostController extends ResourceController
{
    protected $modelName = 'App\Models\PostModel';
    protected $format    = 'json';

    public function index()
    {
        $this->setCorsHeaders();
        return $this->respond($this->model->findAll());
    }

    public function create()
    {
        $this->setCorsHeaders();
        $data = $this->request->getJSON(true);
        $this->model->insert($data);

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'data berhasil masuk!',
            'data'    => $data
        ]);
    }

    // FITUR DELETE
    public function delete($id = null)
    {
        $this->setCorsHeaders();
        if ($this->model->find($id)) {
            $this->model->delete($id);
            return $this->respondDeleted(['message' => 'data berhasil dihapus']);
        }
        return $this->failNotFound('data tidak ditemukan');
    }

    // FITUR OPTIONS (UNTUK CORS)
    public function options($id = null)
    {
        $this->setCorsHeaders();
        return $this->response->setStatusCode(200);
    }

    private function setCorsHeaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    }
    // FITUR UPDATE (EDIT)
public function update($id = null)
{
    $this->setCorsHeaders();
    $data = $this->request->getJSON(true);

    if ($this->model->find($id)) {
        $this->model->update($id, $data);
        return $this->respond([
            'status'  => 200,
            'message' => 'data berhasil diperbarui!',
            'data'    => $data
        ]);
    }

    return $this->failNotFound('data tidak ditemukan');
}
}