<?php
namespace App\Controllers;

use App\Models\Photo;

class PhotoController {
    private $photoModel;

    public function __construct($db) {
        $this->photoModel = new Photo($db);
    }

    // GET /photos
    public function index() {
        $photos = $this->photoModel->all();
        require VIEWS . '/photos/index.php';
    }

    // GET /photos/create
    public function create() {
        require 'views/photos/create.php';
    }

    // POST /photos
    public function store() {
        $this->photoModel->create($_POST);
        header('Location: /photos');
    }

    // GET /photos/{id}
    public function show($id) {
        $photo = $this->photoModel->find($id);
        require 'views/photos/show.php';
    }

    // GET /photos/{id}/edit
    public function edit($id) {
        $photo = $this->photoModel->find($id);
        require 'views/photos/edit.php';
    }

    // PUT/PATCH /photos/{id}
    public function update($id) {
        $this->photoModel->update($id, $_POST);
        header('Location: /photos/' . $id);
    }

    // DELETE /photos/{id}
    public function destroy($id) {
        $this->photoModel->delete($id);
        header('Location: /photos');
    }
}