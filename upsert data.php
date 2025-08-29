<?php
//model:
public function update_atau_insert_kronologis($datakronologis, $proyek_id, $ids_terpakai)
	{
		
		// Update & Insert
		foreach ($datakronologis as $row) {
			if (!empty($row['kronologis_id'])) {
				// UPDATE by ID
				$id = $row['kronologis_id'];
				unset($row['kronologis_id']);
				$this->db->where('kronologis_id', $id);
				$this->db->update('win_proyek_kronologis', $row);

				// tambahkan ke ids_terpakai supaya aman dari delete
				$ids_terpakai[] = $id;
			} else {
				// INSERT baru
				unset($row['kronologis_id']);
				$this->db->insert('win_proyek_kronologis', $row);

				// ambil ID hasil insert & tambahkan
				$new_id = $this->db->insert_id();
				$ids_terpakai[] = $new_id;
			}
		}

		// DELETE yg tidak ada di POST (berarti sudah dihapus user)
		if (!empty($ids_terpakai)) {
			$this->db->where('proyek_id', $proyek_id);
			$this->db->where_not_in('kronologis_id', $ids_terpakai);
			$this->db->delete('win_proyek_kronologis');
		} else {
			// kalau semua dihapus
			$this->db->where('proyek_id', $proyek_id);
			$this->db->delete('win_proyek_kronologis');
		}

		return true;
	}

//controller:
 public function update()
  {
    $id = $this->input->post('proyek_id');
    $dataproyek = array(
      'lokasi_id' => $this->input->post('lokasi_id'),
      'kategori_id' => $this->input->post('kategori_id'),
      'cust_id' => $this->input->post('cust_id'),
      'project_manager_id' => $this->input->post('project_manager_id'),
      'deskripsi' => $this->input->post('deskripsi')
    );
    $updateproyek = $this->win->update_proyek($dataproyek, $id);

    $datastatusproyek = [];
    foreach ($this->input->post('status_id') as $key => $status_id) {
      $tanggal = $this->input->post('tanggal')[$key];
      $target = $this->input->post('target')[$key];

      if ($tanggal === '0000-00-00' || $tanggal === '1970-01-01' || $tanggal === '') {
        $tanggal = NULL;
      }
      if ($target === '0000-00-00' || $target === '1970-01-01' || $target === '') {
        $target = NULL;
      }

      $datastatusproyek[] = array(
        'proyek_id' => $id,
        'status_id' => $status_id,
        'tanggal' => $tanggal,
        'target' => $target
      );
    }

    $updateatauinsertstatusproyek = $this->win->update_atau_insert_status_proyek($datastatusproyek, $id);

    $datakronologis = [];
    $ids_terpakai = []; //

    if ($this->input->post('tanggal_kronologis')) {
      foreach ($this->input->post('tanggal_kronologis') as $key => $tanggal_kronologis) {
        $update = $this->input->post('update')[$key];
        $kendala = $this->input->post('kendala')[$key];
        $pic = $this->input->post('pic')[$key];
        $id_row  = $this->input->post('kronologis_id')[$key];  // 

        $pic_id = $this->win->get_idpic($pic);

        $datakronologis[] = array(
          'kronologis_id' => $id_row,//
          'proyek_id' => $id,
          'tanggal' => $tanggal_kronologis,
          'update' => $update,
          'kendala' => $kendala,
          'pic' => $pic,
          'pic_id' => $pic_id
        );
      }

      // Update or insert kronologis data
      $updateatauinsertkronologis = $this->win->update_atau_insert_kronologis($datakronologis, $id, $ids_terpakai);
    }

    // if($updateproyek && $updateatauinsertstatusproyek){
    if (($updateproyek && $updateatauinsertstatusproyek) || $updateatauinsertstatusproyek || isset($updateatauinsertkronologis)) {
      echo '<script>alert("Proyek berhasil diupdate!");</script>';
      echo '<script>window.location.href = "' . base_url('win/winproject/edit?user_token=') . $this->session->userdata('user_token') . '&id=' . $id . '";</script>';
    } else {
      echo '<script>alert("Proyek gagal diupdate!");</script>';
      echo '<script>window.location.href = "' . base_url('win/winproject/edit?user_token=') . $this->session->userdata('user_token') . '&id=' . $id . '";</script>';
    }


    //  // Debug 
    //  echo '<pre>';
    //  print_r($dataproyek);
    //  print_r($datastatusproyek);
    //  print_r($id); 
    //  echo '</pre>';
    //  die(); 
  }

//view:
 <div class="table-responsive">
                      <table class="table table-sm table-bordered nowrap m-0" style="max-width:100%">
                        <thead class="bg-success">
                          <tr>
                            <th scope="col" colspan="5">
                              <button class="btn btn-sm btn-block btn-success" type="button" data-toggle="collapse" data-target="#collapseKronologis" aria-expanded="false" aria-controls="collapseExample">
                                <b>Kronologis</b>
                              </button>
                            </th>
                          </tr>
                          <tr>
                            <th style="width:120px;">Tanggal</th>
                            <th>Update</th>
                            <th>Kendala</th>
                            <th style="width:270px;">PIC</th>
                            <th><button id="tambahrowkronologis" class="btn btn-sm btn-success" type="button"><i class="fa fa-plus"></i></button></th>
                          </tr>
                        </thead>
                        <tbody class="collapse show" id="collapseKronologis">
                          <?php if (!empty($kronologis)): ?>
                          <?php foreach ($kronologis as $kronologis_item): ?>
                            <tr id="tr_kronologis">
                              <th class="p-0">
                                <input type="hidden" name="kronologis_id[]" value="<?=$kronologis_item->kronologis_id?>">
                                <input type="date" name="tanggal_kronologis[]" value="<?=$kronologis_item->tanggal?>" class="form-control form-control-sm tanggal-input" autocomplete="off" required>
                              </th>
                              <td class="p-0"><input type="text" name="update[]" value="<?=$kronologis_item->update?>" class="form-control form-control-sm" autocomplete="off"></td>
                              <td class="p-0"><input type="text" name="kendala[]" value="<?=$kronologis_item->kendala?>" class="form-control form-control-sm" autocomplete="off"></td>
                              <td class="p-0">
                                <select name="pic[]" class="form-control form-control-sm pic" autocomplete="off" required multiple="multiple">
                                  <option value=""></option>
                                  <?php foreach($list_orangkantor as $orang):?>
                                  <option value="<?=$orang['nama']?>" <?=$kronologis_item->pic == $orang['nama'] ? 'selected' : '';?>><?=$orang['nama']?></option>
                                  <?php endforeach;?>
                                  <?php
                                  $pic_found = false;
                                  foreach ($list_orangkantor as $orang) {
                                      if ($kronologis_item->pic == $orang['nama']) {
                                          $pic_found = true;
                                          break;
                                      }
                                  }
                                  if (!$pic_found && !empty($kronologis_item->pic)): ?>
                                      <option value="<?=$kronologis_item->pic?>" selected><?=$kronologis_item->pic?></option>
                                  <?php endif; ?>
                                </select>
                              </td>
                              <td class="p-0" align="center">
                                <button class="btn btn-sm btn-danger hapusrow" type="button"><i class="fa fa-minus"></i></button>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <?php endif; ?>
                        </tbody>
                      </table>
                  </div>

ajax clone:
 $(document).ready(function() {
        $('#tambahrowkronologis').on('click', function() {
            // Baris baru yang ingin ditambahkan
            var newRow = `
                <tr>
                    <th class="p-0">
                        <input type="hidden" name="kronologis_id[]" value="">
                        <input type="date" name="tanggal_kronologis[]" class="form-control form-control-sm tanggal-input" autocomplete="off" required>
                    </th>
                    <td class="p-0">
                        <input type="text" name="update[]" class="form-control form-control-sm" autocomplete="off">
                    </td>
                    <td class="p-0">
                        <input type="text" name="kendala[]" class="form-control form-control-sm" autocomplete="off">
                    </td>
                    <td class="p-0">
                        <select name="pic[]" class="form-control form-control-sm pic" autocomplete="off" required multiple="multiple">
                            <option value=""></option>
                            <?php foreach($list_orangkantor as $orang): ?>
                            <option value="<?=$orang['nama']?>"><?=$orang['nama']?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="p-0" align="center">
                        <button class="btn btn-sm btn-danger removerowkronologis" type="button"><i class="fa fa-minus"></i></button>
                    </td>
                </tr>
            `;

            // Menambahkan baris baru ke dalam tabel
            $('#collapseKronologis').append(newRow);

            // Inisialisasi ulang Select2 pada elemen yang baru ditambahkan
            $('.pic').select2({
                placeholder: 'Pilih PIC dari MSA/WIN/Pihak Eksternal',
                allowClear: true,
                width: 'resolve',
                maximumSelectionLength: 1,
                tags: true, // Mengizinkan input kustom
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === 'Pihak eksternal') {
                        return {
                            id: 'Pihak eksternal',
                            text: 'Pihak eksternal',
                            isNew: true
                        };
                    }
                    return {
                        id: params.term,
                        text: params.term
                    };
                }
            });
            $('.pic').on('select2:select', function (e) {
                if ($(this).val().length >= 1) {
                    $(this).next('.select2-container').find('.select2-search__field').prop('disabled', true);
                }
            });

            $('.pic').on('select2:unselect', function (e) {
                if ($(this).val().length === 0) {
                    $(this).next('.select2-container').find('.select2-search__field').prop('disabled', false);
                }
            });
        });

        // Menghapus baris jika tombol minus diklik
        $(document).on('click', '.removerowkronologis', function() {
            $(this).closest('tr').remove();
        });
    });
