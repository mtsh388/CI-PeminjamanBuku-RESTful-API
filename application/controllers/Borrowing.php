<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Borrowing extends RestController
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
  public function index_get()
  {
    $data = $this->m_borrowing->get_borrowings();
    $this->response($data, RestController::HTTP_OK);
  }
  // Borrow a book
  public function index_post()
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
  public function index_put()
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
}