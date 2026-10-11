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
        $requestData = $this->request->getJSON(true) ?? [];
        $data = [
            'title' => $requestData['title'] ?? null,
            'body'  => $requestData['body'] ?? null,
        ];

        $insertId = $this->model->insert($data, true);

        if ($insertId === false || $insertId === null || $insertId === '') {
            return $this->failServerError('data gagal disimpan');
        }

        $post = $this->model->find($insertId);
        if ($post === null) {
            return $this->failServerError('data tersimpan tetapi gagal mengambil kembali record');
        }

        return $this->respondCreated([
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'data berhasil disimpan'
            ],
            'data'     => $post,
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