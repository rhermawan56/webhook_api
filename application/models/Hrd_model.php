<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hrd_model extends CI_Model
{
    private $dbs, $dbs_st;
    private $verify = [
        'masuk' => 'jamin',
        'pulang' => 'jamout'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->dbs = $this->load->database('hrd', TRUE);
        $this->dbs_st = $this->load->database('hrd_st', TRUE);
    }

    public function get_employeesbackup($input)
    {
        $table = 'karyawan_data';
        $where = [];
        $wherein = [];
        $wherenotin = [];
        $raw = [
            "tgl_terima <= '" . date('Y-m-d') . "'",
            "statuskaryawan <> 'Keluar'",
            "del = '0'",
            "tgl_keluar = '0000-00-00'"
        ];
        $start = 0;
        $length = 100;
        $exclude = ['wherein', 'wherenotin', 'raw', 'start', 'length'];

        if (isset($input['company'])) {
            if (stripos($input['company'], 'SINAR TERANG') || stripos($input['company'], 'SINARTERANG')) {
                $data = $this->dbs_st;
            } else {
                $data = $this->dbs;
            }
        } else {
            $data = $this->dbs;
        }

        if ($input) {
            $dataKeys = array_keys($input);

            foreach ($dataKeys as $k => $v) {
                if (!in_array($v, $exclude)) {
                    if ($v !== 'company') {
                        $where[$v] = $input[$v];
                    }
                } else {
                    if ($v !== 'start' && $v !== 'length') {
                        foreach ($input[$v] as $kw => $vw) {
                            switch ($v) {
                                case 'wherein':
                                    if ($vw['values']) {
                                        $wherein[] = [
                                            'field' => $vw['field'],
                                            'values' => $vw['values']
                                        ];
                                    }
                                    break;

                                case 'wherenotin':
                                    if ($vw['values']) {
                                        $wherenotin[] = [
                                            'field' => $vw['field'],
                                            'values' => $vw['values']
                                        ];
                                    }
                                    break;

                                case 'raw':
                                    $raw[] = $vw;
                                    break;
                            }
                        }
                    } else {
                        switch ($v) {
                            case 'start':
                                $start = $input['start'];
                                break;
                            case 'length':
                                $length = $input['length'];
                                break;
                        }
                    }
                }
            }

            if ($where) {
                $data = $data->where($where);
            }

            if ($wherein) {
                foreach ($wherein as $k => $v) {
                    $data = $data->where_in($v['field'], $v['values']);
                }
            }

            if ($wherenotin) {
                foreach ($wherenotin as $k => $v) {
                    $data = $data->where_not_in($v['field'], $v['values']);
                }
            }

            if ($raw) {
                foreach ($raw as $k => $v) {
                    $data = $data->where("{$v}", null, false);
                }
            }
        } else {
            if ($raw) {
                foreach ($raw as $k => $v) {
                    $data = $data->where("{$v}", null, false);
                }
            }
        }

        $response['rows'] = clone $data;
        $response['rows'] = $response['rows']->get($table)->num_rows();
        $response['data'] = $data->limit($length, $start)->get($table)->result();

        return $response;
    }

    public function get_employees($input)
    {
        $table = 'karyawan_data';
        $where = [];
        $wherein = [];
        $wherenotin = [];
        $raw = [
            "tgl_terima <= '" . date('Y-m-d') . "'",
            "statuskaryawan <> 'Keluar'",
            "del = '0'",
            "tgl_keluar = '0000-00-00'"
        ];
        $start = 0;
        $length = 100;
        $exclude = ['wherein', 'wherenotin', 'raw', 'start', 'length'];

        if (isset($input['company'])) {
            if (stripos($input['company'], 'SINAR TERANG') || stripos($input['company'], 'SINARTERANG')) {
                $data = $this->dbs_st;
            } else {
                $data = $this->dbs;
            }
        } else {
            $data = $this->dbs;
        }

        if ($input) {
            $dataKeys = array_keys($input);

            foreach ($dataKeys as $k => $v) {
                if (!in_array($v, $exclude)) {
                    if ($v !== 'company') {
                        if ($v == 'kar_id' && stripos($input['company'], 'SINAR TERANG')) {
                            $where[$v] = substr($input[$v], 2);
                        } else {
                            $where[$v] = $input[$v];
                        }
                    }
                } else {
                    if ($v !== 'start' && $v !== 'length') {
                        foreach ($input[$v] as $kw => $vw) {
                            switch ($v) {
                                case 'wherein':
                                    if ($vw['field'] !== 'kar_idxx') {
                                        if ($vw['values']) {
                                            $wherein[] = [
                                                'field' => $vw['field'],
                                                'values' => $vw['values']
                                            ];
                                        }
                                    }
                                    break;

                                case 'wherenotin':
                                    if ($vw['values']) {
                                        $wherenotin[] = [
                                            'field' => $vw['field'],
                                            'values' => $vw['values']
                                        ];
                                    }
                                    break;

                                case 'raw':
                                    $raw[] = $vw;
                                    break;
                            }
                        }
                    } else {
                        switch ($v) {
                            case 'start':
                                $start = $input['start'];
                                break;
                            case 'length':
                                $length = $input['length'];
                                break;
                        }
                    }
                }
            }

            if ($where) {
                $data = $data->where($where);
            }

            if ($wherein) {
                foreach ($wherein as $k => $v) {
                    $data = $data->where_in($v['field'], $v['values']);
                }
            }

            if ($wherenotin) {
                foreach ($wherenotin as $k => $v) {
                    $data = $data->where_not_in($v['field'], $v['values']);
                }
            }

            if ($raw) {
                foreach ($raw as $k => $v) {
                    $data = $data->where("{$v}", null, false);
                }
            }
        } else {
            if ($raw) {
                foreach ($raw as $k => $v) {
                    $data = $data->where("{$v}", null, false);
                }
            }
        }

        $response['rows'] = clone $data;
        $response['rows'] = $response['rows']->get($table)->num_rows();
        $response['data'] = $data->limit($length, $start)->get($table)->result();

        return $response;
    }

    private function getData($input, $table)
    {
        $where = [];
        $wherein = [];
        $wherenotin = [];
        $raw = [];
        $start = 0;
        $length = 100;
        $exclude = ['wherein', 'wherenotin', 'raw', 'start', 'length'];

        
        if (isset($input['company'])) {
            if (stripos($input['company'], 'SINAR TERANG') || stripos($input['company'], 'SINARTERANG')) {
                unset($input['company']);
                $data = $this->dbs_st;
            } else {
                unset($input['company']);
                $data = $this->dbs;
            }
        } else {
            $data = $this->dbs;
        }

        if ($input) {
            $dataKeys = array_keys($input);

            foreach ($dataKeys as $k => $v) {
                if (!in_array($v, $exclude)) {
                    $where[$v] = $input[$v];
                } else {
                    // print_r($v);
                    if ($v !== 'start' && $v !== 'end') {
                        foreach ($input[$v] as $kw => $vw) {
                            switch ($v) {
                                case 'wherein':
                                    if ($vw['values']) {
                                        $wherein[] = [
                                            'field' => $vw['field'],
                                            'values' => $vw['values']
                                        ];
                                    }
                                    break;

                                case 'wherenotin':
                                    if ($vw['values']) {
                                        $wherenotin[] = [
                                            'field' => $vw['field'],
                                            'values' => $vw['values']
                                        ];
                                    }
                                    break;

                                case 'raw':
                                    $raw[] = $vw;
                                    break;
                            }
                        }
                    } else {
                        switch ($v) {
                            case 'start':
                                $start = $input('start');
                                break;
                            case 'length':
                                $length = $input('end');
                                break;
                        }
                    }
                }
            }

            if ($where) {
                $data = $data->where($where);
            }

            if ($wherein) {
                foreach ($wherein as $k => $v) {
                    $data = $data->where_in($v['field'], $v['values']);
                }
            }

            if ($wherenotin) {
                foreach ($wherenotin as $k => $v) {
                    $data = $data->where_not_in($v['field'], $v['values']);
                }
            }

            if ($raw) {
                foreach ($raw as $k => $v) {
                    $data = $data->where("{$v}", null, false);
                }
            }
        } else {
            if ($raw) {
                foreach ($raw as $k => $v) {
                    $data = $data->where("{$v}", null, false);
                }
            }
        }

        $data = $data->limit($length, $start)->get($table)->result();

        return $data;
    }

    public function attendance_insert($data)
    {
        if (stripos($data['company'], 'SINAR TERANG') || stripos($data['company'], 'SINARTERANG')) {
            $data['karyawan_id'] = substr($data['karyawan_id'],2);
        }

        $check = $this->getData(
            [
                'tanggal' => $data['tgl_absen'],
                'kar_id' => $data['karyawan_id'],
                'company' => $data['company']
            ],
            'absensi_d'
        );

        if ($check) {
            $doing = $this->updateDataAttendance($check[0], $data);
        } else {
            $doing = $this->insertDataAttendance($data);
        }
        return $doing;
    }

    private function insertDataAttendance($data)
    {
        if (stripos(strtolower($data['company']), 'sinar terang') || stripos(strtolower($data['company']), 'sinarterang')) {
            $data['karyawan_id'] = "80{$data['karyawan_id']}";
        }

        $employees = $this->get_employees(['kar_id' => $data['karyawan_id'], 'company' => $data['company']]);

        if ($employees['data']) {
            $employees = $employees['data'][0];
            $shift = $this->getData(
                [
                    'company' => $data['company'],
                    'id_group' => $employees->group,
                    'raw' => [
                        "drtgl <= '{$data['tgl_absen']}' AND ketgl >= '{$data['tgl_absen']}'"
                    ]
                ]
            , 'karyawan_shift');
            // return $shift;
    
            if ($shift) {
                $shift = $shift[0];

                $dataInsert = [
                    'userid' => $employees->kar_id,
                    'kar_id' => $employees->kar_id,
                    'nik' => $employees->nik,
                    'nama' => $employees->nama,
                    'divisi' => $employees->divisi,
                    'subdivisi' => $employees->subdivisi,
                    'tanggal' => $data['tgl_absen'],
                    'ip' => $data['cloud_id'],
                    'statusabsen' => 'Hadir',
                    'validasi' => '0',
                    'validasi_a' => '0',
                    'del' => '0',
                    'group' => $employees->group,
                    'shift' => $shift->id_shift
                ];

                $dataInsert[$this->verify[$data['status']]] = $data['jam'];

                if (stripos(strtolower($data['company']), 'jembes') || stripos(strtolower($data['company']), 'padamulya') || stripos(strtolower($data['company']), 'bandung')) {
                    $dataInsert['lokasi'] = '2';
                } else {
                    $dataInsert['lokasi'] = '1';
                }

                if (stripos($data['company'], 'SINAR TERANG') || stripos($data['company'], 'SINARTERANG')) {
                    unset($dataInsert['lokasi']);
                    return $this->dbs_st->insert('absensi_d', $dataInsert);
                } else {
                    return $this->dbs->insert('absensi_d', $dataInsert);
                }
            }
        }

        return false;
    }

    private function updateDataAttendance($attendance, $data)
    {
        $updateData = [
            'statusabsen' => 'Hadir',
            "{$this->verify[$data['status']]}" => $data['jam'],
            'validasi' => '0',
            'validasi_a' => '0',
        ];

        if (stripos($data['company'], 'sinar terang') || stripos($data['company'], 'sinarterang')) {
            return $this->dbs_st->where('idabsensi', $attendance->idabsensi)->update('absensi_d', $updateData);
        } else {
            return $this->dbs->where('idabsensi', $attendance->idabsensi)->update('absensi_d', $updateData);
        }
    }

    public function getEmployeeShift($data) {
        $table = 'karyawan_data';
        $tableJoin = 'karyawan_shift';
        $currentDate = date('Y-m-d');
        $where = [];
        $wherein = [];
        $wherenotin = [];
        $raw = [
            "tgl_terima <= '" . date('Y-m-d') . "'",
            "statuskaryawan <> 'Keluar'",
            "del = '0'",
            "tgl_keluar = '0000-00-00'",
        ];
        $exclude = ['wherein', 'wherenotin', 'raw'];

        $dataKeys = array_keys($data);

        foreach ($dataKeys as $k => $v) {
            if (!in_array($v, $exclude)) {
                if ($v !== 'company') {
                    $where["kar.{$v}"] = $data[$v];
                }
            } else {
                foreach ($data[$v] as $kw => $vw) {
                    switch ($v) {
                        case 'wherein':
                            if ($vw['values']) {
                                $wherein[] = $vw;
                            }
                            break;

                        case 'wherenotin':
                            if ($vw['values']) {
                                $wherenotin[] = $vw;
                            }
                            break;

                        case 'raw':
                            $raw[] = $vw;
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
            }
        }

        if (stripos(strtolower($data['company']), 'sinar terang') || stripos(strtolower($data['company']), 'sinarterang')) {
            $data = $this->dbs_st;
        } else {
            $data = $this->dbs;
        }
        
        $data = $data->join("{$tableJoin} s", 's.id_group = kar.group', 'innerjoin');

        if ($where) {
            $data = $data->where($where);
        }

        if ($wherein) {
            foreach ($wherein as $k => $v) {
                $data = $data->where_in($v['field'], $v['values']);
            }
        }

        if ($wherenotin) {
            foreach ($wherenotin as $k => $v) {
                $data = $data->where_not_in($v['field'], $v['values']);
            }
        }

        if ($raw) {
            foreach ($raw as $k => $v) {
                $data = $data->where("{$v}", null, false);
            }
        }

        return $data->select('kar.*, s.drtgl, s.ketgl, s.id_shift')->get("{$table} kar")->result();
    }
}