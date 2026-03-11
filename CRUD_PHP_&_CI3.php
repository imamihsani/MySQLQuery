<?php
// VERSI QUERY BUILDER PHP MYSQL
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


// VERSI CI3
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

// VERSI LARAVEL 10
public function getDataBuku(){
    return DB::table('buku')->get();
}

public function getDataBukuById($id){
    return DB::table('buku')->where('id', $id)->first();
}

public function insertDataBuku($data){
    return DB::table('buku')->insertGetId([
        'judul' => $data['judul'],
        'penulis' => $data['penulis'],
        'tahun' => $data['tahun']
    ]);
}
//atau bisa juga versi ini insertnya
public static function insertkelas(array $data){
    return DB::table('buku')->insert($data);
}

public function updateDataBuku($id, $data){
    return DB::table('buku')->where('id', $id)->update($data);
}

public function deleteDataBuku($id){
    return DB::table('buku')->where('id', $id)->delete();
}

public function cekBoqAlokasiProyek($proyek_id, $boq_id){
    return DB::table('eng_proyek_boq_rekap')->where(['proyek_id' => $proyek_id,'boq_id' => $boq_id])->exists();
}
