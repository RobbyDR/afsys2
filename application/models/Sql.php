<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sql extends CI_Model
{
    public function select_table($table, $where = null, $order_by = null)
    {
        if (isset($where)) {
            $this->db->where($where);
        }
        if (isset($order_by)) {
            $this->db->order_by($order_by);
        }
        $query = $this->db->get($table);
        return $query;
    }

    public function manual_query($query)
    {
        return $this->db->query($query);
    }

    function insert_table($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    function update_table($table, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        return 'OK';
    }

    function select_table_join($table, $column, $join_table, $join_on, $join, $where = array(1 => 1), $order_by = null)
    {
        if ($order_by != null) {
            $this->db->order_by($order_by);
        }
        $this->db->select($column);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->join($join_table, $join_on, $join);
        return $this->db->get();
    }

    public function delete_table($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
        return 'OK';
    }



    public function sanitasi($val)
    {
        $db = get_instance()->db->conn_id;

        //hilangkan enter
        $val = str_replace(array("\r", "\n", "\r\n"), ' ', $val);

        $val = mysqli_real_escape_string($db, $val);
        return $val;
    }

    public function sanitasi_numerik($val1, $val2)
    {
        if (is_numeric($val1)) {
        } else {
            redirect("$val2");
        }
    }
}
