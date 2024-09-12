<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Books extends RestController
{

  function __construct()
  {
    // Construct the parent class
    parent::__construct();
    $this->load->model([
      'Books_model' => 'm_books',
      'Borrowing_model' => 'm_borrowing'
    ]);
  }

  public function index_get($id = NULL)
  {
    var_dump($id);

    if (!empty($id)) {
      $data = $this->m_books->get_book($id);
      if (empty($data)) {
        $this->response([
          'status' => false,
          'message' => 'Data not found'
        ], 404);
      } else {
        $this->response($data, RestController::HTTP_OK);
      }
    } else {
      $data = $this->m_books->get_books();
      $this->response($data, RestController::HTTP_OK);
    }
  }
  public function index_post()
  {
    $data = [
      'title' => $this->post('title'),
      'author' => $this->post('author'),
      'published_date' => $this->post('published_date'),
    ];
    $result = $this->m_books->insert_book($data);
    if ($result) {
      $this->response(['message' => 'Book created successfully'], RestController::HTTP_CREATED);
    } else {
      $this->response(['message' => 'Failed to create book'], RestController::HTTP_BAD_REQUEST);
    }
  }
  public function index_put($id)
  {
    $data = [
      'title' => $this->put('title'),
      'author' => $this->put('author'),
      'published_date' => $this->put('published_date'),
    ];
    $result = $this->m_books->update_book($id, $data);
    if ($result) {
      $this->response(['message' => 'Book updated successfully'], RestController::HTTP_OK);
    } else {
      $this->response(['message' => 'Failed to update book'], RestController::HTTP_BAD_REQUEST);
    }
  }
  public function index_delete($id)
  {
    $result = $this->m_books->delete_book($id);
    if ($result) {
      $this->response(['message' => 'Book deleted successfully'], RestController::HTTP_OK);
    } else {
      $this->response(['message' => 'Failed to delete book'], RestController::HTTP_BAD_REQUEST);
    }
  }
  // Borrow a book
  public function borrow_post()
  {

    $email = $this->post('email');
    $title = $this->post('title');
    $published_date = $this->post('published_date');
    $borrow_date = $this->post('borrow_date');

    $result = $this->m_borrowing->borrow_book($email, $title, $published_date, $borrow_date);
    if ($result) {
      $this->response(['message' => 'Book borrowed successfully'], RestController::HTTP_OK);
    } else {
      $this->response(['message' => 'Failed to borrow book'], RestController::HTTP_BAD_REQUEST);
    }
  }

  // Return a book
  public function return_put()
  {
    $email = $this->put('email');
    $title = $this->put('title');
    $published_date = $this->put('published_date');
    $return_date = $this->put('return_date');
    $result = $this->m_borrowing->return_book($email, $title, $published_date, $return_date);
    if ($result) {
      echo json_encode(['message' => 'Book returned successfully']);
    } else {
      echo json_encode(['message' => 'Failed to return book'], 400);
    }
  }
  public function show_borrowing()
  {
    $data = $this->m_borrowing->get_borrowing();
    $this->response($data, RestController::HTTP_OK);
  }
}