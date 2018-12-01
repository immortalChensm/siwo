<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/22
 * Time: 14:58
 */
namespace Siwo\Foundation\Database;

use Siwo\Foundation\Traits\Singleton;
use Swoole\Coroutine\MySQL;

class Query
{
    use Singleton;
    public static $instance;
    protected $db = null;
    protected $table;
    protected $fields;
    protected $where;
    protected $order = "";
    protected $group = "";
    protected $sql;
    protected $expressionsConvert = ['eq'=>'=','neq'=>'<>','gt'=>'>','lt'=>'<','elt'=>'<=','egt'=>'>=','like'=>'LIKE','not like'=>'NOT LIKE ','not between'=>'NOT BETWEEN ','between'=>'BETWEEN','in'=>'IN','not in'=>'NOT IN'];

    private function __construct()
    {
        $this->db = new MySQL();
        $this->db->connect([
            'host' => config('db')['host'],
            'port' => config('db')['port'],
            'user' => config('db')['user'],
            'password' => config('db')['password'],
            'database' => config('db')['database'],
        ]);
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function field($filed){
        if (is_array($filed)){

            $this->fields = implode("`,`",$filed);
        }elseif(is_string($filed)){
            $this->fields = $filed;
        }
        return $this;
    }
    public function insert($data)
    {
        if (is_array($data)){
            if (empty($this->table)){
                throw new \InvalidArgumentException("table not Found!");
            }
            $this->sql = "INSERT INTO ".$this->table."(`".implode("`,`",array_keys($data))."`) VALUES ('".implode("','",array_values($data))."')";
            return $this->execute($this->sql);
        }else{
            throw  new \InvalidArgumentException("参数错误");
        }
    }

    public function where($condition,$option='',$param='')
    {
        $argc = func_get_args();
        if (count($argc) == 3 && !empty($condition) && !empty($option) && !empty($param)){
            switch ($option) {
                case 'in':
                    if (is_array($param)) {
                        $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . "('" . implode("','", $param) . "') AND ";
                    } elseif (is_string($param)) {
                        $this->where .= "`{$condition} `" . $this->expressionsConvert[$option] . "('" . $param . "') AND ";
                    }

                    break;
                case 'not in':
                    if (is_array($param)) {
                        $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . "('" . implode("','", $param) . "') AND ";
                    } elseif (is_string($param)) {
                        $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . "('" . $param . "') AND ";
                    }
                    break;
                case 'like':
                    if (is_array($param)) {
                        throw new \InvalidArgumentException("LIKE expression Not support Array!");
                    } elseif (is_string($param)) {
                        $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . " '" . $param . "' AND ";
                    }
                    break;
                case 'not like':
                    if (is_array($param)) {
                        throw new \InvalidArgumentException("Not Like expression Not support Array!");
                    } elseif (is_string($param)) {
                        $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . " '" . $param . "' AND  ";
                    }
                    break;
                case 'between':
                    if (is_array($param)) {
                        $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . " '" . $param[0] . "' AND '" . $param[1] . "' AND ";
                    } elseif (is_string($param)) {
                        throw new \InvalidArgumentException("Between expression should be array!Not string");
                    }
                    break;
                case 'not between':
                    if (is_array($param)) {
                        $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . " '" . $param[0] . "' AND '" . $param[1] . "' AND ";
                    } elseif (is_string($param)) {
                        throw new \InvalidArgumentException("NOT Between expression should be array!Not string");
                    }
                    break;
                default:
                    $this->where .= "`{$condition}` " . $this->expressionsConvert[$option] . "'" . $param . "'" . " AND ";
                    break;
            }
        }
        elseif (is_string($condition)){
            $this->where.= $condition." AND ";

        }elseif(is_array($condition)){
            if (count($condition) == 3 && is_numeric(key($condition))){

                switch ($condition[1]) {
                    case 'in':
                        if (is_array($condition[2])) {
                            $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . "('" . implode("','", $condition[2]) . "') AND ";
                        } elseif (is_string($param)) {
                            $this->where .= "`{$condition[0]} `" . $this->expressionsConvert[$condition[1]] . "('" . $condition[2] . "') AND ";
                        }

                        break;
                    case 'not in':
                        if (is_array($condition[2])) {
                            $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . "('" . implode("','", $condition[2]) . "') AND ";
                        } elseif (is_string($param)) {
                            $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . "('" . $condition[2] . "') AND ";
                        }
                        break;
                    case 'like':
                        if (is_array($condition[2])) {
                            throw new \InvalidArgumentException("LIKE expression Not support Array!");
                        } elseif (is_string($condition[2])) {
                            $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . " '" . $condition[2] . "' AND ";
                        }
                        break;
                    case 'not like':
                        if (is_array($condition[2])) {
                            throw new \InvalidArgumentException("Not Like expression Not support Array!");
                        } elseif (is_string($condition[2])) {
                            $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . " '" . $condition[2] . "' AND  ";
                        }
                        break;
                    case 'between':
                        if (is_array($condition[2])) {
                            $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . " '" . $condition[2][0] . "' AND '" . $condition[2][1] . "' AND ";
                        } elseif (is_string($param)) {
                            throw new \InvalidArgumentException("Between expression should be array!Not string");
                        }
                        break;
                    case 'not between':
                        if (is_array($condition[2])) {
                            $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . " '" . $condition[2][0] . "' AND '" . $condition[2][1] . "' AND ";
                        } elseif (is_string($condition[2])) {
                            throw new \InvalidArgumentException("NOT Between expression should be array!Not string");
                        }
                        break;
                    default:
                        $this->where .= "`{$condition[0]}` " . $this->expressionsConvert[$condition[1]] . "'" . $condition[2] . "'" . " AND ";
                        break;
                }
            }elseif(count($condition) == 2 && is_numeric(key($condition))){

                $this->where .= "`{$condition[0]}` = '" . $condition[1] . "' AND ";
            }else {

                foreach ($condition as $field => $value) {

                    if (false !== strpos($field, "|")) {
                        $columns = explode("|", $field);

                        if (is_array($columns)) {
                            if (count($columns)!=count($value)){
                                throw  new \InvalidArgumentException("多字段查询参数值错误");
                            }

                            foreach($columns as $col=>$col_name){

                                $col_val = $value[$col];

                                if (is_array($col_val)) {

                                    if (isset($this->expressionsConvert[$col_val[0]])) {
                                        switch ($col_val[0]) {
                                            case 'in':
                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "('" . implode("','", $col_val[1]) . "')  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name} `" . $this->expressionsConvert[$col_val[0]] . "('" . $col_val[1] . "')  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                }

                                                break;
                                            case 'not in':
                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "('" . implode("','", $col_val[1]) . "')  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "('" . $col_val[1] . "')  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                }
                                                break;
                                            case 'like':
                                                if (is_array($col_val[1])) {
                                                    throw new \InvalidArgumentException("LIKE expression Not support Array!");
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1] . "'  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                }
                                                break;
                                            case 'not like':
                                                if (is_array($col_val[1])) {
                                                    throw new \InvalidArgumentException("Not Like expression Not support Array!");
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1] . "'  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                }
                                                break;
                                            case 'between':

                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1][0] . "' AND '" . $col_val[1][1] . "'  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                } elseif (is_string($col_val[1])) {
                                                    throw new \InvalidArgumentException("Between expression should be array!Not string");
                                                }
                                                break;
                                            case 'not between':
                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1][0] . "' AND '" . $col_val[1][1] . "'  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                } elseif (is_string($col_val[1])) {
                                                    throw new \InvalidArgumentException("NOT Between expression should be array!Not string");
                                                }
                                                break;
                                            default:

                                                $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "'" . $col_val[1] . "'" . "  ".(count($columns)==$col+1?'AND':'OR')." ";
                                                break;
                                        }

                                    } else {
                                        $this->where .= "`{$col_name}` " . $col_val[0] . "'" . $col_val[1] . "' ".(count($columns)==$col+1?'AND':'OR')." ";
                                    }

                                }
                            }
                        }
                    } elseif (false !== strpos($field, "&")) {
                        $columns = explode("&", $field);
                        if (is_array($columns)) {
                            if (count($columns)!=count($value)){
                                throw  new \InvalidArgumentException("多字段查询参数值错误");
                            }

                            foreach($columns as $col=>$col_name){

                                $col_val = $value[$col];

                                if (is_array($col_val)) {

                                    if (isset($this->expressionsConvert[$col_val[0]])) {
                                        switch ($col_val[0]) {
                                            case 'in':
                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "('" . implode("','", $col_val[1]) . "') AND ";
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name} `" . $this->expressionsConvert[$col_val[0]] . "('" . $col_val[1] . "') AND ";
                                                }

                                                break;
                                            case 'not in':
                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "('" . implode("','", $col_val[1]) . "') AND ";
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "('" . $col_val[1] . "') AND ";
                                                }
                                                break;
                                            case 'like':
                                                if (is_array($col_val[1])) {
                                                    throw new \InvalidArgumentException("LIKE expression Not support Array!");
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1] . "' AND ";
                                                }
                                                break;
                                            case 'not like':
                                                if (is_array($col_val[1])) {
                                                    throw new \InvalidArgumentException("Not Like expression Not support Array!");
                                                } elseif (is_string($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1] . "' AND ";
                                                }
                                                break;
                                            case 'between':
                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1][0] . "' AND '" . $col_val[1][1] . "' AND ";
                                                } elseif (is_string($col_val[1])) {
                                                    throw new \InvalidArgumentException("Between expression should be array!Not string");
                                                }
                                                break;
                                            case 'not between':
                                                if (is_array($col_val[1])) {
                                                    $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . " '" . $col_val[1][0] . "' AND '" . $col_val[1][1] . "' AND ";
                                                } elseif (is_string($col_val[1])) {
                                                    throw new \InvalidArgumentException("NOT Between expression should be array!Not string");
                                                }
                                                break;
                                            default:
                                                $this->where .= "`{$col_name}` " . $this->expressionsConvert[$col_val[0]] . "'" . $col_val[1] . "'" . " AND ";
                                                break;
                                        }

                                    } else {
                                        $this->where .= "`{$col_name}` " . $col_val[0] . "'" . $col_val[1] . "' AND ";
                                    }

                                }
                            }
                        }
                    } else {
                        if (is_array($value)) {

                            if (isset($this->expressionsConvert[$value[0]])) {
                                switch ($value[0]) {
                                    case 'in':
                                        if (is_array($value[1])) {
                                            $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . "('" . implode("','", $value[1]) . "') AND ";
                                        } elseif (is_string($value[1])) {
                                            $this->where .= "`{$field} `" . $this->expressionsConvert[$value[0]] . "('" . $value[1] . "') AND ";
                                        }

                                        break;
                                    case 'not in':
                                        if (is_array($value[1])) {
                                            $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . "('" . implode("','", $value[1]) . "') AND ";
                                        } elseif (is_string($value[1])) {
                                            $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . "('" . $value[1] . "') AND ";
                                        }
                                        break;
                                    case 'like':
                                        if (is_array($value[1])) {
                                            throw new \InvalidArgumentException("LIKE expression Not support Array!");
                                        } elseif (is_string($value[1])) {
                                            $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . " '" . $value[1] . "' AND ";
                                        }
                                        break;
                                    case 'not like':
                                        if (is_array($value[1])) {
                                            throw new \InvalidArgumentException("Not Like expression Not support Array!");
                                        } elseif (is_string($value[1])) {
                                            $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . " '" . $value[1] . "' AND ";
                                        }
                                        break;
                                    case 'between':
                                        if (is_array($value[1])) {
                                            $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . " '" . $value[1][0] . "' AND '" . $value[1][1] . "' AND ";
                                        } elseif (is_string($value[1])) {
                                            throw new \InvalidArgumentException("Between expression should be array!Not string");
                                        }
                                        break;
                                    case 'not between':
                                        if (is_array($value[1])) {
                                            $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . " '" . $value[1][0] . "' AND '" . $value[1][1] . "' AND ";
                                        } elseif (is_string($value[1])) {
                                            throw new \InvalidArgumentException("NOT Between expression should be array!Not string");
                                        }
                                        break;
                                    default:
                                        $this->where .= "`{$field}` " . $this->expressionsConvert[$value[0]] . "'" . $value[1] . "'" . " AND ";
                                        break;
                                }

                            } else {
                                $this->where .= "`{$field}` " . $value[0] . "'" . $value[1] . "' AND ";
                            }

                        }
                    }

                }
            }

        }
        return $this;
    }

    public function whereIn($field,$param)
    {
        $this->where.="`{$field}` IN('".implode("','",$param)."') AND ";
        return $this;
    }

    public function whereNotIn($field,$param)
    {
        $this->where.="`{$field}` NOT IN('".implode("','",$param)."') AND ";
        return $this;
    }

    public function whereLike($field,$value)
    {
        $this->where.="`{$field}` LIKE '%".$value."%' AND ";
        return $this;
    }

    public function whereNotLike($field,$value)
    {
        $this->where.="`{$field}` NOT LIKE '%".$value."%' AND ";
        return $this;
    }

    public function whereBetween($field,$param)
    {
        $this->where.="`{$field}` BETWEEN '".$param[0]."' AND '".$param[1]."' AND ";
        return $this;
    }

    public function whereNotBetween($field,$param)
    {
        $this->where.="`{$field}` NOT BETWEEN '".$param[0]."' AND '".$param[1]."' AND ";
        return $this;
    }

    public function whereNull($field)
    {
        $this->where.="`{$field}` IS NULL AND ";
        return $this;
    }

    public function alias($param)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->table.=$this->table." AS ".$param;
        return $this;
    }
    public function join($table,$condition,$type='inner')
    {
        if (empty($table)){
            throw new \InvalidArgumentException("table can not empty!");
        }
        if (empty($condition)){
            throw new \InvalidArgumentException("join condition should be set!");
        }

        switch ($type){
            case 'inner':
                $this->table.=" INNER JOIN ".$table." ON ".$condition;
                break;
            case 'left':
                $this->table.=" LEFT JOIN ".$table." ON ".$condition;
                break;
            case 'right':
                $this->table.=" RIGHT JOIN ".$table." ON ".$condition;
                break;
            case 'cross':
                $this->table.=" CROSS JOIN ".$table." ON ".$condition;
                break;
        }
        return $this;
    }


    public function update($data)
    {
        if (is_array($data)){
            if (empty($this->table)){
                throw new \InvalidArgumentException("table not Found!");
            }
            $fields = '';
            foreach($data as $field=>$value){
                $fields.="`{$field}` = '".$value."',";
            }
            $this->sql = "UPDATE ".$this->table." SET ".substr($fields,0,-1)." WHERE ".substr($this->where,0,-4);
            return $this->execute($this->sql);
        }else{
            throw  new \InvalidArgumentException("参数错误");
        }
    }

    public function delete($ids)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        if (is_string($ids)&&!empty($ids)){

            if (empty($this->where)){
                $this->sql = "DELETE FROM ".$this->table." WHERE id in ('".$ids."')";
            }else{
                $this->sql = "DELETE FROM ".$this->table." WHERE ".substr($this->where,0,-4)." AND id in ('".$ids."')";
            }

        }elseif(is_array($ids)){

            if (empty($this->where)){
                $this->sql = "DELETE FROM ".$this->table." WHERE id in ('".implode("','",$ids)."')";
            }else{
                $this->sql = "DELETE FROM ".$this->table." WHERE ".substr($this->where,0,-4)." AND id in ('".implode("','",$ids)."')";
            }
        }

        return $this->execute($this->sql);
    }

    public function find()
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT ".(empty($this->fields)?"*":$this->fields)." FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4))." LIMIT 1";
        return $this->execute($this->sql);
    }

    public function all()
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT * FROM ".$this->table;
        return $this->execute($this->sql);
    }

    public function get()
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT * FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4));

        return $this->execute($this->sql);
    }

    public function orderby($field,$sort)
    {
        if (empty($this->order)){
            $this->order.= " ORDER BY `{$field}` {$sort},";
        }else{
            $this->order.= " `{$field}` {$sort},";
        }

        return $this;
    }

    public function groupby($field)
    {
        if (empty($this->order)){
            $this->order.= " GROUP BY `{$field}`";
        }else{
            $this->order.= " `{$field}`";
        }

        return $this;
    }

    public function paginate($page,$rows)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $total = $this->execute("SELECT COUNT(*) AS p FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4)));
        $pages = ceil($total[0]['p']/$rows);
        $page = ($page-1)*$rows;
        $this->sql = "SELECT ".(empty($this->fields)?"*":$this->fields)." FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4))." LIMIT {$page},{$rows}".(!empty($this->order)?substr($this->order,0,-1):'');
        return $this->execute($this->sql);
        return ['pages'=>$pages,'rows'=>$result];
    }

    public function column($column)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT ".$column." FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4));
        return $this->execute($this->sql);
    }

    public function value($column)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT ".$column." FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4))." LIMIT 1";
        return $this->execute($this->sql);
    }

    public function max($column)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT MAX({$column}) AS `max` FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4));
        return $this->execute($this->sql);
    }

    public function min($column)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT MIN({$column}) AS `min` FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4));
        return $this->execute($this->sql);
    }

    public function avg($column)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT AVG({$column}) AS `avg` FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4));
        return $this->execute($this->sql);
    }

    public function count($column)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT COUNT({$column}) AS `count` FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4));
        return $this->execute($this->sql);
    }

    public function sum($column)
    {
        if (empty($this->table)){
            throw new \InvalidArgumentException("table not Found!");
        }
        $this->sql = "SELECT SUM({$column}) AS `sum` FROM ".$this->table.(empty($this->where)?'':" WHERE ".substr($this->where,0,-4));
        return $this->execute($this->sql);
    }

    public function toSql()
    {
        return $this->sql;
    }

    public function execute($sql)
    {
        $result = $this->db->query($this->sql);

        if ($this->db->errno == 0){
            $this->table = '';
            $this->fields= '';
            $this->where = '';
            $this->order = '';
            $this->group ='';
            $this->sql   = '';
            return $result;
        }else{

            self::$instance = new self();
            return $this->db->query($this->sql);
        }
    }
}