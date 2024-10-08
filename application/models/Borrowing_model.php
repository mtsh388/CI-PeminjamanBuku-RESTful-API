<?php
class Borrowing_model extends CI_Model
{
  public function get_borrowings()
  {
    $sql = "SELECT b.title, c.name, c.email, a. borrow_date, a.return_date
            FROM borrowings a
            JOIN `books` b
            ON a.`book_id`=b.`id`
            JOIN `borrowers` c
            ON a.`borrower_id`=c.`id`";

    return $this->db->query($sql)->result_array();
  }
  public function borrow_book($email, $title, $published_date, $borrow_date)
  {
    $book = $this->db->get_where('books', ['title' => $title, 'published_date' => $published_date, 'available' => 1])->row_array();
    $borrower = $this->db->get_where('borrowers', ['email' => $email])->row_array();
    if ($book) {
      $data = [
        'borrower_id' => $borrower['id'],
        'book_id' => $book['id'],
        'borrow_date' => $borrow_date
      ];
      $this->db->insert('borrowings', $data);
      $this->db->update('books', ['available' => 0], ['title' => $title, 'published_date' => $published_date]);
      return true;
    }
    return false;
  }

  public function return_book($email, $title, $published_date, $return_date)
  {
    $book = $this->db->get_where('books', ['title' => $title, 'published_date' => $published_date])->row_array();
    $borrower = $this->db->get_where('borrowers', ['email' => $email])->row_array();

    $borrowing = $this->db->order_by('id', 'DESC')->get_where('borrowings', [
      'borrower_id' => $borrower['id'],
      'book_id' => $book['id']
    ])->row_array();
    if ($borrowing) {
      $this->db->update('borrowings', ['return_date' => $return_date], ['id' => $borrowing['id']]);
      $this->db->update('books', ['available' => 1], ['id' => $book['id']]);
      return true;
    }
    return false;
  }
}