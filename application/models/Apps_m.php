<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Apps_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function login($tabel, $data)
    {
        $kondisi = "(nik =  '" . $data['username'] . "' OR username = '" . $data['username'] . "') AND password = '" . $data['password'] . "'";
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($kondisi);
        $query = $this->db->get();
        return $query->result_array();
    }

    function insert_data($tabel, $data)
    {
        return $this->db->insert($tabel, $data);
    }

    function update_data($tabel, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($tabel, $data);
        return TRUE;
    }

    function delete_data($tabel, $where = "")
    {
        if ($where != "") {
            $this->db->where($where);
        }
        $this->db->delete($tabel);
        if ($this->db->affected_rows() == 1) {
            return TRUE;
        }
        return FALSE;
    }

    function get_data($tabel, $select = "", $where = "", $order = "", $join = "", $group_by = "", $limit = "")
    {
        $selectdb = "*";
        if ($select != "") {
            $selectdb = $select;
        }
        $this->db->select($selectdb);
        $this->db->from($tabel);
        if ($join != "") {
            $i = 0;
            while ($i < count($join['table'])) {
                $this->db->join($join['table'][$i], $join['kondisi'][$i], $join['posisi'][$i]);
                $i++;
            }
        }
        if ($where != "") {
            $this->db->where($where);
        }
        if ($order != "") {
            $this->db->order_by($order);
        }
        if ($group_by != "") {
            $this->db->group_by($group_by);
        }
        if ($limit != "") {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        return $query;
    }

    // Datatables Server Side
    private function _get_datatables_query($tabel, $param, $select = "", $where = "", $join = "")
    {
        $selectdb = "*";
        if ($select != "") {
            $selectdb = $select;
        }
        $this->db->select($selectdb);
        $this->db->from($tabel);
        if ($join != "") {
            $i = 0;
            while ($i < count($join['table'])) {
                $this->db->join($join['table'][$i], $join['kondisi'][$i], $join['posisi'][$i]);
                $i++;
            }
        }
        if ($where != "") {
            $this->db->where($where);
        }
        $i = 0;
        foreach ($param['column_search'] as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($param['column_search']) - 1 == $i) {
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($param['column_order'][$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($param['order'])) {
            $order = $param['order'];
            $order2 = $param['order2'];
            $i = 0;
            while ($i < count($order)) {
                $this->db->order_by($order[$i], $order2[$i]);
                $i++;
            }
        }
    }

    function get_datatables($tabel, $param, $select = "", $where = "", $join = "")
    {
        $this->_get_datatables_query($tabel, $param, $select, $where, $join);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($tabel, $param, $select = "", $where = "", $join = "")
    {
        $this->_get_datatables_query($tabel, $param, $select, $where, $join);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tabel, $select = "", $where = "", $join = "")
    {
        $selectdb = "*";
        if ($select != "") {
            $selectdb = $select;
        }
        $this->db->select($selectdb);
        $this->db->from($tabel);
        if ($join != "") {
            $i = 0;
            while ($i < count($join['table'])) {
                $this->db->join($join['table'][$i], $join['kondisi'][$i], $join['posisi'][$i]);
                $i++;
            }
        }
        if ($where != "") {
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }
    // End Datatables Server Side
}
