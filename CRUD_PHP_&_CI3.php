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

public function cekboqalokasiproyek($proyek_id, $boq_id) {
    $DB = $this->load->database('old_db', TRUE);
    $sql = "
        SELECT COUNT(*) as total 
        FROM eng_proyek_boq_rekap 
        WHERE proyek_id = ? 
        AND boq_id = ?
    ";
    
    $query = $DB->query($sql, [$proyek_id, $boq_id]);
    $result = $query->row();
    
    return $result->total > 0;
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

  public function cekboqalokasiproyek($proyek_id,$boq_id){
    $DB2 = $this->load->database('old_db', TRUE);
    return $DB2->where('proyek_id', $proyek_id)->where('boq_id', $boq_id)->get('eng_proyek_boq_rekap')->num_rows() > 0;
  }
