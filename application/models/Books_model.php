<?php
class Books_model extends CI_Model
{

  public function get_books()
  {
    $query = $this->db->get('books');
    return $query->result_array();
  }

  public function get_book($id)
  {
    $query = $this->db->get_where('books', ['id' => $id]);
    return $query->row_array();
  }

  public function insert_book($data)
  {
    $this->db->insert('books', $data);
    return $this->db->insert_id();
  }

  public function update_book($id, $data)
  {
    $this->db->where('id', $id);
    return $this->db->update('books', $data);
  }

  public function delete_book($id)
  {
    $this->db->where('id', $id);
    return $this->db->delete('books');
  }
}
