<?php
public function getDataBuku() {
    $DB = $this->load->database('default',TRUE);
    $sql = "SELECT * FROM buku";
    $query = $DB->query($sql);
    return $query->result();
}

  public function getDataBukuById($id) {
    $DB = $this->load->database('default', TRUE);
    $sql = "SELECT * FROM buku WHERE id = '$id'";
    $query = $DB->query($sql);
    return $query->row();
  }

  public function insertDataBuku($data) {
    $DB = $this->load->database('default', TRUE);
    $sql = "INSERT INTO buku (judul, penulis, tahun) VALUES ('".$data['judul']."', '".$data['penulis']."', '".$data['tahun']."')";
    $DB->query($sql);
    return $DB->insert_id();
  }

  public function updateDataBuku($id, $data) {
    $DB = $this->load->database('default', TRUE);
    $sql = "UPDATE buku SET judul = '".$data['judul']."', penulis = '".$data['penulis']."', tahun = '".$data['tahun']."' WHERE id = '$id'";
    $DB->query($sql);
    return $DB->affected_rows();
  }

  public function deleteDataBuku($id) {
    $DB = $this->load->database('default', TRUE);
    $sql = "DELETE FROM buku WHERE id = '$id'";
    $DB->query($sql);
    return $DB->affected_rows();
  }



-- CI3


  public function getDataBuku() {
    $this->db->select('*');
    $this->db->from('buku');
    $query = $this->db->get();
    return $query->result();
  }

  public function getDataBukuById($id) {
    $this->db->select('*');
    $this->db->from('buku');
    $this->db->where('id', $id);
    $query = $this->db->get();
    return $query->row();
  }

  public function insertDataBuku($data) {
    $this->db->insert('buku', $data);
    return $this->db->insert_id();
  }

  public function updateDataBuku($id, $data) {
    $this->db->where('id', $id);
    $this->db->update('buku', $data);
    return $this->db->affected_rows();
  }

  public function deleteDataBuku($id) {
    $this->db->where('id', $id);
    $this->db->delete('buku');
    return $this->db->affected_rows();
  }
